<?php


namespace Data;


use Goodgod\ApiTransform\Resources;
use Goodgod\ApiTransform\Transform;
use Illuminate\Support\Str;

class SuccessDataProvider
{
    private array $keyNames;

    public function __construct(array $keyNames)
    {
        $this->keyNames = $keyNames;
    }

    public function getVerifications(): array
    {
        return array_merge(
            $this->verifyOutputSameResources(),
            $this->verifyTwoResources(),
            $this->verifyFalseOutputKey(),
            $this->verifyWhenMethod(),
        );
    }

    protected function verifyOutputSameResources(): array
    {
        [$firstKey] = $this->keyNames;
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
            __FUNCTION__ => [
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
            ]
        ];
    }

    protected function verifyTwoResources(): array
    {
        [$firstKey, $secondKey] = $this->keyNames;
        $input = [$firstKey => ['output' => 'first'], $secondKey => ['output' => 'second']];
        return [
            __FUNCTION__ => [
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
            ]
        ];
    }

    protected function verifyFalseOutputKey(): array
    {
        [$firstKey, $secondKey] = $this->keyNames;
        $input = [$firstKey => ['output' => 'first'], $secondKey => ['output' => 'second']];
        return [
            __FUNCTION__ => [
                [$firstKey => false, $secondKey => $secondKey],
                $input,
                [
                    fn(Transform $transform, Resources $resources) => [
                        'output' => $resources->output,
                    ],
                    fn(Transform $transform, Resources $resources) => [
                        'output' => $resources->output,
                    ]
                ],
                [
                    'output' => 'first',
                    $secondKey => ['output' => 'second']
                ]
            ]
        ];
    }

    protected function verifyWhenMethod()
    {
        [$firstKey] = $this->keyNames;
        $input = [
            $firstKey => [
                ['name' => 'John', 'age' => 20],
                ['name' => 'Mike', 'age' => 16],
                ['name' => 'Max', 'age' => 12]
            ]
        ];

        return [
            __FUNCTION__ => [
                [$firstKey => $firstKey],
                $input,
                [
                    fn(Transform $transform, Resources $resources) => [
                        'name' => $resources->name,
                        'age'  => $transform->when($resources->age >= 16, fn() => $resources->age)
                    ]
                ],
                [
                    $firstKey => [
                        ['name' => 'John', 'age' => 20],
                        ['name' => 'Mike', 'age' => 16],
                        ['name' => 'Max']
                    ]
                ]
            ]
        ];
    }
}