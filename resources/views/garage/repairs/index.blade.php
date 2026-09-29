<x-layouts::app :title="__('garage.Réparations')">
    <x-garage.index-header
        title="Ordres de Réparation"
        subtitle="Flux de travail de l'atelier - {{ $branch->name }}"
        secondaryActionText="Entrées Véhicules"
        secondaryActionUrl="{{ route('garage.check-ins.index') }}"
        secondaryActionIcon="truck"
        actionText="Nouvelle Réparation"
        actionUrl="{{ route('garage.repairs.create') }}"
        actionIcon="wrench-screwdriver"
        canExport="true"
        searchPlaceholder="Plaque, VIN ou Nom client..."
        :filters="[
            [
                'name' => 'status',
                'label' => 'Statut : Tous',
                'options' => [
                    'REQUESTED' => '⏳ Demandé',
                    'IN_PROGRESS' => '🔧 En cours',
                    'DIAGNOSIS' => '🔍 Diagnostic',
                    'WAITING_PARTS' => '📦 Attente pièces',
                    'ESTIMATE_PENDING' => '📄 Devis en attente',
                    'COMPLETED' => '✅ Terminé',
                    'DELIVERED' => '🤝 Livré',
                ]
            ],
            [
                'name' => 'priority',
                'label' => 'Priorité : Toutes',
                'options' => ['LOW' => '🟢 Basse', 'MEDIUM' => '🟡 Moyenne', 'HIGH' => '🟠 Haute', 'URGENT' => '🔴 Urgente']
            ]
        ]"
    />

    @php
        $stats = [
            'total' => $repairs->total(),
            'in_progress' => collect($repairs->items())->whereIn('status', ['IN_PROGRESS', 'DIAGNOSIS', 'WAITING_PARTS'])->count(),
            'completed' => collect($repairs->items())->whereIn('status', ['COMPLETED', 'DELIVERED'])->count(),
        ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
        <x-dashboard.stat-card
            title="Total Ordres"
            :value="$stats['total']"
            icon="note-01"
            color="var(--active-2)"
        />
        <x-dashboard.stat-card
            title="En cours"
            :value="$stats['in_progress']"
            icon="clock-01"
            color="#F59E0B"
        />
        <x-dashboard.stat-card
            title="Terminées"
            :value="$stats['completed']"
            icon="tick-double-02"
            color="#10B981"
        />
    </div>

    <div class="mt-4">
        <div class="card-premium !p-0">
            <div class="overflow-x-auto">
                <table class="piiston-table w-full text-left">
                    <thead>
                        <tr>
                            <th>Intervention</th>
                            <th>Véhicule</th>
                            <th>Client</th>
                            <th class="text-center">Délai</th>
                            <th class="text-center">Statut</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($repairs ?? [] as $repair)
                            <tr class="cursor-pointer" onclick="if(!event.target.closest('button')) window.location='{{ route('garage.repairs.show', $repair->id) }}'">
                                <td>
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-black text-zinc-400 tracking-wider uppercase">#{{ str_pad($repair->id, 5, '0', STR_PAD_LEFT) }}</span>
                                        <span class="text-[8px] font-bold text-zinc-500 uppercase">{{ $repair->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-4">
                                        <div class="piiston-icon-avatar" style="--active-rgb: 59,130,246; --active-2-rgb: 37,99,235; color: #3b82f6;">
                                            <flux:icon icon="truck" variant="outline" class="size-4" />
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ $repair->vehicle->license_plate ?? 'N/A' }}</span>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ $repair->vehicle->brand->name ?? '' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-[11px] font-bold text-zinc-700 dark:text-zinc-200">{{ $repair->garageCustomer->user->name ?? 'N/A' }}</span>
                                </td>
                                <td class="text-center">
                                    @php
                                        $days = $repair->created_at->diffInDays(now());
                                        $color = $days > 7 ? 'text-red-500' : ($days > 3 ? 'text-amber-500' : 'text-green-500');
                                    @endphp
                                    <span class="text-[9px] font-black {{ $color }} uppercase tracking-tighter">{{ $days }}J</span>
                                </td>
                                <td class="text-center">
                                    <div class="flex flex-col items-center gap-0.5">
                                        <span class="piiston-badge
                                            @switch($repair->status)
                                                @case('REQUESTED') piiston-badge--neutral @break
                                                @case('IN_PROGRESS') piiston-badge--blue @break
                                                @case('DIAGNOSIS') piiston-badge--amber @break
                                                @case('COMPLETED') piiston-badge--green @break
                                                @case('DELIVERED') piiston-badge--teal @break
                                                @default piiston-badge--neutral
                                            @endswitch">
                                            {{ $repair->status }}
                                        </span>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <flux:dropdown>
                                        <flux:button size="xs" variant="ghost" icon="ellipsis-vertical" class="rounded-lg" />
                                        <flux:menu class="min-w-[160px] rounded-xl p-1 shadow-xl">
                                            <flux:menu.item icon="eye" href="{{ route('garage.repairs.show', $repair->id) }}" class="rounded-lg font-bold text-[10px] uppercase">Détails</flux:menu.item>
                                            <flux:menu.item icon="pencil" class="rounded-lg font-bold text-[10px] uppercase">Modifier</flux:menu.item>
                                            <flux:menu.separator />
                                            @if($repair->invoice)
                                                <flux:menu.item icon="printer" href="{{ route('garage.repairs.invoice.print', $repair->id) }}" target="_blank" class="rounded-lg font-bold text-[10px] uppercase">Imprimer</flux:menu.item>
                                            @endif
                                            <flux:menu.item icon="trash" variant="danger" class="rounded-lg font-bold text-[10px] uppercase">Annuler</flux:menu.item>
                                        </flux:menu>
                                    </flux:dropdown>
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-state">
                                <td colspan="6" class="py-24 text-center">
                                    <div class="flex flex-col items-center justify-center gap-4">
                                        <div class="size-16 rounded-full bg-slate-50 dark:bg-white/5 flex items-center justify-center border border-slate-100 dark:border-white/10">
                                            <flux:icon icon="wrench-screwdriver" class="size-8 text-slate-300 dark:text-slate-600" />
                                        </div>
                                        <div class="flex flex-col gap-1">
                                            <p class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest">Aucune réparation</p>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase">Commencez par créer un nouvel ordre.</p>
                                        </div>
                                        <flux:button href="{{ route('garage.repairs.create') }}" size="sm" class="btn-premium-primary mt-2">Nouveau RO</flux:button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($repairs instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="piiston-pagination">
                    {{ $repairs->links() }}
                </div>
            @endif
        </div>
    </div>
    </div>
</x-layouts::app>
