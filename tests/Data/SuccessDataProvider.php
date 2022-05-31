<?php


namespace Data;


use jerry58321\ApiTransform\Resources;
use jerry58321\ApiTransform\Transform;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class SuccessDataProvider extends DataProvider
{
    /**
     * @return array[]
     */
    public function verifyOutputSameResources(): array
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

    /**
     * @return array[]
     */
    public function verifyStringResources(): array
    {
        [$firstKey] = $this->keyNames;
        return [
            __FUNCTION__ => [
                [$firstKey => $firstKey],
                [$firstKey => 'test'],
                [
                    fn(Transform $transform, Resources $resources) => $resources
                ],
                [
                    $firstKey => 'test'
                ]
            ]
        ];
    }

    /**
     * @return array[]
     */
    public function verifyTwoResources(): array
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

    /**
     * @return array[]
     */
    public function verifyFalseOutputKey(): array
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

    /**
     * @return array[]
     */
    public function verifyWhenMethod(): array
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

    /**
     * @return array[]
     */
    public function verifyWithPagination(): array
    {
        [$firstKey, $secondKey] = $this->keyNames;
        $input = [
            $firstKey => new LengthAwarePaginator([
                ['name' => 'John', 'age' => 20],
                ['name' => 'Mike', 'age' => 16],
                ['name' => 'Max', 'age' => 12]
            ], 3, 2),
            $secondKey => new LengthAwarePaginator([
                ['nickname' => 'J'],
                ['nickname' => 'M'],
                ['nickname' => 'M'],
            ], 3, 2)
        ];

        return [
            __FUNCTION__ => [
                [$firstKey => false, $secondKey => $secondKey],
                $input,
                [
                    fn(Transform $transform, Resources $resources) => [
                        'name' => $resources->name,
                        'age'  => $resources->age,
                    ],
                    fn(Transform $transform, Resources $resources) => [
                        'nickname' => $resources->nickname,
                    ]
                ],
                [

                    ['name' => 'John', 'age' => 20],
                    ['name' => 'Mike', 'age' => 16],
                    ['name' => 'Max', 'age' => 12],
                    $secondKey => [
                        ['nickname' => 'J'],
                        ['nickname' => 'M'],
                        ['nickname' => 'M'],
                    ]
                ],
                'meta' => [
                    'current_page' => 1,
                    'last_page'    => 2,
                    'per_page'     => 2,
                    'total'        => 3,
                    $secondKey => [
                        'current_page' => 1,
                        'last_page'    => 2,
                        'per_page'     => 2,
                        'total'        => 3
                    ],
                ]
            ]
        ];
    }

    /**
     * @return array[]
     */
    public function verifyWhenRelationLoaded()
    {
        $input = ['name' => 'John', 'age' => 19, 'nickname' => 'J'];

        return [
            __FUNCTION__ => [
                [],
                $input,
                [
                    fn(Transform $transform, Resources $resources) => [
                        'name'     => $transform->whenRelationLoaded('user', fn() => $resources->name . '_prefix'),
                        'age'      => $transform->whenRelationLoaded('none', fn() => $resources->age),
                        'nickname' => $transform->whenRelationLoaded('user', fn() => $resources->nickname),
                    ]
                ],
                [
                    'name'     => $input['name'] . '_prefix',
                    'nickname' => $input['nickname'],
                ]
            ]
        ];
    }
}