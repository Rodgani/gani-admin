<?php

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Admin\Models\Role;
use Modules\Admin\Models\User;

class UserSeeder extends Seeder
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
                'remember_token' => Str::random(10),
                "role_id" => Role::first()->id,
                "timezone" => "Asia/Manila"
            ]
        );

        User::factory(20)->create();
    }
}
