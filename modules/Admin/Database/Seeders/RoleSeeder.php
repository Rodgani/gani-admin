<?php

declare(strict_types=1);

namespace Modules\Admin\Database\Seeders;

use App\Enums\UserRoleTypeEnum;
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
        $contentCreator = 'content-creator';
        $brandOwner = 'brand-owner';
       
        Role::updateOrCreate(
            ["slug" => "admin"],
            [
                "name" => "Administrator",
                "slug" => "admin",
                "menus_permissions" => $MenuManager->getAllMenus(),
                "type" => UserRoleTypeEnum::INTERNAL
            ]
        );
        Role::updateOrCreate(
            ["slug" => $contentCreator],
            [
                "name" => "Content Creator",
                "slug" => $contentCreator,
                "menus_permissions" => config('menus.1_statistic_menus'),
                "type" => UserRoleTypeEnum::EXTERNAL
            ]
        );
        Role::updateOrCreate(
            ["slug" => $brandOwner],
            [
                "name" => "Brand Owner",
                "slug" => $brandOwner,
                "menus_permissions" => config('menus.1_statistic_menus'),
                "type" => UserRoleTypeEnum::EXTERNAL
            ]
        );
    }
}
