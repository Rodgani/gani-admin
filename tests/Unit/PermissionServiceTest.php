<?php

namespace Tests\Unit;

use App\Helpers\PermissionHelper;
use App\Services\Admin\MenusPermissions;
use PHPUnit\Framework\TestCase;

class PermissionServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     */

    public function test_parent_menu_exist(): void
    {
        $permissionService = app(PermissionHelper::class);
        $menusPermissions = collect(new MenusPermissions()());
        $this->assertTrue($permissionService
            ->parentMenu("/dashboard")
            ->authorize("can-create", $menusPermissions));
    }


}
