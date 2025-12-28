<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\WorkoutSchema;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AssignSchema extends Component
{
    public $memberId = null;
    public $schemaTemplateId = null;

    public function assignSchema()
    {
        $this->validate([
            'memberId' => 'required|exists:users,id',
            'schemaTemplateId' => 'required|exists:workout_schemas,id',
        ]);

        $trainer = Auth::user();
        $member = User::find($this->memberId);
        $template = WorkoutSchema::find($this->schemaTemplateId);

        // Ensure the current user is a trainer and the selected schema is a template
        if ($trainer->isTrainer() && $template->is_template) {
            // Create a copy of the schema for the member
            $newSchema = $template->replicate()->fill([
                'user_id' => $member->id,
                'is_template' => false, // This is now a member's personal copy
                'source_schema_id' => $template->id,
                'scheduled_at' => now()->addDay(), // Default schedule for tomorrow
            ]);
            $newSchema->save();

            // Copy the planned exercises
            foreach ($template->schemaExercises as $exercise) {
                $newSchema->schemaExercises()->create($exercise->only(['exercise_id', 'target_sets', 'target_reps']));
            }

            session()->flash('success', 'Schema assigned successfully to ' . $member->name);
            $this->redirect(route('dashboard'));
        }
    }

    public function render()
    {
        $trainer = Auth::user();

        // Trainers can see all members
        $members = User::where('role', 'member')->get();

        // Trainers can only assign their own templates
        $schemaTemplates = $trainer->workoutSchemas()->where('is_template', true)->get();

        return view('livewire.assign-schema', [
            'members' => $members,
            'schemaTemplates' => $schemaTemplates,
        ])->layout('components.layouts.app', ['title' => 'Assign Schema']);
    }
}
