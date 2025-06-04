<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Facades\File;

final class MenuManager
{
    protected array $menus;
    public function __construct()
    {
        $menuFolder = config_path('menus');

        $mergedMenus = collect(File::files($menuFolder))
            ->flatMap(function ($file) {
                $configKey = 'menus.' . basename((string) $file, '.php');
                return config($configKey, []);
            })
            ->values()
            ->toArray();

        $this->menus = $mergedMenus;
    }

    public function getAllMenus(): array
    {
        return $this->menus;
    }
}
