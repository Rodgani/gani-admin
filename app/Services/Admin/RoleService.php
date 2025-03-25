<?php

namespace App\Services\Admin;

use App\Constants\AdminConstant;
use App\Helpers\PaginationHelper;
use App\Models\Admin\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RoleService
{
    public function __construct(protected Role $role)
    {
    }

    public function roles()
    {
        return $this->role->select('slug', 'name')->get();
    }

    public function paginatedRoles($request): LengthAwarePaginator
    {
        $search = $request->search;
        $option = PaginationHelper::pageQueryOptions($request);

        return $this->role
            ->when($search, function ($query, $search) {
                $query->whereAny(['name', 'slug'], 'like', "%{$search}%");
            })
            ->when($request->user()->id != AdminConstant::DEFAULT_ADMIN_ID, function ($query) {
                $query->whereNot('id', AdminConstant::DEFAULT_ROLE_ID);
            })
            ->orderBy($option->column, $option->direction)
            ->paginate($option->perPage);
    }

    public function store($request): Role
    {
        return $this->role->create($request);
    }

    public function update(Role $role, $request): bool
    {
        // here you are updating an existing Role instance
        return $role->update($request);
    }
}
