<?php

namespace App\Enums;

use Illuminate\Support\Collection;

enum PublicRoleEnum: string
{
    case CONTENT_CREATOR = 'content-creator';
    case BRAND_OWNER = 'brand-owner';
    public static function slugArray(): Collection
    {
        return collect([
            self::CONTENT_CREATOR->value,
            self::BRAND_OWNER->value,
        ]);
    }
}