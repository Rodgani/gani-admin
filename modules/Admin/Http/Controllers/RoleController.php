<?php

namespace Modules\Admin\Http\Controllers;

use App\Services\MenusPermissions;
use Inertia\Inertia;
use Modules\Admin\Http\Requests\Roles\RoleCreateRequest;
use Modules\Admin\Http\Requests\Roles\RoleIndexRequest;
use Modules\Admin\Http\Requests\Roles\RoleUpdateRequest;
use Modules\Admin\Models\Role;
use Modules\Admin\Repositories\RoleRepository;

class RoleController extends Controller
{

    public function __construct(
        protected RoleRepository $roleRepository,
        protected MenusPermissions $menusPermissionsService
    ) {

    }

    public function index(RoleIndexRequest $request)
    {
        $roles = $this->roleRepository->paginatedRoles($request);
        $defaultMenusPermissions = $this->menusPermissionsService->__invoke();
        return Inertia::render('admin/roles/index', [
            "roles" => $roles,
            "default_menus_permissions" => $defaultMenusPermissions
        ]);
    }

    public function store(RoleCreateRequest $request)
    {
        $this->roleRepository->store($request->validated());
    }

    public function update(Role $role, RoleUpdateRequest $request)
    {
        $this->roleRepository->update($role, $request->validated());
    }
}
