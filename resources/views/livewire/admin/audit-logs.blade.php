<div class="flex flex-col gap-2 pb-8">
    <x-admin.index-header
        title="Audit Logs"
        subtitle="Complete history of administrative actions"
        searchModel="search"
        searchPlaceholder="Action or Entity..."
    />

    <div class="bg-[var(--surface)] p-2 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-left border-b border-zinc-50 dark:border-white/5 bg-zinc-50/50 dark:bg-white/[0.02]">
                        <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Date & Time</th>
                        <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">Action</th>
                        <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Entity</th>
                        <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Administrator</th>
                        <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50 dark:divide-white/[0.02]">
                    @forelse($logs as $log)
                        <tr class="group hover:bg-zinc-50 dark:hover:bg-white/[0.01] transition-all">
                            <td class="py-2.5 px-4">
                                <span class="text-[10px] font-bold text-zinc-900 dark:text-white">{{ $log->created_at->format('d M Y H:i') }}</span>
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                <span class="px-2 py-0.5 rounded bg-blue-500/10 text-blue-600 text-[8px] font-black uppercase tracking-widest">{{ $log->action }}</span>
                            </td>
                            <td class="py-2.5 px-3">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-zinc-700 dark:text-zinc-300 uppercase">{{ $log->entity_type }}</span>
                                    <span class="text-[8px] font-mono font-bold text-zinc-400">ID: {{ $log->entity_id }}</span>
                                </div>
                            </td>
                            <td class="py-2.5 px-3">
                                @if($log->administrator?->user)
                                    <div class="flex items-center gap-2">
                                        <flux:avatar :user="$log->administrator->user" size="xs" class="rounded-lg" />
                                        <span class="text-[10px] font-black text-zinc-600 dark:text-zinc-400 uppercase">{{ $log->administrator->user->name }}</span>
                                    </div>
                                @else
                                    <span class="text-[9px] font-black text-zinc-400 uppercase">System</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-4 text-right">
                                @if($log->old_values || $log->new_values)
                                    <flux:button size="xs" variant="ghost" icon="eye" wire:click="viewDetails({{ $log->id }})" class="rounded-lg" />
                                @else
                                    <span class="text-[10px] text-zinc-300">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="hgi-stroke hgi-file-script text-3xl text-zinc-200 dark:text-zinc-700 mb-2"></i>
                                    <p class="text-[10px] font-black text-zinc-400 uppercase">No audit log found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-4 border-t border-zinc-50 dark:border-white/5">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

    @if($showDetailsModal)
        <flux:modal wire:model="showDetailsModal" class="rounded-3xl" variant="large">
            <div class="mb-6">
                <h3 class="text-base font-black uppercase text-zinc-900 dark:text-white tracking-tight">Log Details</h3>
                <p class="text-[9px] text-zinc-500 font-bold uppercase tracking-widest mt-0.5">System modifications inspection</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="p-4 rounded-2xl bg-zinc-50 dark:bg-white/[0.02] border border-zinc-100 dark:border-white/5">
                        <span class="text-[9px] font-black text-zinc-400 uppercase tracking-widest block mb-1">Action Performed</span>
                        <p class="text-xs font-black text-[var(--active-2)] uppercase">{{ $selectedLog->action }}</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-zinc-50 dark:bg-white/[0.02] border border-zinc-100 dark:border-white/5">
                        <span class="text-[9px] font-black text-zinc-400 uppercase tracking-widest block mb-1">Entité & ID</span>
                        <p class="text-xs font-bold text-zinc-900 dark:text-white uppercase">{{ $selectedLog->entity_type }} #{{ $selectedLog->entity_id }}</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-zinc-50 dark:bg-white/[0.02] border border-zinc-100 dark:border-white/5">
                        <span class="text-[9px] font-black text-zinc-400 uppercase tracking-widest block mb-1">Administrateur</span>
                        <p class="text-xs font-bold text-zinc-900 dark:text-white uppercase">{{ $selectedLog->administrator?->user?->name ?? 'Système' }}</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="p-4 rounded-2xl bg-zinc-900 dark:bg-zinc-800 text-white shadow-xl border border-white/5 h-full">
                        <div class="flex items-center gap-2 mb-4">
                            <i class="hgi-stroke hgi-database text-blue-400"></i>
                            <span class="text-[10px] font-black uppercase tracking-widest text-white/50">Différence de données</span>
                        </div>

                        <div class="grid grid-cols-1 gap-4 overflow-y-auto max-h-[300px] pr-2 custom-scrollbar">
                            <div>
                                <span class="text-[8px] font-black text-red-400 uppercase tracking-[2px] mb-2 block">Old Values</span>
                                <pre class="text-[9px] font-mono text-white/70 bg-white/5 p-3 rounded-xl border border-white/5 overflow-x-auto">{{ json_encode($oldValues, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                            <div>
                                <span class="text-[8px] font-black text-green-400 uppercase tracking-[2px] mb-2 block">New Values</span>
                                <pre class="text-[9px] font-mono text-white/70 bg-white/5 p-3 rounded-xl border border-white/5 overflow-x-auto">{{ json_encode($newValues, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-8">
                <flux:button wire:click="closeDetailsModal" variant="ghost" class="rounded-xl font-bold uppercase text-[10px]">Close</flux:button>
            </div>
        </flux:modal>
    @endif
</div>
