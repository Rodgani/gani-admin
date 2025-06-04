<?php

declare(strict_types=1);

namespace App\Helpers;

final class PermissionHelper
{
    private ?string $parentMenu = "#";
    private ?string $subMenu = null;
    private $userMenuManager;
    public function parentMenu(string $parentMenu): self
    {
        $this->parentMenu = $parentMenu;
        return $this;
    }

    public function subMenu(string $subMenu): self
    {
        $this->subMenu = $subMenu;
        return $this;
    }

    public function forUser(object $user): static
    {
        $this->userMenuManager = collect($user->role->menus_permissions);
        return $this;
    }
    public function can(string $action): bool
    {
        $defaultMenuManager = collect(app(MenuManager::class)->getAllMenus());
        $valid = $this->validateMenuPermission($this->parentMenu, $this->subMenu, $action, $defaultMenuManager);

        if (!$valid) {
            return false;
        }

        return $this->validateMenuPermission($this->parentMenu, $this->subMenu, $action, $this->userMenuManager);
    }

    private function validateMenuPermission(string $parentMenuUrl, ?string $subMenuUrl, string $action, $MenuManager): bool
    {
        if (!$subMenuUrl) {
            // Find parent by URL
            $parent = $MenuManager->firstWhere('url', $parentMenuUrl);
            if (!$parent) {
                return false;
            }

            return collect($parent['permissions'] ?? [])->contains($action);
        } else {
            // Find parent with url = '#' that contains the submenu with $subMenuUrl
            $parentWithSubmenu = $MenuManager->first(function ($menu) use ($parentMenuUrl, $subMenuUrl) {
                if (($menu['url'] === '#' || $menu['url'] === $parentMenuUrl) && isset($menu['items'])) {
                    return collect($menu['items'])->firstWhere('url', $subMenuUrl);
                }
                return false;
            });

            if (!$parentWithSubmenu) {
                return false;
            }

            // Find submenu by URL and check permission
            $submenu = collect($parentWithSubmenu['items'])->firstWhere('url', $subMenuUrl);
            return collect($submenu['permissions'] ?? [])->contains($action);
        }
    }
}
