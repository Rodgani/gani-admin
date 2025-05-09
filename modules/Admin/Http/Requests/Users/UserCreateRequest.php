<?php

namespace Modules\Admin\Http\Requests\Users;

use App\Helpers\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $permissionHelper = app(PermissionHelper::class);
        
        return $permissionHelper
            ->forUser($this->user())
            ->subMenu("/admin/users")
            ->can("create");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required|string|max:255",
            "email" => "required|email|unique:users,email",
            "password" => "required|string|min:8|confirmed",
            "role_id" => "required|exists:roles,id",
        ];
    }
}
