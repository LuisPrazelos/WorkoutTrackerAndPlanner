<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Mijn Workout Schema's</h2>

        <div class="flex w-full md:w-auto items-center gap-2">
            <div class="flex-grow">
                <flux:input wire:model.live.debounce.300ms="search" placeholder="Zoek op naam..." icon="magnifying-glass" />
            </div>
            <div class="w-40">
                <flux:select wire:model.live="difficulty">
                    <option value="all">Alle Niveaus</option>
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="advanced">Advanced</option>
                </flux:select>
            </div>
            <flux:button href="{{ route('workout-schemas.create') }}" icon="plus" variant="primary" wire:navigate>
                Nieuw
            </flux:button>
        </div>
    </div>

    @if($schemas->isEmpty())
        <div class="flex flex-col items-center justify-center h-64 rounded-xl border border-dashed border-neutral-300 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-800/50">
            <div class="text-center">
                <flux:icon name="document-magnifying-glass" class="mx-auto h-12 w-12 text-gray-400" />
                <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">Geen schema's gevonden</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Je zoekopdracht leverde geen resultaten op. Probeer het opnieuw.</p>
            </div>
        </div>
    @else
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            @foreach($schemas as $schema)
                <a href="{{ route('workout-schemas.show', $schema) }}" class="group relative flex flex-col overflow-hidden rounded-xl border border-neutral-200 bg-white transition hover:border-neutral-300 dark:border-neutral-700 dark:bg-neutral-800 dark:hover:border-neutral-600" wire:navigate>
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400">
                                {{ $schema->name }}
                            </h3>
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                {{ $schema->difficulty === 'beginner' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : '' }}
                                {{ $schema->difficulty === 'intermediate' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' : '' }}
                                {{ $schema->difficulty === 'advanced' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : '' }}
                            ">
                                {{ ucfirst($schema->difficulty) }}
                            </span>
                        </div>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 line-clamp-2">
                            {{ $schema->description ?? 'Geen beschrijving.' }}
                        </p>
                        <div class="mt-4 flex items-center text-xs text-gray-400 dark:text-gray-500">
                            <flux:icon name="calendar" class="mr-1.5 h-4 w-4" />
                            @if($schema->scheduled_at)
                                Gepland: {{ $schema->scheduled_at->format('d M Y') }}
                            @else
                                Aangemaakt: {{ $schema->created_at->format('d M Y') }}
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
