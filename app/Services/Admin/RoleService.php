<?php

namespace App\Services\Admin;

use App\Models\Admin\Role;

class RoleService
{
    public function roles()
    {
        return Role::all("slug", "name");
    }

    public function paginatedRoles($request)
    {
        return Role::
            orderBy('updated_at', 'desc')
            ->paginate($request->per_page ?? 10);
    }
}
