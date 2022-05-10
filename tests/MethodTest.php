<?php

use Goodgod\ApiTransform\Resources;
use Mock\Model;
use Data\SuccessDataProvider;

class MethodTest extends BaseTest
{
    /**
     * @dataProvider successWhenRelationLoadedDataProvider
     * @param array $methodOutputKey
     * @param $resources
     * @param array $transformData
     * @param $result
     */
    public function testWhenRelationLoaded(array $methodOutputKey, $resources, array $transformData, $result)
    {
        $model = new Model($resources);
        $resources = new Resources($resources);

        $transform = new $this->transform([]);

        foreach ($transformData as $data) {
            foreach ($data($transform, $resources) as $key => $value) {
                if ($value instanceof \Closure) $value($model, $key);
            }
        }

        foreach ($model as $key => $value) {
            if ($value instanceof \Closure) $value($model, $key);
        }

        $this->assertSame($result, $model->getItems());
    }

    /**
     * @return array
     */
    public function successWhenRelationLoadedDataProvider(): array
    {
        $provider = new SuccessDataProvider($this->getTransformKeyNames());
        return $provider->verifyWhenRelationLoaded();
    }
}