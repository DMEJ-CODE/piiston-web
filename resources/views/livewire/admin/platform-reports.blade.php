<div class="flex flex-col gap-2 pb-8">
    <x-admin.index-header
        title="Signalements Plateforme"
        subtitle="Gestion des retours et incidents utilisateurs"
        searchModel="search"
        searchPlaceholder="Titre ou description..."
    />

    <!-- Status Filters Bar -->
    <div class="mb-6 flex flex-wrap gap-2">
        @foreach(['' => 'Tous', 'pending' => 'En attente', 'in_review' => 'En cours', 'resolved' => 'Résolus', 'dismissed' => 'Rejetés'] as $val => $label)
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
                        <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Signalement</th>
                        <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Auteur</th>
                        <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">Statut</th>
                        <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">Date</th>
                        <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50 dark:divide-white/[0.02]">
                    @forelse($reports as $report)
                        <tr class="group hover:bg-zinc-50 dark:hover:bg-white/[0.01] transition-all">
                            <td class="py-3 px-4">
                                <div class="flex flex-col max-w-md">
                                    <span class="text-[11px] font-black text-zinc-900 dark:text-white uppercase tracking-tight">{{ $report->title }}</span>
                                    <span class="text-[10px] font-bold text-zinc-500 dark:text-zinc-400 mt-0.5 line-clamp-1">{{ Str::limit($report->description, 100) }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-3">
                                <div class="flex items-center gap-2">
                                    <flux:avatar :user="$report->reporter" size="xs" class="rounded-lg" />
                                    <span class="text-[10px] font-black text-zinc-600 dark:text-zinc-400 uppercase">{{ $report->reporter?->name ?? 'Système' }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-3 text-center">
                                @php
                                    $variant = match($report->status) {
                                        'pending' => 'bg-yellow-500/10 text-yellow-600',
                                        'in_review' => 'bg-blue-500/10 text-blue-600',
                                        'resolved' => 'bg-green-500/10 text-green-600',
                                        'dismissed' => 'bg-zinc-500/10 text-zinc-600',
                                        default => 'bg-zinc-500/10 text-zinc-600'
                                    };
                                @endphp
                                <span class="px-2 py-0.5 rounded-lg {{ $variant }} text-[8px] font-black uppercase tracking-widest">
                                    {{ Str::title(str_replace('_', ' ', $report->status)) }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-center">
                                <span class="text-[9px] font-black text-zinc-500 uppercase">{{ $report->created_at->format('d M Y') }}</span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <flux:dropdown>
                                    <flux:button size="xs" variant="ghost" icon="ellipsis-vertical" class="rounded-lg" />
                                    <flux:menu class="min-w-[180px] rounded-xl p-1 shadow-xl">
                                        <flux:menu.item wire:click="updateReportStatus({{ $report->id }}, 'in_review')" icon="eye" class="rounded-lg font-bold text-[10px] uppercase">Examiner</flux:menu.item>
                                        <flux:menu.item wire:click="updateReportStatus({{ $report->id }}, 'resolved')" icon="check" class="rounded-lg font-bold text-[10px] uppercase">Résoudre</flux:menu.item>
                                        <flux:menu.separator />
                                        <flux:menu.item wire:click="updateReportStatus({{ $report->id }}, 'dismissed')" icon="trash" variant="danger" class="rounded-lg font-bold text-[10px] uppercase">Rejeter</flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="hgi-stroke hgi-alert-02 text-3xl text-zinc-200 dark:text-zinc-700 mb-2"></i>
                                    <p class="text-[10px] font-black text-zinc-400 uppercase">Aucun signalement trouvé</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reports->hasPages())
            <div class="p-4 border-t border-zinc-50 dark:border-white/5">
                {{ $reports->links() }}
            </div>
        @endif
    </div>
</div>
