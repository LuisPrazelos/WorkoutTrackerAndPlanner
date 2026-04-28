<?php
namespace App\Livewire;

use App\Models\WorkoutSchema;
use App\Models\Exercise;
use App\Models\SchemaExercise;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CreateWorkoutSchema extends Component
{
    public string $name = '';
    public string $description = '';
    public string $difficulty = 'beginner';
    public string $category = 'general';
    public string $goal = 'general';
    public $scheduled_at = '';
    public bool $is_public = false;
    public string $selectedTraineeId = '';

    // For adding exercises dynamically
    public $selectedExercises = []; // Array of ['exercise_id', 'target_sets', 'target_reps']

    protected array $rules = [
        'name'         => 'required|string|min:3|max:255',
        'description'  => 'nullable|string|max:1000',
        'difficulty'   => 'required|in:beginner,intermediate,advanced',
        'category'     => 'required|in:general,push,pull,legs,cardio,fullbody,upper,lower',
        'goal'         => 'required|in:general,strength,endurance,weight_loss,muscle_gain',
        'scheduled_at' => 'nullable|date',
        'selectedTraineeId' => 'nullable|exists:users,id',
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

    public function reorderExercises($oldIndex, $newIndex)
    {
        $item = $this->selectedExercises[$oldIndex];
        array_splice($this->selectedExercises, $oldIndex, 1);
        array_splice($this->selectedExercises, $newIndex, 0, [$item]);
    }

    public function save(): void
    {
        $this->validate();

        $user = Auth::user();
        
        $schema = WorkoutSchema::create([
            'user_id'     => $user->id,
            'name'        => $this->name,
            'description' => $this->description,
            'difficulty'  => $this->difficulty,
            'category'    => $this->category,
            'goal'        => $this->goal,
            'is_template' => $user->isTrainer(), // Trainers maken verplicht templates, leden maken persoonlijke schema's
            'is_public'   => $user->isTrainer() && $this->is_public,
            'scheduled_at'=> null,
        ]);

        foreach ($this->selectedExercises as $exerciseData) {
            if (!empty($exerciseData['exercise_id'])) {
                SchemaExercise::create([
                    'workout_schema_id' => $schema->id,
                    'exercise_id'       => $exerciseData['exercise_id'],
                    'target_sets'       => $exerciseData['target_sets'] ?: null,
                    'target_reps'       => $exerciseData['target_reps'] ?: null,
                ]);
            }
        }

        if ($user->isTrainer() && $this->selectedTraineeId !== '') {
            $trainee = User::whereKey($this->selectedTraineeId)
                ->where('role', 'member')
                ->first();

            if ($trainee) {
                $schema->assignToUser($trainee);
                session()->flash('success', 'Template opgeslagen en toegewezen aan ' . $trainee->name . '.');
            } else {
                session()->flash('success', 'Template succesvol opgeslagen.');
            }
        } else {
            session()->flash('success', 'Schema succesvol opgeslagen.');
        }

        $this->reset(['name', 'description', 'difficulty', 'category', 'goal', 'scheduled_at', 'selectedExercises', 'is_public', 'selectedTraineeId']);
        $this->redirect(route('dashboard'), navigate: true);
    }

    public function cancel()
    {
        $this->redirect(route('dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.create-workout-schema', [
            'exercises' => Exercise::orderBy('name')->get(),
            'trainees' => User::where('role', 'member')->orderBy('name')->get(),
        ])
        ->layout('components.layouts.app', ['title' => 'New Template']);
    }
}
