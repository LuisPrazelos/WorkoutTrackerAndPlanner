<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\WorkoutSchema;

class Dashboard extends Component
{
    public string $search = '';
    public string $difficulty = 'all';

    public function render()
    {
        $user = Auth::user();
        $query = WorkoutSchema::query();

        if ($user->isTrainer()) {
            // Trainers see their own templates
            $query->where('user_id', $user->id)->where('is_template', true);
        } else {
            // Members see schemas assigned to them
            $query->where('user_id', $user->id)->where('is_template', false);
        }

        $schemas = $query
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->difficulty !== 'all', function ($query) {
                $query->where('difficulty', $this->difficulty);
            })
            ->latest()
            ->get();

        return view('livewire.dashboard', [
            'schemas' => $schemas,
        ])->layout('components.layouts.app', ['title' => 'Dashboard']);
    }
}
