<?php

namespace App\Livewire;

use App\Models\Exercise;
use App\Models\ExerciseLog;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class Stats extends Component
{
    public $barChartData    = [];
    public $lineChartData   = [];
    public $selectedExerciseId = null;
    public $personalRecords = [];
    public $totalStats      = [];

    public function mount(): void
    {
        $this->selectedExerciseId = Exercise::first()?->id;
        $this->loadAllData();
    }

    public function updatedSelectedExerciseId(): void
    {
        $this->loadLineChartData();
        $this->dispatch('line-chart-updated', data: $this->lineChartData);
    }

    public function loadAllData(): void
    {
        $this->loadBarChartData();
        $this->loadLineChartData();
        $this->loadPersonalRecords();
        $this->loadTotalStats();
    }

    public function loadBarChartData(): void
    {
        $loggedExercises = ExerciseLog::where('user_id', Auth::id())
            ->with('exercise')
            ->select('exercise_id')
            ->distinct()
            ->get();

        $labels     = [];
        $dataPoints = [];

        foreach ($loggedExercises as $log) {
            $maxWeight = ExerciseLog::where('user_id', Auth::id())
                ->where('exercise_id', $log->exercise_id)
                ->max('weight');

            if ($log->exercise) {
                $labels[]     = $log->exercise->name;
                $dataPoints[] = $maxWeight;
            }
        }

        $this->barChartData = [
            'labels'   => $labels,
            'datasets' => [[
                'label'              => 'PR (kg)',
                'data'               => $dataPoints,
                'backgroundColor'    => 'rgba(99, 102, 241, 0.8)',
                'hoverBackgroundColor' => 'rgba(139, 92, 246, 0.9)',
                'borderRadius'       => 8,
                'borderWidth'        => 0,
                'maxBarThickness'    => 50,
            ]],
        ];

        $this->dispatch('bar-chart-updated', data: $this->barChartData);
    }

    public function loadLineChartData(): void
    {
        if (!$this->selectedExerciseId) {
            $this->lineChartData = [];
            return;
        }

        $logs = ExerciseLog::where('user_id', Auth::id())
            ->where('exercise_id', $this->selectedExerciseId)
            ->orderBy('logged_at')
            ->orderBy('created_at')
            ->get();

        $labels     = [];
        $weights    = [];

        foreach ($logs as $log) {
            $date     = $log->logged_at ? $log->logged_at->format('d/m/Y') : $log->created_at->format('d/m/Y');
            $labels[] = $date;
            $weights[] = $log->weight;
        }

        $this->lineChartData = [
            'labels'   => $labels,
            'datasets' => [
                [
                    'label'           => 'Gewicht (kg)',
                    'data'            => $weights,
                    'borderColor'     => 'rgb(99, 102, 241)',
                    'backgroundColor' => 'rgba(99, 102, 241, 0.1)',
                    'tension'         => 0.4,
                    'fill'            => true,
                    'pointBackgroundColor' => 'rgb(99, 102, 241)',
                    'pointRadius'     => 5,
                ]
            ],
        ];
    }

    public function loadPersonalRecords(): void
    {
        $this->personalRecords = ExerciseLog::where('user_id', Auth::id())
            ->with('exercise')
            ->select('exercise_id')
            ->selectRaw('MAX(weight) as max_weight')
            ->selectRaw('MAX(sets * reps * weight) as max_volume')
            ->groupBy('exercise_id')
            ->orderByDesc('max_weight')
            ->limit(5)
            ->get()
            ->map(fn($log) => [
                'name'       => $log->exercise?->name ?? 'Onbekend',
                'max_weight' => $log->max_weight,
                'max_volume' => round($log->max_volume, 1),
            ])
            ->toArray();
    }

    public function loadTotalStats(): void
    {
        $userId = Auth::id();

        $this->totalStats = [
            'total_sessions'  => ExerciseLog::where('user_id', $userId)->distinct('logged_at')->count('logged_at'),
            'total_logs'      => ExerciseLog::where('user_id', $userId)->count(),
            'total_volume'    => round(ExerciseLog::where('user_id', $userId)->selectRaw('SUM(sets * reps * weight) as vol')->value('vol') ?? 0, 0),
            'unique_exercises'=> ExerciseLog::where('user_id', $userId)->distinct('exercise_id')->count('exercise_id'),
        ];
    }

    public function render()
    {
        return view('livewire.stats', [
            'exercises' => Exercise::orderBy('muscle_group')->orderBy('name')->get(),
        ])->layout('components.layouts.app', ['title' => 'Statistieken']);
    }
}
