<x-layouts::app :title="__('garage.Recherche Avancée')">
    <div class="max-w-4xl mx-auto py-6">
        <div class="mb-8">
            <flux:heading size="lg" class="font-black uppercase tracking-tight text-center">Recherche Intelligente</flux:heading>
            <flux:text class="text-[9px] font-bold uppercase tracking-[3px] text-zinc-500 text-center mt-1">Explorez les données de l'atelier</flux:text>
        </div>

        <!-- Centered Big Search Bar -->
        <div class="max-w-2xl mx-auto">
            <div class="bg-[var(--surface)] p-2 rounded-[24px] border border-zinc-100 dark:border-white/5 shadow-xl flex items-center gap-2">
                <form action="{{ route('garage.search') }}" method="GET" class="flex-1 flex items-center gap-2">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <flux:icon icon="magnifying-glass" class="size-4 text-zinc-300" />
                        </div>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Nom, plaque, VIN ou référence..."
                            class="block w-full pl-11 pr-4 py-3 bg-zinc-50 dark:bg-white/5 border-none rounded-xl text-xs font-black text-zinc-900 dark:text-white focus:ring-0 outline-none transition-all placeholder:text-zinc-300">
                    </div>
                    <flux:button type="submit" variant="primary" class="rounded-xl font-black uppercase tracking-widest bg-zinc-900 text-white border-none px-8 h-10">
                        Trouver
                    </flux:button>
                </form>
            </div>

            <!-- Search Suggestion Chips -->
            <div class="flex flex-wrap justify-center gap-2 mt-6">
                @foreach(['Plaque', 'Client', 'Facture', 'Pièce', 'RO'] as $chip)
                    <button class="px-3 py-1.5 rounded-full bg-white dark:bg-white/5 border border-zinc-100 dark:border-white/5 text-[8px] font-black text-zinc-500 uppercase tracking-widest hover:text-[var(--active-2)] transition-all">{{ $chip }}</button>
                @endforeach
            </div>
        </div>

        @if(request('q'))
            <div class="mt-12">
                <div class="flex items-center justify-between mb-6 px-2">
                    <h3 class="text-[10px] font-black text-zinc-900 dark:text-white uppercase tracking-widest">Résultats pour "{{ request('q') }}"</h3>
                </div>

                <!-- Empty State -->
                <div class="bg-[var(--surface)] p-12 rounded-[24px] border border-zinc-100 dark:border-white/5 text-center flex flex-col items-center gap-4 shadow-sm">
                    <flux:icon icon="document-magnifying-glass" class="size-8 text-zinc-200" />
                    <p class="text-xs font-black text-zinc-900 dark:text-white uppercase tracking-tight">Aucun résultat</p>
                </div>
            </div>
        @endif
    </div>
</x-layouts::app>
