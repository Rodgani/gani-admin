<?php

declare(strict_types=1);

namespace Modules\Authentication\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Modules\Admin\Models\User;

final class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Summary of rules
     * @return array{email: string, password: array<Password|string>, token: string}
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => "required|exists:roles,id",
            "country" => [
                "required",
                Rule::in(config('app.supported_timezones')),
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
