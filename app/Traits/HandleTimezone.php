<?php

declare(strict_types=1);

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

trait HandleTimezone
{
    protected function convertTimezoneToUserTimezone($value, ?string $format = 'Y F d h:i A')
    {
        if (!$value) {
            return null;
        }

        $timezone = Auth::check() && Auth::user()->timezone ? Auth::user()->timezone : Config::get('app.timezone');
        return Carbon::parse($value)
            ->timezone($timezone)->format($format);
    }
}
