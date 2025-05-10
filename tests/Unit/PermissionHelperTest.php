<?php

namespace Tests\Unit;

use App\Helpers\PermissionHelper;
use App\Services\MenusPermissions;
use Modules\Admin\Models\User;
use PHPUnit\Framework\TestCase;

class PermissionHelperTest extends TestCase
{
    /**
     * A basic unit test example.
     */

    public function test_parent_menu_and_permission_exist(): void
    {
        $permissionService = app(PermissionHelper::class);
        $user = User::first();
        $this->assertTrue($permissionService
            ->forUser($user)
            ->parentMenu("/dashboard")
            ->can("create"));
    }

    public function test_sub_menu_and_permission_exist(): void
    {
        $permissionService = app(PermissionHelper::class);
        $user = User::first();
        $this->assertTrue($permissionService
            ->forUser($user)
            ->subMenu("/admin/users")
            ->can("create"));
    }

    public function test_parent_menu_not_exist(): void
    {
        $permissionService = app(PermissionHelper::class);
        $user = User::first();
        $this->assertFalse($permissionService
            ->forUser($user)
            ->parentMenu("/parent/menu")
            ->can("create"));
    }

    public function test_sub_menu_not_exist(): void
    {
        $permissionService = app(PermissionHelper::class);
        $user = User::first();
        $this->assertFalse($permissionService
            ->forUser($user)
            ->subMenu("/sub/menu")
            ->can("create"));
    }


    public function test_parent_menu_and_permission_not_exist(): void
    {
        $permissionService = app(PermissionHelper::class);
        $user = User::first();
        $this->assertFalse($permissionService
            ->forUser($user)
            ->parentMenu("/dashboard")
            ->can("dummy-permission"));
    }

    public function test_sub_menu_and_permission_not_exist(): void
    {
        $permissionService = app(PermissionHelper::class);
        $user = User::first();
        $this->assertFalse($permissionService
            ->forUser($user)
            ->parentMenu("/admin/users")
            ->can("dummy-permission"));
    }

}
