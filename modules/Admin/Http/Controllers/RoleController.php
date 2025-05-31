<?php

namespace Modules\Admin\Http\Controllers;

use App\Helpers\MenuManager;
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
        protected MenuManager $MenuManager
    ) {}

    public function index(RoleIndexRequest $request)
    {
        $roles = $this->roleRepository->paginatedRoles($request->validatedObject());
        return Inertia::render('admin/role/index', [
            "roles" => $roles,
            "default_menus_permissions" => $this->MenuManager->getAllMenus()
        ]);
    }

    public function store(RoleCreateRequest $request)
    {
        $this->roleRepository->storeRole($request->validated());
        return redirect()->route('roles.index');
    }

    public function update(RoleUpdateRequest $request)
    {
        $validated = $request->validated();
        $this->roleRepository->updateRole($validated['id'], $validated);
        return redirect()->route('roles.index');
    }
}
