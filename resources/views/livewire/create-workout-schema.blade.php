<div class="max-w-7xl mx-auto space-y-6 pb-20 px-4 md:px-0">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-3xl font-black text-gradient tracking-tight">
                {{ Auth::user()->isTrainer() ? 'Nieuwe Template Smeden' : 'Eigen Schema Ontwerpen' }}
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ Auth::user()->isTrainer() ? 'Maak een professioneel schema voor de bibliotheek of je cliënten.' : 'Stel je ideale workout samen voor je eigen trainingen.' }}
            </p>
        </div>
        <flux:button href="{{ route('dashboard') }}" variant="subtle" size="sm" icon="chevron-left" wire:navigate>Terug</flux:button>
    </div>

    <form wire:submit.prevent="save" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Linkerkant: Basis info --}}
            <div class="md:col-span-1 space-y-6">
                <div class="glass-panel p-6 space-y-4">
                    <h4 class="text-sm font-bold uppercase tracking-widest text-indigo-500 mb-2">Basis Informatie</h4>
                    
                    <flux:input label="Naam" wire:model="name" placeholder="Bijv. Upper Body Power" />
                    
                    <flux:textarea label="Focus / Beschrijving" wire:model="description" rows="3" placeholder="Wat is het doel van deze sessie?" />

                    <flux:select label="Moeilijkheid" wire:model="difficulty">
                        <x-slot name="icon">
                            <flux:icon name="bolt" class="h-4 w-4" />
                        </x-slot>
                        <option value="beginner">Beginner</option>
                        <option value="intermediate">Intermediate</option>
                        <option value="advanced">Advanced</option>
                    </flux:select>

                    <flux:select label="Categorie" wire:model="category">
                        <option value="general">Algemeen</option>
                        <option value="push">Push</option>
                        <option value="pull">Pull</option>
                        <option value="legs">Benen</option>
                        <option value="cardio">Cardio</option>
                        <option value="fullbody">Full Body</option>
                    </flux:select>
                    
                    @if(Auth::user()->isTrainer())
                        <div class="pt-4 border-t border-gray-100 dark:border-zinc-800">
                            <div class="flex items-center justify-between p-3 rounded-xl bg-indigo-50/50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800/50">
                                <div>
                                    <p class="text-sm font-bold text-indigo-900 dark:text-indigo-300">Publiek Schema</p>
                                    <p class="text-[10px] text-indigo-700/70 dark:text-indigo-400/70">Zichtbaar in de online bibliotheek</p>
                                </div>
                                <flux:switch wire:model="is_public" color="indigo" />
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Rechterkant: Oefeningen --}}
            <div class="md:col-span-2 space-y-4">
                <div class="glass-panel p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="text-sm font-bold uppercase tracking-widest text-purple-500">Oefeningen Selecteren</h4>
                        <flux:button type="button" wire:click="addExercise" size="sm" variant="primary" icon="plus">Voeg Oefening Toe</flux:button>
                    </div>

                    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
                    <div class="space-y-3" x-data="{
                        init() {
                            if (typeof Sortable !== 'undefined') {
                                new Sortable(this.$refs.sortableList, {
                                    handle: '.drag-handle',
                                    animation: 150,
                                    ghostClass: 'opacity-50',
                                    onEnd: (e) => {
                                        if (e.oldIndex !== e.newIndex) {
                                            @this.call('reorderExercises', e.oldIndex, e.newIndex);
                                        }
                                    }
                                });
                            }
                        }
                    }" x-ref="sortableList">
                        @forelse($selectedExercises as $index => $exercise)
                            <div wire:key="exercise-item-{{ $index }}" class="group relative flex items-center gap-3 p-3 bg-white/40 dark:bg-zinc-900/40 rounded-2xl border border-gray-200/60 dark:border-zinc-800/60 transition-all hover:border-indigo-300 dark:hover:border-indigo-800 shadow-sm">
                                <div class="drag-handle cursor-grab p-1 text-gray-300 hover:text-indigo-500 transition-colors">
                                    <flux:icon name="bars-3" class="h-5 w-5" />
                                </div>
                                
                                <div class="flex-1 grid grid-cols-1 sm:grid-cols-4 gap-3">
                                    <div class="sm:col-span-2">
                                        <flux:select wire:model="selectedExercises.{{ $index }}.exercise_id" size="sm" class="!bg-transparent border-transparent">
                                            <option value="">Kies oefening...</option>
                                            @foreach($exercises as $ex)
                                                <option value="{{ $ex->id }}">{{ $ex->name }}</option>
                                            @endforeach
                                        </flux:select>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <flux:input type="number" wire:model="selectedExercises.{{ $index }}.target_sets" placeholder="Sets" size="sm" class="!bg-transparent border-transparent" />
                                        <span class="text-[10px] text-gray-400 font-bold uppercase">Sets</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <flux:input type="number" wire:model="selectedExercises.{{ $index }}.target_reps" placeholder="Reps" size="sm" class="!bg-transparent border-transparent" />
                                        <span class="text-[10px] text-gray-400 font-bold uppercase">Reps</span>
                                    </div>
                                </div>

                                <button type="button" wire:click="removeExercise({{ $index }})" class="p-2 text-gray-300 hover:text-red-500 transition-colors">
                                    <flux:icon name="x-mark" class="h-4 w-4" />
                                </button>
                            </div>
                        @empty
                            <div class="text-center py-12 border-2 border-dashed border-gray-200 dark:border-zinc-800 rounded-3xl">
                                <flux:icon name="plus-circle" class="mx-auto h-12 w-12 text-gray-300 dark:text-zinc-700" />
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Nog geen oefeningen toegevoegd.</p>
                                <flux:button type="button" wire:click="addExercise" variant="subtle" size="sm" class="mt-4">Klik om te beginnen</flux:button>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-8 flex justify-end gap-3">
                        <flux:button type="button" wire:click="cancel" variant="subtle">Annuleren</flux:button>
                        <flux:button type="submit" variant="primary" icon="check" class="px-8">
                            {{ Auth::user()->isTrainer() ? 'Template Publiceren' : 'Schema Opslaan' }}
                        </flux:button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

