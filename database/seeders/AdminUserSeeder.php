<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin@anonfeedback.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create sample employees
        \App\Models\User::create([
            'name' => 'John Doe',
            'email' => 'john@anonfeedback.com',
            'password' => bcrypt('password'),
            'role' => 'employee',
            'email_verified_at' => now(),
        ]);

        \App\Models\User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@anonfeedback.com',
            'password' => bcrypt('password'),
            'role' => 'employee',
            'email_verified_at' => now(),
        ]);
    }
}
