<?php

declare(strict_types=1);

namespace Modules\Admin\Database\Factories;

use App\Helpers\TimezoneHelper;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Admin\Models\Role;
use Modules\Admin\Models\User;

final class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = User::class;
    public function definition(): array
    {
        $timezone = Arr::random(TimezoneHelper::getTimezones());
        $country = TimezoneHelper::getCountry($timezone);
        $roleId = Role::inRandomOrder()->value('id');
    
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'country' =>  $country,
            'timezone' => $timezone,
            'role_id' => $roleId,
        ];
    }
    
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
