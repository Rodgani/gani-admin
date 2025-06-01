<?php

namespace Modules\Admin\Http\Controllers;

use App\Helpers\MenuManager;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
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

    public function index(RoleIndexRequest $request): Response
    {
        $roles = $this->roleRepository->paginatedRoles($request->validatedObject());
        return Inertia::render('admin/role/index', [
            "roles" => $roles,
            "default_menus_permissions" => $this->MenuManager->getAllMenus()
        ]);
    }

    public function store(RoleCreateRequest $request): RedirectResponse
    {
        $this->roleRepository->storeRole($request->validated());
        return redirect()->route('roles.index');
    }

    public function update(RoleUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $this->roleRepository->updateRole($validated['id'], $validated);
        return redirect()->route('roles.index');
    }
}
