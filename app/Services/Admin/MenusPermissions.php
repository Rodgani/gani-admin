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
                "permissions" => ["view", "search", "create", "update", "delete"]
            ],
            [
                "title" => "Users Management",
                "url" => "#",
                "icon" => "SquareTerminal",
                "items" => [
                    [
                        "title" => "Users",
                        "url" => "/admin/users",
                        "permissions" => ["view", "search", "create", "update", "delete"]
                    ],
                    [
                        "title" => "Roles & Permissions",
                        "url" => "/admin/roles",
                        "permissions" => ["view", "search", "create", "update"]
                    ]
                ]
            ],
        ];

        return $menus;
    }

}
