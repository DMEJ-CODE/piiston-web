<flux:dropdown position="bottom" align="end">
    <button type="button" class="flex items-center gap-2 p-1 rounded-lg hover:bg-zinc-100 dark:hover:bg-white/5 transition-all group">
        <flux:avatar :user="auth()->user()" size="xs" class="rounded-lg shadow-sm" />
        <div class="hidden xl:flex flex-col items-start text-left">
            <span class="text-[9px] font-bold text-zinc-900 dark:text-white uppercase leading-tight">{{ auth()->user()->name }}</span>
            <span class="text-[7px] font-medium text-zinc-400 uppercase tracking-tighter">{{ auth()->user()->roles->first()->name ?? 'Membre' }}</span>
        </div>
        <i class="hgi-stroke hgi-chevron-down text-[8px] text-zinc-400 group-hover:text-zinc-600 ml-0.5"></i>
    </button>

    <flux:menu class="min-w-[180px] rounded-xl shadow-xl">
        <div class="flex items-center gap-3 px-3 py-2 border-b border-zinc-50 dark:border-white/5 mb-1">
            <flux:avatar :user="auth()->user()" size="xs" class="rounded shadow-sm" />
            <div class="flex flex-col min-w-0">
                <span class="text-[10px] font-bold text-zinc-900 dark:text-white uppercase truncate">{{ auth()->user()->name }}</span>
                <span class="text-[8px] font-medium text-zinc-500 truncate">{{ auth()->user()->email }}</span>
            </div>
        </div>

        <flux:menu.item :href="route('profile.edit')" icon="user-circle" class="rounded-lg font-bold text-[10px] uppercase">
            {{ __('Mon Profil') }}
        </flux:menu.item>

        <flux:menu.separator />

        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <flux:menu.item
                as="button"
                type="submit"
                icon="arrow-right-start-on-rectangle"
                variant="danger"
                class="w-full rounded-lg font-bold text-[10px] uppercase"
            >
                {{ __('Déconnexion') }}
            </flux:menu.item>
        </form>
    </flux:menu>
</flux:dropdown>
