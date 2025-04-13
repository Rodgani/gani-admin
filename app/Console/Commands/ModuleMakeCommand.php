<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Console\Concerns\CreatesMatchingTest;
class ModuleMakeCommand extends GeneratorCommand
{
    protected $name = 'module:make';

    protected $description = 'Generate a class (controller, model, request, etc.) inside a module.';

    public function handle()
    {
        $name = $this->argument('name');
        $type = strtolower($this->option('type'));
        $module = $this->option('module');

        if (!$type || !$module) {
            $this->error('Both --type and --module options are required.');
            return;
        }

        $stubPath = $this->getStub();

        if (!file_exists($stubPath)) {
            $this->error("Stub for type '{$type}' not found at {$stubPath}");
            return;
        }

        $className = class_basename($name);
        $namespace = $this->resolveNamespace($type, $module);

        $targetPath = $this->resolvePath($type, $module, $name);

        $stub = file_get_contents($stubPath);
        $content = str_replace(
            ['{{ namespace }}', '{{ class }}'],
            [$namespace, $className],
            $stub
        );

        $this->makeDirectory($targetPath);

        file_put_contents($targetPath, $content);
        $this->info("{$type} created at: {$targetPath}");
    }

    protected function resolveNamespace(string $type, string $module): string
    {
        return match ($type) {
            'controller' => "Modules\\{$module}\\Http\\Controllers",
            'model' => "Modules\\{$module}\\Models",
            'request' => "Modules\\{$module}\\Http\\Requests",
            default => "Modules\\{$module}"
        };
    }

    protected function resolvePath(string $type, string $module, string $name): string
    {
        // Normalize path (convert forward slashes to namespace style)
        $name = str_replace('/', '\\', $name);
        $name = Str::studly($name); // Capitalize segments

        $relativePath = match ($type) {
            'controller' => 'Http/Controllers/',
            'model' => 'Models/',
            'request' => 'Http/Requests/',
            default => '',
        };

        // Convert namespace to path
        $path = str_replace('\\', '/', $name);

        return base_path("modules/{$module}/{$relativePath}{$path}.php");
    }
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the class'],
        ];
    }

    protected function getOptions()
    {
        return [
            ['type', null, InputOption::VALUE_REQUIRED, 'The type of class (controller, model, request, etc.)'],
            ['module', null, InputOption::VALUE_REQUIRED, 'The name of the module'],
        ];
    }

    protected function getStub()
    {
        $type = strtolower($this->option('type'));
        return base_path("/stubs/{$type}.stub");
    }
}
