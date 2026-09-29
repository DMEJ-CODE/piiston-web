<x-layouts::app :title="__('garage.Gestion des Annexes')">
    <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" class="font-black uppercase tracking-tight">Mes Succursales</flux:heading>
                <flux:text class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">Gérez les différents sites de votre réseau.</flux:text>
            </div>
            <flux:button href="{{ route('garage.branches.create') }}" variant="primary" size="xs" class="rounded-lg font-black uppercase tracking-widest bg-gradient-to-br from-blue-600 to-indigo-700 text-white border-none shadow-md px-4 py-2">
                + Nouvelle Annexe
            </flux:button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($branches as $b)
            <flux:card class="group relative overflow-hidden">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="size-10 rounded-xl bg-zinc-100 dark:bg-white/5 flex items-center justify-center text-zinc-400">
                            <flux:icon icon="building-office" class="size-5" />
                        </div>
                        <div>
                            <h4 class="text-sm font-black uppercase text-zinc-900 dark:text-white">{{ $b->name }}</h4>
                            <p class="text-[9px] font-bold text-zinc-500 uppercase">{{ $b->city ?? 'Ville non définie' }}</p>
                        </div>
                    </div>
                    <flux:badge size="xs" color="{{ $b->status ? 'green' : 'zinc' }}" class="text-[7px] font-black tracking-tighter uppercase">{{ $b->status ? 'Actif' : 'Fermé' }}</flux:badge>
                </div>

                <div class="space-y-3 mb-6">
                    <div class="flex items-center gap-2 text-[10px] font-medium text-zinc-600 dark:text-zinc-400">
                        <flux:icon icon="phone" class="size-3" />
                        <span>{{ $b->phone ?? '---' }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-[10px] font-medium text-zinc-600 dark:text-zinc-400">
                        <flux:icon icon="envelope" class="size-3" />
                        <span>{{ $b->email ?? '---' }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-[10px] font-medium {{ $b->latitude && $b->longitude ? 'text-blue-500' : 'text-amber-500' }}">
                        <flux:icon icon="map-pin" class="size-3" />
                        <span>{{ $b->latitude && $b->longitude ? 'Géolocalisé' : 'Position manquante' }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <flux:button href="{{ route('garage.branches.edit', $b->id) }}" variant="ghost" size="xs" class="flex-1 text-[8px] font-black uppercase tracking-widest">Modifier</flux:button>
                    <form action="{{ route('garage.switch-branch', $b->id) }}" method="POST" class="flex-1">
                        @csrf
                        <flux:button type="submit" variant="filled" size="xs" class="w-full text-[8px] font-black uppercase tracking-widest rounded-lg">Gérer</flux:button>
                    </form>
                </div>
            </flux:card>
            @endforeach
        </div>
    </div>
</x-layouts::app>
