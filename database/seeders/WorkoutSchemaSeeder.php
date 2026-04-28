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

        // Haal of maak een admin
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
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
        $pushTemplate = WorkoutSchema::updateOrCreate(
            [
                'user_id' => $trainer->id,
                'name' => 'Push Day (Borst, Schouders, Triceps)',
                'is_template' => true,
            ],
            [
                'description' => 'Focus op de duwspieren. Ideaal om kracht en massa op te bouwen in het bovenlichaam.',
                'difficulty' => 'intermediate',
                'category' => 'push',
                'goal' => 'muscle_gain',
                'is_public' => true,
            ]
        );

        $pushExercises = [
            ['exercise' => $chest->where('name', 'Bench Press')->first(), 'sets' => 4, 'reps' => 8],
            ['exercise' => $chest->where('name', 'Incline Dumbbell Press')->first(), 'sets' => 3, 'reps' => 10],
            ['exercise' => $shoulders->where('name', 'Overhead Press')->first(), 'sets' => 4, 'reps' => 8],
            ['exercise' => $shoulders->where('name', 'Lateral Raise')->first(), 'sets' => 3, 'reps' => 12],
            ['exercise' => $triceps->where('name', 'Tricep Pushdown')->first(), 'sets' => 3, 'reps' => 12],
        ];

        $this->syncTemplateExercises($pushTemplate, $pushExercises);

        // 2. PULL SCHEMA
        $pullTemplate = WorkoutSchema::updateOrCreate(
            [
                'user_id' => $trainer->id,
                'name' => 'Pull Day (Rug, Biceps)',
                'is_template' => true,
            ],
            [
                'description' => 'Focus op de trekspieren. Voor een een indrukwekkende V-taper en sterke armen.',
                'difficulty' => 'intermediate',
                'category' => 'pull',
                'goal' => 'muscle_gain',
                'is_public' => true,
            ]
        );

        $pullExercises = [
            ['exercise' => $back->where('name', 'Deadlift')->first(), 'sets' => 4, 'reps' => 5],
            ['exercise' => $back->where('name', 'Pull-Up')->first(), 'sets' => 3, 'reps' => 8],
            ['exercise' => $back->where('name', 'Barbell Row')->first(), 'sets' => 3, 'reps' => 10],
            ['exercise' => $biceps->where('name', 'Barbell Curl')->first(), 'sets' => 3, 'reps' => 10],
            ['exercise' => $biceps->where('name', 'Hammer Curl')->first(), 'sets' => 3, 'reps' => 12],
        ];

        $this->syncTemplateExercises($pullTemplate, $pullExercises);

        // 3. LEGS SCHEMA
        $legsTemplate = WorkoutSchema::updateOrCreate(
            [
                'user_id' => $trainer->id,
                'name' => 'Leg Day (Quads, Hams, Kuiten)',
                'is_template' => true,
            ],
            [
                'description' => 'Sla noot leg day over. Dit schema richt zich op maximale beenontwikkeling.',
                'difficulty' => 'intermediate',
                'category' => 'legs',
                'goal' => 'strength',
                'is_public' => true,
            ]
        );

        $legsExercises = [
            ['exercise' => $legs->where('name', 'Squat')->first(), 'sets' => 4, 'reps' => 6],
            ['exercise' => $legs->where('name', 'Leg Press')->first(), 'sets' => 3, 'reps' => 10],
            ['exercise' => $legs->where('name', 'Romanian Deadlift')->first(), 'sets' => 3, 'reps' => 10],
            ['exercise' => $legs->where('name', 'Leg Curl')->first(), 'sets' => 3, 'reps' => 12],
            ['exercise' => $legs->where('name', 'Calf Raise')->first(), 'sets' => 4, 'reps' => 15],
        ];

        $this->syncTemplateExercises($legsTemplate, $legsExercises);

        // 4. FULL BODY SCHEMA
        $fullBodyTemplate = WorkoutSchema::updateOrCreate(
            [
                'user_id' => $trainer->id,
                'name' => 'Full Body Workout',
                'is_template' => true,
            ],
            [
                'description' => 'De perfecte training waarbij je hele lichaam in 1 sessie wordt aangepakt.',
                'difficulty' => 'beginner',
                'category' => 'fullbody',
                'goal' => 'general',
                'is_public' => true,
            ]
        );

        $fullBodyExercises = [
            ['exercise' => $legs->where('name', 'Squat')->first(), 'sets' => 3, 'reps' => 10],
            ['exercise' => $chest->where('name', 'Bench Press')->first(), 'sets' => 3, 'reps' => 10],
            ['exercise' => $back->where('name', 'Lat Pulldown')->first(), 'sets' => 3, 'reps' => 10],
            ['exercise' => $shoulders->where('name', 'Overhead Press')->first(), 'sets' => 3, 'reps' => 10],
            ['exercise' => $core->where('name', 'Plank')->first(), 'sets' => 3, 'reps' => 60],
        ];

        $this->syncTemplateExercises($fullBodyTemplate, $fullBodyExercises);

        // Geef persoonlijke kopieen aan gebruikers zodat ze direct op hun dashboard staan
        $templates = [$pushTemplate, $pullTemplate, $legsTemplate, $fullBodyTemplate];

        foreach ([$trainer, $member, $admin] as $dashboardUser) {
            foreach ($templates as $idx => $template) {
                $template->loadMissing('schemaExercises');
                $template->assignToUser($dashboardUser, [
                    'scheduled_at' => now()->addDays($idx),
                ]);
            }
        }
    }

    private function syncTemplateExercises(WorkoutSchema $template, array $templateExercises): void
    {
        $template->schemaExercises()->delete();

        foreach ($templateExercises as $exerciseData) {
            if (! $exerciseData['exercise']) {
                continue;
            }

            $template->schemaExercises()->create([
                'exercise_id' => $exerciseData['exercise']->id,
                'target_sets' => $exerciseData['sets'],
                'target_reps' => $exerciseData['reps'],
            ]);
        }
    }
}
