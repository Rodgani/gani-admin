<?php

declare(strict_types=1);

namespace Modules\Authentication\Http\Controllers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Admin\Repositories\RoleRepository;
use Modules\Authentication\Http\Requests\RegisterRequest;
use Modules\Authentication\Repositories\RegisterRepository;
use Modules\Controller;

final class RegisteredUserController extends Controller
{

    public function __construct(
        private RegisterRepository $registerRepository,
        private RoleRepository $roleRepository
    ){}
    /**
     * Show the registration page.
     */
    public function index(): Response
    {
        $roles =$this->roleRepository->externalUserRoles();
        return Inertia::render('auth/register',[
            "roles" => $roles,
            "timezones" => config('app.supported_timezones')
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request): RedirectResponse
    {

        $user = $this->registerRepository->storeUser($request->validated());

        event(new Registered($user));

        Auth::login($user);

        return to_route('dashboard');
    }
}
