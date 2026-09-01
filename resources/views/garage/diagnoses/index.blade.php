<x-layouts::app :title="__('Diagnostics')">
    <x-garage.index-header
        title="Expertises & Diagnostics"
        subtitle="Rapports techniques détaillés - {{ $branch->name }}"
        actionText="Nouveau Diagnostic"
        actionUrl="{{ route('garage.diagnoses.create') }}"
        actionIcon="magnifying-glass"
        searchPlaceholder="Plaque ou Problème détecté..."
        :filters="[
            [
                'name' => 'severity',
                'label' => 'Gravité : Toutes',
                'options' => ['low' => '🟢 Basse', 'medium' => '🟡 Moyenne', 'high' => '🟠 Haute', 'critical' => '🔴 Critique']
            ]
        ]"
    />

    <div class="mt-2">
        <div class="bg-[var(--surface)] p-2 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-left border-b border-zinc-50 dark:border-white/5 bg-zinc-50/50 dark:bg-white/[0.02]">
                            <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Rapport</th>
                            <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Véhicule</th>
                            <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Problème</th>
                            <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">IA</th>
                            <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">Gravité</th>
                            <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50 dark:divide-white/[0.02]">
                        @forelse($diagnoses ?? [] as $diag)
                            <tr class="group hover:bg-zinc-50 dark:hover:bg-white/[0.01] transition-all cursor-pointer">
                                <td class="py-2.5 px-4">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-black text-zinc-400 tracking-wider uppercase">#{{ str_pad($diag->id, 5, '0', STR_PAD_LEFT) }}</span>
                                        <span class="text-[8px] font-bold text-zinc-500 uppercase">{{ $diag->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3">
                                    <span class="text-[11px] font-black text-zinc-900 dark:text-white uppercase tracking-tighter">{{ $diag->repairOrder->vehicle->license_plate ?? '---' }}</span>
                                </td>
                                <td class="py-2.5 px-3">
                                    <span class="text-[10px] font-bold text-zinc-600 dark:text-zinc-300 truncate max-w-[200px] block">{{ $diag->detected_problem }}</span>
                                </td>
                                <td class="py-2.5 px-3 text-center">
                                    @if($diag->is_ai_generated)
                                        <flux:icon icon="cpu-chip" variant="solid" class="size-3.5 text-blue-500 mx-auto" />
                                    @else
                                        <flux:icon icon="user" variant="outline" class="size-3.5 text-zinc-200 mx-auto" />
                                    @endif
                                </td>
                                <td class="py-2.5 px-3 text-center">
                                    <span class="px-2 py-0.5 rounded text-[7px] font-black uppercase
                                        @switch($diag->severity)
                                            @case('critical') bg-red-100 text-red-700 @break
                                            @case('medium') bg-amber-100 text-amber-700 @break
                                            @default bg-zinc-100 text-zinc-600
                                        @endswitch">
                                        {{ $diag->severity }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-4 text-right">
                                    <flux:dropdown>
                                        <flux:button size="xs" variant="ghost" icon="ellipsis-vertical" class="rounded-lg" />
                                        <flux:menu class="min-w-[150px] rounded-xl p-1 shadow-xl">
                                            <flux:menu.item icon="pencil" href="{{ route('garage.diagnoses.edit', $diag->id) }}" class="rounded-xl font-bold text-[10px] uppercase">Modifier</flux:menu.item>
                                            <flux:menu.item icon="trash" variant="danger" class="rounded-xl font-bold text-[10px] uppercase">Supprimer</flux:menu.item>
                                        </flux:menu>
                                    </flux:dropdown>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-zinc-400">
                                    <p class="text-[10px] font-black uppercase">Aucun diagnostic</p>
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
