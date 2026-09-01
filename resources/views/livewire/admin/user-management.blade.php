<div class="flex flex-col gap-6 pb-12">
    <x-admin.index-header
        title="Gestion Utilisateurs"
        subtitle="Contrôle des comptes plateforme"
        searchModel="search"
        searchPlaceholder="Rechercher par nom ou email..."
    />

    <div class="overflow-x-auto">
        <table class="table-premium">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Email / Contact</th>
                    <th class="text-center">Date Inscription</th>
                    <th class="text-center">Statut</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="group">
                        <td>
                            <div class="flex items-center gap-4">
                                <flux:avatar :user="$user" size="sm" class="rounded-xl shadow-sm border-2 border-white dark:border-zinc-800" />
                                <div class="flex flex-col">
                                    <span class="text-[13px] font-black text-zinc-900 dark:text-white uppercase tracking-tight">{{ $user->name }}</span>
                                    <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mt-0.5">ID #{{ $user->id }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="flex flex-col">
                                <span class="text-[11px] font-bold text-zinc-600 dark:text-zinc-400">{{ $user->email }}</span>
                                <span class="text-[10px] text-zinc-400 uppercase tracking-tighter">{{ $user->phone ?? 'Aucun téléphone' }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="text-[11px] font-black text-zinc-500 uppercase">{{ $user->created_at->format('d M Y') }}</span>
                        </td>
                        <td class="text-center">
                            <div class="flex justify-center">
                                @if($user->status === 'active')
                                    <span class="px-3 py-1 rounded-full bg-green-500/10 text-green-600 text-[10px] font-black uppercase tracking-widest border border-green-500/10">Actif</span>
                                @else
                                    <span class="px-3 py-1 rounded-full bg-red-500/10 text-red-600 text-[10px] font-black uppercase tracking-widest border border-red-500/10">Inactif</span>
                                @endif
                            </div>
                        </td>
                        <td class="text-right">
                            <flux:dropdown>
                                <flux:button size="xs" variant="ghost" icon="ellipsis-vertical" class="rounded-xl hover:bg-zinc-100" />
                                <flux:menu class="min-w-[200px] rounded-2xl shadow-2xl">
                                    <flux:menu.item
                                        wire:click="changeUserStatus({{ $user->id }}, '{{ $user->status === 'active' ? 'inactive' : 'active' }}')"
                                        icon="{{ $user->status === 'active' ? 'lock-closed' : 'lock-open' }}"
                                        class="rounded-xl font-bold text-[11px] uppercase"
                                    >
                                        {{ $user->status === 'active' ? 'Désactiver' : 'Réactiver' }}
                                    </flux:menu.item>
                                    <flux:menu.item icon="pencil" class="rounded-xl font-bold text-[11px] uppercase">Détails Compte</flux:menu.item>
                                    <flux:menu.separator />
                                    <flux:menu.item icon="trash" variant="danger" class="rounded-xl font-bold text-[11px] uppercase">Supprimer</flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-20 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="hgi-stroke hgi-user-group text-5xl text-zinc-200 dark:text-zinc-800 mb-4"></i>
                                <p class="text-xs font-black text-zinc-400 uppercase tracking-widest">Aucun utilisateur trouvé</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="mt-8 p-6 card-premium !p-4 bg-white/50 backdrop-blur-sm">
            {{ $users->links() }}
        </div>
    @endif
</div>
