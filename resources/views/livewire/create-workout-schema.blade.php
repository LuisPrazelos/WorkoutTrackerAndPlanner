<div class="p-6 bg-gray-50 rounded-lg shadow-inner dark:bg-gray-800">
    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Nieuwe Template Aanmaken</h3>

    <form wire:submit.prevent="save" class="space-y-6">
        {{-- Naam --}}
        <div>
            <flux:input label="Naam Template" wire:model="name" placeholder="Bijv. Full Body Strength A" />
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Beschrijving --}}
        <div>
            <flux:textarea label="Beschrijving" wire:model="description" rows="3" placeholder="Een korte beschrijving van de template..." />
            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Moeilijkheidsgraad --}}
        <div>
            <flux:select label="Moeilijkheidsgraad" wire:model="difficulty">
                <option value="beginner">Beginner</option>
                <option value="intermediate">Intermediate</option>
                <option value="advanced">Advanced</option>
            </flux:select>
            @error('difficulty') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Oefeningen Plannen --}}
        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-medium text-gray-900 dark:text-white">Oefeningen voor Template</h4>
                <flux:button type="button" wire:click="addExercise" size="sm" icon="plus">Oefening Toevoegen</flux:button>
            </div>

            <div class="space-y-4">
                @foreach($selectedExercises as $index => $exercise)
                    <div class="flex flex-col md:flex-row gap-4 p-4 bg-white dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                        <div class="flex-1">
                            <flux:select label="Oefening" wire:model="selectedExercises.{{ $index }}.exercise_id">
                                <option value="">Kies een oefening...</option>
                                @foreach($exercises as $ex)
                                    <option value="{{ $ex->id }}">{{ $ex->name }} ({{ $ex->muscle_group }})</option>
                                @endforeach
                            </flux:select>
                            @error('selectedExercises.'.$index.'.exercise_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="w-full md:w-24">
                            <flux:input type="number" label="Doel Sets" wire:model="selectedExercises.{{ $index }}.target_sets" placeholder="3" />
                        </div>
                        <div class="w-full md:w-24">
                            <flux:input type="number" label="Doel Reps" wire:model="selectedExercises.{{ $index }}.target_reps" placeholder="10" />
                        </div>
                        <div class="flex items-end pb-1">
                            <flux:button type="button" wire:click="removeExercise({{ $index }})" variant="danger" icon="trash" size="sm" />
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Knoppen --}}
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
            <flux:button type="button" wire:click="cancel" variant="subtle">
                Annuleren
            </flux:button>
            <flux:button type="submit" primary>
                Template Opslaan
            </flux:button>
        </div>
    </form>
</div>
