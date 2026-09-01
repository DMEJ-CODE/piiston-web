<x-layouts::app :title="__('Dashboard')">
    <div class="flex flex-col gap-3 pb-0">
        <!-- Top Stats Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <x-dashboard.stat-card
                title="Page Views"
                value="16,431"
                icon="view"
                trend="+34.5%"
                chartId="spark-1"
            />
            <x-dashboard.stat-card
                title="Visitors"
                value="6,225"
                icon="user-group"
                trend="+12.0%"
                chartId="spark-2"
                color="#818CF8"
            />
            <x-dashboard.stat-card
                title="Click Rate"
                value="2,832"
                icon="cursor-01"
                trend="-5.2%"
                chartId="spark-3"
                isNegative="true"
                color="#F472B6"
            />
            <x-dashboard.stat-card
                title="Orders"
                value="1,224"
                icon="shopping-basket-01"
                trend="+14.8%"
                chartId="spark-4"
                color="#10B981"
            />
        </div>

        <div class="grid grid-cols-12 gap-4">
            <!-- Left Main Column (Charts & Table) -->
            <div class="col-span-12 lg:col-span-8 flex flex-col gap-[18px]">
                <!-- Large Profit Chart -->
                <x-dashboard.chart-card
                    title="Total Profit"
                    subtitle="Performance vs last period"
                    chartId="profit-chart"
                />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Customer Metrics -->
                    <div class="bg-[var(--surface)] p-3 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm">
                        <h5 class="text-sm font-black text-zinc-900 dark:text-white uppercase tracking-wider mb-6">Customers</h3>
                        <div class="flex flex-col gap-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="size-2 rounded-full bg-blue-500"></div>
                                    <span class="text-sm font-bold text-zinc-600 dark:text-zinc-400">Regular</span>
                                </div>
                                <span class="text-sm font-black">2,884</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="size-2 rounded-full bg-teal-400"></div>
                                    <span class="text-sm font-bold text-zinc-600 dark:text-zinc-400">Occasional</span>
                                </div>
                                <span class="text-sm font-black">1,432</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="size-2 rounded-full bg-orange-400"></div>
                                    <span class="text-sm font-bold text-zinc-600 dark:text-zinc-400">New Users</span>
                                </div>
                                <span class="text-sm font-black">562</span>
                            </div>
                        </div>
                    </div>

                    <!-- Best Selling Summary (Replacing with another chart from image) -->
                    <x-dashboard.chart-card
                        title="Repeat Customer Rate"
                        chartId="radial-gauge"
                    />
                </div>

                <x-dashboard.product-table />
            </div>

            <!-- Right Sidebar Column -->
            <div class="col-span-12 lg:col-span-4 flex flex-col gap-4">
                <!-- Active Days Chart -->
                <x-dashboard.chart-card
                    title="Most Day Active"
                    chartId="activity-bar"
                />

                <x-dashboard.ai-assistant-card />

                <!-- Quick Stats List -->
                <div class="bg-zinc-900 dark:bg-gray-400 p-3 rounded-[15px] text-white flex flex-col gap-4 shadow-xl">
                    <h5 class="text-xs font-black uppercase tracking-[3px] text-white/40 text-center">Top Performing Region</h5>
                    <div class="flex flex-col items-center gap-2">
                        <div class="text-3xl font-black tracking-tighter">98.2%</div>
                        <span class="text-sm font-bold text-green-400">Douala Hub</span>
                    </div>
                    <div class="h-px bg-white/10"></div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black text-white/30 uppercase tracking-widest">Growth</span>
                            <span class="text-base font-bold">+12.4%</span>
                        </div>
                        <div class="flex flex-col text-right">
                            <span class="text-[10px] font-black text-white/30 uppercase tracking-widest">Efficiency</span>
                            <span class="text-base font-bold">94.8%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sparklines
            const commonSparkOptions = {
                chart: { type: 'area', height: 40, sparkline: { enabled: true }, animations: { enabled: true } },
                stroke: { curve: 'smooth', width: 2 },
                fill: { opacity: 0.3 },
                tooltip: { enabled: false }
            };

            new ApexCharts(document.querySelector("#spark-1"), { ...commonSparkOptions, series: [{ data: [10, 15, 8, 25, 18, 40] }], colors: ['#467A9F'] }).render();
            new ApexCharts(document.querySelector("#spark-2"), { ...commonSparkOptions, series: [{ data: [20, 10, 30, 15, 45, 35] }], colors: ['#315A7D'] }).render();
            new ApexCharts(document.querySelector("#spark-3"), { ...commonSparkOptions, series: [{ data: [40, 30, 35, 20, 25, 15] }], colors: ['#AFC4D2'] }).render();
            new ApexCharts(document.querySelector("#spark-4"), { ...commonSparkOptions, series: [{ data: [15, 25, 20, 35, 30, 50] }], colors: ['#467A9F'] }).render();

            // Large Profit Chart
            new ApexCharts(document.querySelector("#profit-chart"), {
                chart: { type: 'area', height: 320, toolbar: { show: false }, fontFamily: 'Instrument Sans' },
                series: [{ name: 'Profit', data: [310, 400, 280, 510, 420, 600, 550, 700, 620, 800, 750, 900] }],
                colors: ['#315A7D'],
                stroke: { curve: 'smooth', width: 3 },
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.45, opacityTo: 0.05, stops: [20, 100] } },
                xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], axisBorder: { show: false }, axisTicks: { show: false } },
                yaxis: { show: false },
                grid: { borderColor: '#f1f1f1', strokeDashArray: 4, padding: { left: 0, right: 0 } },
                dataLabels: { enabled: false }
            }).render();

            // Activity Bar Chart
            new ApexCharts(document.querySelector("#activity-bar"), {
                chart: { type: 'bar', height: 250, toolbar: { show: false } },
                series: [{ name: 'Active', data: [44, 55, 57, 100, 61, 58, 63] }],
                plotOptions: { bar: { columnWidth: '35%', borderRadius: 8, distributed: true } },
                colors: ['#E5E7EB', '#E5E7EB', '#E5E7EB', '#315A7D', '#E5E7EB', '#E5E7EB', '#E5E7EB'],
                xaxis: { categories: ['S', 'M', 'T', 'W', 'T', 'F', 'S'], axisBorder: { show: false }, axisTicks: { show: false } },
                dataLabels: { enabled: false },
                legend: { show: false }
            }).render();

            // Radial Gauge
            new ApexCharts(document.querySelector("#radial-gauge"), {
                chart: { type: 'radialBar', height: 280 },
                series: [68],
                colors: ['#10B981'],
                plotOptions: {
                    radialBar: {
                        startAngle: -135,
                        endAngle: 135,
                        hollow: { size: '70%' },
                        dataLabels: {
                            name: { show: false },
                            value: { fontSize: '32px', fontWeight: '900', color: '#111827', offsetY: 10 }
                        }
                    }
                },
                fill: { type: 'gradient', gradient: { shade: 'dark', shadeIntensity: 0.15, inverseColors: false, opacityFrom: 1, opacityTo: 1, stops: [0, 50, 65, 91] } },
                stroke: { dashArray: 4 }
            }).render();
        });
    </script>
    @endpush
</x-layouts::app>
