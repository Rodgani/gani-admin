<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Admin\Models\Role;
use Modules\Admin\Models\User;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page()
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_authenticated_users_can_visit_the_dashboard()
    {
        Role::factory()->create();
        $user = User::factory()->create();
        /** @var \Modules\Admin\Models\User $user */
        $this->actingAs($user);

        $this->get('/dashboard')->assertOk();
    }
}
