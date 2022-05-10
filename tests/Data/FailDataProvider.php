<?php


namespace Data;


use jerry58321\ApiTransform\Resources;
use jerry58321\ApiTransform\Transform;

class FailDataProvider extends DataProvider
{
    public function verifyOnlyOneFalseKey(): array
    {
        [$firstKey, $secondKey] = $this->keyNames;
        return [
            __FUNCTION__ => [
                [$firstKey => false, $secondKey => false],
                [$firstKey => 'test', $secondKey => 'test'],
                [
                    fn(Transform $transform, Resources $resources) => [],
                    fn(Transform $transform, Resources $resources) => []
                ],
                []
            ]
        ];
    }
}