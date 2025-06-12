<?php

declare(strict_types=1);

namespace Modules\Admin\Http\Controllers;

use App\Enums\UserRoleTypeEnum;
use App\Helpers\MenuManager;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Admin\Http\Requests\Roles\RoleCreateRequest;
use Modules\Admin\Http\Requests\Roles\RoleIndexRequest;
use Modules\Admin\Http\Requests\Roles\RoleUpdateRequest;
use Modules\Admin\Repositories\RoleRepository;
use Modules\Controller;

final class RoleController extends Controller
{

    public function __construct(
        private RoleRepository $roleRepository,
        private MenuManager $MenuManager
    ) {}

    public function index(RoleIndexRequest $request): Response
    {
        $roles = $this->roleRepository->paginatedRoles($request->validatedObject());
        return Inertia::render('admin/role/index', [
            "roles" => $roles,
            "default_menus_permissions" => $this->MenuManager->getAllMenus(),
            "role_types" => UserRoleTypeEnum::array()
        ]);
    }

    public function store(RoleCreateRequest $request): RedirectResponse
    {
        $roleData = $request->validated();
        $this->roleRepository->storeRole($roleData);
        return back();
    }

    public function update(RoleUpdateRequest $request): RedirectResponse
    {
        $roleData = $request->validated();
        $this->roleRepository->updateRole((int) $roleData['id'], $roleData);
        return back();
    }
}
