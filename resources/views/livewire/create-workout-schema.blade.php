<div class="p-6 bg-gray-50 rounded-lg shadow-inner dark:bg-gray-800">
    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Nieuw Workout Schema Aanmaken</h3>

    <form wire:submit.prevent="save" class="space-y-4">
        {{-- Naam --}}
        <div>
            <flux:input label="Naam Schema" wire:model="name" placeholder="Bijv. Full Body Strength A" />
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Beschrijving --}}
        <div>
            <flux:textarea label="Beschrijving" wire:model="description" rows="3" placeholder="Een korte beschrijving van het schema..." />
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

        {{-- Submit knop --}}
        <flux:button type="submit" primary class="mt-4">
            Schema Opslaan
        </flux:button>
    </form>
</div>
