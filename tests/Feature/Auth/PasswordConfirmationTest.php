<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Admin\Models\Role;
use Modules\Admin\Models\User;
use Tests\TestCase;

class PasswordConfirmationTest extends TestCase
{
    use RefreshDatabase;

    public function test_confirm_password_screen_can_be_rendered()
    {
        Role::factory()->create();
        $user = User::factory()->create();

        /** @var \Modules\Admin\Models\User $user */
        $response = $this->actingAs($user)->get('/confirm-password');

        $response->assertStatus(200);
    }

    public function test_password_can_be_confirmed()
    {
        Role::factory()->create();
        $user = User::factory()->create();

        // Visit confirm-password page first to ensure session & CSRF are initialized
        /** @var \Modules\Admin\Models\User $user */
        $this->actingAs($user)->get('/confirm-password');

        // Post to confirm-password
        $response = $this->actingAs($user)->post('/confirm-password', [
            'password' => 'password',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(); // Default redirect: intended route
        $response->assertSessionHasNoErrors();
    }

    public function test_password_is_not_confirmed_with_invalid_password()
    {
        Role::factory()->create();
        $user = User::factory()->create();

        // Hit GET first to bootstrap session
        /** @var \Modules\Admin\Models\User $user */
        $this->actingAs($user)->get('/confirm-password');

        $response = $this->actingAs($user)->post('/confirm-password', [
            'password' => 'wrong-password',
            '_token' => csrf_token(),
        ]);

        $response->assertStatus(302); // still redirected
        $this->assertFalse(session()->has('auth.password_confirmed_at')); // ✅ password not confirmed
    }
}
