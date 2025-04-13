<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Admin\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(10)->create();
    }
}
