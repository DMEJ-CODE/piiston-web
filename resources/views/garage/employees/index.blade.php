<x-layouts::app :title="__('garage.Employés')">
    <x-garage.index-header
        title="Gestion de l'Équipe"
        subtitle="Collaborateurs et accès - {{ $branch->name }}"
        actionText="Inviter un Collaborateur"
        actionUrl="{{ route('garage.employees.create') }}"
        actionIcon="user-plus"
        searchPlaceholder="Nom ou Email..."
    />

    @php
        $stats = [
            'total' => collect($employees)->count(),
            'active' => collect($employees)->where('status', 'ACTIVE')->count(),
            'invites' => collect($invitations)->count(),
        ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
        <x-dashboard.stat-card
            title="Total Employés"
            :value="$stats['total']"
            icon="user-group"
            color="var(--active-2)"
        />
        <x-dashboard.stat-card
            title="Actifs"
            :value="$stats['active']"
            icon="tick-double-02"
            color="#10B981"
        />
        <x-dashboard.stat-card
            title="Invitations"
            :value="$stats['invites']"
            icon="mail-01"
            color="#F59E0B"
        />
    </div>

    <div class="mt-4 flex flex-col gap-6">
        <!-- Pending Invitations -->
        @if($invitations->count() > 0)
        <div class="space-y-3">
            <h3 class="text-[9px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-[3px] px-2">Invitations en attente</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($invitations as $invite)
                <div class="group card-premium !p-4 border-blue-500/20 shadow-sm flex flex-col gap-3">
                    <div class="flex items-center gap-3">
                        <div class="piiston-icon-avatar text-blue-500" style="--active-rgb: 59, 130, 246; --active-2-rgb: 37, 99, 235; color: #3b82f6;">
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
        <div class="card-premium !p-0">
            <div class="overflow-x-auto">
                <table class="piiston-table w-full text-left">
                    <thead>
                        <tr>
                            <th>Membre</th>
                            <th class="text-center">Rôle</th>
                            <th class="text-center">Statut</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees ?? [] as $emp)
                            <tr class="cursor-pointer">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="piiston-icon-avatar text-xs font-black" style="--active-rgb: 168, 85, 247; --active-2-rgb: 99, 102, 241; color: #a855f7;">
                                            {{ substr($emp->user->name ?? '?', 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[11px] font-black text-zinc-900 dark:text-white uppercase tracking-tight">{{ $emp->user->name ?? 'Membre' }}</span>
                                            <span class="text-[8px] font-bold text-zinc-400 uppercase">{{ $emp->user->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="px-2 py-0.5 rounded-lg bg-zinc-100 dark:bg-white/5 text-[8px] font-black text-zinc-500 uppercase tracking-widest border border-zinc-200/50">
                                        {{ $emp->position ?? 'STAFF' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="px-2 py-0.5 rounded text-[7px] font-black uppercase
                                        @if($emp->status === 'ACTIVE') bg-green-500/10 text-green-600 @else bg-zinc-100 text-zinc-600 @endif">
                                        ● {{ $emp->status ?? 'ACTIVE' }}
                                    </span>
                                </td>
                                <td class="text-right">
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
                            <tr class="empty-state">
                                <td colspan="4" class="py-24 text-center">
                                    <div class="flex flex-col items-center justify-center gap-4">
                                        <div class="size-16 rounded-full bg-slate-50 dark:bg-white/5 flex items-center justify-center border border-slate-100 dark:border-white/10">
                                            <flux:icon icon="user-group" class="size-8 text-slate-300 dark:text-slate-600" />
                                        </div>
                                        <div class="flex flex-col gap-1">
                                            <p class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest">Aucun Employé</p>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase">Commencez par inviter vos collaborateurs.</p>
                                        </div>
                                        <flux:button href="{{ route('garage.employees.create') }}" size="sm" class="btn-premium-primary mt-2">Inviter un Membre</flux:button>
                                    </div>
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
