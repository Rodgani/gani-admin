<?php

namespace App\Enums;

use Illuminate\Support\Collection;

enum UserRoleTypeEnum: string
{
    case INTERNAL = 'internal';
    case EXTERNAL = 'external';
    public static function array(): Collection
    {
        return collect([
            self::INTERNAL->value,
            self::EXTERNAL->value,
        ]);
    }
}