<?php

return  [
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
    ]
];
