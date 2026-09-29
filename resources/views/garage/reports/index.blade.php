<x-layouts::app :title="__('garage.Rapports & Statistiques')">
    <x-garage.index-header
        title="Rapports & Performance"
        subtitle="Analytique détaillée de l'atelier - {{ $branch->name }}"
        actionText="Analytique Pro"
        actionUrl="{{ route('garage.reports.advanced') }}"
        actionIcon="chart-bar"
        canExport="true"
    />

    <!-- KPI Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="group bg-[var(--surface)] p-4 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm hover:border-blue-500/20 transition-all">
            <span class="text-[8px] font-black text-zinc-400 uppercase tracking-widest">Chiffre d'Affaires</span>
            <div class="text-xl font-black text-zinc-900 dark:text-white mt-1">{{ number_format($revenueThisMonth ?? 0, 0, ',', ' ') }} F</div>
            <div class="flex items-center gap-1 mt-2">
                <flux:icon icon="arrow-trending-up" class="size-2.5 text-green-500" />
                <span class="text-[8px] font-black text-green-500 uppercase">+5.2%</span>
            </div>
        </div>

        <div class="group bg-[var(--surface)] p-4 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm hover:border-blue-500/20 transition-all">
            <span class="text-[8px] font-black text-zinc-400 uppercase tracking-widest">Réparations</span>
            <div class="text-xl font-black text-zinc-900 dark:text-white mt-1">{{ $repairsThisMonth ?? 0 }}</div>
        </div>

        <div class="group bg-[var(--surface)] p-4 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm hover:border-blue-500/20 transition-all">
            <span class="text-[8px] font-black text-zinc-400 uppercase tracking-widest">Satisfaction</span>
            <div class="text-xl font-black text-zinc-900 dark:text-white mt-1">4.8 <span class="text-[10px]">/ 5</span></div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6">
        <!-- Main Analysis -->
        <div class="col-span-12 lg:col-span-8 flex flex-col gap-6">
            <x-dashboard.chart-card
                title="Performance"
                subtitle="Revenus sur 30 jours"
                chartId="detailed-growth-chart"
                class="p-4 rounded-[16px]"
            />

            <!-- Operational Efficiency -->
            <div class="bg-[var(--surface)] p-6 rounded-[16px] border border-zinc-100 dark:border-white/5 shadow-sm">
                <h3 class="text-[10px] font-black text-zinc-900 dark:text-white uppercase tracking-widest mb-6">Efficacité</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="flex flex-col gap-4">
                        @foreach($repairsByStatus ?? [] as $status)
                        <div class="space-y-1">
                            <div class="flex justify-between text-[9px] font-black uppercase">
                                <span class="text-zinc-400">{{ $status->status }}</span>
                                <span class="text-zinc-700 dark:text-white">{{ $status->count }}</span>
                            </div>
                            <div class="h-1 w-full bg-zinc-50 dark:bg-white/5 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-500" style="width: {{ min(100, ($status->count / max(1, $repairsThisMonth)) * 100) }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Secondary Insights -->
        <div class="col-span-12 lg:col-span-4 flex flex-col gap-6">
            <div class="bg-[var(--surface)] p-6 rounded-[16px] border border-zinc-100 dark:border-white/5 shadow-sm">
                <h3 class="text-[10px] font-black text-zinc-900 dark:text-white uppercase tracking-widest mb-6">Top Clients</h3>
                <div class="flex flex-col gap-4">
                    @forelse($topCustomers ?? [] as $customer)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="size-8 rounded-lg bg-zinc-50 dark:bg-white/5 flex items-center justify-center font-black text-[10px] border border-zinc-100">
                                    {{ substr($customer->user->name ?? '?', 0, 1) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-zinc-800 dark:text-zinc-100 uppercase tracking-tight">{{ $customer->user->name ?? 'Client' }}</span>
                                </div>
                            </div>
                            <span class="text-[10px] font-black text-blue-600">{{ number_format($customer->total_revenue ?? 0, 0, ',', ' ') }} F</span>
                        </div>
                    @empty
                        <div class="py-4 text-center text-zinc-400 text-[8px] font-bold uppercase">Aucune donnée</div>
                    @endforelse
                </div>
            </div>

            <div class="bg-gradient-to-br from-[var(--active-2)] to-[var(--active)] p-6 rounded-[20px] text-white shadow-lg relative overflow-hidden group">
                <h4 class="text-xs font-black uppercase tracking-tight mb-2">Export Comptable</h4>
                <p class="text-[9px] font-bold text-white/80 uppercase">Générer un bilan PDF certifié</p>
                <flux:button size="xs" class="mt-4 bg-white text-zinc-900 border-none rounded-lg font-black uppercase tracking-widest w-full">Générer</flux:button>
            </div>
        </div>
    </div>
    </div>
</x-layouts::app>
