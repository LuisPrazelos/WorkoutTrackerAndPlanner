<div class="p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gradient tracking-tight">Workout Logboek</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Bekijk en beheer je gelogde oefeningen per dag.</p>
        </div>
        <flux:button wire:click="$set('showAddForm', true)" icon="plus" variant="primary" id="btn-add-log">
            Oefening Loggen
        </flux:button>
    </div>

    {{-- Datum navigatie --}}
    <div class="glass-panel p-4 flex flex-col sm:flex-row items-center gap-3">
        <div class="flex items-center gap-2 flex-1">
            <flux:icon name="calendar" class="h-5 w-5 text-indigo-500" />
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Datum:</label>
            <input type="date" wire:model.live="selectedDate"
                   class="rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white px-3 py-1.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
        </div>
        <div class="flex gap-2">
            <button wire:click="$set('selectedDate', '{{ now()->subDay()->toDateString() }}')"
                    class="px-3 py-1.5 text-sm rounded-lg bg-gray-100 dark:bg-zinc-700 hover:bg-gray-200 dark:hover:bg-zinc-600 text-gray-700 dark:text-gray-300 transition-colors">
                ← Gisteren
            </button>
            <button wire:click="$set('selectedDate', '{{ now()->toDateString() }}')"
                    class="px-3 py-1.5 text-sm rounded-lg bg-indigo-100 dark:bg-indigo-900/50 hover:bg-indigo-200 dark:hover:bg-indigo-800/50 text-indigo-700 dark:text-indigo-300 font-medium transition-colors">
                Vandaag
            </button>
        </div>
    </div>

    {{-- Dagelijkse statistieken --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div class="glass-panel p-4 text-center group hover:scale-[1.02] transition-transform">
            <p class="text-3xl font-black text-indigo-600 dark:text-indigo-400">{{ $stats['exercises'] }}</p>
            <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest mt-1">Oefeningen</p>
        </div>
        <div class="glass-panel p-4 text-center group hover:scale-[1.02] transition-transform">
            <p class="text-3xl font-black text-purple-600 dark:text-purple-400">{{ $stats['total_sets'] }}</p>
            <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest mt-1">Totaal Sets</p>
        </div>
        <div class="glass-panel p-4 text-center group hover:scale-[1.02] transition-transform">
            <p class="text-3xl font-black text-cyan-600 dark:text-cyan-400">{{ number_format($stats['total_volume'], 0) }}</p>
            <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest mt-1">Volume (kg)</p>
        </div>
    </div>

    {{-- Flash melding --}}
    @if(session('log_added'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             class="flex items-center gap-2 p-3 rounded-xl bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-300 text-sm">
            <flux:icon name="check-circle" class="h-5 w-5" />
            {{ session('log_added') }}
        </div>
    @endif

    {{-- Formulier: Nieuwe log toevoegen --}}
    @if($showAddForm)
        <div class="glass-panel p-6 space-y-4" x-data x-transition>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <flux:icon name="plus-circle" class="h-5 w-5 text-indigo-500" />
                Oefening Loggen
            </h3>
            <form wire:submit.prevent="addLog" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Schema (optioneel)</label>
                        <select wire:model.live="newSchemaId" id="select-new-schema"
                                class="w-full rounded-xl border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="">Geen schema</option>
                            @foreach($schemas as $schema)
                                <option value="{{ $schema->id }}">{{ $schema->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Oefening *</label>
                        <select wire:model="newExerciseId" id="select-new-exercise"
                                class="w-full rounded-xl border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="">Kies een oefening...</option>
                            @if($exercises->isEmpty())
                                <option value="" disabled>Geen oefeningen in dit schema</option>
                            @else
                                @foreach($exercises->groupBy('muscle_group') as $group => $exs)
                                    <optgroup label="{{ ucfirst($group) }}">
                                        @foreach($exs as $ex)
                                            <option value="{{ $ex->id }}">{{ $ex->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            @endif
                        </select>
                        @error('newExerciseId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <flux:input type="number" label="Sets *" wire:model="newSets" placeholder="3" min="1" id="input-new-sets" />
                        @error('newSets') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <flux:input type="number" label="Reps *" wire:model="newReps" placeholder="10" min="1" id="input-new-reps" />
                        @error('newReps') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <flux:input type="number" label="Gewicht (kg) *" wire:model="newWeight" placeholder="60" min="0" step="0.5" id="input-new-weight" />
                        @error('newWeight') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <flux:textarea label="Notities" wire:model="newNotes" rows="2" placeholder="Optionele opmerkingen..." id="textarea-new-notes" />
                </div>

                <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-200 dark:border-zinc-700">
                    <flux:button type="button" wire:click="$set('showAddForm', false)" variant="subtle">Annuleren</flux:button>
                    <flux:button type="submit" variant="primary" icon="check">Opslaan</flux:button>
                </div>
            </form>
        </div>
    @endif

    {{-- Logs lijst --}}
    @if($logs->isEmpty())
        <div class="flex flex-col items-center justify-center h-48 glass-panel border border-dashed border-gray-300 dark:border-zinc-700">
            <flux:icon name="clipboard-document-list" class="h-12 w-12 text-indigo-300 dark:text-indigo-600 mb-3" />
            <p class="text-gray-500 dark:text-gray-400 font-medium">Geen logs op deze datum.</p>
            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Klik op "Oefening Loggen" om te beginnen.</p>
        </div>
    @else
        <div class="space-y-4">
            @php $groupedLogs = $logs->groupBy('exercise_id'); @endphp
            @foreach($groupedLogs as $exerciseId => $exerciseLogs)
                @php $firstLog = $exerciseLogs->first(); @endphp
                <div class="glass-panel p-4 sm:p-5" wire:key="group-{{ $exerciseId }}">
                    <div class="flex items-center gap-2 flex-wrap mb-4 border-b border-gray-100 dark:border-zinc-800 pb-3">
                        <h4 class="text-lg font-bold text-gray-900 dark:text-white">{{ $firstLog->exercise->name }}</h4>
                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 leading-none font-semibold">
                            {{ ucfirst($firstLog->exercise->muscle_group) }}
                        </span>
                        @if($firstLog->workoutSchema)
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300 leading-none font-semibold">
                                {{ $firstLog->workoutSchema->name }}
                            </span>
                        @endif
                    </div>
                    
                    <div class="space-y-2">
                        @php $setCounter = 1; @endphp
                        @foreach($exerciseLogs->sortBy('created_at') as $index => $log)
                            <div class="flex items-center justify-between gap-3 bg-gray-50/80 dark:bg-zinc-800/50 p-2.5 rounded-lg border border-gray-100 dark:border-zinc-700/80 transition-all hover:bg-gray-100 dark:hover:bg-zinc-800" wire:key="log-{{ $log->id }}">
                                @if($editingLogId === $log->id)
                                    {{-- Edit mode --}}
                                    <form wire:submit.prevent="saveEdit" class="flex-1 flex flex-col sm:flex-row items-start sm:items-center gap-2">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <flux:input type="number" wire:model="editSets" min="1" size="sm" class="w-16" /> <span class="text-xs text-gray-500">sets</span>
                                            <flux:input type="number" wire:model="editReps" min="1" size="sm" class="w-16" /> <span class="text-xs text-gray-500">reps</span>
                                            <flux:input type="number" wire:model="editWeight" min="0" step="0.5" size="sm" class="w-20" /> <span class="text-xs text-gray-500">kg</span>
                                        </div>
                                        <div class="flex-1 w-full mt-2 sm:mt-0">
                                            <flux:input wire:model="editNotes" placeholder="Notities..." size="sm" />
                                        </div>
                                        <div class="flex gap-1 shrink-0 mt-2 sm:mt-0 ml-auto">
                                            <flux:button type="button" wire:click="cancelEdit" variant="subtle" size="sm" icon="x-mark"></flux:button>
                                            <flux:button type="submit" variant="primary" size="sm" icon="check"></flux:button>
                                        </div>
                                    </form>
                                @else
                                    {{-- View mode --}}
                                    <div class="flex items-center gap-3 sm:gap-4 flex-1">
                                        <div class="w-7 h-7 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-400 font-bold text-[11px] flex items-center justify-center shrink-0">
                                            {{ $setCounter++ }}
                                        </div>
                                        
                                        <div class="flex-1 flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-4">
                                            <div class="flex items-center gap-3 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                @if($log->sets > 1) 
                                                    <span>{{ $log->sets }}x</span>
                                                @endif
                                                <span>{{ $log->reps }} reps</span>
                                                @if($log->weight > 0)
                                                    <span class="text-indigo-600 dark:text-indigo-400 font-bold">{{ $log->weight }} kg</span>
                                                @endif
                                            </div>
                                            @if($log->notes)
                                                <p class="text-xs text-gray-500 dark:text-gray-400 italic">"{{ $log->notes }}"</p>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-1 shrink-0">
                                        <span class="text-[10px] text-gray-400 dark:text-gray-500 mr-2 hidden sm:block">{{ $log->created_at->format('H:i') }}</span>
                                        <flux:button wire:click="startEdit({{ $log->id }})" size="sm" variant="subtle" class="!px-2">
                                            <flux:icon name="pencil" class="h-4 w-4 text-gray-500" />
                                        </flux:button>
                                        <flux:button wire:click="deleteLog({{ $log->id }})" wire:confirm="Log verwijderen?" size="sm" variant="subtle" class="!px-2 hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-500/10">
                                            <flux:icon name="trash" class="h-4 w-4 text-gray-500" />
                                        </flux:button>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
