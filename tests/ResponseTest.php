<?php

use Transforms\ExampleTransform;
use Contracts\TestTransform;
use PHPUnit\Framework\TestCase;
use Data\SuccessDataProvider;
use Goodgod\ApiTransform\Exceptions\OnlyOneFalseKey;
use Data\FailDataProvider;

class ResponseTest extends TestCase
{
    private string $transform = ExampleTransform::class;

    /**
     * @dataProvider successDataProvider
     * @param $resource
     * @param array $methodOutputKey
     * @param array $transformData
     * @param $result
     */
    public function testSuccess(array $methodOutputKey, $resource, array $transformData, $result)
    {
        /** @var TestTransform $transform */
        $transform = new $this->transform($resource);
        $transform->methodOutputKey = $methodOutputKey;
        $this->setTransformDataFromKey($transform, $transformData, $transform::getKeyNames());
        $content = json_decode($transform->mockResponse()->getContent(), true);

        $this->assertSame($result, $content['data'] ?? []);
    }

    /**
     * @dataProvider failOnlyOneFalseKeyDataProvider
     * @param array $methodOutputKey
     * @param $resource
     * @param array $transformData
     * @param $result
     */
    public function testOnlyOneFalseKey(array $methodOutputKey, $resource, array $transformData, $result)
    {
        $this->expectException(OnlyOneFalseKey::class);

        /** @var TestTransform $transform */
        $transform = new $this->transform($resource);
        $transform->methodOutputKey = $methodOutputKey;
        $this->setTransformDataFromKey($transform, $transformData, $transform::getKeyNames());
        $transform->mockResponse();
    }

    public function successDataProvider(): array
    {
        $provider = new SuccessDataProvider($this->getTransformKeyNames());
        return $provider->getVerifications();
    }

    public function failOnlyOneFalseKeyDataProvider()
    {
        $provider = new FailDataProvider($this->getTransformKeyNames());
        return $provider->onlyFunc(['verifyOnlyOneFalseKey']);
    }

    private function setTransformDataFromKey(TestTransform $transform, array $transformData, array $keyNames): void
    {
        foreach ($transformData as $key => $data) {
            $transform->setKeyMethods($keyNames[$key], $data);
        }
    }

    private function getTransformKeyNames(): array
    {
        return call_user_func([$this->transform, 'getKeyNames']);
    }
}