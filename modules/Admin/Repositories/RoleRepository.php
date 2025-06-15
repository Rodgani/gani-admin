<?php

declare(strict_types=1);

namespace Modules\Admin\Repositories;

use App\Constants\AdminConstants;
use App\Enums\UserRoleTypeEnum;
use App\Helpers\PaginationHelper;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Models\Role;

final class RoleRepository
{
    public function getRoles(): Collection
    {
        return Role::select('id', 'slug', 'name')->get();
    }

    public function getExternalUserRoles(): Collection
    {
        return Role::select('id', 'slug', 'name')
            ->where("type", UserRoleTypeEnum::EXTERNAL)
            ->get();
    }

    public function getPaginatedRoles(object $request): LengthAwarePaginator
    {
        $search = $request->search ?? null;
        $option = PaginationHelper::pageQueryOptions($request);

        return Role::when($search, function ($query, $search) {
            $query->whereAny(['name', 'slug', 'type'], 'like', "%{$search}%");
        })
            ->when(Auth::user()->id != AdminConstants::DEFAULT_ADMIN_ID, function ($query) {
                $query->whereNot('id', AdminConstants::DEFAULT_ROLE_ID);
            })
            ->orderBy($option->column, $option->direction)
            ->paginate($option->perPage);
    }

    public function storeRole(array $roleData): Role
    {
        $roleData['menus_permissions'] = json_decode($roleData['menus_permissions'],true);
        return Role::create($roleData);
    }

    public function updateRole(int $id, array $roleData): bool
    {
        $roleData['menus_permissions'] = json_decode($roleData['menus_permissions'],true);
        return Role::findOrFail($id)->update($roleData);
    }
}
