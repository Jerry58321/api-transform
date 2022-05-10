<?php

namespace ezp\ApiTransform\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class TransformMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:transform';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new transform';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->option('example') ?
            $this->resolveStubPath('/stubs/example-transform.stub') :
            $this->resolveStubPath('/stubs/transform.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param string $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . $stub;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Http\Transforms';
    }

    protected function getOptions(): array
    {
        return [
            ['example', null, InputOption::VALUE_NONE, 'Create a example transform'],
        ];
    }
}
