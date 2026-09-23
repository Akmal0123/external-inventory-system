<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Integration Service User (Section 7.1)
        User::updateOrCreate(
            ['username' => 'integration-service'],
            [
                'name' => 'Integration Service Account',
                'email' => 'integration@external-system.local',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Default Administrator User
        User::updateOrCreate(
            ['email' => 'admin@external-system.local'],
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'password' => Hash::make('password'),
            ]
        );

        // 3. Test User (test@mail.com / password123)
        User::updateOrCreate(
            ['email' => 'test@mail.com'],
            [
                'name' => 'Test User',
                'username' => 'test@mail.com',
                'password' => Hash::make('password123'),
            ]
        );
    }
}
