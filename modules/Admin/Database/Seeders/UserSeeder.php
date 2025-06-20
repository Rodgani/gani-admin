<?php

declare(strict_types=1);

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Admin\Models\Role;
use Modules\Admin\Models\User;

final class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create(
            [
                "name" => "Admin",
                "email" => "admin@gmail.com",
                'email_verified_at' => now(),
                "password" => Hash::make("password"),
                "role_id" => Role::first()->id,
                "timezone" => "Asia/Manila",
                "country" => "Philippines"
            ]
        );

        User::factory(20)->create();
    }
}
