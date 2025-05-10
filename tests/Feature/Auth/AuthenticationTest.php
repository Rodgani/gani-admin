<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Admin\Models\Role;
use Modules\Admin\Models\User;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen()
    {
        Role::factory()->create();
        $user = User::factory()->create();

        $response = $this->get('/login'); // This initializes the session & CSRF token

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
            '_token' => csrf_token(), // optional, Laravel handles it if session is active
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        Role::factory()->create();
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout()
    {
        Role::factory()->create();
        $user = User::factory()->create();

        // Visit any page to initiate session and CSRF
        $this->actingAs($user)->get('/');

        $response = $this->post('/logout', [
            '_token' => csrf_token(), // include CSRF token
        ]);

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
