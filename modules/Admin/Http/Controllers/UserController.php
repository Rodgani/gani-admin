<?php

namespace Modules\Admin\Http\Controllers;

use Modules\Admin\Http\Requests\Users\UserCreateRequest;
use Modules\Admin\Http\Requests\Users\UserDeleteRequest;
use Modules\Admin\Http\Requests\Users\UserIndexRequest;
use Modules\Admin\Http\Requests\Users\UserUpdateRequest;
use Inertia\Inertia;
use Modules\Admin\Repositories\RoleRepository;
use Modules\Admin\Repositories\UserRepository;
use Modules\Controller;

class UserController extends Controller
{
    public function __construct(
        protected UserRepository $userRepository,
        protected RoleRepository $roleRepository
    ) {}

    public function index(UserIndexRequest $request)
    {
        $users = $this->userRepository->users($request->validatedAsObject());
        $roles = $this->roleRepository->roles();

        return Inertia::render('admin/users/index', [
            "users" => $users,
            "roles" => $roles
        ]);
    }

    public function destroy(UserDeleteRequest $request)
    {
        $this->userRepository->destroyUser($request->validated()['id']);
        return redirect()->route('users.index');
    }

    public function update(UserUpdateRequest $request)
    {
        $validated = $request->validated();
        $this->userRepository->updateUser($validated['id'], $validated);
        return redirect()->route('users.index');
    }

    public function store(UserCreateRequest $request)
    {
        $this->userRepository->storeUser($request->validated());
        return redirect()->route('users.index');
    }
}
