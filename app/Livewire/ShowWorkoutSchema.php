<?php
namespace App\Livewire;

use App\Models\WorkoutSchema;
use App\Models\Exercise;
use App\Models\ExerciseLog;
use App\Models\SchemaExercise;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class ShowWorkoutSchema extends Component
{
    public WorkoutSchema $workoutSchema;

    // This will hold the member's input for each planned exercise
    public $logs = [];

    public function mount(WorkoutSchema $workoutSchema): RedirectResponse|null
    {
        $isOwner = $workoutSchema->user_id === Auth::id();
        $isTrainerViewingTemplate = Auth::user()->isTrainer() && $workoutSchema->is_template;

        if (!($isOwner || $isTrainerViewingTemplate)) {
            session()->flash('error', 'You do not have access to this workout schema.');
            return redirect()->route('dashboard');
        }

        $this->workoutSchema = $workoutSchema;

        // Initialize the logs array with existing data or defaults
        foreach ($this->workoutSchema->schemaExercises as $plannedExercise) {
            $this->logs[$plannedExercise->id] = [
                'sets' => $plannedExercise->target_sets,
                'reps' => $plannedExercise->target_reps,
                'weight' => '',
            ];
        }

        return null;
    }

    // Log a specific exercise from the planned list
    public function logExercise($schemaExerciseId)
    {
        $plannedExercise = SchemaExercise::find($schemaExerciseId);
        if (!$plannedExercise || $this->workoutSchema->is_template) return;

        $logData = $this->logs[$schemaExerciseId];

        $this->validate([
            "logs.{$schemaExerciseId}.sets" => 'required|integer|min:1',
            "logs.{$schemaExerciseId}.reps" => 'required|integer|min:1',
            "logs.{$schemaExerciseId}.weight" => 'required|numeric|min:0',
        ]);

        ExerciseLog::create([
            'user_id' => Auth::id(),
            'workout_schema_id' => $this->workoutSchema->id,
            'exercise_id' => $plannedExercise->exercise_id,
            'sets' => $logData['sets'],
            'reps' => $logData['reps'],
            'weight' => $logData['weight'],
        ]);

        session()->flash('log_success_' . $schemaExerciseId, 'Logged!');
        $this->dispatch('log-added');
    }

    // For Trainers: Add a planned exercise to a template
    public function addSchemaExercise()
    {
        if (!Auth::user()->isTrainer() || $this->workoutSchema->user_id !== Auth::id()) return;

        $this->validate([
            'new_exercise_id' => 'required|exists:exercises,id',
            'new_target_sets' => 'nullable|integer|min:1',
            'new_target_reps' => 'nullable|integer|min:1',
        ]);

        $this->workoutSchema->schemaExercises()->create([
            'exercise_id' => $this->new_exercise_id,
            'target_sets' => $this->new_target_sets ?: null,
            'target_reps' => $this->new_target_reps ?: null,
        ]);

        $this->reset(['new_exercise_id', 'new_target_sets', 'new_target_reps']);
        $this->workoutSchema->refresh();
    }

    // For Trainers: Remove a planned exercise from a template
    public function removeSchemaExercise($schemaExerciseId)
    {
        if (!Auth::user()->isTrainer() || $this->workoutSchema->user_id !== Auth::id()) return;

        $exercise = SchemaExercise::find($schemaExerciseId);
        if ($exercise && $exercise->workout_schema_id === $this->workoutSchema->id) {
            $exercise->delete();
            $this->workoutSchema->refresh();
        }
    }

    public function deleteSchema()
    {
        if ($this->workoutSchema->user_id === Auth::id()) {
            $this->workoutSchema->delete();
            session()->flash('success', 'Schema deleted successfully.');
            return redirect()->route('dashboard');
        }
    }

    public function render()
    {
        $isMemberView = !Auth::user()->isTrainer() && !$this->workoutSchema->is_template;
        $isOwnerTrainer = Auth::user()->isTrainer() && $this->workoutSchema->user_id === Auth::id();

        return view('livewire.show-workout-schema', [
            'exercises' => Exercise::orderBy('name')->get(),
            'isMemberView' => $isMemberView,
            'isOwnerTrainer' => $isOwnerTrainer,
        ])
        ->layout('components.layouts.app', ['title' => $this->workoutSchema->name]);
    }
}
