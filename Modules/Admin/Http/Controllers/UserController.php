<?php

namespace Modules\Admin\Http\Controllers;

use Modules\Admin\Http\Requests\Users\UserCreateRequest;
use Modules\Admin\Http\Requests\Users\UserDeleteRequest;
use Modules\Admin\Http\Requests\Users\UserIndexRequest;
use Modules\Admin\Http\Requests\Users\UserUpdateRequest;
use Modules\Admin\Models\User;
use Inertia\Inertia;
use Modules\Admin\Repositories\RoleRepository;
use Modules\Admin\Repositories\UserRepository;
use Modules\BaseController;

class UserController extends BaseController
{
    public function __construct(
        protected UserRepository $userRepository,
        protected RoleRepository $roleRepository
    ) {
    }

    public function index(UserIndexRequest $request)
    {
        $users = $this->userRepository->users($request);
        $roles = $this->roleRepository->roles();

        return Inertia::render('admin/users/index', [
            "users" => $users,
            "roles" => $roles
        ]);
    }

    public function destroy(User $user, UserDeleteRequest $request)
    {
        $request->validated();
        $this->userRepository->destroyUser($user);
    }

    public function update(User $user, UserUpdateRequest $request)
    {
        $this->userRepository->updateUser($user, $request->validated());
    }

    public function store(UserCreateRequest $request)
    {
        $this->userRepository->storeUser($request->validated());
    }
}
