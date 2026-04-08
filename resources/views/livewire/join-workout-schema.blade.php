<div class="max-w-2xl mx-auto py-12 px-4">
    <div class="glass-panel p-8 text-center relative overflow-hidden group">
        <div class="absolute -top-10 -right-10 h-40 w-40 rounded-full bg-indigo-500/10 blur-3xl group-hover:bg-indigo-500/20 transition-all duration-700"></div>
        
        <div class="h-20 w-20 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center mx-auto mb-6 shadow-inner">
            <flux:icon name="bolt" class="h-10 w-10 text-indigo-600 dark:text-indigo-400" />
        </div>

        <h2 class="text-3xl font-black text-gray-900 dark:text-white mb-2">Workout Uitnodiging!</h2>
        <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-md mx-auto">
            Iemand heeft het schema <span class="font-bold text-gray-900 dark:text-white italic">"{{ $workoutSchema->name }}"</span> met je gedeeld. Wil je dit toevoegen aan je eigen lijst?
        </p>

        <div class="bg-gray-50 dark:bg-zinc-900/50 rounded-2xl p-6 mb-8 border border-gray-100 dark:border-zinc-800 text-left max-w-sm mx-auto">
            <h4 class="text-xs font-bold uppercase tracking-widest text-indigo-500 mb-3">Preview</h4>
            <div class="space-y-3">
                @foreach($workoutSchema->schemaExercises as $se)
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-medium text-gray-700 dark:text-gray-300">{{ $se->exercise->name }}</span>
                        <span class="text-gray-400">{{ $se->target_sets }}x{{ $se->target_reps }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <flux:button href="{{ route('dashboard') }}" variant="subtle" class="flex-1 max-w-[150px]">Annuleren</flux:button>
            <flux:button wire:click="import" variant="primary" icon="plus" class="flex-1 max-w-[200px] shadow-lg shadow-indigo-500/25">Kopieer naar mijn lijst</flux:button>
        </div>
    </div>
</div>
