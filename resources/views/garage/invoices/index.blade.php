<x-layouts::app :title="__('Facturation Globale')">
    <x-garage.index-header
        title="Facturation Globale"
        subtitle="Suivi des règlements et documents fiscaux - {{ $branch->name }}"
        canExport="true"
        searchPlaceholder="Client ou Plaque..."
        :filters="[
            [
                'name' => 'status',
                'label' => 'État : Tous',
                'options' => ['unpaid' => '🔴 Impayé', 'paid' => '🟢 Payé', 'partial' => '🟡 Partiel']
            ]
        ]"
    />

    <div class="mt-2">
        <div class="bg-[var(--surface)] p-2 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-left border-b border-zinc-50 dark:border-white/5 bg-zinc-50/50 dark:bg-white/[0.02]">
                            <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">N° Facture</th>
                            <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Client</th>
                            <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Total (F)</th>
                            <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">État</th>
                            <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50 dark:divide-white/[0.02]">
                        @forelse($invoices ?? [] as $inv)
                            <tr class="group hover:bg-zinc-50 dark:hover:bg-white/[0.01] transition-all cursor-pointer">
                                <td class="py-2.5 px-4">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-black text-zinc-900 dark:text-white uppercase tracking-wider">#{{ str_pad($inv->id, 6, '0', STR_PAD_LEFT) }}</span>
                                        <span class="text-[8px] font-bold text-zinc-400 uppercase">{{ $inv->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3">
                                    <span class="text-[11px] font-black text-zinc-700 dark:text-zinc-200 uppercase tracking-tighter">{{ $inv->repairOrder->garageCustomer->user->name ?? '---' }}</span>
                                </td>
                                <td class="py-2.5 px-3 text-right">
                                    <span class="text-[11px] font-black text-zinc-900 dark:text-white">{{ number_format($inv->total_payable, 0, ',', ' ') }}</span>
                                </td>
                                <td class="py-2.5 px-3 text-center">
                                    <span class="px-2 py-0.5 rounded-lg text-[7px] font-black tracking-widest uppercase
                                        @switch($inv->status)
                                            @case('paid') bg-green-500/10 text-green-600 @break
                                            @case('unpaid') bg-red-500/10 text-red-600 @break
                                            @default bg-zinc-100 text-zinc-600
                                        @endswitch">
                                        {{ $inv->status }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-4 text-right">
                                    <flux:dropdown>
                                        <flux:button size="xs" variant="ghost" icon="ellipsis-vertical" class="rounded-lg" />
                                        <flux:menu class="min-w-[150px] rounded-xl p-1 shadow-xl">
                                            <flux:menu.item icon="printer" href="{{ route('garage.repairs.invoice.print', $inv->repair_order_id) }}" target="_blank" class="rounded-lg font-bold text-[10px] uppercase">Imprimer</flux:menu.item>
                                            <flux:menu.item icon="banknotes" class="rounded-lg font-bold text-[10px] uppercase text-green-600">Payer</flux:menu.item>
                                            <flux:menu.separator />
                                            <flux:menu.item icon="eye" href="{{ route('garage.repairs.show', $inv->repair_order_id) }}" class="rounded-lg font-bold text-[10px] uppercase">RO</flux:menu.item>
                                        </flux:menu>
                                    </flux:dropdown>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-zinc-400">
                                    <p class="text-[10px] font-black uppercase">Aucune facture</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts::app>
