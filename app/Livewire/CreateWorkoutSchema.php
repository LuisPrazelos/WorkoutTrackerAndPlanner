<?php
namespace App\Livewire;
use App\Models\WorkoutSchema;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CreateWorkoutSchema extends Component
{
    public string $name = '';
    public string $description = '';
    public string $difficulty = 'beginner';

    protected array $rules = [
        'name' => 'required|string|min:3|max:255',
        'description' => 'nullable|string|max:1000',
        'difficulty' => 'required|in:beginner,intermediate,advanced',
    ];

    public function save(): void
    {
        $this->validate();

        $user = Auth::user();
        if ($user) {
            WorkoutSchema::create([
                'user_id' => $user->id,
                'name' => $this->name,
                'description' => $this->description,
                'difficulty' => $this->difficulty,
            ]);

            $this->reset(['name', 'description', 'difficulty']);
            $this->dispatch('workoutSchemaCreated');
            $this->redirect(route('dashboard'), navigate: true);
        }
    }

    public function render()
    {
        return view('livewire.create-workout-schema')
            ->layout('components.layouts.app', ['title' => 'Nieuw Schema']);
    }
}
