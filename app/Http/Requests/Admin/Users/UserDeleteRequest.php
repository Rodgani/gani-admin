<?php

namespace App\Http\Requests\Admin\Users;

use App\Helpers\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;

class UserDeleteRequest extends FormRequest
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
            ->authorize("delete", $userPermissions);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
