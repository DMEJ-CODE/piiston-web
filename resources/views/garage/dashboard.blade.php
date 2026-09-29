<x-layouts::app :title="__('garage.Garage Dashboard')">
    <div class="flex flex-col gap-4 pb-6">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div class="flex flex-col gap-1">
                <h2 class="text-base font-black uppercase text-slate-900 dark:text-white tracking-tight">Overview</h2>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest">{{ $branch->name }}</p>
            </div>
            <div class="flex gap-2">
                <flux:button href="{{ route('garage.repairs.create') }}" size="xs" class="btn-premium-primary">
                    <i class="hgi-stroke hgi-plus mr-2"></i> New RO
                </flux:button>
            </div>
        </div>

        <!-- Top Stats Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-dashboard.stat-card
                title="Repairs"
                :value="$stats['repairs_this_month'] ?? 0"
                icon="wrench-screwdriver"
                trend="+8%"
                chartId="spark-repairs"
                color="var(--active-2)"
            />
            <x-dashboard.stat-card
                title="Revenue"
                :value="number_format($stats['revenue_this_month'] ?? 0, 0, ',', ' ') . ' F'"
                icon="banknotes"
                trend="+12%"
                chartId="spark-revenue"
                color="#10B981"
            />
            <x-dashboard.stat-card
                title="Appointments"
                :value="$stats['pending_appointments'] ?? 0"
                icon="calendar-days"
                trend="+4%"
                chartId="spark-appointments"
                color="#F59E0B"
            />
            <x-dashboard.stat-card
                title="SOS Emergencies"
                :value="$stats['urgent_emergencies'] ?? 0"
                icon="bolt"
                trend="LIVE"
                chartId="spark-urgencies"
                :isNegative="($stats['urgent_emergencies'] ?? 0) > 0"
                color="#EF4444"
            />
        </div>

        <div class="grid grid-cols-12 gap-4">
            <!-- Left Main Column (Charts & Table) -->
            <div class="col-span-12 lg:col-span-8 flex flex-col gap-4">
                <!-- Monthly Revenue Chart -->
                <x-dashboard.chart-card
                    title="Financial Performance"
                    subtitle="Revenue trends (14 days)"
                    chartId="revenue-performance-chart"
                />

                <!-- Recent Activity -->
                <div class="card-premium !p-5">
                    <div class="flex items-center justify-between mb-4 px-1">
                        <div class="flex flex-col gap-1">
                            <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest">Recent Activity</h3>
                            <p class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase">Latest repair orders</p>
                        </div>
                        <a href="{{ route('garage.repairs.index') }}" class="text-[10px] font-black uppercase text-[var(--active-2)] hover:underline">View History</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-slate-100 dark:border-slate-800/80">
                                    <th class="pb-3 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Vehicle / RO</th>
                                    <th class="pb-3 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Owner</th>
                                    <th class="pb-3 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/40">
                                @forelse($recentRepairs as $repair)
                                <tr class="group hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-colors cursor-pointer" onclick="window.location='{{ route('garage.repairs.show', $repair->id) }}'">
                                    <td class="py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="size-9 rounded-xl bg-[var(--active)]/10 text-[var(--active-2)] flex items-center justify-center border border-white/10 shadow-sm">
                                                <flux:icon icon="truck" variant="outline" class="size-4" />
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ $repair->vehicle->license_plate ?? 'N/A' }}</span>
                                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">RO #{{ $repair->id }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <span class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-tighter">{{ $repair->garageCustomer->user->name ?? '---' }}</span>
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="px-2.5 py-1 rounded-full bg-slate-100 dark:bg-white/5 border border-slate-200/50 dark:border-white/10 text-[9px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest">{{ $repair->status }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="py-12 text-center">
                                        <p class="text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">No repairs recorded</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar Column -->
            <div class="col-span-12 lg:col-span-4 flex flex-col gap-4">
                <!-- Garage Efficiency Gauge -->
                <x-dashboard.chart-card
                    title="Workshop Occupancy"
                    chartId="workshop-occupancy-gauge"
                />

                <!-- AI Assistant Suggestion -->
                <x-dashboard.ai-assistant-card />

                <!-- Subscription & Branch Stats -->
                <div class="bg-zinc-900 dark:bg-zinc-800 p-4 rounded-2xl text-white flex flex-col gap-4 shadow-xl border border-white/5 relative overflow-hidden">
                    <div class="relative z-10">
                        <h5 class="text-[9px] font-black uppercase tracking-[2px] text-white/40 text-center mb-4">Professional Plan</h5>
                        <div class="flex flex-col items-center gap-2">
                            <div class="text-xl font-black tracking-tighter uppercase">{{ $subscription->plan->name ?? 'Trial Pack' }}</div>
                            <span class="px-3 py-1 rounded-full bg-green-500/20 text-green-400 text-[10px] font-black uppercase tracking-widest border border-green-500/20">Active Subscription</span>
                        </div>

                        <div class="h-px bg-white/10 my-4"></div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex flex-col gap-1">
                                <span class="text-[10px] font-black text-white/30 uppercase tracking-widest">Active</span>
                                <span class="text-xl font-black tracking-tighter">{{ $stats['active_repairs'] ?? 0 }}</span>
                            </div>
                            <div class="flex flex-col gap-1 text-right">
                                <span class="text-[10px] font-black text-white/30 uppercase tracking-widest">Low Stock</span>
                                <span class="text-xl font-black tracking-tighter {{ ($stats['low_stock_items'] ?? 0) > 0 ? 'text-red-400' : '' }}">{{ $stats['low_stock_items'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- Glow -->
                    <div class="absolute -right-20 -bottom-20 size-64 bg-[var(--active-2)]/10 rounded-full blur-3xl"></div>
                </div>

                <!-- Quick Access Links -->
                <div class="flex flex-col gap-4">
                    <h5 class="text-[11px] font-black text-zinc-400 uppercase tracking-[2px] px-3">Quick Access</h5>
                    <a href="{{ route('garage.inventory.index') }}" class="flex items-center justify-between p-3 rounded-xl bg-[var(--surface)] border border-zinc-100 dark:border-white/5 hover:border-[var(--active-2)]/30 hover:shadow-xl transition-all group">
                        <div class="flex items-center gap-4">
                            <div class="size-12 rounded-[18px] bg-blue-500/10 text-blue-500 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                                <flux:icon icon="archive-box" class="size-6" />
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-black text-zinc-700 dark:text-zinc-300 uppercase tracking-tight">Inventory</span>
                                <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Stock & Parts</span>
                            </div>
                        </div>
                        <i class="hgi-stroke hgi-chevron-right text-zinc-300 group-hover:text-[var(--active-2)] transition-all"></i>
                    </a>

                    <a href="{{ route('garage.employees.index') }}" class="flex items-center justify-between p-3 rounded-xl bg-[var(--surface)] border border-zinc-100 dark:border-white/5 hover:border-purple-500/30 hover:shadow-xl transition-all group">
                        <div class="flex items-center gap-4">
                            <div class="size-12 rounded-[18px] bg-purple-500/10 text-purple-500 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                                <flux:icon icon="user-group" class="size-6" />
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-black text-zinc-700 dark:text-zinc-300 uppercase tracking-tight">Staff</span>
                                <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Staff Management</span>
                            </div>
                        </div>
                        <i class="hgi-stroke hgi-chevron-right text-zinc-300 group-hover:text-purple-500 transition-all"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const commonSparkOptions = {
                chart: { type: 'area', height: 40, sparkline: { enabled: true }, animations: { enabled: true } },
                stroke: { curve: 'smooth', width: 2 },
                fill: { opacity: 0.15 },
                tooltip: { enabled: false }
            };

            new ApexCharts(document.querySelector("#spark-repairs"), { ...commonSparkOptions, series: [{ data: [12, 15, 18, 14, 22, 19, 25] }], colors: ['#315A7D'] }).render();
            new ApexCharts(document.querySelector("#spark-revenue"), { ...commonSparkOptions, series: [{ data: [40, 30, 50, 45, 60, 55, 70] }], colors: ['#10B981'] }).render();
            new ApexCharts(document.querySelector("#spark-appointments"), { ...commonSparkOptions, series: [{ data: [5, 8, 4, 10, 7, 12, 9] }], colors: ['#F59E0B'] }).render();
            new ApexCharts(document.querySelector("#spark-stock"), { ...commonSparkOptions, series: [{ data: [10, 9, 8, 12, 11, 10, 8] }], colors: ['#EF4444'] }).render();

            const revenueDates = @json($revenueData->keys());
            const revenueValues = @json($revenueData->values());

            new ApexCharts(document.querySelector("#revenue-performance-chart"), {
                chart: { type: 'area', height: 280, toolbar: { show: false }, fontFamily: 'Instrument Sans' },
                series: [{ name: 'Revenue', data: revenueValues.length ? revenueValues : [120, 150, 140, 200, 180, 250, 220, 300, 280, 350, 320, 400] }],
                colors: ['#315A7D'],
                stroke: { curve: 'smooth', width: 3 },
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [20, 100] } },
                xaxis: {
                    categories: revenueDates.length ? revenueDates : ['D-11', 'D-10', 'D-9', 'D-8', 'D-7', 'D-6', 'D-5', 'D-4', 'D-3', 'D-2', 'D-1', 'Today'],
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { style: { fontSize: '10px', fontWeight: 600, colors: '#94a3b8' } }
                },
                yaxis: { show: false },
                grid: { borderColor: '#f1f1f1', strokeDashArray: 4, padding: { left: 10, right: 10 } },
                dataLabels: { enabled: false }
            }).render();

            new ApexCharts(document.querySelector("#workshop-occupancy-gauge"), {
                chart: { type: 'radialBar', height: 240 },
                series: [{{ $stats['occupation_rate'] ?? 0 }}],
                colors: ['#315A7D'],
                plotOptions: {
                    radialBar: {
                        startAngle: -135,
                        endAngle: 135,
                        hollow: { size: '65%' },
                        dataLabels: {
                            name: { show: false },
                            value: { fontSize: '28px', fontWeight: '900', color: '#111827', offsetY: 10, formatter: (val) => val + '%' }
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
