<?php


namespace Goodgod\ApiTransform;


use ArrayAccess;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Arr;
use JsonSerializable;

class Resources implements ArrayAccess, JsonSerializable
{
    use DelegatesToResource;

    /** @var mixed $resources */
    protected mixed $resources;

    public function __construct($resources)
    {
        $this->resources = $resources;
    }

    /**
     * @return mixed
     */
    public function get(): mixed
    {
        return $this->resources;
    }

    /**
     * @param $data
     * @param string|null $deep
     * @return $this
     */
    public function push($data, string $deep = null): static
    {
        if (!is_null($deep)) {
            Arr::set($this->resources, $deep, $data);
        } else {
            array_push($this->resources, $data);
        }

        return $this;
    }

    /**
     * @param $data
     * @return $this
     */
    public function merge($data): static
    {
        $this->resources = array_merge($this->resources, $data);
        return $this;
    }

    /**
     * @param $resource
     * @param \Closure $callback
     * @return mixed
     */
    public function mapUnit($resource, \Closure $callback): mixed
    {
        if (is_numeric_list($resource) || $resource instanceof AbstractPaginator) {
            return collect($resource)->map(fn ($data) => $callback($data))->toArray();
        } else {
            return $callback($resource);
        }
    }

    /**
     * @return $this
     */
    public function mapExecClosure(): static
    {
        if (!is_array($this->resources)) return $this;

        $lastKey = $this->getRefLastKey($this->resources);

        do {
            $currentKey = key($this->resources);
            $data = current($this->resources);
            if ($data instanceof \Closure && $data($this, $currentKey) === null) {
                continue;
            }
            $lastKey = $this->getRefLastKey($this->resources);
            next($this->resources);
        } while (!is_null($currentKey) && $lastKey !== $currentKey);

        return $this;
    }

    /**
     * @param $resource
     * @return int|string|null
     */
    private function getRefLastKey($resource): int|string|null
    {
        end($resource);
        return key($resource);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        return $this->offsetExists($name) ? $this->offsetGet($name) : null;
    }
}