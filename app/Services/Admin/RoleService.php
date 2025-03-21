<?php

namespace App\Services\Admin;

use App\Helper\PaginationHelper;
use App\Models\Admin\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
        $options = PaginationHelper::pageQueryOptions($request);
        $column = $options->column;
        $direction = $options->direction;
        $perPage = $options->perPage;

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
            ->orderBy($column, $direction)
            ->paginate($perPage);
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
