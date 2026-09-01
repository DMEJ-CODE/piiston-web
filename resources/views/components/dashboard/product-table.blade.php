<div class="bg-[var(--surface)] p-3 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-base font-black text-zinc-900 dark:text-white tracking-tight text-xl">Best Selling Products</h3>
        <button class="text-xs font-bold text-zinc-400 hover:text-zinc-900 dark:hover:text-white">View All</button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left border-b border-zinc-100 dark:border-white/5">
                    <th class="pb-1 text-[11px] font-black text-zinc-400 uppercase tracking-widest">Product</th>
                    <th class="pb-1 text-[11px] font-black text-zinc-400 uppercase tracking-widest">Category</th>
                    <th class="pb-1 text-[11px] font-black text-zinc-400 uppercase tracking-widest text-center">Sold</th>
                    <th class="pb-1 text-[11px] font-black text-zinc-400 uppercase tracking-widest text-right">Profit</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-50 dark:divide-white/[0.02]">
                @php
                    $products = [
                        ['name' => 'Brembo Brake Pads', 'cat' => 'Brakes', 'sold' => '1,240', 'profit' => '$12,450', 'icon' => 'wrench-01'],
                        ['name' => 'Total Quartz 9000', 'cat' => 'Oils', 'sold' => '980', 'profit' => '$8,900', 'icon' => 'oil-barrel'],
                        ['name' => 'Varta Blue Battery', 'cat' => 'Electrical', 'sold' => '750', 'profit' => '$15,200', 'icon' => 'zap'],
                        ['name' => 'Total Quartz 9000', 'cat' => 'Oils', 'sold' => '980', 'profit' => '$8,900', 'icon' => 'oil-barrel'],
                        ['name' => 'Varta Blue Battery', 'cat' => 'Electrical', 'sold' => '750', 'profit' => '$15,200', 'icon' => 'zap'],

                        ];
                @endphp
                @foreach($products as $p)
                <tr class="group hover:bg-zinc-50 dark:hover:bg-white/[0.01] transition-colors">
                    <td class="py-1">
                        <div class="flex items-center gap-3">
                            <div class="size-8 rounded-lg bg-[var(--active)]/10 text-[var(--active)] flex items-center justify-center border border-[var(--active)]/20">
                                <i class="hgi-stroke hgi-{{ $p['icon'] }} text-lg"></i>
                            </div>
                            <span class="text-sm font-bold text-zinc-900 dark:text-white">{{ $p['name'] }}</span>
                        </div>
                    </td>
                    <td class="py-1">
                        <span class="px-3 py-1.5 rounded-lg bg-zinc-100 dark:bg-white/5 text-[10px] font-black text-zinc-500 uppercase">{{ $p['cat'] }}</span>
                    </td>
                    <td class="py-1 text-center">
                        <span class="text-sm font-bold text-zinc-600 dark:text-zinc-400">{{ $p['sold'] }}</span>
                    </td>
                    <td class="py-1 text-right">
                        <span class="text-sm font-black text-zinc-900 dark:text-white">{{ $p['profit'] }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
