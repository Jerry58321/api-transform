<?php

use Contracts\TestTransform;
use Data\SuccessDataProvider;
use Goodgod\ApiTransform\Exceptions\OnlyOneFalseKey;
use Data\FailDataProvider;

class ResponseTest extends BaseTest
{
    /**
     * @dataProvider successDataProvider
     * @param $resources
     * @param array $methodOutputKey
     * @param array $transformData
     * @param $result
     */
    public function testSuccess(array $methodOutputKey, $resources, array $transformData, $result)
    {
        /** @var TestTransform $transform */
        $transform = new $this->transform($resources);
        $transform->methodOutputKey = $methodOutputKey;
        $this->setTransformDataFromKey($transform, $transformData, $this->getTransformKeyNames());
        $content = json_decode($transform->mockResponse()->getContent(), true);

        $this->assertSame($result, $content['data'] ?? []);
    }

    /**
     * @dataProvider failOnlyOneFalseKeyDataProvider
     * @param array $methodOutputKey
     * @param $resources
     * @param array $transformData
     * @param $result
     */
    public function testOnlyOneFalseKey(array $methodOutputKey, $resources, array $transformData, $result)
    {
        $this->expectException(OnlyOneFalseKey::class);

        /** @var TestTransform $transform */
        $transform = new $this->transform($resources);
        $transform->methodOutputKey = $methodOutputKey;
        $this->setTransformDataFromKey($transform, $transformData, $transform->getKeyNames());
        $transform->mockResponse();
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        $provider = new SuccessDataProvider($this->getTransformKeyNames());

        return array_merge(
            $provider->verifyOutputSameResources(),
            $provider->verifyStringResources(),
            $provider->verifyTwoResources(),
            $provider->verifyFalseOutputKey(),
            $provider->verifyWhenMethod(),
            $provider->verifyWithPagination()
        );
    }

    /**
     * @return array
     */
    public function failOnlyOneFalseKeyDataProvider(): array
    {
        $provider = new FailDataProvider($this->getTransformKeyNames());
        return $provider->verifyOnlyOneFalseKey();
    }
}