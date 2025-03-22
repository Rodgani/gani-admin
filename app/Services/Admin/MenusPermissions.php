<?php

namespace App\Services\Admin;

class MenusPermissions
{
    /**
     * Icons reference https://lucide.dev/icons/
     */

    /**
     * Summary of __invoke
     * @return array[]
     */
    public function __invoke()
    {
        $menus = [
            [
                "title" => "Dashboard",
                "url" => "/dashboard",
                "icon" => "LayoutDashboard",
                "permissions" => ["view", "create", "update", "delete"]
            ],
            [
                "title" => "Admin",
                "url" => "#",
                "icon" => "SquareTerminal",
                "items" => [
                    [
                        "title" => "Users",
                        "url" => "/admin/users",
                        "permissions" => ["view", "create", "update", "delete"]
                    ],
                    [
                        "title" => "Roles & Permissions",
                        "url" => "/admin/roles",
                        "permissions" => ["view", "create", "update", "delete"]
                    ]
                ]
            ],
        ];

        return $menus;
    }

}
