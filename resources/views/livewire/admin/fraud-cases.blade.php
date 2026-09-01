<div class="flex flex-col gap-2 pb-8">
    <x-admin.index-header
        title="Détection de Fraude"
        subtitle="Surveillance des activités suspectes"
        searchModel="search"
        searchPlaceholder="Utilisateur ou type..."
    />

    <!-- Status Filters Bar -->
    <div class="mb-6 flex flex-wrap gap-2">
        @foreach(['' => 'Tous', 'reported' => 'Signalés', 'investigating' => 'Enquête', 'confirmed' => 'Confirmés', 'dismissed' => 'Classés'] as $val => $label)
            <button
                wire:click="$set('status', '{{ $val }}')"
                class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ $status === $val ? 'bg-red-600 text-white shadow-lg' : 'bg-[var(--surface)] text-zinc-500 hover:bg-zinc-50 dark:hover:bg-white/5 border border-zinc-100 dark:border-white/5' }}"
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
                        <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Utilisateur Signalé</th>
                        <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Type de Fraude</th>
                        <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">Statut</th>
                        <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">Date</th>
                        <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50 dark:divide-white/[0.02]">
                    @forelse($cases as $case)
                        <tr class="group hover:bg-zinc-50 dark:hover:bg-white/[0.01] transition-all">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <flux:avatar :user="$case->reported_user" size="xs" class="rounded-lg" />
                                    <div class="flex flex-col">
                                        <span class="text-[11px] font-black text-zinc-900 dark:text-white uppercase tracking-tight">{{ $case->reported_user->name ?? 'Compte Supprimé' }}</span>
                                        <span class="text-[8px] font-bold text-zinc-400 uppercase tracking-tighter">{{ $case->reported_user->email ?? '---' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-3">
                                <span class="px-2 py-0.5 rounded bg-zinc-100 dark:bg-white/10 text-[8px] font-black text-zinc-500 uppercase tracking-widest">
                                    {{ Str::title(str_replace('_', ' ', $case->fraud_type)) }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-center">
                                @php
                                    $variant = match($case->status) {
                                        'reported' => 'bg-yellow-500/10 text-yellow-600',
                                        'investigating' => 'bg-blue-500/10 text-blue-600',
                                        'confirmed' => 'bg-red-500/10 text-red-600 font-bold',
                                        'dismissed' => 'bg-zinc-500/10 text-zinc-600',
                                        default => 'bg-zinc-500/10 text-zinc-600'
                                    };
                                @endphp
                                <span class="px-2 py-0.5 rounded-lg {{ $variant }} text-[8px] font-black uppercase tracking-widest">
                                    {{ Str::title($case->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-center">
                                <span class="text-[9px] font-black text-zinc-500 uppercase">{{ $case->created_at->format('d M Y') }}</span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <flux:dropdown>
                                    <flux:button size="xs" variant="ghost" icon="ellipsis-vertical" class="rounded-lg" />
                                    <flux:menu class="min-w-[180px] rounded-xl p-1 shadow-xl">
                                        <flux:menu.item wire:click="updateCaseStatus({{ $case->id }}, 'investigating')" icon="eye" class="rounded-lg font-bold text-[10px] uppercase">Enquêter</flux:menu.item>
                                        <flux:menu.item wire:click="updateCaseStatus({{ $case->id }}, 'confirmed')" icon="check-circle" variant="danger" class="rounded-lg font-bold text-[10px] uppercase">Confirmer Fraude</flux:menu.item>
                                        <flux:menu.separator />
                                        <flux:menu.item wire:click="updateCaseStatus({{ $case->id }}, 'dismissed')" icon="x-mark" class="rounded-lg font-bold text-[10px] uppercase">Classer</flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="hgi-stroke hgi-security-check text-3xl text-zinc-200 dark:text-zinc-700 mb-2"></i>
                                    <p class="text-[10px] font-black text-zinc-400 uppercase">Aucun cas de fraude détecté</p>
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
