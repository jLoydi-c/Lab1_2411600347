<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // admin account so the system is ready right after seeding
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@buildright.com',
            'password' => bcrypt('password123'),
            'is_admin' => true,
        ]);

        $this->call(ProductSeeder::class);
    }
}