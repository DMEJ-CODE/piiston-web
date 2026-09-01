<x-layouts::app :title="__('Réparations')">
    <x-garage.index-header
        title="Ordres de Réparation"
        subtitle="Flux de travail de l'atelier - {{ $branch->name }}"
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

    <div class="mt-2">
        <div class="bg-[var(--surface)] p-2 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-zinc-50 dark:border-white/5 bg-zinc-50/50 dark:bg-white/[0.02]">
                            <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Intervention</th>
                            <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Véhicule</th>
                            <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Client</th>
                            <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">Délai</th>
                            <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">Statut</th>
                            <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50 dark:divide-white/[0.02]">
                        @forelse($repairs ?? [] as $repair)
                            <tr class="group hover:bg-zinc-50 dark:hover:bg-white/[0.01] transition-all cursor-pointer" onclick="if(!event.target.closest('button')) window.location='{{ route('garage.repairs.show', $repair->id) }}'">
                                <td class="py-2.5 px-4">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-black text-zinc-400 tracking-wider uppercase">#{{ str_pad($repair->id, 5, '0', STR_PAD_LEFT) }}</span>
                                        <span class="text-[8px] font-bold text-zinc-500 uppercase">{{ $repair->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3">
                                    <div class="flex items-center gap-2">
                                        <div class="size-7 rounded-lg bg-zinc-100 dark:bg-white/5 flex items-center justify-center border border-white/10">
                                            <flux:icon icon="truck" variant="outline" class="size-3 text-zinc-600 dark:text-zinc-300" />
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[11px] font-black text-zinc-900 dark:text-white uppercase">{{ $repair->vehicle->license_plate ?? 'N/A' }}</span>
                                            <span class="text-[8px] font-bold text-zinc-400 uppercase">{{ $repair->vehicle->brand->name ?? '' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3">
                                    <span class="text-[11px] font-bold text-zinc-700 dark:text-zinc-200">{{ $repair->garageCustomer->user->name ?? 'N/A' }}</span>
                                </td>
                                <td class="py-2.5 px-3 text-center">
                                    @php
                                        $days = $repair->created_at->diffInDays(now());
                                        $color = $days > 7 ? 'text-red-500' : ($days > 3 ? 'text-amber-500' : 'text-green-500');
                                    @endphp
                                    <span class="text-[9px] font-black {{ $color }} uppercase tracking-tighter">{{ $days }}J</span>
                                </td>
                                <td class="py-2.5 px-3 text-center">
                                    <div class="flex flex-col items-center gap-0.5">
                                        <span class="px-2 py-0.5 rounded-lg text-[7px] font-black tracking-widest uppercase
                                            @switch($repair->status)
                                                @case('REQUESTED') bg-zinc-100 text-zinc-600 @break
                                                @case('IN_PROGRESS') bg-blue-500/10 text-blue-600 @break
                                                @case('DIAGNOSIS') bg-amber-500/10 text-amber-600 @break
                                                @case('COMPLETED') bg-green-500/10 text-green-600 @break
                                                @case('DELIVERED') bg-teal-500/10 text-teal-600 @break
                                                @default bg-zinc-100 text-zinc-600
                                            @endswitch">
                                            {{ $repair->status }}
                                        </span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-4 text-right">
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
                            <tr>
                                <td colspan="6" class="py-12 text-center">
                                    <p class="text-[11px] font-black text-zinc-400 uppercase">Aucune réparation</p>
                                    <flux:button href="{{ route('garage.repairs.create') }}" size="xs" variant="primary" class="rounded-lg font-black uppercase mt-2">Nouveau RO</flux:button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($repairs instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="p-3 border-t border-zinc-50 dark:border-white/5">
                    {{ $repairs->links() }}
                </div>
            @endif
        </div>
    </div>
    </div>
</x-layouts::app>
