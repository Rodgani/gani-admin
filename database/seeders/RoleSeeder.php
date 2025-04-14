<?php

namespace Database\Seeders;

use App\Services\MenusPermissions;
use Illuminate\Database\Seeder;

use Modules\Admin\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menusPermissions = new MenusPermissions();
        Role::create([
            "name" => "Administrator",
            "slug" => "admin",
            "menus_permissions" => json_encode($menusPermissions(), true)
        ]);

    }
}
