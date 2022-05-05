<?php

use transform\ExampleTransform;
use transform\ExampleTest;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;
use Goodgod\ApiTransform\Transform;
use Goodgod\ApiTransform\Resources;

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

    private function setTransformDataFromKey(ExampleTest $transform, array $transformData, array $keyNames): void
    {
        foreach ($transformData as $key => $data) {
            $transform->setAttribute($keyNames[$key], $data);
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
        $input = [
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
            [$firstKey => $input],
            [
                fn(Transform $transform, Resources $resources) => [
                    'name'  => $resources->name,
                    'age'   => $resources->age,
                    'level' => $resources->level,
                ]
            ],
            [Str::camel($firstKey) => $input]
        ];
    }

    protected function verifyTwoResources($keyNames): array
    {
        [$firstKey, $secondKey] = $keyNames;
        $input = [$firstKey => ['output' => 'first'], $secondKey => ['output' => 'second']];
        return [
            [$firstKey => $firstKey, $secondKey => $secondKey],
            $input,
            [
                fn(Transform $transform, Resources $resources) => [
                    'output' => $transform->when(true, fn () => $resources->output),
                ],
                fn(Transform $transform, Resources $resources) => [
                    'output' => $resources->output,
                ]
            ],
            $input
        ];
    }
}