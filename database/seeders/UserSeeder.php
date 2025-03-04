<?php

namespace Database\Seeders;

use App\Models\Admin\Role;
use App\Models\Admin\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::create([
            "name" => "Admin",
            "email" => "admin@gmail.com",
            "password" => Hash::make("password"),
            "role_slug" => Role::first()->slug
        ]);
        User::factory()->count(10)->create();
    }
}
