<?php


namespace jerry58321\ApiTransform;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;


class TransformServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerCommands();
    }

    protected function registerCommands()
    {
        $this->commands([
            Commands\TransformMakeCommand::class,
        ]);

        Artisan::call('make:transform ExampleTransform --example');
    }
}