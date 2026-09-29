<x-layouts::app :title="__('garage.Gestion Générale')">
    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-black uppercase text-zinc-900 dark:text-white tracking-tight">Réseau Garage</h2>
                <p class="text-[9px] font-bold uppercase tracking-widest text-zinc-500">{{ $company->name }} - Global</p>
            </div>
            <flux:button href="{{ route('garage.branches.create') }}" variant="primary" size="xs" class="rounded-lg font-black uppercase tracking-widest bg-gradient-to-br from-blue-600 to-indigo-700 text-white border-none shadow-md px-4 py-2">
                + Ajouter une Annexe
            </flux:button>
        </div>

        <!-- KPI Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-[var(--surface)] p-4 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm">
                <span class="text-[8px] font-black text-zinc-400 uppercase tracking-widest">Réparations</span>
                <div class="text-xl font-black text-zinc-900 dark:text-white mt-1">{{ $stats['repairs_this_month'] }}</div>
                <div class="mt-2 h-1 w-full bg-zinc-100 dark:bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-500" style="width: 65%"></div>
                </div>
            </div>
            <div class="bg-zinc-900 p-4 rounded-2xl shadow-lg relative overflow-hidden">
                <span class="text-[8px] font-black text-white/30 uppercase tracking-widest">Revenu Réseau</span>
                <div class="text-xl font-black text-white mt-1">{{ number_format($stats['revenue_this_month'], 0, ',', ' ') }} F</div>
            </div>
            <div class="bg-[var(--surface)] p-4 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm">
                <span class="text-[8px] font-black text-zinc-400 uppercase tracking-widest">Sites</span>
                <div class="text-xl font-black text-zinc-900 dark:text-white mt-1">{{ $branches->count() }}</div>
            </div>
            <div class="bg-[var(--surface)] p-4 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm">
                <span class="text-[8px] font-black text-zinc-400 uppercase tracking-widest">Alertes Stock</span>
                <div class="text-xl font-black {{ $stats['low_stock_items'] > 0 ? 'text-red-500' : 'text-zinc-900 dark:text-white' }} mt-1">{{ $stats['low_stock_items'] }}</div>
            </div>
        </div>

        <div class="bg-[var(--surface)] p-4 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm mt-2">
            <h3 class="text-[10px] font-black text-zinc-900 dark:text-white uppercase tracking-widest mb-4">Mes Succursales</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($branches as $b)
                <div class="group p-4 rounded-2xl border border-zinc-100 dark:border-white/5 bg-zinc-50 dark:bg-white/[0.02] hover:border-blue-500/20 transition-all">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <flux:icon icon="building-office" class="size-4 text-zinc-400" />
                            <span class="text-[11px] font-black uppercase text-zinc-800 dark:text-white">{{ $b->name }}</span>
                        </div>
                        <flux:badge size="xs" color="{{ $b->status ? 'green' : 'zinc' }}" class="text-[7px] font-black tracking-tighter">{{ $b->status ? 'ACTIF' : 'FERMÉ' }}</flux:badge>
                    </div>
                    <div class="space-y-1 mb-4">
                        <div class="flex justify-between text-[8px] font-bold uppercase">
                            <span class="text-zinc-400">Responsable</span>
                            <span class="text-zinc-700 dark:text-zinc-200">{{ $b->manager->name ?? '---' }}</span>
                        </div>
                    </div>
                    <form action="{{ route('garage.switch-branch', $b->id) }}" method="POST">
                        @csrf
                        <flux:button type="submit" variant="filled" size="xs" class="w-full text-[8px] font-black uppercase tracking-widest rounded-lg h-8">
                            Gérer le site
                        </flux:button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts::app>
