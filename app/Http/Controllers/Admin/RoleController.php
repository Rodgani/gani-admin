<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Roles\RoleCreateRequest;
use App\Http\Requests\Admin\Roles\RoleIndexRequest;
use App\Http\Requests\Admin\Roles\RoleUpdateRequest;
use App\Services\Admin\MenusPermissions;
use App\Services\Admin\RoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class RoleController extends Controller
{
    /**
     * Summary of __construct
     * @param \App\Services\Admin\RoleService $service
     */
    public function __construct(
        protected RoleService $service,
        protected MenusPermissions $menusPermissionsService
    ) {

    }

    /**
     * Summary of roleIndex
     * @param \App\Http\Requests\Admin\Roles\RoleIndexRequest $request
     * @return \Inertia\Response
     */
    public function roleIndex(RoleIndexRequest $request)
    {
        $roles = $this->service->paginatedRoles($request);
        $defaultMenusPermissions = $this->menusPermissionsService->__invoke();
        return Inertia::render('roles/index', [
            "roles" => $roles,
            "default_menus_permissions" => $defaultMenusPermissions
        ]);
    }

    /**
     * Summary of store
     * @param \App\Http\Requests\Admin\roles\RoleCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RoleCreateRequest $request)
    {
        $this->service->store($request->validated());
        return Redirect::route('role.index');
    }

    /**
     * Summary of update
     * @param \App\Http\Requests\Admin\Roles\RoleUpdateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(RoleUpdateRequest $request)
    {
        $this->service->update($request->id, $request->validated());
        return Redirect::route('role.index');
    }
}
