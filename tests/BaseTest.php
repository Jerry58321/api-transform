<?php

use Transforms\ExampleTransform;
use Contracts\TestTransform;
use PHPUnit\Framework\MockObject\MockBuilder;

abstract class BaseTest extends TestCase
{
    protected string $transform = ExampleTransform::class;

    /**
     * @param TestTransform|MockBuilder $transform
     * @param array $transformData
     * @param array $keyNames
     */
    protected function setTransformDataFromKey(TestTransform|MockBuilder $transform, array $transformData, array $keyNames): void
    {
        foreach ($transformData as $key => $data) {
            $transform->setKeyMethods($keyNames[$key], $data);
        }
    }

    /**
     * @return array
     */
    protected function getTransformKeyNames(): array
    {
        return call_user_func([$this->transform, 'getKeyNames']);
    }
}