<x-layouts::app :title="__('garage.Clients')">
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

    @php
        $customerItems = $customers instanceof \Illuminate\Pagination\LengthAwarePaginator ? $customers->items() : $customers;
        $stats = [
            'total' => $customers instanceof \Illuminate\Pagination\LengthAwarePaginator ? $customers->total() : collect($customers)->count(),
            'individuals' => collect($customerItems)->where('customer_type', 'PARTICULIER')->count(),
            'companies' => collect($customerItems)->where('customer_type', 'ENTREPRISE')->count(),
        ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
        <x-dashboard.stat-card
            title="Total Clients"
            :value="$stats['total']"
            icon="user-group"
            color="var(--active-2)"
        />
        <x-dashboard.stat-card
            title="Particuliers"
            :value="$stats['individuals']"
            icon="user-profile-02"
            color="#3B82F6"
        />
        <x-dashboard.stat-card
            title="Entreprises"
            :value="$stats['companies']"
            icon="building-04"
            color="#8B5CF6"
        />
    </div>

    <div class="mt-4">
        <div class="card-premium !p-0">
            <div class="overflow-x-auto">
                <table class="piiston-table w-full text-left">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th class="text-center">Interventions</th>
                            <th>Contact</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers ?? [] as $customer)
                            <tr class="cursor-pointer">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="piiston-icon-avatar text-xs font-black" style="--active-rgb: 59, 130, 246; --active-2-rgb: 99, 102, 241; color: #6366f1;">
                                            {{ substr($customer->user->name ?? '?', 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[11px] font-black text-zinc-900 dark:text-white uppercase tracking-tight">{{ $customer->user->name ?? 'Client Inconnu' }}</span>
                                            <span class="text-[8px] font-black text-zinc-400 uppercase tracking-tighter">{{ $customer->customer_type ?? 'PARTICULIER' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="text-[10px] font-black text-zinc-900 dark:text-white">{{ $customer->repair_orders_count ?? 0 }}</span>
                                </td>
                                <td>
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-zinc-600 dark:text-zinc-400">{{ $customer->user->phone ?? '---' }}</span>
                                    </div>
                                </td>
                                <td class="text-right">
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
                            <tr class="empty-state">
                                <td colspan="4" class="py-24 text-center">
                                    <div class="flex flex-col items-center justify-center gap-4">
                                        <div class="size-16 rounded-full bg-slate-50 dark:bg-white/5 flex items-center justify-center border border-slate-100 dark:border-white/10">
                                            <flux:icon icon="users" class="size-8 text-slate-300 dark:text-slate-600" />
                                        </div>
                                        <div class="flex flex-col gap-1">
                                            <p class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest">Aucun Client</p>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase">La base de données est vide.</p>
                                        </div>
                                        <flux:button href="{{ route('garage.customers.create') }}" size="sm" class="btn-premium-primary mt-2">Nouveau Client</flux:button>
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
