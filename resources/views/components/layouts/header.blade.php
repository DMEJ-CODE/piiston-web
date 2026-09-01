<flux:header sticky class="!max-w-none !w-full border-b border-slate-200 bg-white/80 dark:bg-slate-900/80 dark:border-slate-800 hidden lg:flex shadow-sm px-8 backdrop-blur-2xl">
    <flux:sidebar.toggle class="lg:hidden mr-4" icon="bars-2" inset="left" />

    <!-- Dynamic Branding Area -->
    <div class="flex items-center gap-4 group">
        <div class="size-9 rounded-2xl bg-gradient-to-br from-[var(--active-2)] to-[var(--active)] flex items-center justify-center shadow-lg shadow-[var(--active-2-rgb)]/20 group-hover:scale-105 transition-transform duration-300">
            <i class="hgi-stroke hgi-command-line text-white text-lg"></i>
        </div>
        <div class="flex flex-col">
            <span class="text-[11px] font-black uppercase tracking-wider text-slate-900 dark:text-white leading-tight">
                {{ request()->routeIs('admin.*') ? 'Piiston Control' : 'Console Piiston' }}
            </span>
            <span class="text-[8px] font-bold text-[var(--active-2)] dark:text-slate-400 uppercase tracking-[1.5px] mt-0.5">
                {{ request()->routeIs('admin.*') ? 'Supervision Système' : 'Gestion des Flux' }}
            </span>
        </div>
    </div>

    <flux:spacer />

    <!-- Unified Search Bar -->
    <div class="flex-1 max-w-sm mx-10">
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="hgi-stroke hgi-search-01 text-sm text-slate-400 group-focus-within:text-[var(--active-2)] transition-colors"></i>
            </div>
            <input type="text" placeholder="Rechercher partout..."
                class="w-full pl-11 pr-4 py-2 bg-slate-100/50 dark:bg-slate-900/50 border border-slate-200/50 dark:border-slate-800 rounded-2xl text-[11px] font-bold text-slate-700 dark:text-slate-200 placeholder:text-slate-400 focus:ring-4 focus:ring-[var(--active-2-rgb)]/5 focus:border-[var(--active-2)] transition-all outline-none">
        </div>
    </div>

    <flux:spacer />

    <div class="flex items-center gap-6">
        <div class="flex items-center gap-2">
            <!-- Unified Notifications -->
            <button type="button" class="relative text-slate-500 hover:text-slate-900 dark:hover:text-white group p-2.5 rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-900 transition-all duration-300">
                <i class="hgi-stroke hgi-notification-02 text-xl group-hover:scale-110 transition-transform"></i>
                @if(auth()->user()?->appNotifications?->where('is_read', false)?->count() > 0)
                    <span class="absolute top-2.5 right-2.5 flex h-4 w-4 items-center justify-center rounded-full bg-red-600 text-[8px] font-black text-white border-2 border-white dark:border-slate-900 shadow-lg">
                        {{ auth()->user()?->appNotifications?->where('is_read', false)?->count() }}
                    </span>
                @endif
            </button>

            <livewire:language-switcher />
        </div>

        <div class="h-8 w-px bg-slate-200 dark:bg-slate-800 mx-1"></div>

        <!-- Global User Profile -->
        <x-desktop-user-menu />
    </div>
</flux:header>
