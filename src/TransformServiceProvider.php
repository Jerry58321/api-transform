<?php


namespace jerry58321\ApiTransform;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;


class TransformServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->offerPublishing();
        $this->registerCommands();
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/api-transform.php',
            'api-transform'
        );
    }

    protected function offerPublishing()
    {
        if (! function_exists('config_path')) {
            // function not available and 'publish' not relevant in Lumen
            return;
        }

        $this->publishes([
            __DIR__.'/../config/api-transform.php' => config_path('api-transform.php'),
        ], 'config');

    }

    protected function registerCommands()
    {
        $this->commands([
            Commands\TransformMakeCommand::class,
        ]);

        Artisan::call('make:transform ExampleTransform --example');
    }
}