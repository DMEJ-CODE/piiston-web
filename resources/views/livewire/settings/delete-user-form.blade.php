<section class="mt-8">
    <div class="p-6 rounded-2xl bg-red-500/5 border border-red-500/10 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex flex-col gap-1">
            <h4 class="text-xs font-black text-red-600 uppercase tracking-widest">{{ __('settings.Danger zone') }}</h4>
            <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-tight">{{ __('settings.Delete your account and all of its resources') }}</p>
        </div>

        <flux:modal.trigger name="confirm-user-deletion">
            <flux:button variant="danger" class="rounded-xl font-black uppercase text-[10px] px-6">
                {{ __('settings.Delete account') }}
            </flux:button>
        </flux:modal.trigger>
    </div>

    <flux:modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable class="rounded-3xl">
        <form method="POST" wire:submit="deleteUser" class="space-y-6">
            <div class="mb-6">
                <h3 class="text-base font-black uppercase text-zinc-900 dark:text-white tracking-tight">{{ __('settings.Delete confirmation') }}</h3>
                <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest mt-1">
                    {{ __('settings.This action is irreversible. All of your data will be erased.') }}
                </p>
            </div>

            <flux:field>
                <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-500">{{ __('settings.Enter your password to confirm') }}</flux:label>
                <flux:input wire:model="password" type="password" viewable class="rounded-xl border-zinc-100 dark:border-white/5" />
                <flux:error name="password" />
            </flux:field>

            <div class="flex justify-end gap-3 mt-8">
                <flux:modal.close>
                    <flux:button variant="ghost" class="rounded-xl font-bold uppercase text-[10px]">{{ __('settings.Cancel') }}</flux:button>
                </flux:modal.close>

                <flux:button variant="danger" type="submit" class="rounded-xl font-black uppercase text-[10px] px-6">
                    {{ __('settings.Confirm deletion') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>
</section>
