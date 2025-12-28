<?php

namespace Database\Seeders;

use App\Models\User;
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
        // Create a Trainer user
        User::factory()->create([
            'name' => 'Trainer User',
            'email' => 'trainer@example.com',
            'password' => Hash::make('password'),
            'role' => 'trainer',
        ]);

        // Create a Member user
        User::factory()->create([
            'name' => 'Member User',
            'email' => 'member@example.com',
            'password' => Hash::make('password'),
            'role' => 'member',
        ]);

        // Create another Member user
        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => Hash::make('password'),
            'role' => 'member',
        ]);

        // Create an Admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
    }
}
