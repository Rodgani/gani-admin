<?php

namespace Modules\Authentication\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Authentication\Http\Requests\LoginRequest;
use Modules\Authentication\Services\AuthenticationService;
use Modules\Controller;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login page.
     */
    public function __construct(private AuthenticationService $authenticationService)
    {
    }
    public function index(Request $request): Response
    {
        return Inertia::render('auth/login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $this->authenticationService->storeUserSession($request);
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $this->authenticationService->destroyUserSession($request);
        return redirect('/');
    }
}
