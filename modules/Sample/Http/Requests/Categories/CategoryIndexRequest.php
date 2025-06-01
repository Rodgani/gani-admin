<?php

namespace Modules\Sample\Http\Requests\Categories;
use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ValidatedAsObject;

class CategoryIndexRequest extends FormRequest
{
    use ValidatedAsObject;
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
            "search"    => "sometimes|string",
            "page"      => "sometimes|integer",
            "paginated" => "sometimes|boolean",
            "per_page"  => "sometimes|integer|min:1",
            "order_by"  => "sometimes|string|in:updated_at,created_at",
            "order_direction" => "sometimes|string|in:asc,desc",
        ];
    }
}
