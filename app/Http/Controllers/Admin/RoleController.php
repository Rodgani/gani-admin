<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Roles\RoleCreateRequest;
use App\Http\Requests\Admin\Roles\RoleIndexRequest;
use App\Http\Requests\Admin\Roles\RoleUpdateRequest;
use App\Models\Admin\Role;
use App\Services\Admin\MenusPermissions;
use App\Services\Admin\RoleService;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class RoleController extends Controller
{

    public function __construct(
        protected RoleService $service,
        protected MenusPermissions $menusPermissionsService
    ) {

    }

    public function roleIndex(RoleIndexRequest $request)
    {
        $roles = $this->service->paginatedRoles($request);
        $defaultMenusPermissions = $this->menusPermissionsService->__invoke();
        return Inertia::render('roles/index', [
            "roles" => $roles,
            "default_menus_permissions" => $defaultMenusPermissions
        ]);
    }

    public function store(RoleCreateRequest $request)
    {
        $this->service->store($request->validated());
        return Redirect::route('role.index');
    }

    public function update(Role $role, RoleUpdateRequest $request)
    {
        $this->service->update($role, $request->validated());
        return Redirect::route('role.index');
    }
}
