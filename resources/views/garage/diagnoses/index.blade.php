<x-layouts::app :title="__('garage.Diagnostics')">
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
        <div class="piiston-table-card">
            <div class="overflow-x-auto">
                <table class="piiston-table w-full text-left">
                    <thead>
                        <tr>
                            <th>Rapport</th>
                            <th>Véhicule</th>
                            <th>Problème</th>
                            <th class="text-center">IA</th>
                            <th class="text-center">Gravité</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($diagnoses ?? [] as $diag)
                            <tr class="cursor-pointer">
                                <td>
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-black text-zinc-400 tracking-wider uppercase">#{{ str_pad($diag->id, 5, '0', STR_PAD_LEFT) }}</span>
                                        <span class="text-[8px] font-bold text-zinc-500 uppercase">{{ $diag->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-[11px] font-black text-zinc-900 dark:text-white uppercase tracking-tighter">{{ $diag->repairOrder->vehicle->license_plate ?? '---' }}</span>
                                </td>
                                <td>
                                    <span class="text-[10px] font-bold text-zinc-600 dark:text-zinc-300 truncate max-w-[200px] block">{{ $diag->detected_problem }}</span>
                                </td>
                                <td class="text-center">
                                    @if($diag->is_ai_generated)
                                        <flux:icon icon="cpu-chip" variant="solid" class="size-3.5 text-blue-500 mx-auto" />
                                    @else
                                        <flux:icon icon="user" variant="outline" class="size-3.5 text-zinc-200 mx-auto" />
                                    @endif
                                </td>
                                </td>
                                <td class="text-center">
                                    <span class="piiston-badge
                                        @switch($diag->severity)
                                            @case('critical') piiston-badge--red @break
                                            @case('medium') piiston-badge--amber @break
                                            @default piiston-badge--neutral
                                        @endswitch">
                                        {{ $diag->severity }}
                                    </span>
                                </td>
                                <td class="text-right">
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
                            <tr class="empty-state">
                                <td colspan="6">
                                    <p class="text-[10px] font-black uppercase text-slate-400">Aucun diagnostic</p>
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
