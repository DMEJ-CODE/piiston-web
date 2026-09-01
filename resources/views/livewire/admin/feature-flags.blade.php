<div class="flex flex-col gap-2 pb-8">
    <x-admin.index-header
        title="Feature Flags"
        subtitle="Déploiement progressif et expérimentation"
        actionText="Nouveau Flag"
        actionClick="openModal()"
        searchModel="search"
        searchPlaceholder="Nom du flag..."
    />

    <div class="bg-[var(--surface)] p-2 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-left border-b border-zinc-50 dark:border-white/5 bg-zinc-50/50 dark:bg-white/[0.02]">
                        <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Fonctionnalité</th>
                        <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Déploiement</th>
                        <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">Statut</th>
                        <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Description</th>
                        <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50 dark:divide-white/[0.02]">
                    @forelse($flags as $flag)
                        <tr class="group hover:bg-zinc-50 dark:hover:bg-white/[0.01] transition-all">
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded bg-zinc-100 dark:bg-white/10 text-[9px] font-mono font-bold text-zinc-700 dark:text-zinc-300 uppercase tracking-tighter">{{ $flag->name }}</span>
                            </td>
                            <td class="py-3 px-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex-1 bg-zinc-200 dark:bg-zinc-700 rounded-full h-1.5 min-w-[80px]">
                                        <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $flag->rollout_percentage }}%"></div>
                                    </div>
                                    <span class="text-[9px] font-black text-zinc-500 w-8 text-right">{{ $flag->rollout_percentage }}%</span>
                                </div>
                            </td>
                            <td class="py-3 px-3 text-center">
                                @if($flag->enabled)
                                    <span class="px-2 py-0.5 rounded-lg bg-green-500/10 text-green-600 text-[8px] font-black uppercase tracking-widest">Activé</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-lg bg-yellow-500/10 text-yellow-600 text-[8px] font-black uppercase tracking-widest">Désactivé</span>
                                @endif
                            </td>
                            <td class="py-3 px-3">
                                <span class="text-[10px] font-bold text-zinc-500 dark:text-zinc-400">{{ Str::limit($flag->description, 50) }}</span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <flux:dropdown>
                                    <flux:button size="xs" variant="ghost" icon="ellipsis-vertical" class="rounded-lg" />
                                    <flux:menu class="min-w-[180px] rounded-xl p-1 shadow-xl">
                                        <flux:menu.item wire:click="openModal({{ $flag->id }})" icon="pencil" class="rounded-lg font-bold text-[10px] uppercase">Modifier</flux:menu.item>
                                        <flux:menu.item wire:click="deleteFlag({{ $flag->id }})" icon="trash" variant="danger" class="rounded-lg font-bold text-[10px] uppercase">Supprimer</flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="hgi-stroke hgi-sparks text-3xl text-zinc-200 dark:text-zinc-700 mb-2"></i>
                                    <p class="text-[10px] font-black text-zinc-400 uppercase">Aucun feature flag configuré</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($flags->hasPages())
            <div class="p-4 border-t border-zinc-50 dark:border-white/5">
                {{ $flags->links() }}
            </div>
        @endif
    </div>

    <!-- Modal -->
    <flux:modal wire:model="showModal" class="rounded-3xl">
        <div class="mb-6">
            <h3 class="text-base font-black uppercase text-zinc-900 dark:text-white tracking-tight">{{ $editingFlag ? 'Modifier Flag' : 'Créer Flag' }}</h3>
            <p class="text-[9px] text-zinc-500 font-bold uppercase tracking-widest mt-0.5">Configuration de l'expérimentation</p>
        </div>

        <div class="space-y-6">
            <flux:field>
                <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Nom du Flag (unique)</flux:label>
                <flux:input type="text" wire:model="form.name" placeholder="ex: new_ui_dashboard" class="rounded-xl border-zinc-100 dark:border-white/5 font-mono" />
                <flux:error name="form.name" />
            </flux:field>

            <flux:field>
                <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Description</flux:label>
                <flux:textarea wire:model="form.description" placeholder="Quel est le but de ce flag ?" rows="3" class="rounded-xl border-zinc-100 dark:border-white/5" />
                <flux:error name="form.description" />
            </flux:field>

            <flux:field>
                <div class="flex justify-between items-center mb-2">
                    <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Pourcentage de Déploiement</flux:label>
                    <span class="text-[10px] font-black text-blue-600 bg-blue-50 px-2 py-0.5 rounded-lg">{{ $form['rollout_percentage'] }}%</span>
                </div>
                <div class="flex items-center gap-4">
                    <input type="range" wire:model.live="form.rollout_percentage" min="0" max="100" class="flex-1 h-1.5 bg-zinc-100 rounded-full appearance-none cursor-pointer accent-blue-600" />
                </div>
                <flux:error name="form.rollout_percentage" />
            </flux:field>

            <div class="flex items-center justify-between p-4 rounded-2xl bg-zinc-50 dark:bg-white/[0.02] border border-zinc-100 dark:border-white/5">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-zinc-700 dark:text-zinc-300">État Global</span>
                    <p class="text-[8px] text-zinc-500 font-bold uppercase mt-0.5">Activé ou Désactivé</p>
                </div>
                <flux:switch wire:model="form.enabled" />
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-8">
            <flux:button wire:click="$set('showModal', false)" variant="ghost" class="rounded-xl font-bold uppercase text-[10px]">Annuler</flux:button>
            <flux:button wire:click="saveFlag()" variant="primary" class="rounded-xl font-black uppercase text-[10px] bg-gradient-to-br from-blue-600 to-blue-500 border-none shadow-lg px-6">Enregistrer le Flag</flux:button>
        </div>
    </flux:modal>
</div>
