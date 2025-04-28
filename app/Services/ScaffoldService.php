<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Str;

class ScaffoldService
{

    public static function resolvePath(string $type, string $module, $name): string
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

    public static function resolveNamespace(string $type, string $module, string $name): string
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

    public static function resolveContent(string $namespace, string $className, $stub)
    {
        return str_replace(
            ['{{ namespace }}', '{{ class }}'],
            [$namespace, $className],
            $stub
        );
    }
}
