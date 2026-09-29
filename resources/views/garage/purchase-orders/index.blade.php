<x-layouts::app :title="__('garage.Commandes Fournisseurs')">
    <x-garage.index-header
        title="Commandes Fournisseurs"
        subtitle="Suivi des achats de pièces - {{ $branch->name }}"
        actionText="Nouvelle Commande"
        actionUrl="{{ route('garage.purchase-orders.create') }}"
        searchPlaceholder="N° de commande ou Fournisseur..."
        :filters="[
            [
                'name' => 'status',
                'label' => 'Tous les statuts',
                'options' => ['draft' => 'Brouillon', 'ordered' => 'Commandé', 'received' => 'Reçu', 'cancelled' => 'Annulé']
            ]
        ]"
    />

    <div class="mt-2">
        <div class="bg-[var(--surface)] p-2 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-left border-b border-zinc-50 dark:border-white/5 bg-zinc-50/50 dark:bg-white/[0.02]">
                            <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Commande</th>
                            <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Fournisseur</th>
                            <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Total</th>
                            <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">État</th>
                            <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50 dark:divide-white/[0.02]">
                        @forelse($purchaseOrders ?? [] as $po)
                            <tr class="group hover:bg-zinc-50 dark:hover:bg-white/[0.01] transition-all cursor-pointer">
                                <td class="py-2.5 px-4">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-black text-zinc-400 uppercase tracking-tighter">PO-{{ str_pad($po->id, 5, '0', STR_PAD_LEFT) }}</span>
                                        <span class="text-[8px] font-bold text-zinc-500 uppercase">{{ $po->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3">
                                    <span class="text-[11px] font-black text-zinc-900 dark:text-white uppercase tracking-tight">{{ $po->supplier->name ?? '---' }}</span>
                                </td>
                                <td class="py-2.5 px-3 text-right">
                                    <span class="text-[10px] font-black text-zinc-900 dark:text-white">{{ number_format($po->total_amount ?? 0, 0, ',', ' ') }}</span>
                                </td>
                                <td class="py-2.5 px-3 text-center">
                                    <span class="px-2 py-0.5 rounded-lg text-[7px] font-black tracking-widest uppercase
                                        @switch($po->status)
                                            @case('received') bg-green-500/10 text-green-600 @break
                                            @case('ordered') bg-blue-500/10 text-blue-600 @break
                                            @default bg-zinc-100 text-zinc-600
                                        @endswitch">
                                        {{ $po->status }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-4 text-right">
                                    <div class="flex justify-end gap-1">
                                        @if($po->status !== 'received')
                                            <form method="POST" action="{{ route('garage.purchase-orders.receive', $po->id) }}">
                                                @csrf
                                                @foreach($po->items as $item) <input type="hidden" name="received_items[{{ $item->id }}]" value="{{ $item->quantity }}"> @endforeach
                                                <flux:button size="xs" type="submit" variant="ghost" class="text-green-600 font-black uppercase text-[8px]">Recevoir</flux:button>
                                            </form>
                                        @endif
                                        <flux:dropdown>
                                            <flux:button size="xs" variant="ghost" icon="ellipsis-vertical" class="rounded-lg" />
                                            <flux:menu class="min-w-[150px] rounded-xl p-1 shadow-xl">
                                                <flux:menu.item icon="pencil" href="{{ route('garage.purchase-orders.edit', $po->id) }}" class="rounded-lg font-bold text-[10px] uppercase">Modifier</flux:menu.item>
                                                <flux:menu.item icon="trash" variant="danger" class="rounded-lg font-bold text-[10px] uppercase">Annuler</flux:menu.item>
                                            </flux:menu>
                                        </flux:dropdown>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-12 text-center text-zinc-400 text-[10px] uppercase font-black">Aucune commande</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
    </div>
</x-layouts::app>
