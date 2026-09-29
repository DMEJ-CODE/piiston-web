<div class="card-premium !p-5">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider">Best Selling Products</h3>
        <button class="text-xs font-bold text-slate-400 hover:text-[var(--active-2)] dark:hover:text-white transition-colors">View All</button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left border-b border-slate-100 dark:border-slate-800/80">
                    <th class="pb-3 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Product</th>
                    <th class="pb-3 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Category</th>
                    <th class="pb-3 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest text-center">Sold</th>
                    <th class="pb-3 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest text-right">Profit</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/40">
                @php
                    $products = [
                        ['name' => 'Brembo Brake Pads', 'cat' => 'Brakes', 'sold' => '1,240', 'profit' => '$12,450', 'icon' => 'wrench-01'],
                        ['name' => 'Total Quartz 9000', 'cat' => 'Oils', 'sold' => '980', 'profit' => '$8,900', 'icon' => 'oil-barrel'],
                        ['name' => 'Varta Blue Battery', 'cat' => 'Electrical', 'sold' => '750', 'profit' => '$15,200', 'icon' => 'zap'],
                    ];
                @endphp
                @foreach($products as $p)
                <tr class="group hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-colors">
                    <td class="py-3">
                        <div class="flex items-center gap-3">
                            <div class="size-8 rounded-xl bg-[var(--active)]/10 text-[var(--active-2)] dark:text-[var(--active)] flex items-center justify-center border border-[var(--active)]/20 shadow-sm">
                                <i class="hgi-stroke hgi-{{ $p['icon'] }} text-lg"></i>
                            </div>
                            <span class="text-xs font-bold text-slate-900 dark:text-slate-100 uppercase tracking-tight">{{ $p['name'] }}</span>
                        </div>
                    </td>
                    <td class="py-3">
                        <span class="px-2.5 py-1 rounded-full bg-slate-100 dark:bg-white/5 border border-slate-200/50 dark:border-white/10 text-[9px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-wider">{{ $p['cat'] }}</span>
                    </td>
                    <td class="py-3 text-center">
                        <span class="text-xs font-bold text-slate-600 dark:text-slate-400">{{ $p['sold'] }}</span>
                    </td>
                    <td class="py-3 text-right">
                        <span class="text-xs font-black text-slate-900 dark:text-white">{{ $p['profit'] }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
