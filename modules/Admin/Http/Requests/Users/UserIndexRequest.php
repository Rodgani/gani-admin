<?php

namespace Modules\Admin\Http\Requests\Users;

use App\Helpers\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;

class UserIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $permissionService = app(PermissionHelper::class);
        $userPermissions = $permissionService->userPermissions($this->user());

        return $permissionService
            ->subMenu("/admin/users")
            ->authorize("view", $userPermissions);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "paginated" => "sometimes|boolean",
            "per_page" => "sometimes|integer|min:1",
            "order_by" => "sometimes|string|in:name,email,updated_at,created_at",
            "order_direction" => "sometimes|string|in:asc,desc"
        ];
    }

}
