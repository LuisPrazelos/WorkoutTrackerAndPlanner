<?php

namespace Database\Seeders;

use App\Models\Exercise;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Exercise::insert([
            ['name' => 'Bench Press', 'muscle_group' => 'Chest', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Squat', 'muscle_group' => 'Legs', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Deadlift', 'muscle_group' => 'Back', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Overhead Press', 'muscle_group' => 'Shoulders', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Barbell Row', 'muscle_group' => 'Back', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bicep Curl', 'muscle_group' => 'Arms', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tricep Extension', 'muscle_group' => 'Arms', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
