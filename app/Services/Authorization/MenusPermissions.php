<?php

namespace App\Services\Authorization;

class MenusPermissions
{
    public function __invoke()
    {

        $menus = [
            [
                "title" => "Admin",
                "url" => "#",
                "icon" => "SquareTerminal",
                "items" => [
                    [
                        "title" => "Users",
                        "url" => "/admin/users",
                        "permissions" => ["can-read", "can-create", "can-update", "can-delete"]
                    ],
                    [
                        "title" => "Roles",
                        "url" => "/admin/roles",
                        "permissions" => ["can-read", "can-create", "can-update", "can-delete"]
                    ]
                ]
            ],
            [
                "title" => "Profile",
                "url" => "/settings/profile",
                "icon" => "Bot",
                "permissions" => ["can-read", "can-create", "can-update", "can-delete"]
            ]
        ];

        return $menus;
    }

}
