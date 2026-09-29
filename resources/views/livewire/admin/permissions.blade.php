<div class="flex flex-col gap-2 pb-8">
    <x-admin.index-header
        title="System Permissions"
        subtitle="Granular platform access definition"
        actionText="New Permission"
        actionClick="openModal()"
        searchModel="search"
        searchPlaceholder="Name or description..."
    />

    <div class="bg-[var(--surface)] p-2 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-left border-b border-zinc-50 dark:border-white/5 bg-zinc-50/50 dark:bg-white/[0.02]">
                        <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Technical Name</th>
                        <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Description</th>
                        <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">Status</th>
                        <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50 dark:divide-white/[0.02]">
                    @forelse($permissions as $permission)
                        <tr class="group hover:bg-zinc-50 dark:hover:bg-white/[0.01] transition-all">
                            <td class="py-2.5 px-4">
                                <span class="px-2 py-0.5 rounded bg-zinc-100 dark:bg-white/10 text-[9px] font-mono font-bold text-zinc-700 dark:text-zinc-300 uppercase tracking-tighter">{{ $permission->name }}</span>
                            </td>
                            <td class="py-2.5 px-3">
                                <span class="text-[10px] font-bold text-zinc-600 dark:text-zinc-400">{{ Str::limit($permission->description, 60) }}</span>
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                @if($permission->status)
                                    <span class="px-2 py-0.5 rounded-lg bg-green-500/10 text-green-600 text-[8px] font-black uppercase tracking-widest">Enabled</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-lg bg-red-500/10 text-red-600 text-[8px] font-black uppercase tracking-widest">Disabled</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-4 text-right">
                                <flux:dropdown>
                                    <flux:button size="xs" variant="ghost" icon="ellipsis-vertical" class="rounded-lg" />
                                    <flux:menu class="min-w-[180px] rounded-xl p-1 shadow-xl">
                                        <flux:menu.item wire:click="openModal({{ $permission->id }})" icon="pencil" class="rounded-lg font-bold text-[10px] uppercase">Edit</flux:menu.item>
                                        <flux:menu.item wire:click="deletePermission({{ $permission->id }})" icon="trash" variant="danger" class="rounded-lg font-bold text-[10px] uppercase">Delete</flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="hgi-stroke hgi-shield-exclamation text-3xl text-zinc-200 dark:text-zinc-700 mb-2"></i>
                                    <p class="text-[10px] font-black text-zinc-400 uppercase">No permission found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($permissions->hasPages())
            <div class="p-4 border-t border-zinc-50 dark:border-white/5">
                {{ $permissions->links() }}
            </div>
        @endif
    </div>

    <!-- Modal -->
    <flux:modal wire:model="showModal" class="rounded-3xl">
        <div class="mb-6">
            <h3 class="text-base font-black uppercase text-zinc-900 dark:text-white tracking-tight">{{ $editingPermission ? 'Edit Permission' : 'Create Permission' }}</h3>
            <p class="text-[9px] text-zinc-500 font-bold uppercase tracking-widest mt-0.5">Technical rights configuration</p>
        </div>

        <div class="space-y-6">
            <flux:field>
                <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Technical Name (slug)</flux:label>
                <flux:input type="text" wire:model="form.name" placeholder="ex: manage_inventory" class="rounded-xl border-zinc-100 dark:border-white/5 font-mono" />
                <flux:error name="form.name" />
            </flux:field>

            <flux:field>
                <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Description</flux:label>
                <flux:textarea wire:model="form.description" placeholder="What is this permission for?" rows="3" class="rounded-xl border-zinc-100 dark:border-white/5" />
                <flux:error name="form.description" />
            </flux:field>

            <div class="flex items-center justify-between p-4 rounded-2xl bg-zinc-50 dark:bg-white/[0.02] border border-zinc-100 dark:border-white/5">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-zinc-700 dark:text-zinc-300">Status</span>
                    <p class="text-[8px] text-zinc-500 font-bold uppercase mt-0.5">Enable or Disable</p>
                </div>
                <flux:switch wire:model="form.status" />
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-8">
            <flux:button wire:click="$set('showModal', false)" variant="ghost" class="rounded-xl font-bold uppercase text-[10px]">Cancel</flux:button>
            <flux:button wire:click="savePermission()" variant="primary" class="rounded-xl font-black uppercase text-[10px] bg-gradient-to-br from-blue-600 to-blue-500 border-none shadow-lg px-6">Save</flux:button>
        </div>
    </flux:modal>
</div>
