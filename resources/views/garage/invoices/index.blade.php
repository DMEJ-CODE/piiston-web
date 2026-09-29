<x-layouts::app :title="__('garage.Facturation Globale')">
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

    @php
        $invoiceItems = $invoices instanceof \Illuminate\Pagination\LengthAwarePaginator ? $invoices->items() : $invoices;
        $stats = [
            'total' => collect($invoiceItems)->sum('total_payable'),
            'paid' => collect($invoiceItems)->where('status', 'paid')->sum('total_payable'),
            'unpaid' => collect($invoiceItems)->where('status', 'unpaid')->sum('total_payable'),
        ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
        <x-dashboard.stat-card
            title="Total Facturé"
            :value="number_format($stats['total'], 0, ',', ' ') . ' F'"
            icon="note-01"
            color="var(--active-2)"
        />
        <x-dashboard.stat-card
            title="Encaissé"
            :value="number_format($stats['paid'], 0, ',', ' ') . ' F'"
            icon="tick-double-02"
            color="#10B981"
        />
        <x-dashboard.stat-card
            title="Impayés"
            :value="number_format($stats['unpaid'], 0, ',', ' ') . ' F'"
            icon="alert-circle"
            color="#EF4444"
        />
    </div>

    <div class="mt-4">
        <div class="card-premium !p-0">
            <div class="overflow-x-auto">
                <table class="piiston-table w-full text-left">
                    <thead>
                        <tr>
                            <th>N° Facture</th>
                            <th>Client</th>
                            <th class="text-right">Total (F)</th>
                            <th class="text-center">État</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices ?? [] as $inv)
                            <tr class="cursor-pointer">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="piiston-icon-avatar text-blue-500" style="--active-rgb: 59, 130, 246; --active-2-rgb: 37, 99, 235; color: #3b82f6;">
                                            <flux:icon icon="document-text" variant="outline" class="size-4" />
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-xs font-black text-zinc-900 dark:text-white uppercase tracking-wider">#{{ str_pad($inv->id, 6, '0', STR_PAD_LEFT) }}</span>
                                            <span class="text-[9px] font-bold text-zinc-400 uppercase tracking-widest">{{ $inv->created_at->format('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-[11px] font-black text-zinc-700 dark:text-zinc-200 uppercase tracking-tighter">{{ $inv->repairOrder->garageCustomer->user->name ?? '---' }}</span>
                                </td>
                                <td class="text-right">
                                    <span class="text-[11px] font-black text-zinc-900 dark:text-white">{{ number_format($inv->total_payable, 0, ',', ' ') }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="piiston-badge
                                        @switch($inv->status)
                                            @case('paid') piiston-badge--green @break
                                            @case('unpaid') piiston-badge--red @break
                                            @default piiston-badge--neutral
                                        @endswitch">
                                        {{ $inv->status }}
                                    </span>
                                </td>
                                <td class="text-right">
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
                            <tr class="empty-state">
                                <td colspan="5" class="py-24 text-center">
                                    <div class="flex flex-col items-center justify-center gap-4">
                                        <div class="size-16 rounded-full bg-slate-50 dark:bg-white/5 flex items-center justify-center border border-slate-100 dark:border-white/10">
                                            <flux:icon icon="document-text" class="size-8 text-slate-300 dark:text-slate-600" />
                                        </div>
                                        <div class="flex flex-col gap-1">
                                            <p class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest">Aucune Facture</p>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase">Aucune donnée de facturation trouvée.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts::app>
