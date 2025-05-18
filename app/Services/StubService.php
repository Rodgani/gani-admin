<?php

namespace App\Services;

class StubService
{
    public static function controller(
        string $module,
        string $namespace,
        string $className,
        string $repository,
        string $model,
        string $pageModule,
        mixed $formRequest,
        string $modelVariable,
        string $subModule,
        string $repositoryNamespace,
        string $findVariable
    ) {

        // Use index positions with fallback
        $indexFormRequest   = $formRequest[0] ?? 'Request';
        $storeFormRequest   = $formRequest[1] ?? 'Request';
        $updateFormRequest  = $formRequest[2] ?? 'Request';
        $destroyFormRequest = $formRequest[3] ?? 'Request';
        
        if (is_array($formRequest)) {
            $importRequests = collect($formRequest)->map(function ($request) use ($module) {
                return "use Modules\\$module\\Http\\Requests\\$request;";
            })->implode("\n");
        }else{
            $importRequests = "";
        }

        return [
            [
                '{{ module }}',
                '{{ namespace }}',
                '{{ class }}',
                '{{ repository }}',
                '{{ model }}',
                '{{ pageModule }}',
                '{{ modelVariable }}',
                '{{ pageSubModule }}',
                '{{ repositoryNamespace }}',
                '{{ findVariable }}',
                '{{ indexFormRequest }}',
                '{{ storeFormRequest }}',
                '{{ updateFormRequest }}',
                '{{ destroyFormRequest }}',
                '{{ import requests }}'
            ],
            [
                $module,
                $namespace,
                $className,
                $repository,
                $model,
                $pageModule,
                $modelVariable,
                $subModule,
                $repositoryNamespace,
                $findVariable,
                $indexFormRequest,
                $storeFormRequest,
                $updateFormRequest,
                $destroyFormRequest,
                $importRequests
            ]
        ];
    }

    public static function repository(
        string $module,
        string $namespace,
        string $className,
        string $list,
        string $model,
        string $modelVariable,
        string $modelNamespacePath
    ) {
        return [
            [
                '{{ module }}',
                '{{ namespace }}',
                '{{ class }}',
                '{{ list }}',
                '{{ model }}',
                '{{ modelVariable }}',
                '{{ modelNamespace }}'
            ],
            [
                $module,
                $namespace,
                $className,
                $list,
                $model,
                $modelVariable,
                $modelNamespacePath
            ]
        ];
    }

    public static function migration(string $table, array $migrationFields)
    {

        $fieldLines = collect($migrationFields)->map(function ($field) {
            $line = '$table->' . $field['type'] . "('{$field['name']}')";

            if (!empty($field['defaultValue'])) {
                $default = is_numeric($field['defaultValue']) ? $field['defaultValue'] : "'{$field['defaultValue']}'";
                $line .= "->default({$default})";
            }

            if ($field['nullable']) {
                $line .= '->nullable()';
            }

            if (!empty($field['comment'])) {
                $line .= "->comment('{$field['comment']}')";
            }

            return $line . ';';
        })->implode("\n            ");

        return [
            [
                '{{ table }}',
                '{{ fields }}'
            ],
            [
                $table,
                $fieldLines
            ]
        ];
    }
}
