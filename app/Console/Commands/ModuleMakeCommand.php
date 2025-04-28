<?php

namespace App\Console\Commands;

use App\Services\ScaffoldService;
use Carbon\Carbon;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
class ModuleMakeCommand extends GeneratorCommand
{
    protected $name = 'module:make';

    protected $description = 'Generate a class (controller, model, request, etc.) inside a module.';
    protected $type;
    protected $argumentName;

    public function handle()
    {

        $type = strtolower($this->argument('type') ?? $this->ask('What type of resource are you generating?'));
        $this->type = $type;
        $name = $this->argument('name') ?? $this->ask('What is the name of the class?');

        $module = $type !== 'middleware' ? $this->argument('module') ?? $this->ask('Which module do you want to use?') : '';

        $this->argumentName = $name;

        if (!$type) {
            $this->error('Both --type and --module options are required.');
            return;
        }

        $stubPath = $this->getStub();

        if (!file_exists($stubPath)) {
            $this->error("Stub for type '{$type}' not found at {$stubPath}");
            return;
        }

        $className = class_basename($name);
        $namespace = $this->resolveNamespace($type, $module, $name);

        $targetPath = $this->resolvePath($type, $module, $name);

        $stub = file_get_contents($stubPath);

        $content = ScaffoldService::resolveContent($namespace, $className, $stub);

        $this->makeDirectory($targetPath);

        file_put_contents($targetPath, $content);
        $this->info("{$type} created at: {$targetPath}");
    }

    protected function resolveNamespace(string $type, string $module, string $name): string
    {
        return ScaffoldService::resolveNamespace($type, $module, $name);
    }

    protected function resolvePath(string $type, string $module, string $name): string
    {
        return ScaffoldService::resolvePath($type, $module, $name);
    }
    protected function getArguments()
    {
        return [
            ['type', InputArgument::REQUIRED, 'The type of class (controller, model, request, etc.)'],
            ['name', InputArgument::REQUIRED, 'The name of the class'],
            ['module', InputArgument::OPTIONAL, 'The name of the module'],
        ];
    }

    // protected function getOptions()
    // {
    //     return [
    //         ['type', null, InputOption::VALUE_REQUIRED, 'The type of class (controller, model, request, etc.)'],
    //         ['module', null, InputOption::VALUE_REQUIRED, 'The name of the module'],
    //     ];
    // }

    protected function getStub()
    {
        $type = $this->type;

        if ($type === 'migration') {
            $type .= Str::contains($this->argumentName, 'create') ? '.create' : '.update';
        }

        return base_path("modules/stubs/{$type}.stub");
    }
}
