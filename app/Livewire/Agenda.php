<?php

namespace App\Livewire;

use App\Models\WorkoutSchema;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Agenda extends Component
{
    public $startOfWeek;
    public $selectedDate;

    public function mount()
    {
        $this->startOfWeek = Carbon::now()->startOfWeek();
        $this->selectedDate = Carbon::now()->toDateString();
    }

    public function previousWeek()
    {
        $this->startOfWeek = Carbon::parse($this->startOfWeek)->subWeek();
    }

    public function nextWeek()
    {
        $this->startOfWeek = Carbon::parse($this->startOfWeek)->addWeek();
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
    }

    public function scheduleWorkout($schemaId, $date)
    {
        $workout = WorkoutSchema::where('id', $schemaId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $workout->update(['scheduled_at' => $date]);
        session()->flash('success', 'Workout gepland!');
    }

    public function unschedule($schemaId)
    {
        WorkoutSchema::where('id', $schemaId)
            ->where('user_id', Auth::id())
            ->update(['scheduled_at' => null]);
    }

    public function render()
    {
        $days = [];
        $tempDate = Carbon::parse($this->startOfWeek);

        for ($i = 0; $i < 7; $i++) {
            $dateString = $tempDate->toDateString();
            $days[] = [
                'name' => $tempDate->format('D'),
                'day' => $tempDate->format('d'),
                'full' => $dateString,
                'isToday' => $tempDate->isToday(),
                'isSelected' => $this->selectedDate === $dateString,
                'hasWorkouts' => WorkoutSchema::where('user_id', Auth::id())
                    ->whereDate('scheduled_at', $dateString)
                    ->exists(),
            ];
            $tempDate->addDay();
        }

        $scheduledWorkouts = WorkoutSchema::where('user_id', Auth::id())
            ->whereDate('scheduled_at', $this->selectedDate)
            ->with('schemaExercises.exercise')
            ->get();

        // Fetch all personal schemas for the draggable sidebar
        $sidebarSchemas = WorkoutSchema::where('user_id', Auth::id())
            ->where('is_template', false)
            ->get();

        return view('livewire.agenda', [
            'days' => $days,
            'scheduledWorkouts' => $scheduledWorkouts,
            'sidebarSchemas' => $sidebarSchemas,
        ])->layout('components.layouts.app', ['title' => 'Mijn Agenda']);
    }

    public function handleDrop($schemaId, $date)
    {
        $workout = WorkoutSchema::where('id', $schemaId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $workout->update(['scheduled_at' => $date]);
        
        $this->selectedDate = $date;
        session()->flash('success', $workout->name . ' gepland!');
    }
}
