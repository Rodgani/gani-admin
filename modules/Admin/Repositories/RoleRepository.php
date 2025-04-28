<?php

namespace Modules\Admin\Repositories;

use App\Constants\AdminConstants;
use App\Helpers\PaginationHelper;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Admin\Models\Role;

class RoleRepository
{
    public function __construct(private Role $roleModel)
    {
    }
    public function roles(): Collection
    {
        return $this->roleModel->select('id', 'slug', 'name')->get();
    }

    public function paginatedRoles($request): LengthAwarePaginator
    {
        $search = $request->search;
        $option = PaginationHelper::pageQueryOptions($request);

        return Role::when($search, function ($query, $search) {
            $query->whereAny(['name', 'slug'], 'like', "%{$search}%");
        })
            ->when($request->user()->id != AdminConstants::DEFAULT_ADMIN_ID, function ($query) {
                $query->whereNot('id', AdminConstants::DEFAULT_ROLE_ID);
            })
            ->orderBy($option->column, $option->direction)
            ->paginate($option->perPage);
    }

    public function storeRole($request): Role
    {
        return $this->roleModel->create($request);
    }

    public function updateRole(Role $role, $request): bool
    {
        // here you are updating an existing Role instance
        return $role->update($request);
    }
}
