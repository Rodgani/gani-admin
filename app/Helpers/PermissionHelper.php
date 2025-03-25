<?php

namespace App\Helpers;

use App\Services\Admin\MenusPermissions;

class PermissionHelper
{
    private ?string $parentMenu = "#";
    private ?string $subMenu = null;

    public function userPermissions($auth)
    {
        return collect(json_decode($auth->role->menus_permissions, true));
    }

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

    public function authorize(string $permission, $userPermissions): bool
    {
        $menusPermissions = collect(new MenusPermissions()());
        $valid = $this->validateMenuPermission($this->parentMenu, $this->subMenu, $permission, $menusPermissions);

        if (!$valid) {
            return false;
        }

        return $this->validateMenuPermission($this->parentMenu, $this->subMenu, $permission, $userPermissions);
    }

    private function validateMenuPermission(string $parentMenuUrl, ?string $subMenuUrl, string $permission, $menusPermissions): bool
    {
        if (!$subMenuUrl) {
            // Find parent by URL
            $parent = $menusPermissions->firstWhere('url', $parentMenuUrl);
            if (!$parent) {
                return false;
            }

            return collect($parent['permissions'] ?? [])->contains($permission);
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
            return collect($submenu['permissions'] ?? [])->contains($permission);
        }
    }

}
