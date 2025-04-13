<?php

namespace Tests\Unit;

use App\Helpers\PermissionHelper;
use App\Services\MenusPermissions;
use PHPUnit\Framework\TestCase;

class PermissionHelperTest extends TestCase
{
    /**
     * A basic unit test example.
     */

    public function test_parent_menu_and_permission_exist(): void
    {
        $permissionService = app(PermissionHelper::class);
        $menusPermissions = collect(new MenusPermissions()());
        $this->assertTrue($permissionService
            ->parentMenu("/dashboard")
            ->authorize("create", $menusPermissions));
    }

    public function test_sub_menu_and_permission_exist(): void
    {
        $permissionService = app(PermissionHelper::class);
        $menusPermissions = collect(new MenusPermissions()());
        $this->assertTrue($permissionService
            ->subMenu("/admin/users")
            ->authorize("create", $menusPermissions));
    }

    public function test_parent_menu_not_exist(): void
    {
        $permissionService = app(PermissionHelper::class);
        $menusPermissions = collect(new MenusPermissions()());
        $this->assertFalse($permissionService
            ->parentMenu("/parent/menu")
            ->authorize("create", $menusPermissions));
    }

    public function test_sub_menu_not_exist(): void
    {
        $permissionService = app(PermissionHelper::class);
        $menusPermissions = collect(new MenusPermissions()());
        $this->assertFalse($permissionService
            ->subMenu("/sub/menu")
            ->authorize("create", $menusPermissions));
    }


    public function test_parent_menu_and_permission_not_exist(): void
    {
        $permissionService = app(PermissionHelper::class);
        $menusPermissions = collect(new MenusPermissions()());
        $this->assertFalse($permissionService
            ->parentMenu("/dashboard")
            ->authorize("dummy-permission", $menusPermissions));
    }

    public function test_sub_menu_and_permission_not_exist(): void
    {
        $permissionService = app(PermissionHelper::class);
        $menusPermissions = collect(new MenusPermissions()());
        $this->assertFalse($permissionService
            ->parentMenu("/admin/users")
            ->authorize("dummy-permission", $menusPermissions));
    }


}
