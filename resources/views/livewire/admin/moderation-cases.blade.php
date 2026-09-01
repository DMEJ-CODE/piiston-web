<div class="flex flex-col gap-2 pb-8">
    <x-admin.index-header
        title="Modération"
        subtitle="Gestion du contenu et comportements plateforme"
        searchModel="search"
        searchPlaceholder="Raison ou description..."
    />

    <!-- Status Filters Bar -->
    <div class="mb-6 flex flex-wrap gap-2">
        @foreach(['' => 'Tous', 'open' => 'Ouverts', 'under_review' => 'En examen', 'resolved' => 'Résolus', 'appealed' => 'Appels'] as $val => $label)
            <button
                wire:click="$set('status', '{{ $val }}')"
                class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ $status === $val ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 shadow-lg' : 'bg-[var(--surface)] text-zinc-500 hover:bg-zinc-50 dark:hover:bg-white/5 border border-zinc-100 dark:border-white/5' }}"
            >
                {{ $label }}
            </button>
        @endforeach
    </div>

    <div class="bg-[var(--surface)] p-2 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-left border-b border-zinc-50 dark:border-white/5 bg-zinc-50/50 dark:bg-white/[0.02]">
                        <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Motif du Cas</th>
                        <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Type de Contenu</th>
                        <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">Statut</th>
                        <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">Date</th>
                        <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50 dark:divide-white/[0.02]">
                    @forelse($cases as $case)
                        <tr class="group hover:bg-zinc-50 dark:hover:bg-white/[0.01] transition-all">
                            <td class="py-3 px-4">
                                <div class="flex flex-col max-w-md">
                                    <span class="text-[11px] font-black text-zinc-900 dark:text-white uppercase tracking-tight">{{ $case->reason }}</span>
                                    <span class="text-[10px] font-bold text-zinc-500 dark:text-zinc-400 mt-0.5 line-clamp-1">{{ Str::limit($case->description, 100) }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-3">
                                <span class="px-2 py-0.5 rounded-lg bg-zinc-100 dark:bg-white/10 text-[8px] font-black text-zinc-500 uppercase tracking-widest">
                                    {{ Str::title(str_replace('_', ' ', $case->entity_type)) }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-center">
                                @php
                                    $variant = match($case->status) {
                                        'open' => 'bg-red-500/10 text-red-600',
                                        'under_review' => 'bg-blue-500/10 text-blue-600',
                                        'resolved' => 'bg-green-500/10 text-green-600',
                                        'appealed' => 'bg-yellow-500/10 text-yellow-600',
                                        default => 'bg-zinc-500/10 text-zinc-600'
                                    };
                                @endphp
                                <span class="px-2 py-0.5 rounded-lg {{ $variant }} text-[8px] font-black uppercase tracking-widest">
                                    {{ Str::title(str_replace('_', ' ', $case->status)) }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-center">
                                <span class="text-[9px] font-black text-zinc-500 uppercase">{{ $case->created_at->format('d M Y') }}</span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <flux:dropdown>
                                    <flux:button size="xs" variant="ghost" icon="ellipsis-vertical" class="rounded-lg" />
                                    <flux:menu class="min-w-[180px] rounded-xl p-1 shadow-xl">
                                        <flux:menu.item wire:click="updateCaseStatus({{ $case->id }}, 'under_review')" icon="eye" class="rounded-lg font-bold text-[10px] uppercase">Examiner</flux:menu.item>
                                        <flux:menu.item wire:click="updateCaseStatus({{ $case->id }}, 'resolved')" icon="check" class="rounded-lg font-bold text-[10px] uppercase">Résoudre</flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="hgi-stroke hgi-flag-01 text-3xl text-zinc-200 dark:text-zinc-700 mb-2"></i>
                                    <p class="text-[10px] font-black text-zinc-400 uppercase">Aucun cas de modération trouvé</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($cases->hasPages())
            <div class="p-4 border-t border-zinc-50 dark:border-white/5">
                {{ $cases->links() }}
            </div>
        @endif
    </div>
</div>
