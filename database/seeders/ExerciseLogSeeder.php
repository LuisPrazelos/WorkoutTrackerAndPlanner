<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\ExerciseLog;
use App\Models\User;
use App\Models\WorkoutSchema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExerciseLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Zoek de benodigde data op
        $user = User::where('email', 'test@example.com')->first();
        $schema = WorkoutSchema::where('name', 'Full Body Strength')->first();
        $benchPress = Exercise::where('name', 'Bench Press')->first();
        $squat = Exercise::where('name', 'Squat')->first();

        // 2. Alleen doorgaan als alle data is gevonden
        if ($user && $schema && $benchPress && $squat) {
            ExerciseLog::insert([
                [
                    'user_id' => $user->id,
                    'workout_schema_id' => $schema->id,
                    'exercise_id' => $benchPress->id,
                    'sets' => 3,
                    'reps' => 8,
                    'weight' => 80.5,
                    'notes' => 'Felt strong today.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'user_id' => $user->id,
                    'workout_schema_id' => $schema->id,
                    'exercise_id' => $squat->id,
                    'sets' => 4,
                    'reps' => 10,
                    'weight' => 100.0,
                    'notes' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }
}
