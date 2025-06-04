<?php

declare(strict_types=1);

namespace Modules\Admin\Database\Seeders;

use App\Helpers\MenuManager;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Admin\Models\Role;

final class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $MenuManager = new MenuManager();
        Role::create([
            "name" => "Administrator",
            "slug" => "admin",
            "menus_permissions" => $MenuManager->getAllMenus()
        ]);

    }
}
