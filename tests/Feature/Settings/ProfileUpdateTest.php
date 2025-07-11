<?php

namespace Tests\Feature\Settings;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Admin\Models\Role;
use Modules\Admin\Models\User;
use Tests\TestCase;

class ProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed()
    {
        Role::factory()->create();
        $user = User::factory()->create();
        /** @var \Modules\Admin\Models\User $user */
        $response = $this
            ->actingAs($user)
            ->get('/settings/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated()
    {
        Role::factory()->create();
        $user = User::factory()->create();
        /** @var \Modules\Admin\Models\User $user */
        $response = $this->get('/settings/profile');
        $response = $this
            ->actingAs($user)
            ->patch('/settings/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
                '_token' => csrf_token(),
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/settings/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged()
    {
        Role::factory()->create();
        $user = User::factory()->create();
        /** @var \Modules\Admin\Models\User $user */
        $response = $this->get('/settings/profile');
        $response = $this
            ->actingAs($user)
            ->patch('/settings/profile', [
                'name' => 'Test User',
                'email' => $user->email,
                '_token' => csrf_token(),
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/settings/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account()
    {
        Role::factory()->create();
        $user = User::factory()->create();
        /** @var \Modules\Admin\Models\User $user */
        $response = $this->get('/settings/profile');
        $response = $this
            ->actingAs($user)
            ->delete('/settings/profile', [
                'password' => 'password',
                '_token' => csrf_token(),
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account()
    {
        Role::factory()->create();
        $user = User::factory()->create();
        /** @var \Modules\Admin\Models\User $user */
        $response = $this->get('/settings/profile');
        $response = $this
            ->actingAs($user)
            ->from('/settings/profile')
            ->delete('/settings/profile', [
                'password' => 'wrong-password',
                '_token' => csrf_token(),
            ]);

        $response
            ->assertSessionHasErrors('password')
            ->assertRedirect('/settings/profile');

        $this->assertNotNull($user->fresh());
    }
}
