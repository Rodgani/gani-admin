<?php

namespace Modules\Authentication\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Admin\Models\User;
use Modules\Authentication\Http\Requests\RegisterRequest;
use Modules\Authentication\Repositories\RegisterRepository;

class RegisteredUserController extends Controller
{

    public function __construct(private RegisterRepository $registerRepository)
    {
    }
    /**
     * Show the registration page.
     */
    public function index(): Response
    {
        return Inertia::render('auth/register');
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
