<?php

namespace Modules\Sample\Http\Requests\Categories;
use Illuminate\Foundation\Http\FormRequest;


class CategoryUpdateRequest extends FormRequest
{
    
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
           "id" => "required",
           "name" => "required|string",
           "description" => "required|string",
        ];
    }
}
