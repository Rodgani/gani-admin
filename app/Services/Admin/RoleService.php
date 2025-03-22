<?php

namespace App\Services\Admin;

use App\Constants\AdminConstant;
use App\Helpers\PaginationHelper;
use App\Models\Admin\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class RoleService
{
    public function roles()
    {
        return Role::all("slug", "name");
    }

    /**
     * Summary of paginatedRoles
     * @param mixed $request
     * @return LengthAwarePaginator
     */
    public function paginatedRoles($request): LengthAwarePaginator
    {
        $search = $request->search;
        $option = PaginationHelper::pageQueryOptions($request);
        return Role::
            when($search, function ($query, $search) {
                $query->whereAny(
                    [
                        'name',
                        'slug'
                    ],
                    'like',
                    "%{$search}%"
                );
            })
            // default user only have access at admin role
            ->when($request->user()->id != AdminConstant::DEFAULT_ADMIN_ID, function ($query) {
                $query->whereNot('id', AdminConstant::DEFAULT_ROLE_ID);
            })
            ->orderBy($option->column, $option->direction)
            ->paginate($option->perPage);
    }

    /**
     * Summary of store
     * @param mixed $request
     * @return Role
     */
    public function store($request): Role
    {
        return Role::create($request);
    }

    /**
     * Summary of update
     * @param int $id
     * @param mixed $request
     * @return bool
     */
    public function update(int $id, $request)
    {
        return Role::findOrFail($id)->update($request);
    }
}
