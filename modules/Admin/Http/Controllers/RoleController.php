<?php

namespace Modules\Admin\Http\Controllers;

use App\Helpers\MenusPermissions;
use Inertia\Inertia;
use Modules\Admin\Http\Requests\Roles\RoleCreateRequest;
use Modules\Admin\Http\Requests\Roles\RoleIndexRequest;
use Modules\Admin\Http\Requests\Roles\RoleUpdateRequest;
use Modules\Admin\Repositories\RoleRepository;
use Modules\Controller;

class RoleController extends Controller
{

    public function __construct(
        protected RoleRepository $roleRepository,
        protected MenusPermissions $menusPermissions
    ) {}

    public function index(RoleIndexRequest $request)
    {
        $roles = $this->roleRepository->paginatedRoles($request->validatedAsObject());
        $defaultMenusPermissions = ($this->menusPermissions)();
        return Inertia::render('admin/roles/index', [
            "roles" => $roles,
            "default_menus_permissions" => $defaultMenusPermissions
        ]);
    }

    public function store(RoleCreateRequest $request)
    {
        $this->roleRepository->storeRole($request->validated());
        return back();
    }

    public function update(RoleUpdateRequest $request)
    {
        $validated = $request->validated();
        $this->roleRepository->updateRole($validated['id'], $validated);
        return back();
    }
}
