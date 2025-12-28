<?php

namespace App\Livewire;

use App\Models\Exercise;
use App\Models\ExerciseLog;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Stats extends Component
{
    public $chartData = [];

    public function mount()
    {
        $this->updateChartData();
    }

    public function updateChartData()
    {
        // Fetch all unique exercises that the user has logged
        $loggedExercises = ExerciseLog::where('user_id', Auth::id())
            ->with('exercise')
            ->select('exercise_id')
            ->distinct()
            ->get();

        $labels = [];
        $dataPoints = [];

        foreach ($loggedExercises as $log) {
            // For each unique exercise, find the max weight ever logged by the user
            $maxWeight = ExerciseLog::where('user_id', Auth::id())
                ->where('exercise_id', $log->exercise_id)
                ->max('weight');

            $labels[] = $log->exercise->name;
            $dataPoints[] = $maxWeight;
        }

        $this->chartData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Personal Record (kg)',
                    'data' => $dataPoints,
                    'backgroundColor' => 'rgba(79, 70, 229, 0.8)', // Indigo
                ]
            ]
        ];

        // Dispatch event to update chart on the frontend
        $this->dispatch('chart-updated', data: $this->chartData);
    }

    public function render()
    {
        return view('livewire.stats')
            ->layout('components.layouts.app', ['title' => 'Statistieken']);
    }
}
