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
    public static function getTimezones(): array
    {
        return array_keys(config('app.supported_countries'));
    }

    /**
     * Summary of getTimezone
     * @param string $country
     */
    public static function getTimezone(string $country): ?string
    {
        $timezones = config('app.supported_countries', []);

        foreach ($timezones as $timezone => $label) {
            if (stripos($label, $country) !== false) {
                return $timezone;
            }
        }

        return null;
    }


    /**
     * Get all supported timezones with labels.
     *
     * @return array<string, string>
     */
    public static function getAll(): array
    {
        return collect(config('app.supported_countries'))->toArray();
    }

    /**
     * Summary of getCountry
     * @param string $timezone
     */
    public static function getCountry(string $timezone): ?string
    {
        return config('app.supported_countries.' . $timezone);
    }

    /**
     * Summary of getCountry
     * @param string $timezone
     */
    public static function getCountries(): array
    {
        return array_values(config('app.supported_countries'));
    }
}
