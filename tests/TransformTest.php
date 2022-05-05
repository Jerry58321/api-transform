<?php

use transform\ExampleTransform;
use Goodgod\ApiTransform\Transform;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class TransformTest extends TestCase
{
    /**
     * @dataProvider additionProvider
     * @param $resource
     * @param array $methodOutputKey
     * @param array $transformData
     * @param $result
     */
    public function test(array $methodOutputKey, $resource, array $transformData, $result)
    {
        $transform = new ExampleTransform($resource);
        $transform->methodOutputKey = $methodOutputKey;
        $this->setTransformDataFromKey($transform, $transformData, $transform::$keyNames);
        $content = json_decode($transform->mockResponse()->getContent(), true);

        $this->assertSame($result, $content['data'] ?? []);
    }

    private function setTransformDataFromKey(Transform $transform, array $transformData, array $keyNames): void
    {
        foreach ($transformData as $key => $data) {
            $transform->{$keyNames[$key]} = $data;
        }
    }

    public function additionProvider()
    {
        $keyNames = ExampleTransform::$keyNames;
        return [
            $this->verifyOutputSameResources($keyNames),
            $this->verifyTwoResources($keyNames),
        ];
    }

    protected function verifyOutputSameResources($keyNames): array
    {
        [$firstKey] = $keyNames;
        $resource = [
            [
                'name'  => 'John',
                'age'   => '20',
                'level' => 1
            ], [
                'name'  => 'Mike',
                'age'   => '22',
                'level' => 2
            ]
        ];
        return [
            [$firstKey => Str::camel($firstKey)],
            [$firstKey => $resource],
            [
                fn($resources) => [
                    'name'  => $resources->name,
                    'age'   => $resources->age,
                    'level' => $resources->level,
                ]
            ],
            [Str::camel($firstKey) => $resource]
        ];
    }

    protected function verifyTwoResources($keyNames): array
    {
        [$firstKey, $secondKey] = $keyNames;
        $resource = [$firstKey => ['output' => 'first'], $secondKey => ['output' => 'second']];
        return [
            [$firstKey => $firstKey, $secondKey => $secondKey],
            $resource,
            [
                fn($resources) => [
                    'output' => $resources->output,
                ],
                fn($resources) => [
                    'output' => $resources->output,
                ]
            ],
            $resource
        ];
    }
}