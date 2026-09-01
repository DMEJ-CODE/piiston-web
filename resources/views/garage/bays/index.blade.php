<x-layouts::app :title="__('Bais de Travail')">
    <x-garage.index-header
        title="Emplacements Atelier"
        subtitle="Gestion des bais et ponts - {{ $branch->name }}"
        actionText="Nouvelle Bai"
        actionUrl="#"
        actionIcon="building-library"
    />

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($bays ?? [] as $bay)
                    <div class="group bg-[var(--surface)] p-4 rounded-[16px] border border-zinc-100 dark:border-white/5 shadow-sm hover:border-blue-500/30 transition-all relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-3 opacity-5">
                            <flux:icon icon="building-library" class="size-12" />
                        </div>
                        <div class="flex items-center justify-between mb-3">
                            <div class="size-8 rounded-lg bg-blue-500/10 text-blue-600 flex items-center justify-center border border-blue-500/20">
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
                    <div class="col-span-full py-12 text-center bg-[var(--surface)] rounded-[16px] border-2 border-dashed border-zinc-100">
                        <flux:icon icon="building-library" class="size-12 text-zinc-200 mx-auto mb-4" />
                        <p class="text-sm font-black text-zinc-900 dark:text-white uppercase">Aucun emplacement défini</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="lg:col-span-4">
            <flux:card class="p-6 rounded-[16px] border-none shadow-xl">
                <h3 class="text-sm font-black uppercase tracking-wider mb-6">Ajouter une Bai</h3>
                <form method="POST" action="{{ route('garage.bays.store') }}" class="space-y-6">
                    @csrf
                    <flux:field>
                        <flux:label>Nom de l'emplacement *</flux:label>
                        <flux:input name="name" placeholder="Ex: Pont élévateur 1" required class="rounded-xl h-12" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Capacité (véhicules) *</flux:label>
                        <flux:input type="number" name="capacity" value="1" required class="rounded-xl h-12" />
                    </flux:field>
                    <div class="pt-4">
                        <flux:button type="submit" variant="primary" class="w-full h-12 bg-gradient-to-br from-zinc-800 to-zinc-900 text-white border-none font-black uppercase tracking-widest shadow-xl rounded-xl">
                            Créer l'espace
                        </flux:button>
                    </div>
                </form>
            </flux:card>
        </div>
    </div>
</x-layouts::app>
