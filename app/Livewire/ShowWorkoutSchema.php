<?php
namespace App\Livewire;
use App\Models\WorkoutSchema;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

class ShowWorkoutSchema extends Component
{
    public WorkoutSchema $workoutSchema;

    public function mount(WorkoutSchema $workoutSchema): RedirectResponse|null
    {
        if ($workoutSchema->user_id !== Auth::id()) {
            session()->flash('error', 'Je hebt geen toegang tot dit workout schema.');
            return redirect()->route('dashboard');
        }

        $this->workoutSchema = $workoutSchema;
        return null;
    }

    public function render()
    {
        return view('livewire.show-workout-schema')
            ->layout('components.layouts.app', [
                'title' => $this->workoutSchema->name
            ]);
    }
}
