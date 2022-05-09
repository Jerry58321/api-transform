<?php

use Mock\Model;
use Data\SuccessDataProvider;

class MethodTest extends BaseTest
{
    /**
     * @param array $methodOutputKey
     * @param $resources
     * @param array $transformData
     * @param $result
     */
    public function testWhenRelationLoaded(array $methodOutputKey, $resources, array $transformData, $result)
    {
        $stub = $this->getMockBuilder(Model::class)
            ->setConstructorArgs(compact('resources'))
            ->getMock();

        $stub->expects($this->any())
            ->method('__get')
            ->willReturnCallback(function ($name) use ($resources) {
                return $resources[$name] ?? null;
            });

        $stub->expects($this->any())
            ->method('get')
            ->willReturn($stub);

        $stub->expects($this->any())
            ->method('offsetUnset')
            ->willReturnCallback(function ($key) use (&$resources) {
                unset($resources[$key]);
            });

        $stub->expects($this->any())
            ->method('offsetSet')
            ->willReturnCallback(function ($key, $value) use (&$resources) {
                $resources[$key] = $value;
            });

        $stub->expects($this->any())
            ->method('relationLoaded')
            ->willReturnCallback(function ($relation) {
                return $relation === 'user';
            });

        $transform = new $this->transform([]);

        foreach ($transformData as $data) {
            foreach ($data($transform, $stub) as $key => $value) {
                if ($value instanceof \Closure) $value($stub, $key);
            }
        }

        foreach ($resources as $key => $value) {
            if ($value instanceof \Closure) $value($stub, $key);
        }

        $this->assertSame($result, $resources);
    }

    /**
     * @return array
     */
    public function successWhenRelationLoadedDataProvider(): array
    {
        $provider = new SuccessDataProvider($this->getTransformKeyNames());
        return $provider->onlyFunc(['verifyWhenRelationLoaded']);
    }
}