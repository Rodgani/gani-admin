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
        string $formRequest,
        string $modelVariable,
        string $subModule,
        string $repositoryNamespace
    ) {
        return [
            [
                '{{ module }}',
                '{{ namespace }}',
                '{{ class }}',
                '{{ repository }}',
                '{{ model }}',
                '{{ pageModule }}',
                '{{ formRequest }}',
                '{{ modelVariable }}',
                '{{ pageSubModule }}',
                '{{ repositoryNamespace }}'
            ],
            [
                $module,
                $namespace,
                $className,
                $repository,
                $model,
                $pageModule,
                $formRequest,
                $modelVariable,
                $subModule,
                $repositoryNamespace
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
