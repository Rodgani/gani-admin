<?php

namespace Database\Seeders;

use App\Models\Admin\Role;
use App\Models\Admin\User;
use App\Services\Admin\MenusPermissions;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use function Illuminate\Log\log;

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
