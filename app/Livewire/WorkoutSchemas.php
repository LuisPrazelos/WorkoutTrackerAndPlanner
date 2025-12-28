<?php

namespace App\Livewire;

use App\Models\WorkoutSchema;
use Livewire\Component;
use Illuminate\Support\Facades\Auth; // Zorg dat deze import er is

class WorkoutSchemas extends Component
{
    // Property om de zichtbaarheid van het aanmaakformulier te beheren
    public bool $showCreateForm = false;

    // Luister naar het 'workoutSchemaCreated' en 'workoutSchemaDeleted' event
    // De 'array' type hint is hier verwijderd, zoals eerder gecorrigeerd.
    protected $listeners = [
        'workoutSchemaCreated' => 'handleWorkoutSchemaCreated',
        'workoutSchemaDeleted' => 'handleWorkoutSchemaCreated', // Hergebruik deze om de lijst te verversen
    ];

    /**
     * Rendert de component view en haalt de workout schema's op.
     */
    public function render()
    {
        $user = Auth::user(); // Gebruik Auth::user() voor de ingelogde gebruiker

        // Als er geen gebruiker is ingelogd, retourneer een lege collectie.
        if (!$user) {
            return view('livewire.workout-schemas', ['workoutSchemas' => collect()]);
        }

        // Haal alle workout schema's op die bij deze gebruiker horen.
        $workoutSchemas = $user->workoutSchemas()->get();

        return view('livewire.workout-schemas', [
            'workoutSchemas' => $workoutSchemas,
        ]);
    }

    /**
     * Wisselt de zichtbaarheid van het aanmaakformulier.
     */
    public function toggleCreateForm(): void
    {
        $this->showCreateForm = !$this->showCreateForm;
    }

    /**
     * Handelt het 'workoutSchemaCreated' of 'workoutSchemaDeleted' event af.
     * Verbergt het aanmaakformulier en zorgt ervoor dat de lijst met schema's wordt ververst.
     */
    public function handleWorkoutSchemaCreated(): void
    {
        $this->showCreateForm = false; // Verberg het aanmaakformulier
        // Livewire zal de component automatisch opnieuw renderen na een property update,
        // wat de lijst met schema's ververst.
    }

    /**
     * Verwijdert een specifiek workout schema.
     * @param int $schemaId De ID van het te verwijderen schema.
     */
    public function deleteWorkoutSchema(int $schemaId): void
    {
        $user = Auth::user();

        if ($user) {
            // Zoek het schema en zorg ervoor dat het van de ingelogde gebruiker is (beveiliging!)
            $schema = $user->workoutSchemas()->find($schemaId);

            if ($schema) {
                $schema->delete();
                // Stuur een event om de lijst te verversen en eventuele meldingen te activeren.
                $this->dispatch('workoutSchemaDeleted');
            }
        }
    }
}
