<div class="p-6 space-y-8">
    @if($actionMessage)
        <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-green-900 shadow-sm dark:border-green-500/30 dark:bg-green-500/10 dark:text-green-100">
            <div class="flex items-start gap-3">
                <flux:icon name="check-circle" class="mt-0.5 h-5 w-5 shrink-0 text-green-600 dark:text-green-400" />
                <p class="text-sm font-medium">{{ $actionMessage }}</p>
            </div>
        </div>
    @endif

    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h1 class="text-4xl font-extrabold text-gradient tracking-tight">{{ $workoutSchema->name }}</h1>
            @if($workoutSchema->is_public)
                <span class="inline-flex items-center gap-1 text-[10px] uppercase font-bold text-indigo-500 bg-indigo-50 dark:bg-indigo-900/30 px-2 py-0.5 rounded-full mt-1 border border-indigo-100 dark:border-indigo-800">
                    <flux:icon name="globe-alt" class="h-3 w-3" /> Openbare Template
                </span>
            @endif
        </div>
        <div class="flex gap-3 flex-wrap items-center">
            {{-- Save to Dashboard Button (for Public/Library schemas) --}}
            @if(Auth::id() !== $workoutSchema->user_id || $workoutSchema->is_template)
                <flux:button wire:click="importSchema" icon="plus" class="bg-vibrant !text-white border-none shadow-lg shadow-cyan-500/20 active:scale-95">
                    {{ Auth::id() === $workoutSchema->user_id ? 'Gebruik Zelf op Dashboard' : 'Voeg toe aan Dashboard' }}
                </flux:button>
            @endif

            {{-- Share Link Button --}}
            @if($workoutSchema->share_token)
                <div x-data="{ 
                        copied: false,
                        shareUrl: '{{ route('workout-schemas.join', $workoutSchema->share_token) }}',
                        copyToClipboard() {
                            navigator.clipboard.writeText(this.shareUrl).then(() => {
                                this.copied = true;
                                setTimeout(() => this.copied = false, 2000);
                            });
                        }
                    }">
                    <button type="button" @click="copyToClipboard" 
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-500 text-white text-sm font-bold hover:bg-indigo-600 transition-all shadow-lg shadow-indigo-500/20 active:scale-95">
                        <flux:icon name="share" class="h-4 w-4" />
                        <span x-text="copied ? 'Gekopieerd!' : 'Deel met Vrienden'"></span>
                    </button>
                </div>
            @endif

            @if(Auth::id() === $workoutSchema->user_id)
                <flux:button variant="danger" wire:click="deleteSchema" wire:confirm="Weet je zeker dat je dit hele schema wilt verwijderen? Dit kan niet ongedaan worden gemaakt." icon="trash">
                    {{ $isMemberView ? 'Verwijderen' : 'Template Verwijderen' }}
                </flux:button>
            @endif
            
            <a href="{{ route('workout-schemas.export-pdf', $workoutSchema) }}" target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-700 transition-all shadow-sm">
                <flux:icon name="document-arrow-down" class="h-4 w-4" />
                Exporteer
            </a>
            <flux:button href="{{ route('dashboard') }}" variant="subtle" icon="arrow-left" wire:navigate>Terug</flux:button>
        </div>
    </div>

    <div class="glass-panel p-6 sm:p-8">
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

    @if($isOwnerTrainer && $workoutSchema->is_template)
        <div class="glass-panel p-6 sm:p-8">
            <div class="flex flex-col lg:flex-row lg:items-end gap-4">
                <div class="flex-1">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Toewijzen aan trainee</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Maak een persoonlijke kopie van deze template op het dashboard van een trainee.</p>
                </div>
                <div class="w-full lg:max-w-md">
                    <flux:select wire:model="selectedTraineeId" label="Trainee">
                        <option value="">Kies een trainee...</option>
                        @foreach($trainees as $trainee)
                            <option value="{{ $trainee->id }}">{{ $trainee->name }} ({{ $trainee->email }})</option>
                        @endforeach
                    </flux:select>
                    @error('selectedTraineeId')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <flux:button wire:click="assignToTrainee" variant="primary" icon="user-plus" class="w-full lg:w-auto">
                        Toewijzen
                    </flux:button>
                </div>
            </div>
        </div>
    @endif

    <div class="glass-panel p-6 sm:p-8 mt-8">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Geplande Oefeningen</h2>
        <div class="space-y-4">
            @forelse($workoutSchema->schemaExercises as $planned)
                <div class="border border-gray-200 dark:border-gray-700/50 bg-white/40 dark:bg-zinc-800/40 rounded-xl p-5 hover:shadow-sm transition-shadow">
                    <div class="flex items-center justify-between">
                        <h4 class="text-lg font-bold text-gray-900 dark:text-white">{{ $planned->exercise->name }}</h4>
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

    @if($isMemberView)
        <!-- Workout History -->
        <div class="glass-panel p-6 sm:p-8 mt-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="h-10 w-10 rounded-full bg-green-100 dark:bg-green-900/50 flex items-center justify-center">
                    <flux:icon name="clock" class="h-5 w-5 text-green-600 dark:text-green-400" />
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Workout Geschiedenis</h2>
            </div>
            
            <div class="space-y-4 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-200 dark:before:via-gray-700 before:to-transparent">
                @forelse ($workoutSchema->exerciseLogs->sortByDesc('created_at') as $log)
                    <div class="border-b border-gray-200 dark:border-gray-600 py-3 last:border-b-0">
                        <p class="text-lg font-medium text-gray-900 dark:text-white">{{ $log->exercise->name }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            <span class="font-semibold">{{ $log->sets }}</span> sets x
                            <span class="font-semibold">{{ $log->reps }}</span> reps
                            @if($log->weight > 0) @ <span class="font-semibold">{{ $log->weight }}</span> kg @endif
                        </p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $log->created_at->format('d M Y, H:i') }}</p>
                    </div>
                @empty
                    <p class="text-gray-600 dark:text-gray-300 text-center py-4">Je hebt nog geen oefeningen gelogd voor dit schema.</p>
                @endforelse
            </div>
        </div>
    @endif
</div>
