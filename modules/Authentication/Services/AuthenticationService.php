<?php

declare(strict_types=1);

namespace Modules\Authentication\Services;

use Illuminate\Support\Facades\Auth;

final class AuthenticationService
{
    public function storeUserSession($request): void
    {
        $request->authenticate();

        $request->session()->regenerate();
    }

    public function destroyUserSession($request): void
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}