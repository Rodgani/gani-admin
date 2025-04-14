<?php

namespace App\Console\Commands;

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

        $content = str_replace(
            ['{{ namespace }}', '{{ class }}'],
            [$namespace, $className],
            $stub
        );

        $this->makeDirectory($targetPath);

        file_put_contents($targetPath, $content);
        $this->info("{$type} created at: {$targetPath}");
    }

    protected function resolveNamespace(string $type, string $module, string $name): string
    {
        $base = match ($type) {
            'controller' => "Modules\\{$module}\\Http\\Controllers",
            'model' => "Modules\\{$module}\\Models",
            'request' => "Modules\\{$module}\\Http\\Requests",
            'provider' => "Modules\\{$module}\\Providers",
            'observer' => "Modules\\{$module}\\Observers",
            'migration' => "Modules\\{$module}\\Database\\Migrations",
            'job' => "Modules\\{$module}\\Jobs",
            'enum' => "Modules\\{$module}\\Enums",
            'middleware' => "Modules\\Middleware",
            default => "Modules\\{$module}"
        };

        if ($name) {
            $name = str_replace('/', '\\', $name);
            $name = Str::studly($name);
            $segments = explode('\\', $name);
            array_pop($segments); // Remove class name
            if (!empty($segments)) {
                $base .= '\\' . implode('\\', $segments);
            }
        }

        return $base;
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
            'provider' => 'Providers/',
            'observer' => 'Observers/',
            'migration' => "Database/Migrations/",
            'job' => "Jobs/",
            'enum' => "Enums/",
            'middleware' => 'Middleware/',
            default => '',
        };

        // Convert namespace to path
        $path = str_replace('\\', '/', $name);
        if ($type === "migration") {
            $dateTime = Carbon::now()->format('Y-m-d H:i:s');
            // convert to underscore
            $path = str_replace(['-'], '_', $dateTime) . "_" . Str::snake($path);
            // remove :
            $path = str_replace(':', '', $path);
            // remove space
            $path = str_replace(' ', '_', $path);
        }
        return base_path("modules/{$module}/{$relativePath}{$path}.php");
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
