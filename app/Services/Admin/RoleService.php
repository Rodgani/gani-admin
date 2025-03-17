<?php

namespace App\Services\Admin;

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
            ->orderBy('updated_at', 'desc')
            ->paginate($request->per_page ?? 10);
    }
}
