<x-layouts::app :title="__('garage.Notifications Garage')">
    <x-garage.index-header
        title="Centre de Notifications"
        subtitle="Alertes et messages importants - {{ $branch->name }}"
        actionText="Tout marquer comme lu"
        actionUrl="#"
        actionIcon="check-circle"
        searchPlaceholder="Filtrer les alertes..."
    />

    <div class="mt-2 flex flex-col gap-4">
        @forelse($notifications ?? [] as $notification)
            <div class="group bg-[var(--surface)] p-6 rounded-[28px] border border-zinc-100 dark:border-white/5 shadow-xl shadow-zinc-200/10 dark:shadow-none hover:border-blue-500/20 hover:shadow-2xl transition-all cursor-pointer flex items-center gap-6 relative overflow-hidden">
                @if(!$notification->read_at)
                    <div class="absolute top-0 left-0 bottom-0 w-1.5 bg-blue-500"></div>
                @endif

                <div class="size-14 rounded-2xl bg-gradient-to-br from-zinc-50 to-zinc-100 dark:from-white/5 dark:to-white/10 flex items-center justify-center border border-white/20 group-hover:scale-105 transition-transform shrink-0">
                    @php
                        $type = $notification->data['type'] ?? 'default';
                        $icon = match($type) {
                            'repair' => 'wrench',
                            'billing' => 'banknotes',
                            'appointment' => 'calendar',
                            'stock' => 'archive-box',
                            default => 'bell',
                        };
                        $color = match($type) {
                            'repair' => 'text-blue-500',
                            'billing' => 'text-green-500',
                            'appointment' => 'text-purple-500',
                            'stock' => 'text-red-500',
                            default => 'text-zinc-500',
                        };
                    @endphp
                    <flux:icon icon="{{ $icon }}" class="size-6 {{ $color }}" />
                </div>

                <div class="flex-1 flex flex-col gap-1">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-black text-zinc-900 dark:text-white uppercase tracking-tight">{{ $notification->data['title'] ?? 'Alerte Système' }}</h4>
                        <span class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed font-medium">{{ $notification->data['message'] ?? 'Détails non disponibles.' }}</p>
                </div>

                <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                    <flux:button size="xs" variant="ghost" icon="trash" class="text-red-400 hover:text-red-600 rounded-xl" />
                </div>
            </div>
        @empty
            <div class="bg-[var(--surface)] p-24 rounded-[40px] border border-zinc-100 dark:border-white/5 text-center flex flex-col items-center gap-6 shadow-2xl shadow-zinc-200/20">
                <div class="size-20 rounded-full bg-zinc-50 dark:bg-white/5 flex items-center justify-center border border-dashed border-zinc-200">
                    <flux:icon icon="bell-slash" class="size-10 text-zinc-200" />
                </div>
                <div>
                    <p class="text-sm font-black text-zinc-900 dark:text-white uppercase tracking-tight">Le calme plat...</p>
                    <p class="text-[10px] text-zinc-400 font-bold uppercase tracking-widest mt-2 leading-relaxed max-w-xs mx-auto">Vous n'avez aucune nouvelle notification pour le moment. Tout est sous contrôle !</p>
                </div>
                <flux:button href="{{ route('garage.dashboard') }}" variant="filled" class="rounded-xl font-black uppercase tracking-widest px-8">Retour au Dashboard</flux:button>
            </div>
        @endforelse
    </div>
</x-layouts::app>
