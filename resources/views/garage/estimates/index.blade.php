<x-layouts::app :title="__('Devis')">
    <x-garage.index-header
        title="Devis & Estimations"
        subtitle="Gestion des propositions commerciales - {{ $branch->name }}"
        actionText="Nouveau Devis"
        actionUrl="{{ route('garage.estimates.create') }}"
        searchPlaceholder="N° de devis ou Client..."
        :filters="[
            [
                'name' => 'status',
                'label' => 'Tous les statuts',
                'options' => ['DRAFT' => 'Brouillon', 'SENT' => 'Envoyé', 'APPROVED' => 'Approuvé', 'REJECTED' => 'Refusé']
            ]
        ]"
    />

    <div class="mt-2">
        <div class="bg-[var(--surface)] p-4 rounded-[32px] border border-zinc-100 dark:border-white/5 shadow-2xl shadow-zinc-200/20 dark:shadow-none overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-zinc-50 dark:border-white/5 bg-zinc-50/50 dark:bg-white/[0.02]">
                            <th class="py-5 px-6 text-[10px] font-black text-zinc-400 uppercase tracking-[2px]">Devis</th>
                            <th class="py-5 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-[2px]">Client / Véhicule</th>
                            <th class="py-5 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-[2px] text-right">Montant Total</th>
                            <th class="py-5 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-[2px] text-center">Validité</th>
                            <th class="py-5 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-[2px] text-center">État</th>
                            <th class="py-5 px-6 text-[10px] font-black text-zinc-400 uppercase tracking-[2px] text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50 dark:divide-white/[0.02]">
                        @forelse($estimates ?? [] as $est)
                            <tr class="group hover:bg-zinc-50 dark:hover:bg-white/[0.01] transition-all cursor-pointer">
                                <td class="py-4 px-6">
                                    <div class="flex flex-col">
                                        <span class="text-[11px] font-black text-zinc-400 tracking-widest uppercase">EST-{{ str_pad($est->id, 5, '0', STR_PAD_LEFT) }}</span>
                                        <span class="text-[9px] font-bold text-zinc-500 uppercase">{{ $est->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-black text-zinc-900 dark:text-white uppercase tracking-tight">{{ $est->repairOrder->garageCustomer->user->name ?? '---' }}</span>
                                        <span class="text-[9px] font-bold text-[var(--active-2)] uppercase">{{ $est->repairOrder->vehicle->license_plate ?? '---' }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <span class="text-sm font-black text-zinc-900 dark:text-white">{{ number_format($est->total_amount ?? 0, 0, ',', ' ') }} F</span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    @php $isExpired = $est->valid_until && $est->valid_until->isPast(); @endphp
                                    <span class="text-[10px] font-bold {{ $isExpired ? 'text-red-500' : 'text-zinc-500' }} uppercase tracking-widest">
                                        {{ $est->valid_until ? $est->valid_until->format('d/m/Y') : '---' }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-[8px] font-black tracking-widest uppercase
                                        @switch($est->status)
                                            @case('PENDING') bg-amber-500/10 text-amber-600 @break
                                            @case('APPROVED') bg-green-500/10 text-green-600 @break
                                            @case('REJECTED') bg-red-500/10 text-red-600 @break
                                            @default bg-zinc-100 text-zinc-600
                                        @endswitch">
                                        {{ $est->status ?? 'BROUILLON' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <flux:dropdown>
                                        <flux:button size="xs" variant="ghost" icon="ellipsis-vertical" class="rounded-xl" />
                                        <flux:menu class="min-w-[180px] rounded-2xl p-2 shadow-2xl">
                                            <flux:menu.item icon="pencil" href="{{ route('garage.estimates.edit', $est->id) }}" class="rounded-xl font-bold text-xs">Modifier</flux:menu.item>
                                            <flux:menu.item icon="share" class="rounded-xl font-bold text-xs text-blue-600">Partager au client</flux:menu.item>
                                            <flux:menu.separator />
                                            @if($est->status === 'PENDING')
                                                <form method="POST" action="{{ route('garage.estimates.approve', $est->id) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <flux:menu.item icon="check-circle" type="submit" class="rounded-xl font-bold text-xs text-green-600">Approuver (Manuel)</flux:menu.item>
                                                </form>
                                            @endif
                                            <flux:menu.item icon="trash" variant="danger" class="rounded-xl font-bold text-xs">Supprimer</flux:menu.item>
                                        </flux:menu>
                                    </flux:dropdown>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-20 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="size-16 rounded-full bg-zinc-50 dark:bg-white/5 flex items-center justify-center border border-dashed border-zinc-200">
                                            <flux:icon icon="calculator" class="size-8 text-zinc-200" />
                                        </div>
                                        <p class="text-sm font-black text-zinc-900 dark:text-white uppercase tracking-tight">Aucun devis généré</p>
                                        <flux:button href="{{ route('garage.estimates.create') }}" size="sm" variant="primary" class="rounded-xl font-black uppercase tracking-widest mt-2">Nouveau Devis</flux:button>
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
</x-layouts::app>
