<?php

namespace Database\Factories\Admin;

use App\Models\Admin\Role;
use App\Models\Admin\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Summary of UsersFactory
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            "timezone" => Config::get('app.timezone'),
            'role_id' => Role::first()?->id, // Ensure Role exists
        ];
    }
}
