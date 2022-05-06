<?php

use Transforms\ExampleTransform;
use Contracts\TestTransform;
use PHPUnit\Framework\TestCase;
use Data\SuccessDataProvider;

class ResponseTest extends TestCase
{
    /**
     * @dataProvider successDataProvider
     * @param $resource
     * @param array $methodOutputKey
     * @param array $transformData
     * @param $result
     */
    public function testSuccess(array $methodOutputKey, $resource, array $transformData, $result)
    {
        $transform = new ExampleTransform($resource);
        $transform->methodOutputKey = $methodOutputKey;
        $this->setTransformDataFromKey($transform, $transformData, $transform::$keyNames);
        $content = json_decode($transform->mockResponse()->getContent(), true);

        $this->assertSame($result, $content['data'] ?? []);
    }

    public function successDataProvider(): array
    {
        $provider = new SuccessDataProvider(ExampleTransform::$keyNames);
        return $provider->getVerifications();
    }

    private function setTransformDataFromKey(TestTransform $transform, array $transformData, array $keyNames): void
    {
        foreach ($transformData as $key => $data) {
            $transform->setKeyMethods($keyNames[$key], $data);
        }
    }
}