<x-layouts::app :title="__('garage.Catalogue des Services')">
    <x-garage.index-header
        title="Catalogue des Services"
        subtitle="Prestations proposées par l'atelier - {{ $branch->name }}"
        actionText="Nouveau Service"
        actionUrl="#"
        actionIcon="tag"
        searchPlaceholder="Rechercher une prestation..."
    />

    @php
        $stats = [
            'total' => collect($services)->count(),
            'avg_price' => collect($services)->avg('price'),
        ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 mb-6">
        <x-dashboard.stat-card
            title="Total Prestations"
            :value="$stats['total']"
            icon="tag-01"
            color="var(--active-2)"
        />
        <x-dashboard.stat-card
            title="Tarif Moyen"
            :value="number_format($stats['avg_price'] ?? 0, 0, ',', ' ') . ' F'"
            icon="money-01"
            color="#10B981"
        />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Services List -->
        <div class="lg:col-span-8">
            <div class="card-premium !p-0">
                <div class="overflow-x-auto">
                    <table class="piiston-table w-full text-left">
                        <thead>
                            <tr>
                                <th>Prestation</th>
                                <th class="text-center">Durée</th>
                                <th class="text-right">Tarif (F)</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($services ?? [] as $service)
                                <tr class="cursor-pointer">
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="piiston-icon-avatar text-blue-500" style="--active-rgb: 59, 130, 246; --active-2-rgb: 37, 99, 235; color: #3b82f6;">
                                                <flux:icon icon="tag" variant="outline" class="size-4" />
                                            </div>
                                            <span class="text-[11px] font-black text-zinc-900 dark:text-white uppercase tracking-tight">{{ $service->name }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-[10px] font-bold text-zinc-500 uppercase">{{ $service->duration_minutes }}m</span>
                                    </td>
                                    <td class="text-right">
                                        <span class="text-[11px] font-black text-zinc-900 dark:text-white">{{ number_format($service->price, 0, ',', ' ') }}</span>
                                    </td>
                                    <td class="text-right">
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
                                <tr class="empty-state">
                                    <td colspan="4" class="py-24 text-center">
                                        <div class="flex flex-col items-center justify-center gap-4">
                                            <div class="size-16 rounded-full bg-slate-50 dark:bg-white/5 flex items-center justify-center border border-slate-100 dark:border-white/10">
                                                <flux:icon icon="tag" class="size-8 text-slate-300 dark:text-slate-600" />
                                            </div>
                                            <div class="flex flex-col gap-1">
                                                <p class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest">Catalogue Vide</p>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase">Ajoutez vos prestations depuis le formulaire.</p>
                                            </div>
                                        </div>
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
            <div class="card-premium p-6">
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
                        <button type="submit" class="btn-premium-primary w-full h-10">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts::app>
