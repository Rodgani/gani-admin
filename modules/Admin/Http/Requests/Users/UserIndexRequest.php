<?php

namespace Modules\Admin\Http\Requests\Users;

use App\Helpers\PermissionHelper;
use App\Traits\ValidatedAsObject;
use Illuminate\Foundation\Http\FormRequest;

class UserIndexRequest extends FormRequest
{
    use ValidatedAsObject;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $permission = app(PermissionHelper::class);

        return $permission
            ->forUser($this->user())
            ->subMenu("/admin/users")
            ->can("view");
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
            "per_page" => "sometimes|integer|min:1",
            "order_by" => "sometimes|string|in:name,email,updated_at,created_at",
            "order_direction" => "sometimes|string|in:asc,desc"
        ];
    }

}
