<?php

use Contracts\TestTransform;
use Data\SuccessDataProvider;
use jerry58321\ApiTransform\Exceptions\OnlyOneFalseKey;
use Data\FailDataProvider;

class ResponseTest extends BaseTest
{
    /**
     * @dataProvider successDataProvider
     * @param  array  $methodOutputKey
     * @param $resources
     * @param  array  $transformData
     * @param $result
     * @param  array  $meta
     */
    public function testSuccess(array $methodOutputKey, $resources, array $transformData, $result, array $meta = [])
    {
        /** @var TestTransform $transform */
        $transform = new $this->transform($resources);
        $transform->methodOutputKey = $methodOutputKey;
        $this->setTransformDataFromKey($transform, $transformData, $this->getTransformKeyNames());
        $content = json_decode($transform->mockResponse()->getContent(), true);

        $this->assertSame($result, $content['data'] ?? []);

        if (!empty($meta)) {
            $this->assertSame($meta, $content['meta'] ?? []);
        }
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