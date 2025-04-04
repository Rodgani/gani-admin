<?php

namespace App\Repositories\Admin;

use App\Constants\AdminConstants;
use App\Helpers\PaginationHelper;
use App\Models\Admin\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class RoleRepository
{

    public function roles(): Collection
    {
        return Role::select('slug', 'name')->get();
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

    public function store($request): Role
    {
        return Role::create($request);
    }

    public function update(Role $role, $request): bool
    {
        // here you are updating an existing Role instance
        return $role->update($request);
    }
}
