<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WorkoutSchema;
use App\Models\Exercise;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkoutSchemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trainer = User::where('email', 'trainer@example.com')->first();
        $member = User::where('email', 'member@example.com')->first();

        if ($trainer) {
            // Template 1: Full Body
            $fullBody = WorkoutSchema::create([
                'user_id' => $trainer->id,
                'name' => 'Full Body Strength',
                'description' => 'A balanced workout routine focusing on major muscle groups.',
                'difficulty' => 'intermediate',
                'is_template' => true,
            ]);
            $fullBody->schemaExercises()->create(['exercise_id' => Exercise::where('name', 'Squat')->first()->id, 'target_sets' => 3, 'target_reps' => 10]);
            $fullBody->schemaExercises()->create(['exercise_id' => Exercise::where('name', 'Bench Press')->first()->id, 'target_sets' => 3, 'target_reps' => 10]);
            $fullBody->schemaExercises()->create(['exercise_id' => Exercise::where('name', 'Barbell Row')->first()->id, 'target_sets' => 3, 'target_reps' => 10]);

            // Template 2: Chest Day
            $chestDay = WorkoutSchema::create([
                'user_id' => $trainer->id,
                'name' => 'Chest Annihilation',
                'description' => 'A workout focused on building chest strength and size.',
                'difficulty' => 'advanced',
                'is_template' => true,
            ]);
            $chestDay->schemaExercises()->create(['exercise_id' => Exercise::where('name', 'Bench Press')->first()->id, 'target_sets' => 5, 'target_reps' => 5]);
        }

        if ($member) {
            // Create an assigned schema for the member to see on their dashboard
            $memberSchema = WorkoutSchema::create([
                'user_id' => $member->id,
                'name' => 'My First Assigned Workout',
                'description' => 'This is a workout assigned by my trainer.',
                'difficulty' => 'beginner',
                'is_template' => false,
                'source_schema_id' => $fullBody->id ?? null,
            ]);
            $memberSchema->schemaExercises()->create(['exercise_id' => Exercise::where('name', 'Squat')->first()->id, 'target_sets' => 3, 'target_reps' => 8]);
        }
    }
}
