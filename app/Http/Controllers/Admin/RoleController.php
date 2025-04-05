<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Roles\RoleCreateRequest;
use App\Http\Requests\Admin\Roles\RoleIndexRequest;
use App\Http\Requests\Admin\Roles\RoleUpdateRequest;
use App\Models\Admin\Role;
use App\Repositories\Admin\RoleRepository;
use App\Services\Admin\MenusPermissions;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class RoleController extends Controller
{

    public function __construct(
        protected RoleRepository $roleRepository,
        protected MenusPermissions $menusPermissionsService
    ) {

    }

    public function roleIndex(RoleIndexRequest $request)
    {
        $roles = $this->roleRepository->paginatedRoles($request);
        $defaultMenusPermissions = $this->menusPermissionsService->__invoke();
        return Inertia::render('roles/index', [
            "roles" => $roles,
            "default_menus_permissions" => $defaultMenusPermissions
        ]);
    }

    public function store(RoleCreateRequest $request)
    {
        $this->roleRepository->store($request->validated());
        return Redirect::route('role.index');
    }

    public function update(Role $role, RoleUpdateRequest $request)
    {
        $this->roleRepository->update($role, $request->validated());
        return Redirect::route('role.index');
    }
}
