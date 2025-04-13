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

class UserController extends Controller
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
        $this->userRepository->destroy($user);
    }

    public function update(User $user, UserUpdateRequest $request)
    {
        $this->userRepository->update($user, $request->validated());
    }

    public function store(UserCreateRequest $request)
    {
        $this->userRepository->store($request->validated());
    }
}
