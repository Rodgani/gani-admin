<?php

namespace App\Services\Admin;

use App\Models\Admin\Role;

class RoleService
{
    public function roles()
    {
        return Role::all("slug", "name");
    }
}
