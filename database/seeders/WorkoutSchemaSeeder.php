<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\User;
use App\Models\WorkoutSchema;
use Illuminate\Database\Seeder;

class WorkoutSchemaSeeder extends Seeder
{
    public function run(): void
    {
        // Haal of maak een trainer
        $trainer = User::where('role', 'trainer')->first() ?? User::factory()->create([
            'name' => 'Trainer',
            'email' => 'trainer@example.com',
            'role' => 'trainer',
        ]);

        // Haal of maak een lid
        $member = User::where('role', 'member')->first() ?? User::factory()->create([
            'name' => 'Test Member',
            'email' => 'member@example.com',
            'role' => 'member',
        ]);

        // Haal oefeningen op per categorie
        $chest = Exercise::where('muscle_group', 'chest')->get();
        $shoulders = Exercise::where('muscle_group', 'shoulders')->get();
        $triceps = Exercise::where('muscle_group', 'triceps')->get();
        $back = Exercise::where('muscle_group', 'back')->get();
        $biceps = Exercise::where('muscle_group', 'biceps')->get();
        $legs = Exercise::where('muscle_group', 'legs')->get();
        $core = Exercise::where('muscle_group', 'core')->get();

        // 1. PUSH SCHEMA
        $pushTemplate = WorkoutSchema::create([
            'user_id' => $trainer->id,
            'name' => 'Push Day (Borst, Schouders, Triceps)',
            'description' => 'Focus op de duwspieren. Ideaal om kracht en massa op te bouwen in het bovenlichaam.',
            'difficulty' => 'intermediate',
            'category' => 'push',
            'goal' => 'muscle_gain',
            'is_template' => true,
        ]);

        $pushExercises = [
            ['exercise' => $chest->where('name', 'Bench Press')->first(), 'sets' => 4, 'reps' => 8],
            ['exercise' => $chest->where('name', 'Incline Dumbbell Press')->first(), 'sets' => 3, 'reps' => 10],
            ['exercise' => $shoulders->where('name', 'Overhead Press')->first(), 'sets' => 4, 'reps' => 8],
            ['exercise' => $shoulders->where('name', 'Lateral Raise')->first(), 'sets' => 3, 'reps' => 12],
            ['exercise' => $triceps->where('name', 'Tricep Pushdown')->first(), 'sets' => 3, 'reps' => 12],
        ];

        foreach ($pushExercises as $ex) {
            if ($ex['exercise']) {
                $pushTemplate->schemaExercises()->create([
                    'exercise_id' => $ex['exercise']->id,
                    'target_sets' => $ex['sets'],
                    'target_reps' => $ex['reps'],
                ]);
            }
        }

        // 2. PULL SCHEMA
        $pullTemplate = WorkoutSchema::create([
            'user_id' => $trainer->id,
            'name' => 'Pull Day (Rug, Biceps)',
            'description' => 'Focus op de trekspieren. Voor een een indrukwekkende V-taper en sterke armen.',
            'difficulty' => 'intermediate',
            'category' => 'pull',
            'goal' => 'muscle_gain',
            'is_template' => true,
        ]);

        $pullExercises = [
            ['exercise' => $back->where('name', 'Deadlift')->first(), 'sets' => 4, 'reps' => 5],
            ['exercise' => $back->where('name', 'Pull-Up')->first(), 'sets' => 3, 'reps' => 8],
            ['exercise' => $back->where('name', 'Barbell Row')->first(), 'sets' => 3, 'reps' => 10],
            ['exercise' => $biceps->where('name', 'Barbell Curl')->first(), 'sets' => 3, 'reps' => 10],
            ['exercise' => $biceps->where('name', 'Hammer Curl')->first(), 'sets' => 3, 'reps' => 12],
        ];

        foreach ($pullExercises as $ex) {
            if ($ex['exercise']) {
                $pullTemplate->schemaExercises()->create([
                    'exercise_id' => $ex['exercise']->id,
                    'target_sets' => $ex['sets'],
                    'target_reps' => $ex['reps'],
                ]);
            }
        }

        // 3. LEGS SCHEMA
        $legsTemplate = WorkoutSchema::create([
            'user_id' => $trainer->id,
            'name' => 'Leg Day (Quads, Hams, Kuiten)',
            'description' => 'Sla noot leg day over. Dit schema richt zich op maximale beenontwikkeling.',
            'difficulty' => 'intermediate',
            'category' => 'legs',
            'goal' => 'strength',
            'is_template' => true,
        ]);

        $legsExercises = [
            ['exercise' => $legs->where('name', 'Squat')->first(), 'sets' => 4, 'reps' => 6],
            ['exercise' => $legs->where('name', 'Leg Press')->first(), 'sets' => 3, 'reps' => 10],
            ['exercise' => $legs->where('name', 'Romanian Deadlift')->first(), 'sets' => 3, 'reps' => 10],
            ['exercise' => $legs->where('name', 'Leg Curl')->first(), 'sets' => 3, 'reps' => 12],
            ['exercise' => $legs->where('name', 'Calf Raise')->first(), 'sets' => 4, 'reps' => 15],
        ];

        foreach ($legsExercises as $ex) {
            if ($ex['exercise']) {
                $legsTemplate->schemaExercises()->create([
                    'exercise_id' => $ex['exercise']->id,
                    'target_sets' => $ex['sets'],
                    'target_reps' => $ex['reps'],
                ]);
            }
        }

        // 4. FULL BODY SCHEMA
        $fullBodyTemplate = WorkoutSchema::create([
            'user_id' => $trainer->id,
            'name' => 'Full Body Workout',
            'description' => 'De perfecte training waarbij je hele lichaam in 1 sessie wordt aangepakt.',
            'difficulty' => 'beginner',
            'category' => 'fullbody',
            'goal' => 'general',
            'is_template' => true,
        ]);

        $fullBodyExercises = [
            ['exercise' => $legs->where('name', 'Squat')->first(), 'sets' => 3, 'reps' => 10],
            ['exercise' => $chest->where('name', 'Bench Press')->first(), 'sets' => 3, 'reps' => 10],
            ['exercise' => $back->where('name', 'Lat Pulldown')->first(), 'sets' => 3, 'reps' => 10],
            ['exercise' => $shoulders->where('name', 'Overhead Press')->first(), 'sets' => 3, 'reps' => 10],
            ['exercise' => $core->where('name', 'Plank')->first(), 'sets' => 3, 'reps' => 60], // 60 rep stands for seconds here for simplicity
        ];

        foreach ($fullBodyExercises as $ex) {
            if ($ex['exercise']) {
                $fullBodyTemplate->schemaExercises()->create([
                    'exercise_id' => $ex['exercise']->id,
                    'target_sets' => $ex['sets'],
                    'target_reps' => $ex['reps'],
                ]);
            }
        }

        // --- Toewijzen aan de Member ---
        // We geven de member persoonlijke kopieën zodat ze direct op z'n dashboard staan
        $templates = [$pushTemplate, $pullTemplate, $legsTemplate, $fullBodyTemplate];

        foreach ($templates as $idx => $template) {
            $assignedSchema = $template->replicate()->fill([
                'user_id' => $member->id,
                'is_template' => false,
                'source_schema_id' => $template->id,
                'scheduled_at' => now()->addDays($idx), // Plan ze in op opeenvolgende dagen
            ]);
            $assignedSchema->save();

            foreach ($template->schemaExercises as $exercise) {
                $assignedSchema->schemaExercises()->create($exercise->only(['exercise_id', 'target_sets', 'target_reps']));
            }
        }
    }
}
