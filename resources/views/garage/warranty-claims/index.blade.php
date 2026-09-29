<x-layouts::app :title="__('garage.Garanties')">
    <x-garage.index-header
        title="Garanties & SAV"
        subtitle="Suivi des réclamations de garantie - {{ $branch->name }}"
        actionText="Nouvelle Réclamation"
        actionUrl="{{ route('garage.warranty-claims.create') }}"
        searchPlaceholder="Véhicule ou Client..."
        :filters="[
            [
                'name' => 'status',
                'label' => 'Tous les statuts',
                'options' => ['PENDING' => 'En attente', 'APPROVED' => 'Approuvé', 'REJECTED' => 'Rejeté', 'RESOLVED' => 'Résolu']
            ]
        ]"
    />

    <div class="mt-2">
        <div class="bg-[var(--surface)] p-2 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-left border-b border-zinc-50 dark:border-white/5 bg-zinc-50/50 dark:bg-white/[0.02]">
                            <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">N°</th>
                            <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Véhicule / Client</th>
                            <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Type</th>
                            <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">État</th>
                            <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50 dark:divide-white/[0.02]">
                        @forelse($warrantyClaims ?? [] as $claim)
                            <tr class="group hover:bg-zinc-50 dark:hover:bg-white/[0.01] transition-all cursor-pointer">
                                <td class="py-2.5 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-tighter">#{{ str_pad($claim->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td class="py-2.5 px-3">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-black text-zinc-900 dark:text-white uppercase tracking-tight">{{ $claim->repairOrder->vehicle->license_plate ?? '---' }}</span>
                                        <span class="text-[8px] font-bold text-zinc-400 uppercase">{{ $claim->customer->user->name ?? '---' }}</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3">
                                    <span class="text-[9px] font-bold text-zinc-600 dark:text-zinc-300 uppercase">{{ $claim->claim_type ?? 'PIÈCE' }}</span>
                                </td>
                                <td class="py-2.5 px-3 text-center">
                                    <span class="px-2 py-0.5 rounded-lg text-[7px] font-black tracking-widest uppercase
                                        @switch($claim->status)
                                            @case('PENDING') bg-amber-500/10 text-amber-600 @break
                                            @case('RESOLVED') bg-green-500/10 text-green-600 @break
                                            @default bg-zinc-100 text-zinc-600
                                        @endswitch">
                                        {{ $claim->status }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-4 text-right">
                                    <flux:dropdown>
                                        <flux:button size="xs" variant="ghost" icon="ellipsis-vertical" class="rounded-lg" />
                                        <flux:menu class="min-w-[150px] rounded-xl p-1 shadow-xl">
                                            <flux:menu.item icon="pencil" href="{{ route('garage.warranty-claims.edit', $claim->id) }}" class="rounded-lg font-bold text-[10px] uppercase">Gérer</flux:menu.item>
                                            <flux:menu.item icon="eye" href="{{ route('garage.repairs.show', $claim->repair_order_id) }}" class="rounded-lg font-bold text-[10px] uppercase">RO lié</flux:menu.item>
                                        </flux:menu>
                                    </flux:dropdown>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-zinc-400">
                                    <p class="text-[10px] font-black uppercase">Aucune réclamation</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
</x-layouts::app>
