<x-layouts::app :title="__('Clients')">
    <x-garage.index-header
        title="Clients"
        subtitle="Base de données clients - {{ $branch->name }}"
        actionText="Nouveau Client"
        actionUrl="{{ route('garage.customers.create') }}"
        searchPlaceholder="Nom, Email ou Téléphone..."
        :filters="[
            [
                'name' => 'type',
                'label' => 'Tous les types',
                'options' => ['PARTICULIER' => 'Particulier', 'ENTREPRISE' => 'Entreprise']
            ]
        ]"
    />

    <div class="mt-2">
        <div class="bg-[var(--surface)] p-2 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-left border-b border-zinc-50 dark:border-white/5 bg-zinc-50/50 dark:bg-white/[0.02]">
                            <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Client</th>
                            <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">Interventions</th>
                            <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Contact</th>
                            <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50 dark:divide-white/[0.02]">
                        @forelse($customers ?? [] as $customer)
                            <tr class="group hover:bg-zinc-50 dark:hover:bg-white/[0.01] transition-all cursor-pointer">
                                <td class="py-2.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="size-8 rounded-xl bg-gradient-to-br from-blue-500/10 to-indigo-500/10 text-blue-600 flex items-center justify-center font-black text-xs border border-blue-500/10">
                                            {{ substr($customer->user->name ?? '?', 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[11px] font-black text-zinc-900 dark:text-white uppercase tracking-tight">{{ $customer->user->name ?? 'Client Inconnu' }}</span>
                                            <span class="text-[8px] font-black text-zinc-400 uppercase tracking-tighter">{{ $customer->customer_type ?? 'PARTICULIER' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3 text-center">
                                    <span class="text-[10px] font-black text-zinc-900 dark:text-white">{{ $customer->repair_orders_count ?? 0 }}</span>
                                </td>
                                <td class="py-2.5 px-3">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-zinc-600 dark:text-zinc-400">{{ $customer->user->phone ?? '---' }}</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-4 text-right">
                                    <flux:dropdown>
                                        <flux:button size="xs" variant="ghost" icon="ellipsis-vertical" class="rounded-lg" />
                                        <flux:menu class="min-w-[180px] rounded-xl p-1 shadow-xl">
                                            <flux:menu.item icon="pencil" href="{{ route('garage.customers.edit', $customer->id) }}" class="rounded-lg font-bold text-[10px] uppercase">Modifier</flux:menu.item>
                                            <flux:menu.item icon="wrench" href="{{ route('garage.repairs.create', ['customer_id' => $customer->id]) }}" class="rounded-lg font-bold text-[10px] uppercase">Réparer</flux:menu.item>
                                            <flux:menu.separator />
                                            <flux:menu.item icon="trash" variant="danger" class="rounded-lg font-bold text-[10px] uppercase">Supprimer</flux:menu.item>
                                        </flux:menu>
                                    </flux:dropdown>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center">
                                    <p class="text-[11px] font-black text-zinc-400 uppercase">Aucun client</p>
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
