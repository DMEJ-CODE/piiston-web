<x-layouts::app :title="__('garage.Bais de Travail')">
    <x-garage.index-header
        title="Emplacements Atelier"
        subtitle="Gestion des bais et ponts - {{ $branch->name }}"
        actionText="Nouvelle Bai"
        actionUrl="#"
        actionIcon="building-library"
    />

    />

    @php
        $stats = [
            'total' => collect($bays)->count(),
            'available' => collect($bays)->where('status', 'available')->count(),
        ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 mb-6">
        <x-dashboard.stat-card
            title="Total Baies"
            :value="$stats['total']"
            icon="building-04"
            color="var(--active-2)"
        />
        <x-dashboard.stat-card
            title="Disponibles"
            :value="$stats['available']"
            icon="tick-double-02"
            color="#10B981"
        />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($bays ?? [] as $bay)
                    <div class="group card-premium p-4 hover:border-blue-500/30 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-3 opacity-5">
                            <flux:icon icon="building-library" class="size-12" />
                        </div>
                        <div class="flex items-center justify-between mb-3">
                            <div class="piiston-icon-avatar text-blue-500" style="--active-rgb: 59, 130, 246; --active-2-rgb: 37, 99, 235; color: #3b82f6;">
                                <flux:icon icon="wrench" class="size-4" />
                            </div>
                            <flux:badge size="xs" color="{{ $bay->status === 'available' ? 'green' : 'zinc' }}" class="text-[7px] font-black tracking-widest">{{ $bay->status ?? 'ACTIF' }}</flux:badge>
                        </div>
                        <h4 class="text-xs font-black text-zinc-900 dark:text-white uppercase tracking-tight">{{ $bay->name }}</h4>
                        <p class="text-[9px] text-zinc-400 font-bold uppercase tracking-widest mt-1">Capacité : {{ $bay->capacity }} Véhicule(s)</p>

                        <div class="mt-4 pt-3 border-t border-zinc-50 dark:border-white/5 flex justify-between items-center">
                            <span class="text-[8px] font-black text-zinc-300 uppercase tracking-widest">ID: #{{ $bay->id }}</span>
                            <div class="flex gap-2">
                                <flux:button size="xs" variant="ghost" icon="pencil" class="text-zinc-400 size-6" />
                                <flux:button size="xs" variant="ghost" icon="trash" class="text-red-400 size-6" />
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-24 text-center card-premium !p-12">
                        <div class="size-16 rounded-full bg-slate-50 dark:bg-white/5 flex items-center justify-center border border-slate-100 dark:border-white/10 mx-auto mb-4">
                            <flux:icon icon="building-library" class="size-8 text-slate-300 dark:text-slate-600" />
                        </div>
                        <p class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest">Aucun Emplacement</p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase mt-1">Créez vos espaces de travail (ponts, zones).</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="lg:col-span-4">
            <div class="card-premium p-6">
                <h3 class="text-[11px] font-black uppercase tracking-widest mb-6">Ajouter une Bai</h3>
                <form method="POST" action="{{ route('garage.bays.store') }}" class="space-y-4">
                    @csrf
                    <flux:field>
                        <flux:label class="text-[9px] uppercase font-black">Nom de l'emplacement *</flux:label>
                        <flux:input name="name" placeholder="Ex: Pont élévateur 1" required class="rounded-lg h-9 text-xs" />
                    </flux:field>
                    <flux:field>
                        <flux:label class="text-[9px] uppercase font-black">Capacité (véhicules) *</flux:label>
                        <flux:input type="number" name="capacity" value="1" required class="rounded-lg h-9 text-xs" />
                    </flux:field>
                    <div class="pt-2">
                        <button type="submit" class="btn-premium-primary w-full h-10">
                            Créer l'espace
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts::app>
