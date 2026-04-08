<div class="p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gradient tracking-tight">Statistieken</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Jouw prestaties in één oogopslag.</p>
        </div>
    </div>

    {{-- Totalen overzicht --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="glass-panel p-5 text-center group hover:scale-[1.02] transition-transform duration-200">
            <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center mx-auto mb-2">
                <flux:icon name="calendar-days" class="h-5 w-5 text-indigo-600 dark:text-indigo-400" />
            </div>
            <p class="text-3xl font-black text-indigo-600 dark:text-indigo-400">{{ $totalStats['total_sessions'] ?? 0 }}</p>
            <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest mt-1">Trainingsdagen</p>
        </div>
        <div class="glass-panel p-5 text-center group hover:scale-[1.02] transition-transform duration-200">
            <div class="h-10 w-10 rounded-full bg-purple-100 dark:bg-purple-900/40 flex items-center justify-center mx-auto mb-2">
                <flux:icon name="clipboard-document-check" class="h-5 w-5 text-purple-600 dark:text-purple-400" />
            </div>
            <p class="text-3xl font-black text-purple-600 dark:text-purple-400">{{ $totalStats['total_logs'] ?? 0 }}</p>
            <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest mt-1">Gelogde Sets</p>
        </div>
        <div class="glass-panel p-5 text-center group hover:scale-[1.02] transition-transform duration-200">
            <div class="h-10 w-10 rounded-full bg-cyan-100 dark:bg-cyan-900/40 flex items-center justify-center mx-auto mb-2">
                <flux:icon name="scale" class="h-5 w-5 text-cyan-600 dark:text-cyan-400" />
            </div>
            <p class="text-3xl font-black text-cyan-600 dark:text-cyan-400">{{ number_format($totalStats['total_volume'] ?? 0) }}</p>
            <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest mt-1">Volume (kg)</p>
        </div>
        <div class="glass-panel p-5 text-center group hover:scale-[1.02] transition-transform duration-200">
            <div class="h-10 w-10 rounded-full bg-green-100 dark:bg-green-900/40 flex items-center justify-center mx-auto mb-2">
                <flux:icon name="bolt" class="h-5 w-5 text-green-600 dark:text-green-400" />
            </div>
            <p class="text-3xl font-black text-green-600 dark:text-green-400">{{ $totalStats['unique_exercises'] ?? 0 }}</p>
            <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest mt-1">Unieke Oefeningen</p>
        </div>
    </div>

    {{-- Persoonlijke Records --}}
    @if(!empty($personalRecords))
    <div class="glass-panel p-5 sm:p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="h-10 w-10 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">
                <flux:icon name="trophy" class="h-5 w-5 text-yellow-600 dark:text-yellow-400" />
            </div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Persoonlijke Records</h2>
        </div>
        <div class="space-y-2">
            @foreach($personalRecords as $index => $record)
                <div class="flex items-center justify-between p-3 rounded-xl
                    {{ $index === 0 ? 'bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700/40' : 'bg-gray-50 dark:bg-zinc-800/50' }}">
                    <div class="flex items-center gap-3">
                        <span class="text-lg font-extrabold
                            {{ $index === 0 ? 'text-yellow-500' : ($index === 1 ? 'text-gray-400' : ($index === 2 ? 'text-amber-600' : 'text-gray-500 dark:text-gray-400')) }}">
                            #{{ $index + 1 }}
                        </span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $record['name'] }}</span>
                    </div>
                    <div class="flex items-center gap-4 text-sm">
                        <span class="text-indigo-600 dark:text-indigo-400 font-bold">{{ $record['max_weight'] }} kg</span>
                        <span class="text-gray-400 dark:text-gray-500 text-xs">Vol: {{ $record['max_volume'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Progressie lijndiagram --}}
    <div class="glass-panel p-5 sm:p-6" wire:ignore x-data="{
        init() {
            // Use nextTick and a small timeout to ensure mobile dimensions are fully calculated
            this.$nextTick(() => {
                setTimeout(() => {
                    const isDark = document.documentElement.classList.contains('dark');
                    const gridColor  = isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)';
                    const labelColor = isDark ? '#9ca3af' : '#6b7280';
                    
                    const rawData = JSON.parse(JSON.stringify(this.$wire.lineChartData));
                    if (!rawData || !rawData.labels) return;

                    // Store chart instance on the element itself (non-reactive) to prevent recursion
                    this.$el._lineChart = new Chart(this.$refs.lineCanvas, {
                        type: 'line',
                        data: rawData,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            animation: { duration: 800 },
                            interaction: { mode: 'index', intersect: false },
                            plugins: {
                                legend: { labels: { color: labelColor, font: { size: 12 } } },
                                tooltip: { backgroundColor: isDark ? '#1f2937' : '#fff', titleColor: isDark ? '#e5e7eb' : '#111827', bodyColor: isDark ? '#9ca3af' : '#6b7280', borderColor: isDark ? '#374151' : '#e5e7eb', borderWidth: 1 }
                            },
                            scales: {
                                y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: labelColor } },
                                x: { grid: { color: gridColor }, ticks: { color: labelColor, maxRotation: 45 } }
                            }
                        }
                    });
                }, 100);
            });

            this.$wire.on('line-chart-updated', (event) => {
                let chartData = event.data || event[0]?.data || event[0] || event;
                const chart = this.$el._lineChart;
                if(chartData.labels && chart) {
                    chart.data = JSON.parse(JSON.stringify(chartData));
                    chart.update();
                }
            });
        }
    }">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center">
                    <flux:icon name="arrow-trending-up" class="h-5 w-5 text-indigo-600 dark:text-indigo-400" />
                </div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Progressie per Oefening</h2>
            </div>
            <select wire:model.live="selectedExerciseId" id="select-progress-exercise"
                    class="rounded-xl border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                @foreach($exercises->groupBy('muscle_group') as $group => $exs)
                    <optgroup label="{{ ucfirst($group) }}">
                        @foreach($exs as $ex)
                            <option value="{{ $ex->id }}">{{ $ex->name }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>
        <div class="relative h-72">
            <canvas x-ref="lineCanvas"></canvas>
        </div>
    </div>

    {{-- PR Staafdiagram --}}
    <div class="glass-panel p-5 sm:p-6" wire:ignore x-data="{
        init() {
            this.$nextTick(() => {
                setTimeout(() => {
                    const isDark = document.documentElement.classList.contains('dark');
                    const gridColor  = isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)';
                    const labelColor = isDark ? '#9ca3af' : '#6b7280';
                    
                    const data = JSON.parse(JSON.stringify(this.$wire.barChartData));
                    if (!data || !data.labels || data.labels.length === 0) return;

                    // Store chart instance on the element itself (non-reactive) to prevent recursion
                    this.$el._barChart = new Chart(this.$refs.barCanvas, {
                        type: 'bar',
                        data: data,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            animation: { duration: 800 },
                            plugins: {
                                legend: { display: false },
                                tooltip: { backgroundColor: isDark ? '#1f2937' : '#fff', titleColor: isDark ? '#e5e7eb' : '#111827', bodyColor: isDark ? '#9ca3af' : '#6b7280', borderColor: isDark ? '#374151' : '#e5e7eb', borderWidth: 1 }
                            },
                            scales: {
                                y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: labelColor } },
                                x: { grid: { display: false }, ticks: { color: labelColor, maxRotation: 45 } }
                            }
                        }
                    });
                }, 150);
            });

            this.$wire.on('bar-chart-updated', (event) => {
                let chartData = event.data || event[0]?.data || event[0] || event;
                const chart = this.$el._barChart;
                if(chartData.labels && chart) {
                    chart.data = JSON.parse(JSON.stringify(chartData));
                    chart.update();
                }
            });
        }
    }">
        <div class="flex items-center gap-3 mb-5">
            <div class="h-10 w-10 rounded-full bg-purple-100 dark:bg-purple-900/40 flex items-center justify-center">
                <flux:icon name="chart-bar" class="h-5 w-5 text-purple-600 dark:text-purple-400" />
            </div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Max Gewicht per Oefening (PR)</h2>
        </div>
        <div class="relative h-72">
            <canvas x-ref="barCanvas"></canvas>
        </div>
    </div>
</div>
