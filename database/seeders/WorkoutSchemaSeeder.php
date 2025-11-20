<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WorkoutSchema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkoutSchemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Zoek de Test User op.
        $user = User::where('email', 'test@example.com')->first();

        // 2. Alleen doorgaan als de gebruiker is gevonden.
        if ($user) {
            WorkoutSchema::create([
                'user_id' => $user->id,
                'name' => 'Full Body Strength',
                'description' => 'A balanced workout routine focusing on major muscle groups.',
                'difficulty' => 'intermediate',
            ]);
        }
    }
}
