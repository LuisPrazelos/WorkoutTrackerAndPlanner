<?php

namespace App\Livewire;

use App\Models\Exercise;
use App\Models\WorkoutSchema;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class WorkoutSchemas extends Component
{
    public string $search = '';
    public string $filterDifficulty = 'all';
    public string $filterCategory = 'all';
    public string $filterMuscle = 'all';
    public string $filterTrainer = 'all';

    public function render()
    {
        $user = Auth::user();

        // Schema bibliotheek: leden zien alle publieke templates, trainers zien eigen templates + publieke
        $query = WorkoutSchema::query()
            ->with(['user', 'schemaExercises.exercise'])
            ->where('is_template', true)
            ->where(function ($q) {
                $q->where('is_public', true)
                  ->orWhere('user_id', Auth::id());
            });

        // Filter on search
        $query->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'));

        // Filter on difficulty
        $query->when($this->filterDifficulty !== 'all', fn($q) => $q->where('difficulty', $this->filterDifficulty));

        // Filter on category
        $query->when($this->filterCategory !== 'all', fn($q) => $q->where('category', $this->filterCategory));

        // Filter on muscle group (via schema exercises)
        $query->when($this->filterMuscle !== 'all', function ($q) {
            $q->whereHas('schemaExercises.exercise', fn($eq) => $eq->where('muscle_group', $this->filterMuscle));
        });

        // Filter on trainer
        $query->when($this->filterTrainer !== 'all', fn($q) => $q->where('user_id', $this->filterTrainer));

        $schemas  = $query->latest()->get();
        $trainers = User::where('role', 'trainer')->orderBy('name')->get();
        $muscleGroups = Exercise::select('muscle_group')->distinct()->orderBy('muscle_group')->pluck('muscle_group');

        return view('livewire.workout-schemas', [
            'schemas'      => $schemas,
            'trainers'     => $trainers,
            'muscleGroups' => $muscleGroups,
        ])->layout('components.layouts.app', ['title' => 'Schema Bibliotheek']);
    }
}
