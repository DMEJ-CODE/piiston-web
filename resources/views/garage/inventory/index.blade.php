<x-layouts::app :title="__('Inventaire')">
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

    <div class="mt-2">
        <div class="bg-[var(--surface)] p-2 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-left border-b border-zinc-50 dark:border-white/5 bg-zinc-50/50 dark:bg-white/[0.02]">
                            <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Référence</th>
                            <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Pièce</th>
                            <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">Stock</th>
                            <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Prix (F)</th>
                            <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50 dark:divide-white/[0.02]">
                        @forelse($parts ?? [] as $part)
                            <tr class="group hover:bg-zinc-50 dark:hover:bg-white/[0.01] transition-all cursor-pointer">
                                <td class="py-2.5 px-4 text-[10px] font-black text-zinc-400 tracking-wider uppercase">{{ $part->part_number ?? '---' }}</td>
                                <td class="py-2.5 px-3">
                                    <div class="flex items-center gap-3">
                                        <div class="size-8 rounded-lg bg-gradient-to-br from-purple-500/10 to-blue-500/10 text-purple-600 flex items-center justify-center border border-purple-500/10">
                                            <flux:icon icon="archive-box" variant="outline" class="size-3.5" />
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[11px] font-black text-zinc-900 dark:text-white uppercase tracking-tight">{{ $part->name }}</span>
                                            <span class="text-[7px] font-black text-zinc-400 uppercase tracking-tighter">{{ $part->category ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3">
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
                                <td class="py-2.5 px-3 text-right">
                                    <span class="text-[11px] font-black text-zinc-900 dark:text-white">{{ number_format($part->selling_price ?? 0, 0, ',', ' ') }}</span>
                                </td>
                                <td class="py-2.5 px-4 text-right">
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
                            <tr>
                                <td colspan="5" class="py-12 text-center text-zinc-400">
                                    <p class="text-[10px] font-black uppercase">Inventaire vide</p>
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
