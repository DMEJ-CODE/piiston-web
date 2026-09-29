<x-layouts::app :title="__('garage.Rendez-vous')">
    <x-garage.index-header
        title="Planning des RDV"
        subtitle="Gestion des rendez-vous et interventions - {{ $branch->name }}"
        actionText="Nouveau RDV"
        actionUrl="{{ route('garage.appointments.create') }}"
        actionIcon="calendar-days"
        searchPlaceholder="Client ou Plaque..."
        :filters="[
            [
                'name' => 'status',
                'label' => 'Statut : Tous',
                'options' => [
                    'REQUESTED' => '⏳ Demandé',
                    'CONFIRMED' => '✅ Confirmé',
                    'COMPLETED' => '🏁 Terminé',
                    'CANCELLED' => '❌ Annulé',
                ]
            ]
        ]"
    />

    @php
        $aptItems = $appointments instanceof \Illuminate\Pagination\LengthAwarePaginator ? $appointments->items() : $appointments;
        $stats = [
            'total' => $appointments instanceof \Illuminate\Pagination\LengthAwarePaginator ? $appointments->total() : collect($appointments)->count(),
            'upcoming' => collect($aptItems)->whereIn('status', ['SCHEDULED', 'CONFIRMED'])->count(),
            'cancelled' => collect($aptItems)->where('status', 'CANCELLED')->count(),
        ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
        <x-dashboard.stat-card
            title="Total Rendez-vous"
            :value="$stats['total']"
            icon="calendar-03"
            color="var(--active-2)"
        />
        <x-dashboard.stat-card
            title="À Venir"
            :value="$stats['upcoming']"
            icon="clock-01"
            color="#F59E0B"
        />
        <x-dashboard.stat-card
            title="Annulés"
            :value="$stats['cancelled']"
            icon="cancel-circle-half-dot"
            color="#EF4444"
        />
    </div>

    <div class="mt-4">
        <div class="card-premium !p-0">
            <div class="overflow-x-auto">
                <table class="piiston-table w-full text-left">
                    <thead>
                        <tr>
                            <th>Date & Heure</th>
                            <th>Client</th>
                            <th>Véhicule</th>
                            <th class="text-center">État</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments ?? [] as $apt)
                            <tr class="cursor-pointer">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="flex flex-col items-center justify-center size-9 rounded-xl bg-zinc-50 dark:bg-white/5 border border-zinc-100">
                                            <span class="text-[7px] font-black uppercase text-zinc-400 leading-none">{{ \Carbon\Carbon::parse($apt->scheduled_date)->format('M') }}</span>
                                            <span class="text-sm font-black text-zinc-900 dark:text-white leading-none">{{ \Carbon\Carbon::parse($apt->scheduled_date)->format('d') }}</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[10px] font-black text-[var(--active-2)] uppercase">{{ \Carbon\Carbon::parse($apt->scheduled_date)->format('H:i') }}</span>
                                        </div>
                                    </div>
                                <td>
                                    <span class="text-[11px] font-bold text-zinc-700 dark:text-zinc-200">{{ $apt->customer->user->name ?? '---' }}</span>
                                <td>
                                    <span class="text-[10px] font-black text-zinc-900 dark:text-white uppercase tracking-tighter">{{ $apt->vehicle->license_plate ?? '---' }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="piiston-badge
                                        @switch($apt->status)
                                            @case('REQUESTED') piiston-badge--neutral @break
                                            @case('CONFIRMED') piiston-badge--blue @break
                                            @case('COMPLETED') piiston-badge--green @break
                                            @case('CANCELLED') piiston-badge--red @break
                                            @default piiston-badge--neutral
                                        @endswitch">
                                        {{ $apt->status }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <flux:dropdown>
                                        <flux:button size="xs" variant="ghost" icon="ellipsis-vertical" class="rounded-lg" />
                                        <flux:menu class="min-w-[150px] rounded-xl p-1 shadow-xl">
                                            <flux:menu.item icon="pencil" href="{{ route('garage.appointments.edit', $apt->id) }}" class="rounded-lg font-bold text-[10px] uppercase">Modifier</flux:menu.item>
                                            <flux:menu.item icon="truck" href="{{ route('garage.check-ins.create', ['appointment_id' => $apt->id]) }}" class="rounded-lg font-bold text-[10px] uppercase">Réception</flux:menu.item>
                                            <flux:menu.separator />
                                            <flux:menu.item icon="trash" variant="danger" class="rounded-lg font-bold text-[10px] uppercase">Annuler</flux:menu.item>
                                        </flux:menu>
                                    </flux:dropdown>
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-state">
                                <td colspan="5" class="py-24 text-center">
                                    <div class="flex flex-col items-center justify-center gap-4">
                                        <div class="size-16 rounded-full bg-slate-50 dark:bg-white/5 flex items-center justify-center border border-slate-100 dark:border-white/10">
                                            <flux:icon icon="calendar-days" class="size-8 text-slate-300 dark:text-slate-600" />
                                        </div>
                                        <div class="flex flex-col gap-1">
                                            <p class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest">Aucun Rendez-vous</p>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase">Le planning est libre pour le moment.</p>
                                        </div>
                                        <flux:button href="{{ route('garage.appointments.create') }}" size="sm" class="btn-premium-primary mt-2">Nouveau RDV</flux:button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
    </div>
</x-layouts::app>
