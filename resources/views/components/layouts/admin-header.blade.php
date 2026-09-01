<flux:header sticky class="!max-w-none !w-full border-b border-zinc-200 bg-[var(--surface)]/80 dark:border-zinc-700/50 hidden lg:flex shadow-sm px-6 backdrop-blur-xl">
    <flux:sidebar.toggle class="lg:hidden mr-2" icon="bars-2" inset="left" />

    <div class="flex items-center gap-3">
        <div class="size-8 rounded-xl bg-blue-600 flex items-center justify-center shadow-lg shadow-blue-600/20">
            <i class="hgi-stroke hgi-shield-01 text-white text-lg"></i>
        </div>
        <div>
            <h1 class="text-[11px] font-black uppercase tracking-tight text-zinc-900 dark:text-white">Centrale de Contrôle</h1>
            <p class="text-[9px] font-bold text-blue-600 uppercase tracking-widest leading-none">Administration Système</p>
        </div>
    </div>

    <flux:spacer />

    <div class="flex items-center gap-6">
        <!-- Command Search -->
        <button type="button" class="hidden xl:flex items-center gap-3 px-4 py-2 bg-zinc-100/50 dark:bg-white/5 border border-zinc-200/50 dark:border-white/5 rounded-2xl group transition-all hover:border-blue-500/50 hover:bg-white dark:hover:bg-zinc-800">
            <i class="hgi-stroke hgi-search-01 text-lg text-zinc-400 group-hover:text-blue-500 transition-colors"></i>
            <span class="text-[11px] font-bold text-zinc-400 group-hover:text-zinc-600">Recherche globale...</span>
            <div class="flex items-center gap-1 ml-6">
                <span class="px-1.5 py-0.5 rounded bg-zinc-200 dark:bg-white/10 text-[9px] font-black text-zinc-500">⌘</span>
                <span class="px-1.5 py-0.5 rounded bg-zinc-200 dark:bg-white/10 text-[9px] font-black text-zinc-500">K</span>
            </div>
        </button>

        <div class="flex items-center gap-2">
            <!-- Notifications -->
            <button type="button" class="relative text-zinc-500 hover:text-zinc-900 dark:hover:text-white group p-2.5 rounded-xl hover:bg-zinc-100 dark:hover:bg-white/5 transition-all">
                <i class="hgi-stroke hgi-notification-02 text-xl group-hover:text-blue-500 transition-colors"></i>
                @if(auth()->user()?->appNotifications?->where('is_read', false)?->count() > 0)
                    <span class="absolute top-2 right-2 flex h-4 w-4 items-center justify-center rounded-full bg-red-600 text-[8px] font-black text-white border-2 border-[var(--surface)]">
                        {{ auth()->user()?->appNotifications?->where('is_read', false)?->count() }}
                    </span>
                @endif
            </button>

            <livewire:language-switcher />
        </div>

        <div class="h-8 w-px bg-zinc-200 dark:bg-zinc-800"></div>

        <!-- User Profile -->
        <x-desktop-user-menu :name="auth()->user()->name" />
    </div>
</flux:header>
