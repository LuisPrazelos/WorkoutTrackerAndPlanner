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
        // Keep seeded users stable across repeated deploys.
        User::updateOrCreate([
            'email' => 'trainer@example.com',
        ], [
            'name' => 'Trainer User',
            'password' => Hash::make('password'),
            'role' => 'trainer',
        ]);

        User::updateOrCreate([
            'email' => 'member@example.com',
        ], [
            'name' => 'Member User',
            'password' => Hash::make('password'),
            'role' => 'member',
        ]);

        User::updateOrCreate([
            'email' => 'john.doe@example.com',
        ], [
            'name' => 'John Doe',
            'password' => Hash::make('password'),
            'role' => 'member',
        ]);

        User::updateOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Admin User',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
    }
}
