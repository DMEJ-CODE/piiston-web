<flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
    <flux:sidebar.header class="mb-6">
        <div class="flex items-center justify-between w-full px-2">
            <x-app-logo :sidebar="true" href="{{ route('admin.dashboard') }}" />
            <div class="flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-blue-500/10 border border-blue-500/20">
                <div class="size-1.5 rounded-full bg-blue-500 animate-pulse"></div>
                <span class="text-[9px] font-black text-blue-600 uppercase tracking-widest">Admin</span>
            </div>
        </div>
        <flux:sidebar.collapse class="lg:hidden" />
    </flux:sidebar.header>

    <nav class="px-3 space-y-6">
        <!-- Section Application -->
        <div class="space-y-2">
            <div class="px-4 text-[11px] font-black uppercase tracking-[1.5px] text-zinc-400 dark:text-zinc-500">{{ __('admin.Navigation') }}</div>
            <div class="space-y-1">
                <flux:sidebar.item href="{{ route('dashboard') }}" class="sidebar-nav-item !text-[13px] !py-3">
                    <i class="hgi-stroke hgi-arrow-left-02 text-xl mr-3 text-zinc-400 group-hover:text-blue-500"></i>
                    <span class="font-bold">{{ __('admin.Back to Application') }}</span>
                </flux:sidebar.item>

                @if(auth()->user()?->administrator?->hasPermission('view_dashboard'))
                <flux:sidebar.item href="{{ route('admin.dashboard') }}" :current="request()->routeIs('admin.dashboard')" class="sidebar-nav-item !text-[13px] !py-3">
                    <i class="hgi-stroke hgi-layout-grid text-xl mr-3"></i>
                    <span class="font-bold">{{ __('admin.Dashboard') }}</span>
                </flux:sidebar.item>
                @endif
            </div>
        </div>

        <!-- Section Gestion Utilisateurs -->
        <div class="space-y-2">
            <div class="px-4 text-[11px] font-black uppercase tracking-[1.5px] text-zinc-400 dark:text-zinc-500">{{ __('admin.Accounts & Rights') }}</div>
            <div class="space-y-1">
                @if(auth()->user()?->administrator?->hasPermission('manage_users'))
                <flux:sidebar.item href="{{ route('admin.users') }}" :current="request()->routeIs('admin.users')" class="sidebar-nav-item !text-[13px] !py-3">
                    <i class="hgi-stroke hgi-user-group text-xl mr-3"></i>
                    <span class="font-bold">{{ __('admin.Users') }}</span>
                </flux:sidebar.item>
                @endif

                @if(auth()->user()?->administrator?->hasPermission('manage_administrators'))
                <flux:sidebar.item href="{{ route('admin.administrators') }}" :current="request()->routeIs('admin.administrators')" class="sidebar-nav-item !text-[13px] !py-3">
                    <i class="hgi-stroke hgi-security-user text-xl mr-3"></i>
                    <span class="font-bold">{{ __('admin.Staff Admin') }}</span>
                </flux:sidebar.item>
                @endif

                @if(auth()->user()?->administrator?->hasPermission('manage_roles'))
                <flux:sidebar.item href="{{ route('admin.roles') }}" :current="request()->routeIs('admin.roles')" class="sidebar-nav-item !text-[13px] !py-3">
                    <i class="hgi-stroke hgi-shield-user text-xl mr-3"></i>
                    <span class="font-bold">{{ __('admin.Roles & Permissions') }}</span>
                </flux:sidebar.item>
                @endif
            </div>
        </div>

        <!-- Section Plateforme -->
        <div class="space-y-2">
            <div class="px-4 text-[11px] font-black uppercase tracking-[1.5px] text-zinc-400 dark:text-zinc-500">{{ __('admin.Ecosystem') }}</div>
            <div class="space-y-1">
                <flux:sidebar.item href="#" class="sidebar-nav-item !text-[13px] !py-3 group">
                    <i class="hgi-stroke hgi-building-03 text-xl mr-3 text-zinc-400 group-hover:text-blue-500"></i>
                    <span class="font-bold">{{ __('admin.Garage Network') }}</span>
                </flux:sidebar.item>
                <flux:sidebar.item href="#" class="sidebar-nav-item !text-[13px] !py-3 group">
                    <i class="hgi-stroke hgi-package-01 text-xl mr-3 text-zinc-400 group-hover:text-blue-500"></i>
                    <span class="font-bold">{{ __('admin.Marketplace') }}</span>
                </flux:sidebar.item>
            </div>
        </div>

        <!-- Section Monitoring -->
        <div class="space-y-2">
            <div class="px-4 text-[11px] font-black uppercase tracking-[1.5px] text-zinc-400 dark:text-zinc-500">{{ __('admin.Monitoring') }}</div>
            <div class="space-y-1">
                @if(auth()->user()?->administrator?->hasPermission('view_audit_logs'))
                <flux:sidebar.item href="{{ route('admin.audit-logs') }}" :current="request()->routeIs('admin.audit-logs')" class="sidebar-nav-item !text-[13px] !py-3">
                    <i class="hgi-stroke hgi-file-script text-xl mr-3"></i>
                    <span class="font-bold">{{ __('admin.Audit Logs') }}</span>
                </flux:sidebar.item>
                @endif

                @if(auth()->user()?->administrator?->hasPermission('view_reports'))
                <flux:sidebar.item href="{{ route('admin.reports') }}" :current="request()->routeIs('admin.reports')" class="sidebar-nav-item !text-[13px] !py-3">
                    <i class="hgi-stroke hgi-alert-02 text-xl mr-3"></i>
                    <span class="font-bold">{{ __('admin.Reports') }}</span>
                </flux:sidebar.item>
                @endif
            </div>
        </div>

        <!-- Section Système -->
        <div class="space-y-2">
            <div class="px-4 text-[11px] font-black uppercase tracking-[1.5px] text-zinc-400 dark:text-zinc-500">{{ __('admin.Settings') }}</div>
            <div class="space-y-1">
                @if(auth()->user()?->administrator?->hasPermission('manage_settings'))
                <flux:sidebar.item href="{{ route('admin.settings') }}" :current="request()->routeIs('admin.settings')" class="sidebar-nav-item !text-[13px] !py-3">
                    <i class="hgi-stroke hgi-settings-02 text-xl mr-3"></i>
                    <span class="font-bold">{{ __('admin.Configuration') }}</span>
                </flux:sidebar.item>
                @endif
                <flux:sidebar.item href="{{ route('admin.feature-flags') }}" :current="request()->routeIs('admin.feature-flags')" class="sidebar-nav-item !text-[13px] !py-3">
                    <i class="hgi-stroke hgi-sparks text-xl mr-3"></i>
                    <span class="font-bold">{{ __('admin.Features') }}</span>
                </flux:sidebar.item>
            </div>
        </div>
    </nav>

    <flux:spacer />

    <!-- Section Bas de Sidebar - Profil & Langue -->
    <div class="px-4 py-4 mt-auto border-t border-zinc-200 dark:border-white/5 bg-zinc-100/50 dark:bg-white/[0.02]">
        <div class="mb-4">
            <livewire:language-switcher />
        </div>

        <div class="flex items-center gap-3 p-2 rounded-2xl bg-white dark:bg-zinc-800 shadow-sm border border-zinc-100 dark:border-white/5">
            <flux:avatar :user="auth()->user()" size="sm" class="rounded-xl shadow-inner" />
            <div class="flex flex-col min-w-0 overflow-hidden">
                <span class="text-[11px] font-black text-zinc-900 dark:text-white uppercase truncate">{{ auth()->user()->name }}</span>
                <span class="text-[9px] font-bold text-zinc-400 uppercase tracking-tighter truncate">{{ auth()->user()->administrator->position ?? 'Administrator' }}</span>
            </div>
            <flux:dropdown>
                <flux:button variant="ghost" size="xs" icon="chevron-up-down" class="ml-auto !p-1" />
                <flux:menu class="min-w-[180px]">
                    <flux:menu.item icon="user" href="{{ route('profile.edit') }}">{{ __('nav.My Profile') }}</flux:menu.item>
                    <flux:menu.separator />
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <flux:menu.item icon="arrow-right-start-on-rectangle" type="submit" variant="danger">{{ __('nav.Log out') }}</flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </div>
    </div>
</flux:sidebar>
