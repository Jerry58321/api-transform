<?php


namespace transform;


use Goodgod\ApiTransform\Transform;

class ExampleTransform extends Transform
{
    public array $methodOutputKey = [];


    public static array $keyNames = [
        'firstKey',
        'secondKey',
    ];

    public \Closure $firstKey;

    public \Closure $secondKey;

    public function mockResponse()
    {
        return $this->addAdditional()
            ->toTransform()
            ->toResponse();
    }

    public function methodOutputKey(): array
    {
        return $this->methodOutputKey;
    }

    public function __firstKey($resources)
    {
        return call_user_func($this->firstKey, $resources);
    }

    public function __secondKey($resources)
    {
        return call_user_func($this->secondKey, $resources);
    }
}