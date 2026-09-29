<x-layouts::app :title="__('garage.Check-ins')">
    <x-garage.index-header
        title="Réception & Check-ins"
        subtitle="Inspection des véhicules à l'arrivée - {{ $branch->name }}"
        actionText="Nouveau Check-in"
        actionUrl="{{ route('garage.check-ins.create') }}"
        searchPlaceholder="Plaque du véhicule..."
    />

    <div class="mt-2">
        <div class="bg-[var(--surface)] p-2 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-left border-b border-zinc-50 dark:border-white/5 bg-zinc-50/50 dark:bg-white/[0.02]">
                            <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Arrivée</th>
                            <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Véhicule / Client</th>
                            <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest">État</th>
                            <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">Signature</th>
                            <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50 dark:divide-white/[0.02]">
                        @forelse($checkIns ?? [] as $ci)
                            <tr class="group hover:bg-zinc-50 dark:hover:bg-white/[0.01] transition-all cursor-pointer">
                                <td class="py-2.5 px-4">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-black text-zinc-900 dark:text-white uppercase">{{ $ci->arrival_date->format('d/m/Y') }}</span>
                                        <span class="text-[8px] font-bold text-zinc-400 uppercase">{{ $ci->arrival_date->format('H:i') }}</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3">
                                    <div class="flex flex-col">
                                        <span class="text-[11px] font-black text-zinc-900 dark:text-white uppercase tracking-tighter">{{ $ci->vehicle->license_plate ?? '---' }}</span>
                                        <span class="text-[8px] font-bold text-zinc-400 uppercase">{{ $ci->customer->user->name ?? '---' }}</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-black text-zinc-700 dark:text-zinc-200 uppercase">{{ number_format($ci->mileage ?? 0, 0, ',', ' ') }} KM</span>
                                        <span class="text-zinc-200">|</span>
                                        <span class="text-[9px] font-bold text-zinc-400 uppercase">{{ $ci->fuel_level ?? '?' }}</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3 text-center">
                                    @if($ci->signature_path)
                                        <flux:icon icon="check-badge" variant="solid" class="size-4 text-green-500 mx-auto" />
                                    @else
                                        <flux:icon icon="x-circle" variant="outline" class="size-4 text-zinc-200 mx-auto" />
                                    @endif
                                </td>
                                <td class="py-2.5 px-4 text-right">
                                    <flux:dropdown>
                                        <flux:button size="xs" variant="ghost" icon="ellipsis-vertical" class="rounded-lg" />
                                        <flux:menu class="min-w-[150px] rounded-xl p-1 shadow-xl">
                                            <flux:menu.item icon="pencil" href="{{ route('garage.check-ins.edit', $ci->id) }}" class="rounded-lg font-bold text-[10px] uppercase">Modifier</flux:menu.item>
                                            <flux:menu.item icon="eye" class="rounded-lg font-bold text-[10px] uppercase">Rapport</flux:menu.item>
                                            <flux:menu.separator />
                                            @if(!$ci->repairOrder)
                                                <flux:menu.item icon="wrench" class="rounded-lg font-bold text-[10px] uppercase text-[var(--active-2)]">Créer RO</flux:menu.item>
                                            @else
                                                <flux:menu.item icon="arrow-top-right-on-square" href="{{ route('garage.repairs.show', $ci->repairOrder->id) }}" class="rounded-lg font-bold text-[10px] uppercase">RO #{{ $ci->repairOrder->id }}</flux:menu.item>
                                            @endif
                                        </flux:menu>
                                    </flux:dropdown>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-zinc-400">
                                    <p class="text-[10px] font-black uppercase">Aucun check-in</p>
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
