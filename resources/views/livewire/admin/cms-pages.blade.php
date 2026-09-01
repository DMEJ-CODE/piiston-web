<div class="flex flex-col gap-2 pb-8">
    <x-admin.index-header
        title="Pages CMS"
        subtitle="Gestion du contenu statique de la plateforme"
        actionText="Créer une Page"
        actionClick="openModal()"
        searchModel="search"
        searchPlaceholder="Titre ou slug..."
    />

    <div class="bg-[var(--surface)] p-2 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-left border-b border-zinc-50 dark:border-white/5 bg-zinc-50/50 dark:bg-white/[0.02]">
                        <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Titre de la Page</th>
                        <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Slug</th>
                        <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">Statut</th>
                        <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">Date</th>
                        <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50 dark:divide-white/[0.02]">
                    @forelse($pages as $page)
                        <tr class="group hover:bg-zinc-50 dark:hover:bg-white/[0.01] transition-all">
                            <td class="py-2.5 px-4">
                                <span class="text-[11px] font-black text-zinc-900 dark:text-white uppercase tracking-tight">{{ $page->title }}</span>
                            </td>
                            <td class="py-2.5 px-3">
                                <span class="px-2 py-0.5 rounded-lg bg-zinc-100 dark:bg-white/10 text-[9px] font-mono font-bold text-zinc-500 uppercase">{{ $page->slug }}</span>
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                @if($page->status)
                                    <span class="px-2 py-0.5 rounded-lg bg-green-500/10 text-green-600 text-[8px] font-black uppercase tracking-widest">Publié</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-lg bg-yellow-500/10 text-yellow-600 text-[8px] font-black uppercase tracking-widest">Brouillon</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                <span class="text-[9px] font-black text-zinc-500 uppercase">{{ $page->created_at->format('d M Y') }}</span>
                            </td>
                            <td class="py-2.5 px-4 text-right">
                                <flux:dropdown>
                                    <flux:button size="xs" variant="ghost" icon="ellipsis-vertical" class="rounded-lg" />
                                    <flux:menu class="min-w-[180px] rounded-xl p-1 shadow-xl">
                                        <flux:menu.item wire:click="openModal({{ $page->id }})" icon="pencil" class="rounded-lg font-bold text-[10px] uppercase">Modifier</flux:menu.item>
                                        <flux:menu.item wire:click="deletePage({{ $page->id }})" icon="trash" variant="danger" class="rounded-lg font-bold text-[10px] uppercase">Supprimer</flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="hgi-stroke hgi-note-01 text-3xl text-zinc-200 dark:text-zinc-700 mb-2"></i>
                                    <p class="text-[10px] font-black text-zinc-400 uppercase">Aucune page CMS trouvée</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pages->hasPages())
            <div class="p-4 border-t border-zinc-50 dark:border-white/5">
                {{ $pages->links() }}
            </div>
        @endif
    </div>

    <!-- Modal -->
    <flux:modal wire:model="showModal" class="rounded-3xl" variant="large">
        <div class="mb-6">
            <h3 class="text-base font-black uppercase text-zinc-900 dark:text-white tracking-tight">{{ $editingPage ? 'Modifier Page' : 'Créer Page' }}</h3>
            <p class="text-[9px] text-zinc-500 font-bold uppercase tracking-widest mt-0.5">Édition du contenu statique</p>
        </div>

        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <flux:field>
                    <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Titre de la Page</flux:label>
                    <flux:input type="text" wire:model="form.title" placeholder="ex: Conditions Générales" class="rounded-xl border-zinc-100 dark:border-white/5" />
                    <flux:error name="form.title" />
                </flux:field>

                <flux:field>
                    <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Slug URL</flux:label>
                    <flux:input type="text" wire:model="form.slug" placeholder="ex: conditions-generales" class="rounded-xl border-zinc-100 dark:border-white/5" />
                    <flux:error name="form.slug" />
                </flux:field>
            </div>

            <flux:field>
                <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Contenu HTML / Markdown</flux:label>
                <flux:textarea wire:model="form.content" placeholder="Saisissez le contenu de la page..." rows="12" class="rounded-xl border-zinc-100 dark:border-white/5 font-mono text-xs" />
                <flux:error name="form.content" />
            </flux:field>

            <div class="flex items-center justify-between p-4 rounded-2xl bg-zinc-50 dark:bg-white/[0.02] border border-zinc-100 dark:border-white/5">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-zinc-700 dark:text-zinc-300">Statut de Publication</span>
                    <p class="text-[8px] text-zinc-500 font-bold uppercase mt-0.5">Publié ou Brouillon</p>
                </div>
                <flux:switch wire:model="form.status" />
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-8">
            <flux:button wire:click="$set('showModal', false)" variant="ghost" class="rounded-xl font-bold uppercase text-[10px]">Annuler</flux:button>
            <flux:button wire:click="savePage()" variant="primary" class="rounded-xl font-black uppercase text-[10px] bg-gradient-to-br from-blue-600 to-blue-500 border-none shadow-lg px-6">Enregistrer la Page</flux:button>
        </div>
    </flux:modal>
</div>
