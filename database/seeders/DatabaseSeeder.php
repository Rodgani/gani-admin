<?php

namespace Database\Seeders;

use App\Models\Admin\Role;
use App\Models\Admin\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::create([
            "name" => "Admin",
            "email" => "admin@gmail.com",
            "password" => Hash::make("password"),
            "role_slug" => Role::first()->slug
        ]);

        $this->call([
            RoleSeeder::class,
            UserSeeder::class
        ]);
    }
}
