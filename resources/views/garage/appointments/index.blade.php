<x-layouts::app :title="__('Rendez-vous')">
    <x-garage.index-header
        title="Planning des RDV"
        subtitle="Gestion des rendez-vous et interventions - {{ $branch->name }}"
        actionText="Nouveau RDV"
        actionUrl="{{ route('garage.appointments.create') }}"
        actionIcon="calendar-days"
        searchPlaceholder="Client ou Plaque..."
        :filters="[
            [
                'name' => 'status',
                'label' => 'Statut : Tous',
                'options' => [
                    'REQUESTED' => '⏳ Demandé',
                    'CONFIRMED' => '✅ Confirmé',
                    'COMPLETED' => '🏁 Terminé',
                    'CANCELLED' => '❌ Annulé',
                ]
            ]
        ]"
    />

    <div class="mt-2">
        <div class="bg-[var(--surface)] p-2 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-left border-b border-zinc-50 dark:border-white/5 bg-zinc-50/50 dark:bg-white/[0.02]">
                            <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Date & Heure</th>
                            <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Client</th>
                            <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Véhicule</th>
                            <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">État</th>
                            <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50 dark:divide-white/[0.02]">
                        @forelse($appointments ?? [] as $apt)
                            <tr class="group hover:bg-zinc-50 dark:hover:bg-white/[0.01] transition-all cursor-pointer">
                                <td class="py-2.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex flex-col items-center justify-center size-9 rounded-xl bg-zinc-50 dark:bg-white/5 border border-zinc-100">
                                            <span class="text-[7px] font-black uppercase text-zinc-400 leading-none">{{ \Carbon\Carbon::parse($apt->scheduled_date)->format('M') }}</span>
                                            <span class="text-sm font-black text-zinc-900 dark:text-white leading-none">{{ \Carbon\Carbon::parse($apt->scheduled_date)->format('d') }}</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[10px] font-black text-[var(--active-2)] uppercase">{{ \Carbon\Carbon::parse($apt->scheduled_date)->format('H:i') }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3">
                                    <span class="text-[11px] font-bold text-zinc-700 dark:text-zinc-200">{{ $apt->customer->user->name ?? '---' }}</span>
                                </td>
                                <td class="py-2.5 px-3">
                                    <span class="text-[10px] font-black text-zinc-900 dark:text-white uppercase tracking-tighter">{{ $apt->vehicle->license_plate ?? '---' }}</span>
                                </td>
                                <td class="py-2.5 px-3 text-center">
                                    <span class="px-2 py-0.5 rounded-lg text-[7px] font-black tracking-widest uppercase
                                        @switch($apt->status)
                                            @case('REQUESTED') bg-zinc-100 text-zinc-600 @break
                                            @case('CONFIRMED') bg-blue-500/10 text-blue-600 @break
                                            @case('COMPLETED') bg-green-500/10 text-green-600 @break
                                            @default bg-zinc-100 text-zinc-600
                                        @endswitch">
                                        {{ $apt->status }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-4 text-right">
                                    <flux:dropdown>
                                        <flux:button size="xs" variant="ghost" icon="ellipsis-vertical" class="rounded-lg" />
                                        <flux:menu class="min-w-[150px] rounded-xl p-1 shadow-xl">
                                            <flux:menu.item icon="pencil" href="{{ route('garage.appointments.edit', $apt->id) }}" class="rounded-lg font-bold text-[10px] uppercase">Modifier</flux:menu.item>
                                            <flux:menu.item icon="truck" href="{{ route('garage.check-ins.create', ['appointment_id' => $apt->id]) }}" class="rounded-lg font-bold text-[10px] uppercase">Réception</flux:menu.item>
                                            <flux:menu.separator />
                                            <flux:menu.item icon="trash" variant="danger" class="rounded-lg font-bold text-[10px] uppercase">Annuler</flux:menu.item>
                                        </flux:menu>
                                    </flux:dropdown>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-zinc-400">
                                    <p class="text-[10px] font-black uppercase">Aucun RDV</p>
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
