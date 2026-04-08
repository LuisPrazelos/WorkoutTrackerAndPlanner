<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\WorkoutSchema;

class Dashboard extends Component
{
    public string $search = '';
    public string $difficulty = 'all';

    // State for the active workout inline logging
    public $setInputs = []; // [exercise_id => [setIndex => ['weight' => x, 'reps' => y]]]
    public $loggedSets = []; // [exercise_id => [setIndex => true]]

    // Scheduling state
    public $selectedSchemaId = null;
    public $planDate = '';

    public function mount()
    {
        $this->restoreLoggedSets();
    }

    private function restoreLoggedSets()
    {
        // Optioneel: als er een actief schema is, check welke sets vandaag al gelogd zijn
        // Dit doen we voorlopig via de UI state (wordt reset bij refresh), maar het toevoegen aan de db logt het soieso
    }

    public function activateSchema($schemaId)
    {
        $user = Auth::user();
        
        $schema = WorkoutSchema::where('id', $schemaId)->where('user_id', $user->id)->firstOrFail();
        
        WorkoutSchema::where('user_id', $user->id)->update([
            'is_active' => false,
            'active_started_at' => null
        ]);
        
        $schema->update([
            'is_active' => true,
            'active_started_at' => now(),
        ]);
        
        // Reset local state if moving to a new workout
        $this->setInputs = [];
        $this->loggedSets = [];

        session()->flash('success', 'Workout ' . $schema->name . ' is nu gestart!');
    }

    public function deactivateSchema()
    {
        WorkoutSchema::where('user_id', Auth::id())->update([
            'is_active' => false,
            'active_started_at' => null
        ]);
        
        $this->setInputs = [];
        $this->loggedSets = [];
        
        session()->flash('success', 'Workout gestopt.');
    }

    public function openPlanModal($schemaId)
    {
        $this->selectedSchemaId = $schemaId;
        $this->planDate = now()->toDateString();
        $this->dispatch('modal-show', name: 'plan-workout-modal');
    }

    public function savePlan()
    {
        $this->validate([
            'planDate' => 'required|date|after_or_equal:today',
        ]);

        $schema = WorkoutSchema::where('id', $this->selectedSchemaId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $schema->update([
            'scheduled_at' => $this->planDate,
        ]);

        $this->reset(['selectedSchemaId', 'planDate']);
        $this->dispatch('modal-close', name: 'plan-workout-modal');
        
        session()->flash('success', 'Workout ' . $schema->name . ' gepland voor ' . $schema->scheduled_at->format('d M') . '!');
    }

    public function logSet($exerciseId, $setIndex, $schemaId)
    {
        $weight = $this->setInputs[$exerciseId][$setIndex]['weight'] ?? 0;
        $reps = $this->setInputs[$exerciseId][$setIndex]['reps'] ?? 0;

        if ($reps <= 0) {
            session()->flash('error', 'Vul het aantal reps in!');
            return;
        }

        \App\Models\ExerciseLog::create([
            'user_id' => Auth::id(),
            'exercise_id' => $exerciseId,
            'workout_schema_id' => $schemaId,
            'sets' => 1,
            'reps' => $reps,
            'weight' => $weight,
            'notes' => 'Gelogd via dashboard active workout',
            'logged_at' => now()->toDateString(),
        ]);

        $this->loggedSets[$exerciseId][$setIndex] = true;
    }

    public function render()
    {
        $user = Auth::user();
        $query = WorkoutSchema::query();

        // Everyone sees their personal schemas (workouts) on the dashboard
        $query->where('user_id', $user->id)->where('is_template', false);

        $schemas = $query
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->difficulty !== 'all', function ($query) {
                $query->where('difficulty', $this->difficulty);
            })
            ->latest()
            ->get();

        $activeSchema = WorkoutSchema::where('user_id', $user->id)
            ->where('is_active', true)
            ->with(['schemaExercises.exercise'])
            ->first();

        // Check if there is a workout planned for today
        $todayWorkout = null;
        if (!$activeSchema) {
            $todayWorkout = WorkoutSchema::where('user_id', $user->id)
                ->where('is_template', false)
                ->whereDate('scheduled_at', now()->toDateString())
                ->with(['schemaExercises.exercise'])
                ->first();
        }

        return view('livewire.dashboard', [
            'schemas' => $schemas,
            'activeSchema' => $activeSchema,
            'todayWorkout' => $todayWorkout,
        ])->layout('components.layouts.app', ['title' => 'Dashboard']);
    }
}
