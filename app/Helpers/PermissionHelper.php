<?php

namespace App\Helpers;

class PermissionHelper
{
    private ?string $parentMenu = "#";
    private ?string $subMenu = null;
    private $userMenusPermissions;
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
        $this->userMenusPermissions = collect(json_decode($user->role->menus_permissions, true));
        return $this;
    }
    public function can(string $action): bool
    {
        $defaultMenusPermissions = collect(app(MenusPermissions::class)());
        $valid = $this->validateMenuPermission($this->parentMenu, $this->subMenu, $action, $defaultMenusPermissions);

        if (!$valid) {
            return false;
        }

        return $this->validateMenuPermission($this->parentMenu, $this->subMenu, $action,$this->userMenusPermissions);
    }

    private function validateMenuPermission(string $parentMenuUrl, ?string $subMenuUrl, string $action, $menusPermissions): bool
    {
        if (!$subMenuUrl) {
            // Find parent by URL
            $parent = $menusPermissions->firstWhere('url', $parentMenuUrl);
            if (!$parent) {
                return false;
            }

            return collect($parent['permissions'] ?? [])->contains($action);
        } else {
            // Find parent with url = '#' that contains the submenu with $subMenuUrl
            $parentWithSubmenu = $menusPermissions->first(function ($menu) use ($parentMenuUrl, $subMenuUrl) {
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
