<div class="flex h-full w-full flex-1 flex-col gap-6">
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <h2 class="text-3xl font-bold text-gradient tracking-tight">Mijn Workout Schema's</h2>

        <div class="flex w-full md:w-auto items-center gap-3">
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

    @if(isset($activeSchema))
        {{-- Active Training Card (High Priority) --}}
        <div class="glass-panel p-6 border-l-4 border-indigo-500 mb-2 relative overflow-hidden group">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-indigo-500/10 blur-xl group-hover:bg-indigo-500/20 transition-all"></div>
            
            <div class="flex items-center justify-between mb-6 relative z-10 border-b border-gray-100 dark:border-zinc-800 pb-4">
                <div>
                    <span class="text-xs font-semibold text-indigo-500 uppercase tracking-widest flex items-center gap-1">
                        <flux:icon name="play-circle" class="h-4 w-4" /> Huidige Training
                    </span>
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white mt-1">{{ $activeSchema->name }}</h3>
                </div>
                
                {{-- AlpineJS Timer --}}
                <div class="flex items-center gap-4">
                    <div x-data="{ 
                            startedAt: {{ $activeSchema->active_started_at ? $activeSchema->active_started_at->timestamp * 1000 : 'Date.now()' }},
                            elapsedHuman: '00:00',
                            updateTimer() {
                                let now = Date.now();
                                let diff = Math.floor(Math.max(0, now - this.startedAt - 1000) / 1000);
                                if(diff < 0) diff = 0;
                                let h = Math.floor(diff / 3600);
                                let m = Math.floor((diff % 3600) / 60);
                                let s = diff % 60;
                                this.elapsedHuman = (h > 0 ? h.toString().padStart(2, '0') + ':' : '') + 
                                                    m.toString().padStart(2, '0') + ':' + 
                                                    s.toString().padStart(2, '0');
                            }
                        }" 
                        x-init="updateTimer(); setInterval(() => updateTimer(), 1000)" 
                        class="text-xl font-mono font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 px-3 py-1 rounded-lg border border-indigo-100 dark:border-indigo-800 shadow-inner">
                        <span x-text="elapsedHuman"></span>
                    </div>
                
                    <flux:button wire:click="deactivateSchema" size="sm" variant="danger" icon="stop">Workout Afronden</flux:button>
                </div>
            </div>
            
            @if($activeSchema->schemaExercises->isEmpty())
                 <p class="text-sm text-gray-500 relative z-10">Dit schema bevat geen geplande oefeningen.</p>
            @else
                <div class="space-y-4 relative z-10">
                    @foreach($activeSchema->schemaExercises as $se)
                        <div class="p-4 rounded-xl bg-white/60 dark:bg-zinc-800/80 border border-gray-200 dark:border-zinc-700 backdrop-blur-sm shadow-sm transition-all block">
                            <div class="flex justify-between items-center mb-3">
                                <div>
                                    <p class="font-bold text-gray-900 dark:text-white text-lg flex items-center gap-2">
                                        {{ $se->exercise->name }}
                                        <span class="text-xs px-2 py-0.5 bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 rounded-full">
                                            Doel: {{ $se->target_sets }}x{{ $se->target_reps }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            
                            {{-- Sets list --}}
                            <div class="space-y-2">
                                @for($i = 1; $i <= max(1, $se->target_sets ?? 1); $i++)
                                    @php
                                        $isLogged = isset($loggedSets[$se->exercise_id][$i]) && $loggedSets[$se->exercise_id][$i];
                                    @endphp
                                    <div class="flex items-center gap-3 p-2 rounded-lg max-w-xl {{ $isLogged ? 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800' : 'bg-gray-50 dark:bg-zinc-900/50 border border-transparent dark:border-transparent' }}">
                                        <div class="w-16 font-semibold text-sm text-gray-500 dark:text-gray-400 text-center">
                                            Set {{ $i }}
                                        </div>
                                        <div class="flex-1 flex gap-2">
                                            <div class="flex items-center gap-1">
                                                <input type="number" wire:model="setInputs.{{ $se->exercise_id }}.{{ $i }}.weight" step="0.5" min="0" 
                                                    class="w-20 text-center text-sm p-1.5 rounded-md border focus:ring-2 focus:ring-indigo-500 focus:outline-none {{ $isLogged ? 'bg-transparent border-transparent' : 'border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white' }}" 
                                                    placeholder="kg" {{ $isLogged ? 'disabled' : '' }}>
                                                <span class="text-xs text-gray-400 font-medium">kg</span>
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <input type="number" wire:model="setInputs.{{ $se->exercise_id }}.{{ $i }}.reps" min="1"
                                                    class="w-20 text-center text-sm p-1.5 rounded-md border focus:ring-2 focus:ring-indigo-500 focus:outline-none {{ $isLogged ? 'bg-transparent border-transparent' : 'border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white' }}" 
                                                    placeholder="reps" {{ $isLogged ? 'disabled' : '' }}>
                                                <span class="text-xs text-gray-400 font-medium">reps</span>
                                            </div>
                                        </div>
                                        <div class="w-12 flex justify-center">
                                            @if($isLogged)
                                                <flux:icon name="check-circle" class="w-6 h-6 text-green-500" />
                                            @else
                                                <button wire:click="logSet({{ $se->exercise_id }}, {{ $i }}, {{ $activeSchema->id }})" 
                                                        class="p-1.5 rounded-md bg-indigo-100 hover:bg-indigo-200 text-indigo-600 dark:bg-indigo-900/50 dark:hover:bg-indigo-800/50 dark:text-indigo-400 transition-colors w-full flex justify-center shadow-sm">
                                                    <flux:icon name="check" class="w-5 h-5" />
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @elseif(isset($todayWorkout))
        {{-- Today's Scheduled Workout Hero --}}
        <div class="glass-panel p-6 border-l-4 border-cyan-500 mb-6 bg-gradient-to-r from-cyan-500/5 to-transparent relative overflow-hidden group hover:shadow-xl hover:shadow-cyan-500/5 transition-all hover-lift">
            <div class="absolute top-0 right-0 -mt-8 -mr-8 h-32 w-32 rounded-full bg-cyan-500/10 blur-2xl group-hover:bg-cyan-500/20 transition-all"></div>
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 relative z-10">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="bg-cyan-100 dark:bg-cyan-900/40 text-cyan-600 dark:text-cyan-400 text-[10px] font-black uppercase tracking-widest px-2 py-0.5 rounded-md">
                            Vandaag Gepland
                        </span>
                        <span class="text-xs text-zinc-500 dark:text-zinc-400 flex items-center gap-1 font-medium">
                            <flux:icon name="calendar" class="h-3 w-3" /> Agenda Workout
                        </span>
                    </div>
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Klaar voor de start?</h1>
                    <p class="text-lg text-zinc-600 dark:text-zinc-400 mt-1 font-medium italic">Je hebt de <span class="text-cyan-600 dark:text-cyan-400 font-bold">"{{ $todayWorkout->name }}"</span> workout gepland staan.</p>
                    
                    <div class="flex flex-wrap gap-2 mt-4">
                        @foreach($todayWorkout->schemaExercises->take(3) as $se)
                            <span class="text-xs py-1 px-3 bg-white/40 dark:bg-zinc-800/40 border border-gray-100 dark:border-zinc-800 rounded-full text-zinc-600 dark:text-zinc-400">
                                {{ $se->exercise->name }}
                            </span>
                        @endforeach
                        @if($todayWorkout->schemaExercises->count() > 3)
                            <span class="text-xs py-1 px-3 text-zinc-400">
                                +{{ $todayWorkout->schemaExercises->count() - 3 }} meer...
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="shrink-0">
                    <flux:button wire:click="activateSchema({{ $todayWorkout->id }})" variant="primary" icon="play" 
                                 class="vibrant-interactive h-16 px-8 text-xl font-black rounded-2xl border-none">
                        START NU!
                    </flux:button>
                </div>
            </div>
        </div>

        {{-- Still show the grid below if wanted, or just a small header --}}
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Mijn Andere Schema's</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($schemas as $schema)
                @if(!isset($todayWorkout) || $schema->id !== $todayWorkout->id)
                    <div class="group relative flex flex-col overflow-hidden glass-card hover-lift">
                        <a href="{{ route('workout-schemas.show', $schema) }}" class="p-5 md:p-6 flex-1 hover:bg-white/5 dark:hover:bg-black/5 block transition-colors" wire:navigate>
                            <div class="flex flex-col items-start justify-between gap-2 mb-3">
                                <div class="flex items-center gap-2">
                                     <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider
                                        {{ $schema->difficulty === 'beginner' ? 'bg-green-100/80 text-green-800 border border-green-200 dark:bg-green-500/20 dark:text-green-300 dark:border-green-500/30' : '' }}
                                        {{ $schema->difficulty === 'intermediate' ? 'bg-yellow-100/80 text-yellow-800 border border-yellow-200 dark:bg-yellow-500/20 dark:text-yellow-300 dark:border-yellow-500/30' : '' }}
                                        {{ $schema->difficulty === 'advanced' ? 'bg-red-100/80 text-red-800 border border-red-200 dark:bg-red-500/20 dark:text-red-300 dark:border-red-500/30' : '' }}
                                    ">
                                        {{ $schema->difficulty }}
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                    {{ $schema->name }}
                                </h3>
                            </div>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 line-clamp-2">
                                {{ $schema->description ?? 'Geen beschrijving.' }}
                            </p>
                        </a>
                        
                        <div class="px-5 pb-5 md:px-6 md:pb-6 pt-2 flex gap-2">
                            <flux:button wire:click="activateSchema({{ $schema->id }})" variant="primary" class="flex-1" size="sm" icon="play">
                                Inladen
                            </flux:button>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @else
        @if($schemas->isEmpty())
...
            <div class="flex flex-col items-center justify-center h-64 glass-panel border border-dashed border-neutral-300 dark:border-neutral-700">
                <div class="text-center">
                    <flux:icon name="document-magnifying-glass" class="mx-auto h-14 w-14 text-indigo-400 dark:text-indigo-500" />
                    <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">Geen schema's gevonden</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Je zoekopdracht leverde geen resultaten op. Probeer het opnieuw.</p>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($schemas as $schema)
                    <div class="group relative flex flex-col overflow-hidden glass-card">
                        <a href="{{ route('workout-schemas.show', $schema) }}" class="p-5 md:p-6 flex-1 hover:bg-white/5 dark:hover:bg-black/5 block transition-colors" wire:navigate>
                            <div class="flex flex-col items-start justify-between gap-2 mb-3">
                                <div class="flex items-center gap-2">
                                     <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider
                                        {{ $schema->difficulty === 'beginner' ? 'bg-green-100/80 text-green-800 border border-green-200 dark:bg-green-500/20 dark:text-green-300 dark:border-green-500/30' : '' }}
                                        {{ $schema->difficulty === 'intermediate' ? 'bg-yellow-100/80 text-yellow-800 border border-yellow-200 dark:bg-yellow-500/20 dark:text-yellow-300 dark:border-yellow-500/30' : '' }}
                                        {{ $schema->difficulty === 'advanced' ? 'bg-red-100/80 text-red-800 border border-red-200 dark:bg-red-500/20 dark:text-red-300 dark:border-red-500/30' : '' }}
                                    ">
                                        {{ $schema->difficulty }}
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                    {{ $schema->name }}
                                </h3>
                            </div>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 line-clamp-2">
                                {{ $schema->description ?? 'Geen beschrijving.' }}
                            </p>
                            <div class="mt-4 flex items-center text-[10px] font-bold uppercase text-gray-400 dark:text-gray-500">
                                <flux:icon name="calendar" class="mr-1.5 h-3.5 w-3.5" />
                                @if($schema->scheduled_at)
                                    Gepland: {{ $schema->scheduled_at->format('d M') }}
                                @else
                                    Sinds: {{ $schema->created_at->format('d M') }}
                                @endif
                            </div>
                        </a>
                        
                        <div class="px-5 pb-5 md:px-6 md:pb-6 pt-2 flex gap-2">
                            <flux:button wire:click="activateSchema({{ $schema->id }})" variant="primary" class="flex-1" size="sm" icon="play">
                                Inladen
                            </flux:button>
                            <flux:button wire:click="openPlanModal({{ $schema->id }})" variant="subtle" size="sm" icon="calendar-days" class="text-cyan-500 hover:text-cyan-400" />
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endif

    {{-- Planning Modal --}}
    <flux:modal name="plan-workout-modal" title="Workout Plannen" class="min-w-[340px]">
        <div class="space-y-6">
            <div>
                <flux:heading level="3">Selecteer een datum</flux:heading>
                <flux:subheading>Plan deze workout in je agenda.</flux:subheading>
            </div>

            <flux:input type="date" wire:model="planDate" label="Datum" />

            <div class="flex gap-3 justify-end">
                <flux:modal.close>
                    <flux:button variant="subtle">Annuleren</flux:button>
                </flux:modal.close>
                <flux:button wire:click="savePlan" variant="primary" class="bg-vibrant !text-white border-none shadow-lg shadow-cyan-500/20 active:scale-95">
                    Plan Workout
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
