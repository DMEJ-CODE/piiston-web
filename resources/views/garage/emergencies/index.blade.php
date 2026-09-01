<x-layouts::app :title="__('Appels d\'Urgence')">
    <x-garage.index-header
        title="Appels d'Urgence"
        subtitle="SOS et assistances immédiates à proximité - {{ $branch->name }}"
        searchPlaceholder="Rechercher une urgence..."
    />

    <div class="mt-2">
        <div class="bg-[var(--surface)] p-4 rounded-[32px] border border-zinc-100 dark:border-white/5 shadow-2xl shadow-zinc-200/20 dark:shadow-none overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-zinc-50 dark:border-white/5 bg-zinc-50/50 dark:bg-white/[0.02]">
                            <th class="py-5 px-6 text-[10px] font-black text-zinc-400 uppercase tracking-[2px]">Alerte</th>
                            <th class="py-5 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-[2px]">Client / Véhicule</th>
                            <th class="py-5 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-[2px]">Position</th>
                            <th class="py-5 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-[2px] text-center">Statut</th>
                            <th class="py-5 px-6 text-[10px] font-black text-zinc-400 uppercase tracking-[2px] text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50 dark:divide-white/[0.02]">
                        @forelse($emergencies ?? [] as $sos)
                            <tr class="group hover:bg-red-50/30 dark:hover:bg-red-900/5 transition-all">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="size-10 rounded-2xl bg-red-500/10 text-red-600 flex items-center justify-center font-black text-[10px] border border-red-500/20 animate-pulse">
                                            SOS
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[9px] font-black text-red-500 uppercase tracking-widest">Alerte Critique</span>
                                            <span class="text-[10px] font-bold text-zinc-400 uppercase">{{ $sos->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-black text-zinc-900 dark:text-white uppercase tracking-tight">{{ $sos->user->name ?? 'Automobiliste' }}</span>
                                        <span class="text-[9px] font-bold text-[var(--active-2)] uppercase">{{ $sos->vehicle->license_plate ?? '---' }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-zinc-50 dark:bg-white/5 border border-zinc-100 dark:border-white/5 w-fit cursor-pointer hover:bg-zinc-100 transition-all">
                                        <flux:icon icon="map-pin" class="size-3.5 text-zinc-400" />
                                        <span class="text-[9px] font-black uppercase tracking-widest text-zinc-600 dark:text-zinc-300">Voir sur carte</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    @if($sos->status === 'REQUESTED')
                                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-[8px] font-black uppercase tracking-widest border border-red-200 animate-pulse">EN ATTENTE</span>
                                    @elseif($sos->status === 'ACCEPTED' || $sos->status === 'IN_PROGRESS')
                                        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-[8px] font-black uppercase tracking-widest border border-blue-200">EN ROUTE</span>
                                    @elseif($sos->status === 'ARRIVED')
                                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-[8px] font-black uppercase tracking-widest border border-green-200">ARRIVÉ SUR LES LIEUX</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right">
                                    @if($sos->status === 'REQUESTED')
                                        <form method="POST" action="{{ route('garage.emergencies.accept', $sos->id) }}" class="flex items-center gap-2 justify-end">
                                            @csrf
                                            <select name="mechanic_id" class="text-[10px] font-bold uppercase tracking-widest bg-zinc-50 dark:bg-white/5 border border-zinc-100 dark:border-white/5 rounded-xl px-3 h-10 outline-none focus:ring-1 focus:ring-red-500/50">
                                                <option value="">{{ __('Assigner à...') }}</option>
                                                @foreach($branch->employees as $employee)
                                                    <option value="{{ $employee->user_id }}">{{ $employee->user->name }}</option>
                                                @endforeach
                                            </select>
                                            <flux:button type="submit" size="sm" variant="primary" class="bg-gradient-to-br from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 border-none shadow-xl shadow-red-500/20 font-black uppercase tracking-widest px-6 h-10">
                                                Intervenir
                                            </flux:button>
                                        </form>
                                    @else
                                        <div class="flex flex-col items-end">
                                            <span class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Assigné à</span>
                                            <span class="text-xs font-black text-zinc-900 dark:text-white">{{ $sos->mechanic->name ?? 'Mécano' }}</span>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-20 text-center">
                                    <div class="flex flex-col items-center gap-4 text-zinc-300">
                                        <flux:icon icon="bolt" class="size-12 opacity-20" />
                                        <p class="text-sm font-black uppercase tracking-widest">Veille SOS active</p>
                                        <p class="text-[9px] font-bold uppercase text-zinc-400">Aucune urgence à proximité immédiate</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts::app>
