<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\UserCreateRequest;
use App\Http\Requests\Admin\Users\UserDeleteRequest;
use App\Http\Requests\Admin\Users\UserIndexRequest;
use App\Http\Requests\Admin\Users\UserUpdateRequest;
use App\Models\Admin\User;
use App\Services\Admin\RoleService;
use App\Services\Admin\UserService;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class UserController extends Controller
{
    public function __construct(
        protected UserService $service,
        protected RoleService $roleService
    ) {
    }

    public function userIndex(UserIndexRequest $request)
    {
        $users = $this->service->users($request);
        $roles = $this->roleService->roles();
        return Inertia::render('users/index', [
            "users" => $users,
            "roles" => $roles
        ]);
    }

    public function destroy(User $user, UserDeleteRequest $request)
    {
        $request->validated();
        $this->service->destroy($user);
        return Redirect::route('user.index');
    }

    public function update(User $user, UserUpdateRequest $request)
    {
        $this->service->update($user, $request->validated());
        return Redirect::route('user.index');
    }

    public function store(UserCreateRequest $request)
    {
        $this->service->store($request->validated());
        return Redirect::route('user.index');
    }
}
