<?php


namespace jerry58321\ApiTransform;

use jerry58321\ApiTransform\Contracts\OutputDefinition;
use jerry58321\ApiTransform\Exceptions\NotFoundSpecifiedResource;
use jerry58321\ApiTransform\Exceptions\OnlyOneFalseKey;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;

abstract class Transform implements OutputDefinition
{
    /** @var Resources $resources */
    protected Resources $resources;

    /** @var Resources $transform */
    protected Resources $transform;

    /** @var array $parameters */
    protected array $parameters;

    /** @var bool $withPaginationOutput */
    protected bool $withPaginationOutput = true;

    /** @var string $pack */
    protected string $pack = 'data';

    /**
     * Transform constructor.
     * @param $resources
     * @param array $parameters
     */
    #[Pure] public function __construct($resources, array $parameters = [])
    {
        $this->resources = new Resources($resources);
        $this->transform = new Resources([]);
        $this->parameters = $parameters;
    }

    /**
     * @param $resources
     * @param array $parameters
     * @return JsonResponse
     */
    public static function response($resources, $parameters = []): JsonResponse
    {
        return (new static($resources, $parameters))
            ->addAdditional()
            ->toTransform()
            ->toResponse();
    }

    /**
     * @param $resources
     * @param array $parameters
     * @return Resources
     */
    public static function quote($resources, $parameters = []): Resources
    {
        return (new static($resources, $parameters))
            ->toTransform()
            ->transform;
    }

    /**
     * @param bool $bool
     * @param \Closure $action
     * @return mixed
     */
    public function when(bool $bool, \Closure $action): mixed
    {
        if (!$bool) return fn(Resources $resources, $key) => $resources->offsetUnset($key);

        return $action();
    }

    /**
     * @param string $relation
     * @param \Closure $action
     * @return \Closure
     */
    public function whenRelationLoaded(string $relation, \Closure $action): \Closure
    {
        return fn(Resources $resource, $key) => $resource->offsetSet($key, $this->when(
            $resource->get()->relationLoaded($relation),
            $action
        ));
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getParameters($key): mixed
    {
        return $this->parameters[$key] ?? null;
    }

    /**
     * @return $this
     */
    protected function addAdditional(): static
    {
        $this->transform->merge([
            'code' => 1,
            'time' => time()
        ]);

        return $this;
    }

    /**
     * @return JsonResponse
     */
    protected function toResponse(): JsonResponse
    {
        return $this->transform->jsonSerialize();
    }

    /**
     * @return $this
     */
    protected function toTransform(): static
    {
        $this->checkIsOnlyOneFalseKey();

        $this->eachResource(function (Resources $resources, $data, $key) {
            $this->withPaginationOutput && $resources->get() instanceof AbstractPaginator ?
                $this->packOutputKeyWithPagination($key, $data, $resources->get()) :
                $this->packOutputKey($key, $data);
        });

        return $this;
    }

    /**
     * @param $key
     * @param $data
     * @return $this
     */
    private function packOutputKey($key, $data): static
    {
        $key === false ?
            $this->transform->offsetSet($this->pack, $data) :
            $this->transform->deepSet($data, "{$this->pack}.{$key}");

        return $this;
    }

    /**
     * @param $key
     * @param $data
     * @param AbstractPaginator $paginator
     * @return $this
     */
    private function packOutputKeyWithPagination($key, $data, AbstractPaginator $paginator): static
    {
        $this->transform->deepSet($data, "{$this->pack}.{$key}.{$this->pack}");

        $this->transform->deepSet([
            'current_page' => $paginator->currentPage(),
            'last_page'    => $paginator->lastPage(),
            'per_page'     => $paginator->perPage(),
            'total'        => $paginator->total()
        ], "{$this->pack}.{$key}.meta");

        return $this;
    }

    /**
     * @param string $methodName
     * @param $resource
     * @return mixed
     */
    private function getMethodNameFunc(string $methodName, $resource): Resources
    {
        $resolve = $this->{'__' . Str::camel($methodName)}($resource);
        return $resolve instanceof Resources ? $resolve : new Resources($resolve);
    }

    /**
     * @param string $key
     * @return Resources
     */
    private function getResourcesByKey(string $key): Resources
    {
        if (!$this->resources->offsetExists($key)) {
            throw new NotFoundSpecifiedResource('The resources brought in must have "outputKeyMethod" definition');
        }

        return new Resources($this->resources->offsetGet($key));
    }

    /**
     * @param \Closure $callback
     */
    private function eachResource(\Closure $callback): void
    {
        foreach ($this->methodOutputKey() as $key => $value) {
            $methodName = is_numeric($key) ? $value : $key;
            $resource = $this->getResourcesByKey($methodName);

            $data = $resource->mapUnit($resource->get(),
                fn($data) => $this->getMethodNameFunc($methodName, new Resources($data))
                    ->mapExecClosure()
                    ->get()
            );

            $callback($resource, $data, $value);
        }
    }

    private function checkIsOnlyOneFalseKey(): void
    {
        $falseKeyCount = collect($this->methodOutputKey())->intersect([false])->count();
        if ($falseKeyCount > 1) throw new OnlyOneFalseKey("methodOutputKey can only have 1 False Key, {$falseKeyCount} are defined");
    }
}