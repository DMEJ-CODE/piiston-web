<x-layouts::app :title="__('garage.Analytique Avancée')">
    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between mb-2">
            <div>
                <flux:heading size="lg" class="font-black uppercase tracking-tight">Analytique Pro</flux:heading>
                <flux:text class="text-[8px] font-bold uppercase tracking-[3px] text-zinc-500">Rentabilité - {{ $branch->name }}</flux:text>
            </div>
            <flux:button href="{{ route('garage.reports.index') }}" variant="filled" size="xs" class="font-black uppercase tracking-widest px-4">Retour</flux:button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <!-- Financial ROI Card -->
            <div class="md:col-span-4 flex flex-col gap-4">
                <div class="bg-zinc-900 text-white p-6 rounded-[20px] shadow-xl relative overflow-hidden">
                    <span class="text-[8px] font-black uppercase tracking-[3px] text-white/30">Bénéfice Net</span>
                    <h3 class="text-2xl font-black tracking-tighter mt-1">{{ number_format($finance['profit'], 0, ',', ' ') }} <span class="text-xs">F</span></h3>

                    <div class="mt-6 space-y-2">
                        <div class="flex justify-between items-center text-[9px] font-bold uppercase">
                            <span class="text-white/40">Revenus</span>
                            <span>{{ number_format($finance['revenue'], 0, ',', ' ') }} F</span>
                        </div>
                        <div class="flex justify-between items-center text-[9px] font-bold uppercase">
                            <span class="text-white/40">Dépenses</span>
                            <span class="text-red-400">- {{ number_format($finance['expenses'], 0, ',', ' ') }} F</span>
                        </div>
                    </div>
                </div>

                <x-dashboard.ai-assistant-card />
            </div>

            <!-- Team Performance -->
            <div class="md:col-span-8">
                <div class="bg-[var(--surface)] p-6 rounded-[20px] border border-zinc-100 dark:border-white/5 shadow-sm">
                    <h3 class="text-[10px] font-black text-zinc-900 dark:text-white uppercase tracking-widest mb-6">Productivité Équipe</h3>

                    <div class="grid grid-cols-1 gap-4">
                        @foreach($performance as $perf)
                        <div class="flex flex-col gap-2">
                            <div class="flex justify-between items-end">
                                <div>
                                    <span class="text-[11px] font-black text-zinc-800 dark:text-zinc-200">{{ $perf['name'] }}</span>
                                    <p class="text-[8px] font-bold text-zinc-400 uppercase tracking-widest">{{ $perf['completed'] }} réparations</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-[10px] font-black">{{ $perf['avg_hours'] }}h <span class="text-zinc-400 text-[8px] font-bold uppercase ml-1">Avg</span></span>
                                </div>
                            </div>
                            <div class="h-1 bg-zinc-100 dark:bg-white/5 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-500 rounded-full" style="width: {{ min(100, ($perf['completed'] / 10) * 100) }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
