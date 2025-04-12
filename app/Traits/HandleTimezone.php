<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Auth;
use Carbon\Carbon;
use Config;


trait HandleTimezone
{
    protected function createdAt(): Attribute
    {
        return Attribute::get(fn($value) => $this->convertTimezoneToUserTimezone($value));
    }

    protected function updatedAt(): Attribute
    {
        return Attribute::get(fn($value) => $this->convertTimezoneToUserTimezone($value));
    }
    protected function convertTimezoneToUserTimezone($value)
    {
        $timezone = Auth::check() && Auth::user()->timezone ? Auth::user()->timezone : Config::get('app.timezone');
        return Carbon::parse($value)
            ->timezone($timezone)
            ->format('Y d F h:i A');
    }
}
