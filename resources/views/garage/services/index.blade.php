<x-layouts::app :title="__('Catalogue des Services')">
    <x-garage.index-header
        title="Catalogue des Services"
        subtitle="Prestations proposées par l'atelier - {{ $branch->name }}"
        actionText="Nouveau Service"
        actionUrl="#"
        actionIcon="tag"
        searchPlaceholder="Rechercher une prestation..."
    />

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Services List -->
        <div class="lg:col-span-8">
            <div class="bg-[var(--surface)] p-2 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-left border-b border-zinc-50 dark:border-white/5 bg-zinc-50/50 dark:bg-white/[0.02]">
                                <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Prestation</th>
                                <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">Durée</th>
                                <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Tarif (F)</th>
                                <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-50 dark:divide-white/[0.02]">
                            @forelse($services ?? [] as $service)
                                <tr class="group hover:bg-zinc-50 dark:hover:bg-white/[0.01] transition-all cursor-pointer">
                                    <td class="py-2.5 px-4">
                                        <div class="flex items-center gap-2">
                                            <div class="size-7 rounded-lg bg-blue-500/10 text-blue-600 flex items-center justify-center">
                                                <flux:icon icon="tag" variant="outline" class="size-3.5" />
                                            </div>
                                            <span class="text-[11px] font-black text-zinc-900 dark:text-white uppercase tracking-tight">{{ $service->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-2.5 px-3 text-center">
                                        <span class="text-[10px] font-bold text-zinc-500 uppercase">{{ $service->duration_minutes }}m</span>
                                    </td>
                                    <td class="py-2.5 px-3 text-right">
                                        <span class="text-[11px] font-black text-zinc-900 dark:text-white">{{ number_format($service->price, 0, ',', ' ') }}</span>
                                    </td>
                                    <td class="py-2.5 px-4 text-right">
                                        <flux:dropdown>
                                            <flux:button size="xs" variant="ghost" icon="ellipsis-vertical" class="rounded-lg" />
                                            <flux:menu class="min-w-[150px] rounded-xl p-1 shadow-xl">
                                                <flux:menu.item icon="pencil" class="rounded-lg font-bold text-[10px] uppercase">Modifier</flux:menu.item>
                                                <flux:menu.item icon="trash" variant="danger" class="rounded-lg font-bold text-[10px] uppercase">Retirer</flux:menu.item>
                                            </flux:menu>
                                        </flux:dropdown>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-12 text-center text-zinc-400">
                                        <p class="text-[10px] font-black uppercase">Catalogue vide</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add Service Card -->
        <div class="lg:col-span-4">
            <flux:card class="p-6 rounded-[16px] border-none shadow-xl">
                <h3 class="text-[11px] font-black uppercase tracking-widest mb-6">Ajouter une prestation</h3>
                <form method="POST" action="{{ route('garage.services.store') }}" class="space-y-4">
                    @csrf
                    <flux:field>
                        <flux:label class="text-[9px] uppercase font-black">Nom du service</flux:label>
                        <flux:input name="name" placeholder="Ex: Vidange moteur" required class="rounded-lg h-9 text-xs" />
                    </flux:field>
                    <div class="grid grid-cols-2 gap-3">
                        <flux:field>
                            <flux:label class="text-[9px] uppercase font-black">Prix (F)</flux:label>
                            <flux:input type="number" name="price" placeholder="0" required class="rounded-lg h-9 text-xs" />
                        </flux:field>
                        <flux:field>
                            <flux:label class="text-[9px] uppercase font-black">Durée (min)</flux:label>
                            <flux:input type="number" name="duration_minutes" placeholder="30" required class="rounded-lg h-9 text-xs" />
                        </flux:field>
                    </div>
                    <div class="pt-2">
                        <flux:button type="submit" variant="primary" class="w-full h-9 bg-gradient-to-br from-[var(--active-2)] to-[var(--active)] text-white border-none font-black uppercase tracking-widest text-[9px] rounded-lg">
                            Enregistrer
                        </flux:button>
                    </div>
                </form>
            </flux:card>
        </div>
    </div>
</x-layouts::app>
