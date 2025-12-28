<div class="p-6 space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Jouw Persoonlijke Records</h1>
    </div>

    <!-- Chart Container -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Max Gewicht per Oefening</h3>
        <div class="relative h-96 w-full">
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
                    type: 'bar', // Changed to bar chart
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
                                    text: 'Oefening' // Changed label
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
