<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Collection;

final class CountryHelper
{
    public static function getAll(): Collection
    {
        return collect(config('app.supported_countries'))
        ->map(fn($item) => (object) $item);
    }

    public static function getCountry(int $id): ?object
    {
        return self::getAll()->firstWhere('id', $id);
    }
}
