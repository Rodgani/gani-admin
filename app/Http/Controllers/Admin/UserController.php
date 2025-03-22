<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\UserCreateRequest;
use App\Http\Requests\Admin\Users\UserDeleteRequest;
use App\Http\Requests\Admin\Users\UserIndexRequest;
use App\Http\Requests\Admin\Users\UserUpdateRequest;
use App\Services\Admin\RoleService;
use App\Services\Admin\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Summary of __construct
     * @param \App\Services\Admin\UserService $service
     * @param \App\Services\Admin\RoleService $roleService
     */
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

    /**
     * Summary of destroy
     * @param \App\Http\Requests\Admin\Users\UserDeleteRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(UserDeleteRequest $request)
    {
        $this->service->destroy($request->id);
        return Redirect::route('user.index');
    }

    /**
     * Summary of update
     * @param \App\Http\Requests\Admin\Users\UserUpdateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserUpdateRequest $request)
    {
        $this->service->update($request->id, $request->validated());
        return Redirect::route('user.index');
    }

    /**
     * Summary of store
     * @param \App\Http\Requests\Admin\Users\UserCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserCreateRequest $request)
    {
        $this->service->store($request->validated());
        return Redirect::route('user.index');
    }
}
