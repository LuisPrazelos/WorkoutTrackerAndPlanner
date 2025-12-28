<div class="space-y-6 p-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ $workoutSchema->name }}</h1>
        <div class="flex gap-2">
            @if(Auth::id() === $workoutSchema->user_id)
                <flux:button variant="danger" wire:click="deleteSchema" wire:confirm="Weet je zeker dat je dit hele schema wilt verwijderen? Dit kan niet ongedaan worden gemaakt.">
                    {{ $isMemberView ? 'Schema Verwijderen' : 'Template Verwijderen' }}
                </flux:button>
            @endif
            <flux:button href="{{ route('dashboard') }}" secondary>Terug naar Dashboard</flux:button>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6 dark:bg-gray-700 dark:border-gray-600">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">Details</h2>
        <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Beschrijving</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $workoutSchema->description ?? 'Geen beschrijving' }}</dd>
            </div>
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Moeilijkheidsgraad</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ ucfirst($workoutSchema->difficulty) }}</dd>
            </div>
        </dl>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6 dark:bg-gray-700 dark:border-gray-600">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Geplande Oefeningen</h2>
        <div class="space-y-4">
            @forelse($workoutSchema->schemaExercises as $planned)
                <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <h4 class="font-medium text-gray-900 dark:text-white">{{ $planned->exercise->name }}</h4>
                        @if($isOwnerTrainer)
                            <flux:button variant="danger" size="sm" icon="trash" wire:click="removeSchemaExercise({{ $planned->id }})" />
                        @endif
                    </div>

                    @if($isMemberView)
                        <form wire:submit.prevent="logExercise({{ $planned->id }})" class="mt-4 grid grid-cols-3 md:grid-cols-4 gap-2 items-end">
                            <div>
                                <flux:input type="number" label="Sets" wire:model="logs.{{ $planned->id }}.sets" />
                            </div>
                            <div>
                                <flux:input type="number" label="Reps" wire:model="logs.{{ $planned->id }}.reps" />
                            </div>
                            <div>
                                <flux:input type="number" label="Gewicht (kg)" wire:model="logs.{{ $planned->id }}.weight" step="0.5" />
                            </div>
                            <div class="flex items-center gap-2">
                                <flux:button type="submit" primary>Log</flux:button>
                                @if(session('log_success_' . $planned->id))
                                    <span class="text-green-500 text-sm">✓</span>
                                @endif
                            </div>
                        </form>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Doel: {{ $planned->target_sets ?? '-' }} sets x {{ $planned->target_reps ?? '-' }} reps
                        </p>
                    @endif
                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400 text-center">Nog geen oefeningen gepland voor deze template.</p>
            @endforelse
        </div>

        @if($isOwnerTrainer)
            <form wire:submit.prevent="addSchemaExercise" class="mt-6 border-t border-gray-200 dark:border-gray-600 pt-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Oefening Toevoegen aan Template</h3>
                <div class="flex flex-col md:flex-row gap-2">
                    <div class="flex-1">
                        <flux:select label="Oefening" wire:model="new_exercise_id" placeholder="Kies een oefening...">
                            @foreach($exercises as $exercise)
                                <option value="{{ $exercise->id }}">{{ $exercise->name }}</option>
                            @endforeach
                        </flux:select>
                    </div>
                    <div class="w-full md:w-24">
                        <flux:input type="number" label="Doel Sets" wire:model="new_target_sets" placeholder="3" />
                    </div>
                    <div class="w-full md:w-24">
                        <flux:input type="number" label="Doel Reps" wire:model="new_target_reps" placeholder="10" />
                    </div>
                    <div class="flex items-end">
                        <flux:button type="submit" primary>Toevoegen</flux:button>
                    </div>
                </div>
                @error('new_exercise_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </form>
        @endif
    </div>
</div>
