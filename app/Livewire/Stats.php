<?php

namespace App\Livewire;

use App\Models\Exercise;
use App\Models\ExerciseLog;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Stats extends Component
{
    public $selectedExerciseId = null;
    public $chartData = [];
    public $personalRecord = 0;

    public function mount()
    {
        // Default to the first exercise if available
        $firstExercise = Exercise::first();
        if ($firstExercise) {
            $this->selectedExerciseId = $firstExercise->id;
            $this->updateChartData();
        }
    }

    public function updatedSelectedExerciseId()
    {
        $this->updateChartData();
    }

    public function updateChartData()
    {
        if (!$this->selectedExerciseId) {
            return;
        }

        // Fetch logs for the selected exercise, belonging to the user
        $logs = ExerciseLog::where('user_id', Auth::id())
            ->where('exercise_id', $this->selectedExerciseId)
            ->orderBy('created_at')
            ->get();

        // Prepare data for the chart (Date vs Max Weight lifted that day)
        // We group by date to handle multiple sets in one day
        $groupedLogs = $logs->groupBy(function ($log) {
            return $log->created_at->format('Y-m-d');
        });

        $dataPoints = [];
        $labels = [];
        $maxWeight = 0;

        foreach ($groupedLogs as $date => $dayLogs) {
            // Find the max weight lifted on this specific day
            $dayMax = $dayLogs->max('weight');

            $labels[] = $date;
            $dataPoints[] = $dayMax;

            if ($dayMax > $maxWeight) {
                $maxWeight = $dayMax;
            }
        }

        $this->chartData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Max Gewicht (kg)',
                    'data' => $dataPoints,
                    'borderColor' => '#4f46e5', // Indigo 600
                    'backgroundColor' => 'rgba(79, 70, 229, 0.2)',
                    'fill' => true,
                ]
            ]
        ];

        $this->personalRecord = $maxWeight;

        // Dispatch event to update chart on frontend
        $this->dispatch('chart-updated', data: $this->chartData);
    }

    public function render()
    {
        return view('livewire.stats', [
            'exercises' => Exercise::orderBy('name')->get(),
        ])->layout('components.layouts.app', ['title' => 'Statistieken']);
    }
}
