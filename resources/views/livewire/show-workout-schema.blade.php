<div class="space-y-6 p-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ $workoutSchema->name }}</h1>
        <flux:button href="{{ route('dashboard') }}" secondary>Terug naar Schema's</flux:button>
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
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">Gelogde Oefeningen</h2>
        @forelse ($workoutSchema->exerciseLogs as $log)
            <div class="border-b border-gray-200 dark:border-gray-600 py-4 last:border-b-0">
                <p class="text-lg font-medium text-gray-900 dark:text-white">{{ $log->exercise->name }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-300">Sets: {{ $log->sets }}, Herhalingen: {{ $log->reps }}, Gewicht: {{ $log->weight }} kg</p>
                @if ($log->notes)
                    <p class="text-xs text-gray-500 italic dark:text-gray-400">Notities: {{ $log->notes }}</p>
                @endif
                <p class="text-xs text-gray-400 dark:text-gray-500">Gelogd op: {{ $log->created_at->format('d M Y H:i') }}</p>
            </div>
        @empty
            <p class="text-gray-600 dark:text-gray-300">Nog geen oefeningen gelogd voor dit schema.</p>
        @endforelse
    </div>
</div>
