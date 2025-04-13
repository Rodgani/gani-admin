<?php

namespace Modules\Authentication\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Authentication\Services\PasswordService;

class ConfirmablePasswordController extends Controller
{
    public function __construct(private PasswordService $passwordService)
    {
    }
    public function show(): Response
    {
        return Inertia::render('auth/confirm-password');
    }

    /**
     * Confirm the user's password.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->passwordService->confirmablePassword($request);
        return redirect()->intended(route('dashboard', absolute: false));
    }
}
