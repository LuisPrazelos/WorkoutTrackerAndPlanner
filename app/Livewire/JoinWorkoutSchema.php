<?php

namespace App\Livewire;

use App\Models\WorkoutSchema;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JoinWorkoutSchema extends Component
{
    public WorkoutSchema $workoutSchema;

    public function mount(string $token)
    {
        $this->workoutSchema = WorkoutSchema::where('share_token', $token)
            ->with('schemaExercises.exercise')
            ->first();

        if (!$this->workoutSchema) {
            session()->flash('error', 'Deze deel-link is niet meer geldig.');
            return redirect()->route('dashboard');
        }

        // If user already owns this specific schema instance, redirect to it
        if ($this->workoutSchema->user_id === Auth::id()) {
            return redirect()->route('workout-schemas.show', $this->workoutSchema);
        }
    }

    public function import()
    {
        DB::transaction(function () {
            // Create a copy of the schema
            $newSchema = $this->workoutSchema->replicate([
                'user_id', 'share_token', 'is_active', 'active_started_at', 'is_template'
            ]);
            
            $newSchema->user_id = Auth::id();
            $newSchema->is_template = false; // Always a personal schema for the importer
            $newSchema->is_active = false;
            $newSchema->active_started_at = null;
            $newSchema->save(); // This will trigger booted() and generate a NEW share_token

            // Copy exercises
            foreach ($this->workoutSchema->schemaExercises as $exercise) {
                $newSchema->schemaExercises()->create([
                    'exercise_id' => $exercise->exercise_id,
                    'target_sets' => $exercise->target_sets,
                    'target_reps' => $exercise->target_reps,
                ]);
            }
        });

        session()->flash('success', 'Workout schema succesvol aan je lijst toegevoegd!');
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.join-workout-schema')
            ->layout('components.layouts.app', ['title' => 'Schema Bekijken']);
    }
}
