<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ScaffoldService
{
    private $module;
    private $table;
    private $migrationFields;
    private $formRequest;
    private const CONTROLLER = "controller";
    private const MODEL = "model";
    private const REPOSITORY = "repository";
    private const MIGRATION = "migration";
    private const FORM_REQUEST = "request";
    public function generate($request): void
    {
        $this->module = Str::ucfirst($request->module);
        $this->table = Str::studly($request->table);
        $this->migrationFields = $request->fields;
        $this->formRequest = $request->form_request;
        $name = ucfirst($this->table);

        // form request
        if (!empty($this->formRequest)) {
            $type = self::FORM_REQUEST;
            $this->formRequest = [
                "{$this->table}IndexRequest",
                "{$this->table}CreateRequest",
                "{$this->table}UpdateRequest",
                "{$this->table}DestroyRequest",
            ];

            foreach ($this->formRequest as $formRequestClass) {
                $this->createFile($type, $this->module, $formRequestClass);
            }
        }
        // controller
        $type = self::CONTROLLER;
        $controllerClass = $name . ucfirst($type);
        $this->createFile($type, $this->module, $controllerClass);

        // model
        $type = self::MODEL;
        $modelClass = $name;
        $this->createFile($type, $this->module, $modelClass);

        // repository
        $type = self::REPOSITORY;
        $repositoryClass = $name . ucfirst($type);
        $this->createFile($type, $this->module, $repositoryClass);

        // migration
        $type = self::MIGRATION;
        $migrationClass = $name;
        $this->createFile($type, $this->module, $migrationClass);
    }

    private function createFile(string $type, string $module, string $name): void
    {
        $stubPath = $this->getStub($type);

        $className = class_basename($name);
        $namespace = $this->resolveNamespace($type, $module, $name);

        $targetPath = $this->resolvePath($type, $module, $name);

        if (file_exists($targetPath)) {
            throw ValidationException::withMessages([
                'failed' => "⚠️ File already exists at: $targetPath",
            ]);
        }

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
            'request' => 'Http/Requests/',
            'controller' => 'Http/Controllers/',
            'model' => 'Models/',
            'repository' => 'Repositories/',
            'migration' => "Database/Migrations/",
            default => '',
        };

        // Convert namespace to path
        $path = str_replace('\\', '/', $name);
        if ($type === "migration") {

            $lastTableSegment = Str::afterLast(Str::snake($name), '/');
            $path = str_replace('\\', '/', Str::lower(Str::plural("create_" . $lastTableSegment)));

            $dateTime = Carbon::now()->format('Y-m-d H:i:s');
            // convert to underscore
            $path = str_replace(['-'], '_', $dateTime) . "_" . Str::snake($path);
            // remove :
            $path = str_replace(':', '', $path);
            // remove space
            $path = str_replace(' ', '_', $path) . "_table";
        }

        return base_path("modules/{$module}/{$relativePath}{$path}.php");
    }

    private function resolveNamespace(string $type, string $module, string $name): string
    {
        $base = match ($type) {
            'request' => "Modules\\{$module}\\Http\\Requests",
            'controller' => "Modules\\{$module}\\Http\\Controllers",
            'model' => "Modules\\{$module}\\Models",
            'repository' => "Modules\\$module\\Repositories",
            'migration' => "Modules\\{$module}\\Database\\Migrations",
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
        $model = $this->table;
        $modelVariable = Str::lower($model);
        $module = $this->module;
        $modelNamespacePath = Str::replace('/', '\\', $model);
        $lastTableSegment = Str::afterLast($model, '/');
        $pluralTable = Str::lower(Str::plural($lastTableSegment));
        $formRequest = $this->formRequest;
        $migrationFields = $this->migrationFields;

        [$search, $replace] = match ($type) {

            self::CONTROLLER => (function () use (
                $module,
                $namespace,
                $className,
                $lastTableSegment,
                $modelNamespacePath,
                $modelVariable,
                $pluralTable,
                $formRequest,
            ) {
                $repository = Str::ucfirst($lastTableSegment) . Str::ucfirst(self::REPOSITORY);
                $repositoryNamespace = Str::ucfirst($modelNamespacePath) . Str::ucfirst(self::REPOSITORY);
               

                $model = $lastTableSegment;
                $pageModule = Str::lower($module);
                $subModule = Str::lower($model);
                $pluralVariable = $pluralTable;
                
                return StubService::controller(
                    $module,
                    $namespace,
                    $className,
                    $repository,
                    $model,
                    $pageModule,
                    $formRequest,
                    $modelVariable,
                    $subModule,
                    $repositoryNamespace,
                    $pluralVariable
                );
            })(),

            self::REPOSITORY => (function () use (
                $module,
                $namespace,
                $className,
                $lastTableSegment,
                $modelVariable,
                $modelNamespacePath,
                $pluralTable,
            ) {
                $list = $pluralTable;
                $model = $lastTableSegment;
                return StubService::repository(
                    $module,
                    $namespace,
                    $className,
                    $list,
                    $model,
                    $modelVariable,
                    $modelNamespacePath
                );
            })(),

            self::MIGRATION => (function () use (
                $migrationFields,
                $lastTableSegment,
            ) {
                $table = Str::lower(Str::plural(Str::snake($lastTableSegment)));
                return StubService::migration($table, $migrationFields);
            })(),
            self::FORM_REQUEST => (function () use (
                $namespace,
                $className,
                $migrationFields,
            ) {
                return StubService::formRequest($namespace, $className,$migrationFields);
            })(),

            default => [
                ['{{ namespace }}', '{{ class }}'],
                [$namespace, $className]
            ]
        };

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
