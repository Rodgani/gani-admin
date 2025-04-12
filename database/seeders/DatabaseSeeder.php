<?php

namespace Database\Seeders;

use App\Models\Admin\Role;
use App\Models\Admin\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        User::create([
            "name" => "Admin",
            "email" => "admin@gmail.com",
            "password" => Hash::make("password"),
            'remember_token' => Str::random(10),
            "role_id" => Role::first()->id,
            "timezone" => Config::get('app.timezone')
        ]);

        $this->call([
            UserSeeder::class
        ]);

    }
}
