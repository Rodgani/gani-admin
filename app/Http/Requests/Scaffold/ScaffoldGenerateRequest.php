<?php

declare(strict_types=1);

namespace App\Http\Requests\Scaffold;

use Illuminate\Foundation\Http\FormRequest;

final class ScaffoldGenerateRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'fields' => json_decode($this->fields, true),
        ]);
    }
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "module" => ['required', 'string', 'not_regex:/\//'],
            "table" => ['required', 'string', 'not_regex:/\//'],
            "form_request" => 'sometimes|boolean',
            "fields" => 'required|array',
            "fields.*.name" => 'required|string',
            "fields.*.type" => 'required|string',
        ];
    }
}
