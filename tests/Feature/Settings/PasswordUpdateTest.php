<?php

namespace Tests\Feature\Settings;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Modules\Admin\Models\Role;
use Modules\Admin\Models\User;
use Tests\TestCase;

class PasswordUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_can_be_updated()
    {
        Role::factory()->create();
        $user = User::factory()->create();
        $response = $this->get('/settings/password');
        /** @var \Modules\Admin\Models\User $user */
        $response = $this
            ->actingAs($user)
            ->from('/settings/password')
            ->put('/settings/password', [
                'current_password' => 'password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
                '_token' => csrf_token(),
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/settings/password');

        $this->assertTrue(Hash::check('new-password', $user->refresh()->password));
    }

    public function test_correct_password_must_be_provided_to_update_password()
    {
        Role::factory()->create();
        $user = User::factory()->create();
        $response = $this->get('/settings/password');
        /** @var \Modules\Admin\Models\User $user */
        $response = $this
            ->actingAs($user)
            ->from('/settings/password')
            ->put('/settings/password', [
                'current_password' => 'wrong-password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
                '_token' => csrf_token(),
            ]);

        $response
            ->assertSessionHasErrors('current_password')
            ->assertRedirect('/settings/password');
    }
}
