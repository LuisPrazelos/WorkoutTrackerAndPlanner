<div class="space-y-4">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold dark:text-white">Jouw Workout Schema's</h2>
        {{-- Knop om formulier te tonen/verbergen --}}
        <flux:button wire:click="toggleCreateForm" primary>
            {{ $showCreateForm ? 'Annuleren' : 'Nieuw Schema' }}
        </flux:button>
    </div>

    {{-- Het aanmaakformulier wordt hier conditioneel geladen --}}
    @if ($showCreateForm)
        <livewire:create-workout-schema />
    @endif

    {{-- Bestaande lijst met schema's --}}
    @forelse ($workoutSchemas as $schema)
        <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6 dark:bg-gray-700 dark:border-gray-600 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $schema->name }}</h3>
                <p class="mt-2 text-gray-600 dark:text-gray-300">{{ $schema->description }}</p>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Moeilijkheid: {{ ucfirst($schema->difficulty) }}</p>
            </div>
            {{-- Verwijder knop --}}
            <flux:button
                wire:click="deleteWorkoutSchema({{ $schema->id }})"
                negative {{-- Gebruik 'negative' voor een rode knop die "verwijderen" aangeeft --}}
                class="ml-4"
                onclick="return confirm('Weet je zeker dat je dit schema wilt verwijderen? Dit kan niet ongedaan worden gemaakt.')"
            >
                Verwijderen
            </flux:button>
        </div>
    @empty
        <p class="text-gray-600 dark:text-gray-300">Je hebt nog geen workout schema's. Maak er een aan om te beginnen!</p>
    @endforelse
</div>
