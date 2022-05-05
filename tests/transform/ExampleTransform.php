<?php


namespace transform;


use Goodgod\ApiTransform\Transform;
use Illuminate\Support\Str;
use PHPUnit\Framework\MockObject\BadMethodCallException;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExampleTransform extends Transform implements ExampleTest
{
    public array $methodOutputKey = [];

    public static array $keyNames = [
        'firstKey',
        'secondKey',
    ];

    private array $attributes;

    public function methodOutputKey(): array
    {
        return $this->methodOutputKey;
    }

    public function setAttribute($name, $value): static
    {
        $this->attributes[$name] = $value;
        return $this;
    }

    public function mockResponse(): JsonResponse
    {
        return $this->addAdditional()
            ->toTransform()
            ->toResponse();
    }

    public function __call(string $name, array $arguments)
    {
        foreach ($this::$keyNames as $keyName) {
            if ($name === '__' . Str::camel($keyName)) {
                return call_user_func($this->attributes[$keyName], $this, ...$arguments);
            }
        }

        throw new BadMethodCallException(sprintf(
            'Call to undefined method %s::%s()', static::class, $name
        ));
    }
}