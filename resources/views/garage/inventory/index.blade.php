<x-layouts::app :title="__('nav.Inventaire')">
    <x-garage.index-header
        title="Inventaire"
        subtitle="Gestion du stock de pièces - {{ $branch->name }}"
        actionText="Nouvelle Pièce"
        actionUrl="{{ route('garage.inventory.create') }}"
        searchPlaceholder="Référence ou Nom de pièce..."
        :filters="[
            [
                'name' => 'category',
                'label' => 'Toutes catégories',
                'options' => ['MOTEUR' => 'Moteur', 'FREINAGE' => 'Freinage', 'SUSPENSION' => 'Suspension', 'ELECTRICITE' => 'Électricité']
            ]
        ]"
    />

    @php
        $partItems = $parts instanceof \Illuminate\Pagination\LengthAwarePaginator ? $parts->items() : $parts;
        $stats = [
            'total' => $parts instanceof \Illuminate\Pagination\LengthAwarePaginator ? $parts->total() : collect($parts)->count(),
            'low_stock' => collect($partItems)->filter(function($p) { return $p->stock_quantity <= ($p->minimum_stock ?? 0); })->count(),
            'value' => collect($partItems)->sum(function($p) { return $p->stock_quantity * ($p->selling_price ?? 0); }),
        ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
        <x-dashboard.stat-card
            title="Total Références"
            :value="$stats['total']"
            icon="archive-02"
            color="var(--active-2)"
        />
        <x-dashboard.stat-card
            title="Ruptures Stock"
            :value="$stats['low_stock']"
            icon="alert-circle"
            color="#EF4444"
            :isNegative="$stats['low_stock'] > 0"
            trend="{{ $stats['low_stock'] > 0 ? 'CRITIQUE' : 'OK' }}"
        />
        <x-dashboard.stat-card
            title="Valeur du Stock"
            :value="number_format($stats['value'], 0, ',', ' ') . ' F'"
            icon="money-01"
            color="#10B981"
        />
    </div>

    <div class="mt-4">
        <div class="card-premium !p-0">
            <div class="overflow-x-auto">
                <table class="piiston-table w-full text-left">
                    <thead>
                        <tr>
                            <th>Référence</th>
                            <th>Pièce</th>
                            <th class="text-center">Stock</th>
                            <th class="text-right">Prix (F)</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($parts ?? [] as $part)
                            <tr class="cursor-pointer">
                                <td class="text-[10px] font-black text-slate-400 tracking-wider uppercase">{{ $part->part_number ?? '---' }}</td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="piiston-icon-avatar text-purple-500" style="--active-rgb: 168, 85, 247; --active-2-rgb: 59, 130, 246; color: #a855f7;">
                                            <flux:icon icon="archive-box" variant="outline" class="size-4" />
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[11px] font-black text-zinc-900 dark:text-white uppercase tracking-tight">{{ $part->name }}</span>
                                            <span class="text-[7px] font-black text-zinc-400 uppercase tracking-tighter">{{ $part->category ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="text-[10px] font-black {{ $part->stock_quantity <= ($part->minimum_stock ?? 0) ? 'text-red-500 animate-pulse' : 'text-zinc-700 dark:text-zinc-200' }}">
                                            {{ $part->stock_quantity }}
                                        </span>
                                        <div class="w-16 h-1 bg-zinc-100 dark:bg-white/5 rounded-full overflow-hidden">
                                            @php $perc = min(100, ($part->stock_quantity / max(1, $part->maximum_stock ?? 100)) * 100); @endphp
                                            <div class="h-full {{ $part->stock_quantity <= ($part->minimum_stock ?? 0) ? 'bg-red-500' : 'bg-green-500' }}" style="width: {{ $perc }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <span class="text-[11px] font-black text-zinc-900 dark:text-white">{{ number_format($part->selling_price ?? 0, 0, ',', ' ') }}</span>
                                </td>
                                <td class="text-right">
                                    <flux:dropdown>
                                        <flux:button size="xs" variant="ghost" icon="ellipsis-vertical" class="rounded-lg" />
                                        <flux:menu class="min-w-[160px] rounded-xl p-1 shadow-xl">
                                            <flux:menu.item icon="pencil" href="{{ route('garage.inventory.edit', $part->id) }}" class="rounded-lg font-bold text-[10px] uppercase">Modifier</flux:menu.item>
                                            <flux:menu.item icon="plus-circle" class="rounded-lg font-bold text-[10px] uppercase">Stock +</flux:menu.item>
                                            <flux:menu.separator />
                                            <flux:menu.item icon="trash" variant="danger" class="rounded-lg font-bold text-[10px] uppercase">Retirer</flux:menu.item>
                                        </flux:menu>
                                    </flux:dropdown>
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-state">
                                <td colspan="5" class="py-24 text-center">
                                    <div class="flex flex-col items-center justify-center gap-4">
                                        <div class="size-16 rounded-full bg-slate-50 dark:bg-white/5 flex items-center justify-center border border-slate-100 dark:border-white/10">
                                            <flux:icon icon="archive-box" class="size-8 text-slate-300 dark:text-slate-600" />
                                        </div>
                                        <div class="flex flex-col gap-1">
                                            <p class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest">Inventaire Vide</p>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase">Commencez à ajouter vos pièces.</p>
                                        </div>
                                        <flux:button href="{{ route('garage.inventory.create') }}" size="sm" class="btn-premium-primary mt-2">Nouvelle Pièce</flux:button>
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
    </div>
</x-layouts::app>
