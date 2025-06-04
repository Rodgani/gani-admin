<?php

declare(strict_types=1);

namespace Modules\Authentication\Providers;

use Illuminate\Support\ServiceProvider;

final class AuthProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->register(AuthRouteProvider::class);
    }
}