<?php

namespace Modules\Admin\Http\Requests\Roles;

use App\Helpers\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;

class RoleCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $permissionHelper = app(PermissionHelper::class);
        
        return $permissionHelper
            ->forUser($this->user())
            ->subMenu("/admin/roles")
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
            'name' => 'required',
            'slug' => 'required|unique:roles,slug',
            'menus_permissions' => 'required|json'
        ];
    }
}
