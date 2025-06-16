<?php

namespace Tests\Feature\Auth;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Modules\Admin\Models\Role;
use Modules\Admin\Models\User;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered()
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested()
    {
        Notification::fake();
        Role::factory()->create();
        $user = User::factory()->create();
        $this->get('/forgot-password');
        $this->post('/forgot-password', [
            'email' => $user->email,
            '_token' => csrf_token(),
        ]);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_reset_password_screen_can_be_rendered()
    {
        Notification::fake();
        Role::factory()->create();
        $user = User::factory()->create();
        $this->get('/forgot-password');
        $this->post('/forgot-password', [
            'email' => $user->email,
            '_token' => csrf_token(),
        ]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
            $response = $this->get('/reset-password/' . $notification->token);

            $response->assertStatus(200);

            return true;
        });
    }

    public function test_password_can_be_reset_with_valid_token()
    {
        Notification::fake();
        Role::factory()->create();
        $user = User::factory()->create();
        $this->get('/forgot-password');
        $this->post('/forgot-password', [
            'email' => $user->email,
            '_token' => csrf_token(),
        ]);
        $this->get('/reset-password');
        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $response = $this->post('/reset-password', [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
                '_token' => csrf_token(),
            ]);

            $response
                ->assertSessionHasNoErrors()
                ->assertRedirect(route('login'));

            return true;
        });
    }
}
