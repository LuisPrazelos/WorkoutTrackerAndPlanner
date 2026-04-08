<?php

namespace App\Livewire;

use App\Models\Exercise;
use App\Models\ExerciseLog;
use App\Models\WorkoutSchema;
use Livewire\Component;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Auth;

class WorkoutLog extends Component
{
    public string $selectedDate = '';
    public string $filterExercise = '';
    public string $filterSchema = '';

    // For adding a new log
    public bool $showAddForm = false;
    
    #[Url]
    public $newExerciseId = '';
    
    #[Url]
    public $newSchemaId = '';
    public $newSets = '';
    public $newReps = '';
    public $newWeight = '';
    public $newNotes = '';

    // For editing
    public $editingLogId = null;
    public $editSets = '';
    public $editReps = '';
    public $editWeight = '';
    public $editNotes = '';

    public function mount(): void
    {
        $this->selectedDate = now()->toDateString();
        
        if ($this->newExerciseId) {
            $this->showAddForm = true;
        }
    }

    public function getLogs()
    {
        return ExerciseLog::where('user_id', Auth::id())
            ->whereDate('logged_at', $this->selectedDate ?: now()->toDateString())
            ->when($this->filterExercise, fn($q) => $q->where('exercise_id', $this->filterExercise))
            ->when($this->filterSchema, fn($q) => $q->where('workout_schema_id', $this->filterSchema))
            ->with(['exercise', 'workoutSchema'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function addLog(): void
    {
        $this->validate([
            'newExerciseId' => 'required|exists:exercises,id',
            'newSets'       => 'required|integer|min:1',
            'newReps'       => 'required|integer|min:1',
            'newWeight'     => 'required|numeric|min:0',
            'newNotes'      => 'nullable|string|max:500',
        ]);

        ExerciseLog::create([
            'user_id'           => Auth::id(),
            'exercise_id'       => $this->newExerciseId,
            'workout_schema_id' => $this->newSchemaId ?: null,
            'sets'              => $this->newSets,
            'reps'              => $this->newReps,
            'weight'            => $this->newWeight,
            'notes'             => $this->newNotes,
            'logged_at'         => $this->selectedDate ?: now()->toDateString(),
        ]);

        $this->reset(['newExerciseId', 'newSchemaId', 'newSets', 'newReps', 'newWeight', 'newNotes', 'showAddForm']);
        session()->flash('log_added', 'Oefening succesvol gelogd!');
    }

    public function startEdit(ExerciseLog $log): void
    {
        $this->editingLogId = $log->id;
        $this->editSets     = $log->sets;
        $this->editReps     = $log->reps;
        $this->editWeight   = $log->weight;
        $this->editNotes    = $log->notes;
    }

    public function saveEdit(): void
    {
        $this->validate([
            'editSets'   => 'required|integer|min:1',
            'editReps'   => 'required|integer|min:1',
            'editWeight' => 'required|numeric|min:0',
            'editNotes'  => 'nullable|string|max:500',
        ]);

        $log = ExerciseLog::where('id', $this->editingLogId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $log->update([
            'sets'   => $this->editSets,
            'reps'   => $this->editReps,
            'weight' => $this->editWeight,
            'notes'  => $this->editNotes,
        ]);

        $this->reset(['editingLogId', 'editSets', 'editReps', 'editWeight', 'editNotes']);
        session()->flash('log_added', 'Log succesvol bijgewerkt!');
    }

    public function cancelEdit(): void
    {
        $this->reset(['editingLogId', 'editSets', 'editReps', 'editWeight', 'editNotes']);
    }

    public function deleteLog(ExerciseLog $log): void
    {
        if ($log->user_id === Auth::id()) {
            $log->delete();
            session()->flash('log_added', 'Log verwijderd.');
        }
    }

    public function getTodayStats()
    {
        $logs = $this->getLogs();
        return [
            'total_sets'   => $logs->sum('sets'),
            'total_volume' => $logs->sum(fn($l) => $l->sets * $l->reps * $l->weight),
            'exercises'    => $logs->unique('exercise_id')->count(),
        ];
    }

    public function render()
    {
        $logs    = $this->getLogs();
        $stats   = $this->getTodayStats();
        $schemas = WorkoutSchema::where('user_id', Auth::id())
            ->where('is_template', false)
            ->with('schemaExercises.exercise')
            ->orderBy('name')
            ->get();

        $exercises = collect();
        if ($this->newSchemaId) {
            // As een schema is gekozen, filter de oefeningen
            $selectedSchema = $schemas->firstWhere('id', $this->newSchemaId);
            if ($selectedSchema) {
                $exercises = $selectedSchema->schemaExercises->map->exercise->filter()->sortBy('name');
            }
        } else {
            // Anders toon alle oefeningen
            $exercises = Exercise::orderBy('muscle_group')->orderBy('name')->get();
        }

        return view('livewire.workout-log', [
            'logs'      => $logs,
            'stats'     => $stats,
            'exercises' => $exercises,
            'schemas'   => $schemas,
        ])->layout('components.layouts.app', ['title' => 'Workout Logboek']);
    }
}
