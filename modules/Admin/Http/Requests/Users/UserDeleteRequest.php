<?php

declare(strict_types=1);

namespace Modules\Admin\Http\Requests\Users;

use App\Helpers\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;

final class UserDeleteRequest extends FormRequest
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
            ->can("delete");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            "id" => $this->route("id"),
        ]);
    }
    public function rules(): array
    {
        return [
            "id" => "required|exists:users,id",
        ];
    }
}
