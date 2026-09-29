<x-layouts::app :title="__('garage.Marketplace de Pièces')">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">Marketplace de Pièces</h1>
                <p class="text-sm text-slate-500 dark:text-zinc-400">Trouvez et achetez des pièces détachées pour vos réparations.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <!-- Marketplace Product Grid Placeholder -->
            <div class="bg-white dark:bg-surfaceDark rounded-2xl p-4 border border-slate-100 dark:border-white/5 shadow-sm">
                <div class="h-40 bg-slate-100 dark:bg-white/5 rounded-xl mb-4 flex items-center justify-center">
                    <i class="hgi-stroke hgi-package-01 size-10 text-slate-400"></i>
                </div>
                <h3 class="font-bold text-slate-800 dark:text-white text-base mb-1">Plaquette de frein avant</h3>
                <p class="text-xs text-slate-500 dark:text-zinc-400 mb-3">Bosch - Ref: BS-4421</p>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-black text-[var(--active)]">15,000 CFA</span>
                    <button class="px-3 py-1.5 bg-[var(--active)] text-white rounded-xl text-xs font-bold">Commander</button>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
