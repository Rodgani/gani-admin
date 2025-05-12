<?php

namespace App\Helpers;

class MenusPermissions
{
    protected array $menus;
    public function __construct()
    {
        $this->menus = [
            [
                "title" => "Dashboard",
                "url" => "/dashboard",
                "icon" => "LayoutDashboard",
                "permissions" => ["view", "search", "create", "update", "delete"]
            ],
            [
                "title" => "Admin",
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
    }

    public function __invoke(): array
    {
        return $this->menus;
    }
}
