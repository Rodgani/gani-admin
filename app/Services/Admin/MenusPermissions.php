<?php

namespace App\Services\Admin;

class MenusPermissions
{
    public function __invoke()
    {
        $menus = [
            [
                "title" => "Dashboard",
                "url" => "/dashboard",
                "icon" => "LayoutDashboard",
                "permissions" => ["can-read-any", "can-create", "can-update", "can-delete"]
            ],
            [
                "title" => "Admin",
                "url" => "#",
                "icon" => "SquareTerminal",
                "items" => [
                    [
                        "title" => "Users",
                        "url" => "/admin/users",
                        "permissions" => ["can-read-any", "can-create", "can-update", "can-delete"]
                    ],
                    [
                        "title" => "Roles & Permissions",
                        "url" => "/admin/roles",
                        "permissions" => ["can-read-any", "can-create", "can-update", "can-delete"]
                    ]
                ]
            ],
        ];

        return $menus;
    }

}
