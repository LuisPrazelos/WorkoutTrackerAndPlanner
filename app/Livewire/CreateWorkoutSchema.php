<?php
namespace App\Livewire;

use App\Models\WorkoutSchema;
use App\Models\Exercise;
use App\Models\SchemaExercise;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CreateWorkoutSchema extends Component
{
    public string $name = '';
    public string $description = '';
    public string $difficulty = 'beginner';
    public $scheduled_at = '';

    // For adding exercises dynamically
    public $selectedExercises = []; // Array of ['exercise_id', 'target_sets', 'target_reps']

    protected array $rules = [
        'name' => 'required|string|min:3|max:255',
        'description' => 'nullable|string|max:1000',
        'difficulty' => 'required|in:beginner,intermediate,advanced',
        'scheduled_at' => 'nullable|date',
        'selectedExercises.*.exercise_id' => 'required|exists:exercises,id',
        'selectedExercises.*.target_sets' => 'nullable|integer|min:1',
        'selectedExercises.*.target_reps' => 'nullable|integer|min:1',
    ];

    public function addExercise()
    {
        $this->selectedExercises[] = [
            'exercise_id' => '',
            'target_sets' => '',
            'target_reps' => '',
        ];
    }

    public function removeExercise($index)
    {
        unset($this->selectedExercises[$index]);
        $this->selectedExercises = array_values($this->selectedExercises);
    }

    public function save(): void
    {
        $this->validate();

        $user = Auth::user();
        if ($user && $user->isTrainer()) {
            $schema = WorkoutSchema::create([
                'user_id' => $user->id,
                'name' => $this->name,
                'description' => $this->description,
                'difficulty' => $this->difficulty,
                'is_template' => true, // Trainers create templates
                'scheduled_at' => null, // Templates are not scheduled
            ]);

            foreach ($this->selectedExercises as $exerciseData) {
                if (!empty($exerciseData['exercise_id'])) {
                    SchemaExercise::create([
                        'workout_schema_id' => $schema->id,
                        'exercise_id' => $exerciseData['exercise_id'],
                        'target_sets' => $exerciseData['target_sets'] ?: null,
                        'target_reps' => $exerciseData['target_reps'] ?: null,
                    ]);
                }
            }

            $this->reset(['name', 'description', 'difficulty', 'scheduled_at', 'selectedExercises']);
            session()->flash('success', 'Template created successfully.');
            $this->redirect(route('dashboard'), navigate: true);
        }
    }

    public function cancel()
    {
        $this->redirect(route('dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.create-workout-schema', [
            'exercises' => Exercise::orderBy('name')->get(),
        ])
        ->layout('components.layouts.app', ['title' => 'New Template']);
    }
}
