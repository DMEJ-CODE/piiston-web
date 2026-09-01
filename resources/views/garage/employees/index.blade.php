<x-layouts::app :title="__('Employés')">
    <x-garage.index-header
        title="Gestion de l'Équipe"
        subtitle="Collaborateurs et accès - {{ $branch->name }}"
        actionText="Inviter un Collaborateur"
        actionUrl="{{ route('garage.employees.create') }}"
        actionIcon="user-plus"
        searchPlaceholder="Nom ou Email..."
    />

    <div class="mt-2 flex flex-col gap-6">
        <!-- Pending Invitations -->
        @if($invitations->count() > 0)
        <div class="space-y-3">
            <h3 class="text-[9px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-[3px] px-2">Invitations</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($invitations as $invite)
                <div class="group bg-[var(--surface)] p-4 rounded-[16px] border border-blue-100 dark:border-white/5 shadow-sm flex flex-col gap-3 relative overflow-hidden">
                    <div class="flex items-center gap-3">
                        <div class="size-8 rounded-lg bg-blue-500/10 text-blue-600 flex items-center justify-center">
                            <flux:icon icon="envelope" class="size-4" />
                        </div>
                        <div class="flex flex-col overflow-hidden">
                            <span class="text-[11px] font-black text-zinc-900 dark:text-white uppercase truncate">{{ $invite->email }}</span>
                            <span class="text-[8px] font-bold text-blue-500 uppercase">{{ $invite->role }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Employees List -->
        <div class="bg-[var(--surface)] p-2 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-left border-b border-zinc-50 dark:border-white/5 bg-zinc-50/50 dark:bg-white/[0.02]">
                            <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Membre</th>
                            <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">Rôle</th>
                            <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">Statut</th>
                            <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50 dark:divide-white/[0.02]">
                        @forelse($employees ?? [] as $emp)
                            <tr class="group hover:bg-zinc-50 dark:hover:bg-white/[0.01] transition-all cursor-pointer">
                                <td class="py-2.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="size-8 rounded-xl bg-gradient-to-br from-purple-500/10 to-indigo-500/10 text-purple-600 flex items-center justify-center font-black text-xs border border-purple-500/10">
                                            {{ substr($emp->user->name ?? '?', 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[11px] font-black text-zinc-900 dark:text-white uppercase tracking-tight">{{ $emp->user->name ?? 'Membre' }}</span>
                                            <span class="text-[8px] font-bold text-zinc-400 uppercase">{{ $emp->user->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3 text-center">
                                    <span class="px-2 py-0.5 rounded-lg bg-zinc-100 dark:bg-white/5 text-[8px] font-black text-zinc-500 uppercase tracking-widest border border-zinc-200/50">
                                        {{ $emp->position ?? 'STAFF' }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-3 text-center">
                                    <span class="px-2 py-0.5 rounded text-[7px] font-black uppercase
                                        @if($emp->status === 'ACTIVE') bg-green-500/10 text-green-600 @else bg-zinc-100 text-zinc-600 @endif">
                                        ● {{ $emp->status ?? 'ACTIVE' }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-4 text-right">
                                    <flux:dropdown>
                                        <flux:button size="xs" variant="ghost" icon="ellipsis-vertical" class="rounded-lg" />
                                        <flux:menu class="min-w-[150px] rounded-xl p-1 shadow-xl">
                                            <flux:menu.item icon="pencil" href="{{ route('garage.employees.edit', $emp->id) }}" class="rounded-lg font-bold text-[10px] uppercase">Gérer</flux:menu.item>
                                            <flux:menu.separator />
                                            <flux:menu.item icon="trash" variant="danger" class="rounded-lg font-bold text-[10px] uppercase">Révoquer</flux:menu.item>
                                        </flux:menu>
                                    </flux:dropdown>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center text-zinc-400">
                                    <p class="text-[10px] font-black uppercase">Aucun membre</p>
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
