<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\UserCreateRequest;
use App\Http\Requests\Admin\Users\UserDeleteRequest;
use App\Http\Requests\Admin\Users\UserIndexRequest;
use App\Http\Requests\Admin\Users\UserUpdateRequest;
use App\Models\Admin\User;
use App\Repositories\Admin\RoleRepository;
use App\Repositories\Admin\UserRepository;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

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
        return Redirect::route('user.index');
    }

    public function update(User $user, UserUpdateRequest $request)
    {
        $this->userRepository->update($user, $request->validated());
        return Redirect::route('user.index');
    }

    public function store(UserCreateRequest $request)
    {
        $this->userRepository->store($request->validated());
        return Redirect::route('user.index');
    }
}
