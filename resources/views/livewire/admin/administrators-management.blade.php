<div class="flex flex-col gap-2 pb-8">
    <x-admin.index-header
        title="Gestion des Administrateurs"
        subtitle="Administrateurs et modérateurs système"
        actionText="Ajouter un Administrateur"
        actionClick="openModal()"
        searchModel="search"
        searchPlaceholder="Nom ou Email..."
    />

    <div class="bg-[var(--surface)] p-2 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-left border-b border-zinc-50 dark:border-white/5 bg-zinc-50/50 dark:bg-white/[0.02]">
                        <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Admin</th>
                        <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Poste</th>
                        <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">Rôles</th>
                        <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">Statut</th>
                        <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50 dark:divide-white/[0.02]">
                    @forelse($administrators as $admin)
                        <tr class="group hover:bg-zinc-50 dark:hover:bg-white/[0.01] transition-all">
                            <td class="py-2.5 px-4">
                                <div class="flex items-center gap-3">
                                    <flux:avatar :user="$admin->user" size="xs" class="rounded-lg shadow-sm" />
                                    <div class="flex flex-col">
                                        <span class="text-[11px] font-black text-zinc-900 dark:text-white uppercase tracking-tight">{{ $admin->user->name }}</span>
                                        <span class="text-[8px] font-bold text-zinc-400 uppercase tracking-tighter">{{ $admin->user->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2.5 px-3">
                                <span class="text-[10px] font-bold text-zinc-600 dark:text-zinc-400">{{ $admin->position }}</span>
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                <div class="flex gap-1 flex-wrap justify-center">
                                    @foreach($admin->roles as $role)
                                        <span class="px-1.5 py-0.5 rounded bg-zinc-100 dark:bg-white/5 text-[7px] font-black text-zinc-500 uppercase">{{ $role->name }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                @if($admin->status)
                                    <span class="px-2 py-0.5 rounded-lg bg-green-500/10 text-green-600 text-[8px] font-black uppercase tracking-widest">Actif</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-lg bg-red-500/10 text-red-600 text-[8px] font-black uppercase tracking-widest">Inactif</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-4 text-right">
                                <flux:dropdown>
                                    <flux:button size="xs" variant="ghost" icon="ellipsis-vertical" class="rounded-lg" />
                                    <flux:menu class="min-w-[180px] rounded-xl p-1 shadow-xl">
                                        <flux:menu.item wire:click="openModal({{ $admin->id }})" icon="pencil" class="rounded-lg font-bold text-[10px] uppercase">Modifier</flux:menu.item>
                                        <flux:menu.item wire:click="deleteAdmin({{ $admin->id }})" icon="trash" variant="danger" class="rounded-lg font-bold text-[10px] uppercase">Supprimer</flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="hgi-stroke hgi-security-user text-3xl text-zinc-200 dark:text-zinc-700 mb-2"></i>
                                    <p class="text-[10px] font-black text-zinc-400 uppercase">Aucun administrateur trouvé</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($administrators->hasPages())
            <div class="p-4 border-t border-zinc-50 dark:border-white/5">
                {{ $administrators->links() }}
            </div>
        @endif
    </div>

    <!-- Modal -->
    <flux:modal wire:model="showModal" class="rounded-3xl">
        <div class="mb-6">
            <h3 class="text-base font-black uppercase text-zinc-900 dark:text-white tracking-tight">{{ $editingAdmin ? 'Modifier Administrateur' : 'Ajouter Administrateur' }}</h3>
            <p class="text-[9px] text-zinc-500 font-bold uppercase tracking-widest mt-0.5">Configuration des accès système</p>
        </div>

        <div class="space-y-6">
            @if(!$editingAdmin)
            <flux:field>
                <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Sélectionner Utilisateur</flux:label>
                <flux:select wire:model="form.user_id" class="rounded-xl border-zinc-100 dark:border-white/5">
                    <option value="">Choisir un utilisateur...</option>
                    @foreach($availableUsers as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </flux:select>
                <flux:error name="form.user_id" />
            </flux:field>
            @endif

            <flux:field>
                <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Poste / Fonction</flux:label>
                <flux:input type="text" wire:model="form.position" placeholder="ex: Administrateur Système" class="rounded-xl border-zinc-100 dark:border-white/5" />
                <flux:error name="form.position" />
            </flux:field>

            <flux:field>
                <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Rôles</flux:label>
                <div class="grid grid-cols-2 gap-3 mt-2">
                    @foreach($roles as $role)
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-zinc-100 dark:border-white/5 bg-zinc-50/50 dark:bg-white/[0.02] cursor-pointer hover:bg-zinc-100 transition-colors">
                            <input
                                type="checkbox"
                                value="{{ $role->id }}"
                                wire:model="form.roles"
                                class="h-4 w-4 rounded border-zinc-300 text-blue-600 focus:ring-blue-500/20"
                            />
                            <span class="text-[10px] font-bold text-zinc-700 dark:text-zinc-300 uppercase">{{ $role->name }}</span>
                        </label>
                    @endforeach
                </div>
                <flux:error name="form.roles" />
            </flux:field>

            <div class="flex items-center justify-between p-4 rounded-2xl bg-zinc-50 dark:bg-white/[0.02] border border-zinc-100 dark:border-white/5">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-zinc-700 dark:text-zinc-300">Statut du compte</span>
                    <p class="text-[8px] text-zinc-500 font-bold uppercase mt-0.5">Actif ou Inactif</p>
                </div>
                <flux:switch wire:model="form.status" />
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-8">
            <flux:button wire:click="$set('showModal', false)" variant="ghost" class="rounded-xl font-bold uppercase text-[10px]">Annuler</flux:button>
            <flux:button wire:click="saveAdmin()" variant="primary" class="rounded-xl font-black uppercase text-[10px] bg-gradient-to-br from-blue-600 to-blue-500 border-none shadow-lg px-6">Enregistrer</flux:button>
        </div>
    </flux:modal>
</div>
