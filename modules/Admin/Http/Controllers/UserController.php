<?php

declare(strict_types=1);

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Modules\Admin\Http\Requests\Users\UserCreateRequest;
use Modules\Admin\Http\Requests\Users\UserDeleteRequest;
use Modules\Admin\Http\Requests\Users\UserIndexRequest;
use Modules\Admin\Http\Requests\Users\UserUpdateRequest;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Admin\Repositories\RoleRepository;
use Modules\Admin\Repositories\UserRepository;
use Modules\Controller;

final class UserController extends Controller
{
    public function __construct(
        protected UserRepository $userRepository,
        protected RoleRepository $roleRepository
    ) {}

    public function index(UserIndexRequest $request): Response
    {
        $users = $this->userRepository->users($request->validatedObject());
        $roles = $this->roleRepository->roles();

        return Inertia::render('admin/user/index', [
            "users" => $users,
            "roles" => $roles,
            "timezones" => config('app.supported_timezones')
        ]);
    }

    public function destroy(UserDeleteRequest $request): RedirectResponse
    {
        $this->userRepository->destroyUser($request->validated()['id']);
        return back();
    }

    public function update(UserUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $this->userRepository->updateUser($validated['id'], $validated);
        return back();
    }

    public function store(UserCreateRequest $request): RedirectResponse
    {
        $this->userRepository->storeUser($request->validated());
        return back();
    }
}
