<?php

namespace Modules\Admin\Http\Requests\Users;

use App\Helpers\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            ->can("update");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = request("user")->id;

        return [
            "name" => "required",
            "email" => "required|email|unique:users,email,$id,id",
            "role_id" => "required"
        ];
    }
}
