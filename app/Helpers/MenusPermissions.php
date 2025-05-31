<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;

class MenusPermissions
{
    protected array $menus;
    public function __construct()
    {
        //     // [
        //     //     "title" => "Dashboard",
        //     //     "url" => "/dashboard",
        //     //     "icon" => "LayoutDashboard",
        //     //     "permissions" => ["view", "search", "create", "update", "delete"]
        //     // ],
        //     ...config('menus_permissions.mp_dashboard'),
        //     [
        //         "title" => "Admin",
        //         "url" => "#",
        //         "icon" => "SquareTerminal",
        //         "items" => [
        //             [
        //                 "title" => "Users",
        //                 "url" => "/admin/users",
        //                 "permissions" => ["view", "search", "create", "update", "delete"]
        //             ],
        //             [
        //                 "title" => "Roles & Permissions",
        //                 "url" => "/admin/roles",
        //                 "permissions" => ["view", "search", "create", "update"]
        //             ]
        //         ]
        //     ],
        // ];
        $menuFolder = config_path('menus_permissions');

        $mergedMenus = collect(File::files($menuFolder))
            ->flatMap(function ($file) {
                $configKey = 'menus_permissions.' . basename($file, '.php');
                return config($configKey, []);
            })
            ->values()
            ->toArray();

        $this->menus = $mergedMenus;
    }

    public function __invoke(): array
    {
        return $this->menus;
    }
}
