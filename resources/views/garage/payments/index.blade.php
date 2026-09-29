<x-layouts::app :title="__('garage.Historique des Paiements')">
    <x-garage.index-header
        title="Historique des Paiements"
        subtitle="Suivi des transactions encaissées - {{ $branch->name }}"
        canExport="true"
        searchPlaceholder="Référence transaction..."
        :filters="[
            [
                'name' => 'method',
                'label' => 'Moyen : Tous',
                'options' => ['CASH' => '💵 Espèces', 'MOBILE_MONEY' => '📱 Mobile Money', 'CARD' => '💳 Carte', 'BANK_TRANSFER' => '🏦 Virement']
            ]
        ]"
    />

    <div class="mt-2">
        <div class="bg-[var(--surface)] p-2 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-left border-b border-zinc-50 dark:border-white/5 bg-zinc-50/50 dark:bg-white/[0.02]">
                            <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Transaction</th>
                            <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Client</th>
                            <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Montant (F)</th>
                            <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">Méthode</th>
                            <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50 dark:divide-white/[0.02]">
                        @forelse($payments ?? [] as $pay)
                            <tr class="group hover:bg-zinc-50 dark:hover:bg-white/[0.01] transition-all cursor-pointer">
                                <td class="py-2.5 px-4">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-black text-zinc-900 dark:text-white uppercase tracking-tighter">{{ $pay->transaction_reference ?? '#' . $pay->id }}</span>
                                        <span class="text-[8px] font-bold text-zinc-400 uppercase">{{ $pay->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3">
                                    <span class="text-[11px] font-black text-zinc-700 dark:text-zinc-200 uppercase tracking-tighter">{{ $pay->invoice->repairOrder->garageCustomer->user->name ?? '---' }}</span>
                                </td>
                                <td class="py-2.5 px-3 text-right">
                                    <span class="text-[11px] font-black text-green-600 dark:text-green-400">+{{ number_format($pay->amount, 0, ',', ' ') }}</span>
                                </td>
                                <td class="py-2.5 px-3 text-center">
                                    <span class="px-2 py-0.5 rounded-lg bg-zinc-100 dark:bg-white/5 text-[7px] font-black text-zinc-500 uppercase tracking-widest">{{ $pay->payment_method }}</span>
                                </td>
                                <td class="py-2.5 px-4 text-right">
                                    <flux:button size="xs" variant="ghost" icon="printer" class="rounded-lg text-zinc-400" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-zinc-400">
                                    <p class="text-[10px] font-black uppercase">Aucun encaissement</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts::app>
