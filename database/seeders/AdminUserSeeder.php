<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Administrator',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'bio' => 'System administrator with full access to all features.',
                'phone' => '+1234567890',
                'email_verified_at' => now(),
            ]
        );

        // Create a regular user for testing
        User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'email' => 'user@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'bio' => 'Regular user account for testing purposes.',
                'phone' => '+0987654321',
                'email_verified_at' => now(),
            ]
        );
    }
}
