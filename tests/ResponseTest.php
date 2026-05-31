<?php

use Contracts\TestTransform;
use Data\SuccessDataProvider;
use jerry58321\ApiTransform\Exceptions\OnlyOneFalseKey;
use Data\FailDataProvider;
use PHPUnit\Framework\Attributes\DataProvider;
use Transforms\ExampleTransform;

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
    #[DataProvider('successDataProvider')]
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
     * @dataProvider failExceptionDataProvider
     * @param array $methodOutputKey
     * @param $resources
     * @param array $transformData
     * @param $result
     */
    #[DataProvider('failExceptionDataProvider')]
    public function testException(array $methodOutputKey, $resources, array $transformData, $result)
    {
        $this->expectException($result);

        /** @var TestTransform $transform */
        $transform = new $this->transform($resources);
        $transform->methodOutputKey = $methodOutputKey;
        $this->setTransformDataFromKey($transform, $transformData, $transform->getKeyNames());
        $transform->mockResponse();
    }

    /**
     * @return array
     */
    public static function successDataProvider(): array
    {
        $provider = new SuccessDataProvider(call_user_func([ExampleTransform::class, 'getKeyNames']));

        return array_merge(
            $provider->verifyOutputSameResources(),
            $provider->verifyObjectResources(),
            $provider->verifyNullResources(),
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
    public static function failExceptionDataProvider(): array
    {
        $provider = new FailDataProvider(call_user_func([ExampleTransform::class, 'getKeyNames']));
        return array_merge(
            $provider->verifyOnlyOneFalseKey(),
            $provider->verifyOnlyOneAbstractPaginator()
        );
    }
}
