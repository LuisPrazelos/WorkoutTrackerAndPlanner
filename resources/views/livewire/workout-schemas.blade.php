<div class="p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gradient tracking-tight">Schema Bibliotheek</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Doorzoek alle beschikbare workout templates.</p>
        </div>
        @if(Auth::user()->isTrainer())
            <flux:button href="{{ route('workout-schemas.create') }}" icon="plus" variant="primary" wire:navigate>
                Nieuwe Template
            </flux:button>
        @endif
    </div>

    {{-- Filter balk --}}
    <div class="glass-panel p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <div>
            <flux:input wire:model.live.debounce.300ms="search" placeholder="Zoeken op naam..." icon="magnifying-glass" id="input-schema-search" />
        </div>
        <div>
            <select wire:model.live="filterDifficulty" id="select-filter-difficulty"
                    class="w-full rounded-xl border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                <option value="all">Alle moeilijkheidsgraden</option>
                <option value="beginner">Beginner</option>
                <option value="intermediate">Intermediate</option>
                <option value="advanced">Advanced</option>
            </select>
        </div>
        <div>
            <select wire:model.live="filterCategory" id="select-filter-category"
                    class="w-full rounded-xl border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                <option value="all">Alle categorieën</option>
                <option value="general">Algemeen</option>
                <option value="push">Push</option>
                <option value="pull">Pull</option>
                <option value="legs">Benen</option>
                <option value="cardio">Cardio</option>
                <option value="fullbody">Full Body</option>
                <option value="upper">Bovenlichaam</option>
                <option value="lower">Onderlichaam</option>
            </select>
        </div>
        <div>
            <select wire:model.live="filterMuscle" id="select-filter-muscle"
                    class="w-full rounded-xl border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                <option value="all">Alle spiergroepen</option>
                @foreach($muscleGroups as $muscle)
                    <option value="{{ $muscle }}">{{ ucfirst($muscle) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Resultaten teller --}}
    <p class="text-sm text-gray-500 dark:text-gray-400">
        <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $schemas->count() }}</span> template(s) gevonden
    </p>

    {{-- Schema cards --}}
    @if($schemas->isEmpty())
        <div class="flex flex-col items-center justify-center h-48 glass-panel border border-dashed border-gray-300 dark:border-zinc-700">
            <flux:icon name="folder-open" class="h-12 w-12 text-indigo-300 dark:text-indigo-600 mb-3" />
            <p class="text-gray-500 dark:text-gray-400 font-medium">Geen templates gevonden.</p>
            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Pas je filters aan of maak een nieuwe template.</p>
        </div>
    @else
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach($schemas as $schema)
                <a href="{{ route('workout-schemas.show', $schema) }}" wire:navigate
                   class="group glass-card flex flex-col overflow-hidden" id="schema-card-{{ $schema->id }}">
                    {{-- Kleur top-balk per categorie --}}
                    <div class="h-1.5 w-full
                        {{ $schema->category === 'push' ? 'bg-gradient-to-r from-orange-400 to-red-500' : '' }}
                        {{ $schema->category === 'pull' ? 'bg-gradient-to-r from-blue-400 to-cyan-500' : '' }}
                        {{ $schema->category === 'legs' ? 'bg-gradient-to-r from-green-400 to-emerald-500' : '' }}
                        {{ $schema->category === 'cardio' ? 'bg-gradient-to-r from-yellow-400 to-orange-500' : '' }}
                        {{ $schema->category === 'fullbody' ? 'bg-gradient-to-r from-indigo-400 to-purple-500' : '' }}
                        {{ in_array($schema->category, ['general','upper','lower']) ? 'bg-gradient-to-r from-gray-300 to-gray-400 dark:from-zinc-600 dark:to-zinc-500' : '' }}
                    "></div>

                    <div class="p-5 flex-1 flex flex-col">
                        {{-- Badges --}}
                        <div class="flex flex-wrap items-center gap-1.5 mb-3">
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                                {{ $schema->difficulty === 'beginner' ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300' : '' }}
                                {{ $schema->difficulty === 'intermediate' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300' : '' }}
                                {{ $schema->difficulty === 'advanced' ? 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300' : '' }}
                            ">{{ ucfirst($schema->difficulty) }}</span>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300">
                                {{ ucfirst($schema->category) }}
                            </span>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors leading-tight">
                            {{ $schema->name }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1.5 line-clamp-2 flex-1">
                            {{ $schema->description ?? 'Geen beschrijving beschikbaar.' }}
                        </p>

                        {{-- Oefeningen preview --}}
                        @if($schema->schemaExercises->isNotEmpty())
                            <div class="mt-3 flex flex-wrap gap-1">
                                @foreach($schema->schemaExercises->take(4) as $se)
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 dark:bg-zinc-700 text-gray-600 dark:text-gray-300">
                                        {{ $se->exercise->name ?? '?' }}
                                    </span>
                                @endforeach
                                @if($schema->schemaExercises->count() > 4)
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 dark:bg-zinc-700 text-gray-500 dark:text-gray-400">
                                        +{{ $schema->schemaExercises->count() - 4 }} meer
                                    </span>
                                @endif
                            </div>
                        @endif

                        {{-- Footer --}}
                        <div class="mt-4 pt-3 border-t border-gray-100 dark:border-zinc-700 flex items-center justify-between">
                            <div class="flex items-center gap-1.5 text-xs text-gray-400 dark:text-gray-500">
                                <flux:icon name="user-circle" class="h-4 w-4" />
                                {{ $schema->user->name }}
                            </div>
                            <div class="flex items-center gap-1.5 text-xs text-gray-400 dark:text-gray-500">
                                <flux:icon name="clipboard-document-list" class="h-4 w-4" />
                                {{ $schema->schemaExercises->count() }} oefeningen
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
