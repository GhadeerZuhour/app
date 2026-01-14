<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::firstOrCreate(
            ['email' => 'admin@tazreem.test'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Subscriber (Tenant)
        User::firstOrCreate(
            ['email' => 'subscriber@tazreem.test'],
            [
                'name' => 'Main Subscriber',
                'password' => Hash::make('password'),
                'role' => 'subscriber',
            ]
        );
    }
}
