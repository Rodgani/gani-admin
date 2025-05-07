<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ScaffoldService
{
    private $module;
    private $table;

    private const CONTROLLER = "controller";
    private const MODEL = "model";

    public function generate($request): mixed
    {
        $module = $request->module;
        $this->module = $module;

        $table = $request->table;
        $this->table = $table;

        $fields = $request->fields;
        $name = ucfirst($table);

        // controller
        $type = self::CONTROLLER;
        $controllerClass = $name . ucfirst($type);
        $this->createFile($type, $module, $controllerClass);

        // model
        $type = self::MODEL;
        $modelClass = $name;
        $this->createFile($type,$module, $modelClass);

         // repository
         $type = "repository";
         $repositoryClass = $name.ucfirst($type);
         $this->createFile($type,$module, $repositoryClass);

        return true;
    }

    private function createFile(string $type, string $module, $name): void
    {
        $stubPath = $this->getStub($type);

        $className = class_basename($name);
        $namespace = $this->resolveNamespace($type, $module, $name);

        $targetPath = $this->resolvePath($type, $module, $name);

        $stub = file_get_contents($stubPath);

        $content = $this->resolveContent($namespace, $className, $stub, $type);


        if (!is_dir(dirname($targetPath))) {
            mkdir(dirname($targetPath), 0755, true);
        }

        file_put_contents($targetPath, $content);
    }
    private function resolvePath(string $type, string $module, $name): string
    {
        // Normalize path (convert forward slashes to namespace style)
        $name = str_replace('/', '\\', $name);
        $name = Str::studly($name); // Capitalize segments

        $relativePath = match ($type) {
            'controller' => 'Http/Controllers/',
            'model' => 'Models/',
            'repository' => 'Repositories/',
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

    private function resolveNamespace(string $type, string $module, string $name): string
    {
        $base = match ($type) {
            'controller' => "Modules\\{$module}\\Http\\Controllers",
            'model' => "Modules\\{$module}\\Models",
            'repository' => "Modules\\$module\\Repositories",
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

    private function resolveContent(string $namespace, string $className, $stub, string $type)
    {
        $search = [];
        $replace = [];

        if ($type === self::CONTROLLER) {
            $model = $this->table;
            $module = $this->module;
            $repository = Str::ucfirst($this->table) . "Repository";
            $modelVariable = Str::lower($this->table);
            $subModule = Str::lower(Str::plural($model));
            $formRequest = "Request";

            $search = [
                '{{ module }}',
                '{{ namespace }}',
                '{{ class }}',
                '{{ repository }}',
                '{{ model }}',
                '{{ pageModule }}',
                '{{ formRequest }}',
                '{{ modelVariable }}',
                '{{ pageSubModule }}'
            ];
           
            $replace = [
                $module,
                $namespace,
                $className,
                $repository,
                $model,
                Str::lower($module),
                $formRequest,
                $modelVariable,
                $subModule
            ];
        }else{
            $search = [
                '{{ namespace }}',
                '{{ class }}',
            ];
            $replace = [
                $namespace,
                $className,
            ];
        }

        return str_replace(
            $search,
            $replace,
            $stub
        );
    }

    private function getStub(string $type)
    {

        return base_path("app/stubs/{$type}.module.stub");
    }
}
