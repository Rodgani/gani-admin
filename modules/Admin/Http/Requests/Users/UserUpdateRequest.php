<?php

declare(strict_types=1);

namespace Modules\Admin\Http\Requests\Users;

use App\Helpers\PermissionHelper;
use App\Helpers\TimezoneHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $permission = app(PermissionHelper::class);

        return $permission
            ->forUser($this->user())
            ->subMenu("/admin/users")
            ->can("update");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    protected function prepareForValidation(): void
    {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }
    public function rules(): array
    {
        return [
            "id" => 'required|exists:users,id',
            "name" => 'required',
            "email" => "required|email|unique:users,email,{$this->id},id",
            "role" => "required|exists:roles,id",
            "country" => [
                'required',
                Rule::in(TimezoneHelper::getKeys()),
            ],
        ];
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated();
        $validated['role_id'] = $validated['role'];
        $validated['timezone'] = $validated['country'];
        unset($validated['role'], $validated['country']);
        return $key ? ($validated[$key] ?? $default) : $validated;
    }
}
