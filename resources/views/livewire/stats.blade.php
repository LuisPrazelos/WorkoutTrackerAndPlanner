<div class="p-6 space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Voortgang & Statistieken</h1>

        <div class="w-full md:w-64">
            <flux:select wire:model.live="selectedExerciseId" label="Selecteer Oefening">
                @foreach($exercises as $exercise)
                    <option value="{{ $exercise->id }}">{{ $exercise->name }}</option>
                @endforeach
            </flux:select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Stats Card -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Persoonlijk Record (1RM)</h3>
            <div class="mt-2 flex items-baseline">
                <span class="text-4xl font-bold text-indigo-600 dark:text-indigo-400">{{ $personalRecord }}</span>
                <span class="ml-2 text-sm font-medium text-gray-500 dark:text-gray-400">kg</span>
            </div>
        </div>

        <!-- Placeholder for other stats -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Totaal Aantal Sets</h3>
            <div class="mt-2 flex items-baseline">
                <span class="text-4xl font-bold text-gray-900 dark:text-white">{{ count($chartData['labels'] ?? []) }}</span>
                <span class="ml-2 text-sm font-medium text-gray-500 dark:text-gray-400">sessies</span>
            </div>
        </div>
    </div>

    <!-- Chart Container -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Progressie Grafiek</h3>
        <div class="relative h-80 w-full">
            <canvas id="progressChart"></canvas>
        </div>
    </div>

    <!-- Chart.js Integration -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            const ctx = document.getElementById('progressChart').getContext('2d');
            let chart;

            const initChart = (data) => {
                if (chart) {
                    chart.destroy();
                }

                chart = new Chart(ctx, {
                    type: 'line',
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Gewicht (kg)'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Datum'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            };

            // Initialize with initial data
            initChart(@json($chartData));

            // Listen for updates from Livewire
            Livewire.on('chart-updated', (event) => {
                initChart(event.data);
            });
        });
    </script>
</div>
