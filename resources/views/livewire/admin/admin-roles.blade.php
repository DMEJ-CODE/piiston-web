<div class="flex flex-col gap-2 pb-8">
    <x-admin.index-header
        title="Administrator Roles"
        subtitle="Access definition and privilege levels"
        actionText="New Role"
        actionClick="openModal()"
        searchModel="search"
        searchPlaceholder="Role name..."
    />

    <div class="bg-[var(--surface)] p-2 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-left border-b border-zinc-50 dark:border-white/5 bg-zinc-50/50 dark:bg-white/[0.02]">
                        <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Role</th>
                        <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Description</th>
                        <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">Permissions</th>
                        <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">Level</th>
                        <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">Status</th>
                        <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50 dark:divide-white/[0.02]">
                    @forelse($roles as $role)
                        <tr class="group hover:bg-zinc-50 dark:hover:bg-white/[0.01] transition-all">
                            <td class="py-2.5 px-4">
                                <span class="text-[11px] font-black text-zinc-900 dark:text-white uppercase tracking-tight">{{ $role->name }}</span>
                            </td>
                            <td class="py-2.5 px-3">
                                <span class="text-[10px] font-bold text-zinc-600 dark:text-zinc-400">{{ Str::limit($role->description, 50) }}</span>
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                <span class="px-2 py-0.5 rounded bg-blue-500/10 text-blue-600 text-[8px] font-black uppercase tracking-widest">
                                    {{ $role->permissions->count() }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                <span class="text-[10px] font-black text-zinc-900 dark:text-white">{{ $role->level }}</span>
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                @if($role->status)
                                    <span class="px-2 py-0.5 rounded-lg bg-green-500/10 text-green-600 text-[8px] font-black uppercase tracking-widest">Active</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-lg bg-red-500/10 text-red-600 text-[8px] font-black uppercase tracking-widest">Inactive</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-4 text-right">
                                <flux:dropdown>
                                    <flux:button size="xs" variant="ghost" icon="ellipsis-vertical" class="rounded-lg" />
                                    <flux:menu class="min-w-[180px] rounded-xl p-1 shadow-xl">
                                        <flux:menu.item wire:click="openModal({{ $role->id }})" icon="pencil" class="rounded-lg font-bold text-[10px] uppercase">Edit</flux:menu.item>
                                        <flux:menu.item wire:click="deleteRole({{ $role->id }})" icon="trash" variant="danger" class="rounded-lg font-bold text-[10px] uppercase">Delete</flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="hgi-stroke hgi-briefcase text-3xl text-zinc-200 dark:text-zinc-700 mb-2"></i>
                                    <p class="text-[10px] font-black text-zinc-400 uppercase">No role found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($roles->hasPages())
            <div class="p-4 border-t border-zinc-50 dark:border-white/5">
                {{ $roles->links() }}
            </div>
        @endif
    </div>

    <!-- Modal -->
    <flux:modal wire:model="showModal" class="rounded-3xl">
        <div class="mb-6">
            <h3 class="text-base font-black uppercase text-zinc-900 dark:text-white tracking-tight">{{ $editingRole ? 'Edit Role' : 'Create Role' }}</h3>
            <p class="text-[9px] text-zinc-500 font-bold uppercase tracking-widest mt-0.5">Access rights definition</p>
        </div>

        <div class="space-y-6">
            <flux:field>
                <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Role Name</flux:label>
                <flux:input type="text" wire:model="form.name" placeholder="ex: Moderator" class="rounded-xl border-zinc-100 dark:border-white/5" />
                <flux:error name="form.name" />
            </flux:field>

            <flux:field>
                <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Description</flux:label>
                <flux:textarea wire:model="form.description" placeholder="Describe the responsibilities of this role..." rows="3" class="rounded-xl border-zinc-100 dark:border-white/5" />
                <flux:error name="form.description" />
            </flux:field>

            <flux:field>
                <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Authority Level</flux:label>
                <flux:input type="number" wire:model="form.level" min="1" class="rounded-xl border-zinc-100 dark:border-white/5" />
                <flux:error name="form.level" />
            </flux:field>

            <flux:field>
                <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Permissions</flux:label>
                <div class="space-y-2 max-h-64 overflow-y-auto p-3 rounded-2xl bg-zinc-50/50 dark:bg-white/[0.02] border border-zinc-100 dark:border-white/5">
                    @foreach($permissions as $permission)
                        <label class="flex items-start gap-3 p-2 rounded-xl hover:bg-zinc-100 dark:hover:bg-white/10 transition-colors cursor-pointer">
                            <input
                                type="checkbox"
                                value="{{ $permission->id }}"
                                wire:model="form.permissions"
                                class="h-4 w-4 rounded border-zinc-300 text-blue-600 focus:ring-blue-500/20 mt-1"
                            />
                            <div class="flex flex-col">
                                <span class="text-[10px] font-bold text-zinc-700 dark:text-zinc-300 uppercase">{{ $permission->name }}</span>
                                <span class="text-[8px] font-medium text-zinc-500 dark:text-zinc-400">{{ $permission->description }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
                <flux:error name="form.permissions" />
            </flux:field>

            <div class="flex items-center justify-between p-4 rounded-2xl bg-zinc-50 dark:bg-white/[0.02] border border-zinc-100 dark:border-white/5">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-zinc-700 dark:text-zinc-300">Role Status</span>
                    <p class="text-[8px] text-zinc-500 font-bold uppercase mt-0.5">Active or Inactive</p>
                </div>
                <flux:switch wire:model="form.status" />
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-8">
            <flux:button wire:click="$set('showModal', false)" variant="ghost" class="rounded-xl font-bold uppercase text-[10px]">Cancel</flux:button>
            <flux:button wire:click="saveRole()" variant="primary" class="rounded-xl font-black uppercase text-[10px] bg-gradient-to-br from-blue-600 to-blue-500 border-none shadow-lg px-6">Save Role</flux:button>
        </div>
    </flux:modal>
</div>
