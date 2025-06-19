<?php

declare(strict_types=1);

namespace App\Helpers;

final class TimezoneHelper
{
    /**
     * Get all supported timezone keys (e.g. "Asia/Manila").
     *
     * @return array<int, string>
     */
    public static function getKeys()
    {
        return array_keys(config('app.supported_timezones'));
    }

    /**
     * Get all supported timezones with labels.
     *
     * @return array<string, string>
     */
    public static function getAll()
    {
        return config('app.supported_timezones');
    }
}
