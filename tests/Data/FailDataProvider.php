<?php


namespace Data;


use Goodgod\ApiTransform\Resources;
use Goodgod\ApiTransform\Transform;
use Illuminate\Support\Str;

class FailDataProvider extends DataProvider
{
    public function getVerifications(): array
    {
        return [
            $this->verifyOnlyOneFalseKey(),
        ];
    }

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