<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Str;

final class StubService
{
    public static function controller(
        string $module,
        string $namespace,
        string $className,
        string $repository,
        string $model,
        string $pageModule,
        mixed $formRequest,
        string $subModule,
        string $repositoryNamespace,
        string $pluralVariable
    ) {

        // Use index positions with fallback
        $indexFormRequest   = $formRequest[0] ?? 'Request';
        $storeFormRequest   = $formRequest[1] ?? 'Request';
        $updateFormRequest  = $formRequest[2] ?? 'Request';
        $destroyFormRequest = $formRequest[3] ?? 'Request';

        if (is_array($formRequest)) {
            $importRequests = collect($formRequest)->map(function ($request) use ($module, $model) {
                $pluralModel = Str::plural(Str::studly($model));
                return "use Modules\\$module\\Http\\Requests\\$pluralModel\\$request;";
            })->implode("\n");
        } else {
            $importRequests = "";
        }
        $list = Str::studly($pluralVariable);
        
        return [
            [
                '{{ module }}',
                '{{ namespace }}',
                '{{ class }}',
                '{{ repository }}',
                '{{ model }}',
                '{{ pageModule }}',
                '{{ pageSubModule }}',
                '{{ repositoryNamespace }}',
                '{{ indexFormRequest }}',
                '{{ storeFormRequest }}',
                '{{ updateFormRequest }}',
                '{{ destroyFormRequest }}',
                '{{ import requests }}',
                '{{ pluralVariable }}',
                '{{ list }}'
            ],
            [
                $module,
                $namespace,
                $className,
                $repository,
                $model,
                $pageModule,
                $subModule,
                $repositoryNamespace,
                $indexFormRequest,
                $storeFormRequest,
                $updateFormRequest,
                $destroyFormRequest,
                $importRequests,
                $pluralVariable,
                $list
            ]
        ];
    }

    public static function repository(
        string $module,
        string $namespace,
        string $className,
        string $list,
        string $model,
        string $modelNamespacePath
    ) {
        return [
            [
                '{{ module }}',
                '{{ namespace }}',
                '{{ class }}',
                '{{ list }}',
                '{{ model }}',
                '{{ modelNamespace }}'
            ],
            [
                $module,
                $namespace,
                $className,
                $list,
                $model,
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

    public static function formRequest(string $namespace, string $className, array $migrationFields)
    {
        $validations = 'return [];';
        $rules = [];
        $classLower = Str::lower($className);
        $preValidation = '';

        // Handle IndexRequest
        if (Str::contains($classLower, 'index')) {
            $validations = <<<PHP
            return [
                        "search"    => "sometimes|string",
                        "page"      => "sometimes|integer",
                        "paginated" => "sometimes|boolean",
                        "per_page"  => "sometimes|integer|min:1",
                        "order_by"  => "sometimes|string|in:updated_at,created_at",
                        "order_direction" => "sometimes|string|in:asc,desc",
                    ];
            PHP;
        }

        // Handle CreateRequest
        if (
            Str::contains($classLower, 'create') ||
            Str::contains($classLower, 'update')
        ) {
            if (Str::contains($classLower, 'update')) {
                $preValidation = <<<PHP
                protected function prepareForValidation(): void
                    {
                        \$this->merge([
                            'id' => \$this->route('id'),
                        ]);
                    }
                PHP;
                $rules[] = '"id" => "required"';
            }

            foreach ($migrationFields as $field) {

                if (in_array($field['name'], ['id', 'created_at', 'updated_at'])) {
                    continue;
                }

                // Use "sometimes" if field is nullable, otherwise "required"
                $rulePrefix = $field['nullable'] ? 'sometimes' : 'required';
                $rule = "\"{$field['name']}\" => \"{$rulePrefix}";

                // Append type validation
                switch ($field['type']) {
                    case 'string':
                        $rule .= '|string';
                        break;
                    case 'integer':
                    case 'bigint':
                        $rule .= '|integer';
                        break;
                    case 'boolean':
                        $rule .= '|boolean';
                        break;
                    case 'date':
                    case 'datetime':
                        $rule .= '|date';
                        break;
                        // Add more types as needed
                }

                $rule .= '"';
                $rules[] = $rule;
            }

            $validations = "return [\n   " .
                "        " . implode(",\n           ", $rules) . ",\n" .
                "        ];";
        }

        if (Str::contains($classLower, 'destroy')) {
            $preValidation = <<<PHP
            protected function prepareForValidation(): void
                {
                    \$this->merge([
                        'id' => \$this->route('id'),
                    ]);
                }
            PHP;
            $rules[] = '"id" => "required"';

            $validations = "return [\n   " .
                "        " . implode(",\n           ", $rules) . ",\n" .
                "        ];";
        }

        if (Str::contains($classLower, 'index')) {
            $importValidatedAsObject = "use App\Traits\ValidatedAsObject;";
            $useValidatedAsObject = "use ValidatedAsObject;";
        } else {
            $importValidatedAsObject = "";
            $useValidatedAsObject = "";
        }
        return [
            ['{{ namespace }}', '{{ class }}', '{{ validations }}', '{{ preValidation }}', '{{ importValidatedAsObject }}', '{{ ValidatedAsObject }}'],
            [$namespace, $className, $validations, $preValidation, $importValidatedAsObject, $useValidatedAsObject]
        ];
    }
}
