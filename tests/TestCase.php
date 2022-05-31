<?php

use Orchestra\Testbench\TestCase as Orchestra;
use jerry58321\ApiTransform\TransformServiceProvider;

abstract class TestCase extends Orchestra
{
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            TransformServiceProvider::class,
        ];
    }
}