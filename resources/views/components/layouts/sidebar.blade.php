<flux:sidebar sticky collapsible="mobile">
    <flux:sidebar.header class="mb-4 px-4">
        <div class="flex items-center justify-between w-full">
            @php
                $isAdminRoute = request()->routeIs('admin.*');
            @endphp
            <x-app-logo :sidebar="true" href="{{ $isAdminRoute ? route('admin.dashboard') : route('dashboard') }}" />

            @if(auth()->user()?->administrator()->exists())
                <div class="flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 shadow-sm">
                    <div class="size-1.5 rounded-full bg-blue-500 animate-pulse"></div>
                    <span class="text-[9px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest">Live</span>
                </div>
            @endif
        </div>
        <flux:sidebar.collapse class="lg:hidden" />
    </flux:sidebar.header>

    <nav class="space-y-5 pb-6">
        @php
            $user = auth()->user();
            $isAdmin = $user?->administrator()->exists() || $user?->hasRole('ADMIN');
            $isGarageOwner = $user?->hasRole('GARAGE_OWNER');
            $isMechanic = $user?->hasRole('MECHANIC');
            $isVehicleOwner = $user?->hasRole('VEHICLE_OWNER');
            $isSparePartSeller = $user?->hasRole('SPARE_PART_SELLER');
            $isGarageUser = $isGarageOwner || $isMechanic;
            $subscriptionFeatures = $user?->subscription?->status === 'active' ? ($user->subscription->plan->features ?? []) : [];
            $hasMarketplaceFeature = in_array('marketplace', $subscriptionFeatures, true);

            $accessibleBranches = collect();
            $company = null;
            $showBranchSelector = false;

            if ($user) {
                if ($user->hasRole('ADMIN')) {
                    $accessibleBranches = \App\Models\Garages\GarageBranch::all();
                    $showBranchSelector = true;
                } else {
                    $company = \App\Models\Garages\GarageCompany::where('owner_id', $user->id)->first();
                    if ($company) {
                        $accessibleBranches = $company->branches;
                        $showBranchSelector = (bool) $company->has_annexes;
                    } else {
                        $employee = \App\Models\Garages\GarageEmployee::where('user_id', $user->id)->first();
                        if ($employee && $employee->branch) {
                            $accessibleBranches = collect([$employee->branch]);
                            $showBranchSelector = (bool) ($employee->branch->company->has_annexes ?? false);
                        }
                    }
                }
            }
        @endphp

        <!-- Branch Selector -->
        @if($showBranchSelector && $accessibleBranches->count() > 0 && !$isAdminRoute)
            <div class="px-4 mb-1">
                <flux:dropdown class="w-full">
                    <flux:button variant="ghost" class="w-full justify-between gap-2 px-3 py-2 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/5 rounded-xl transition-all">
                        <div class="flex items-center gap-2 overflow-hidden">
                            <i class="hgi-stroke hgi-location-01 size-3.5 text-[var(--active-2)]"></i>
                            <span class="text-[10px] font-black uppercase truncate text-slate-700 dark:text-zinc-300 tracking-tight">{{ $activeBranch->name ?? 'General' }}</span>
                        </div>
                        <i class="hgi-stroke hgi-arrow-up-down size-2.5 text-slate-400"></i>
                    </flux:button>
                    <flux:menu class="min-w-[200px] rounded-xl shadow-xl border-slate-100 dark:border-white/5">
                        @if($company?->has_annexes)
                        <flux:menu.item onclick="event.preventDefault(); document.getElementById('exit-branch').submit();" class="rounded-lg font-bold text-[10px] uppercase">
                            {{ __('nav.General View') }}
                            <form id="exit-branch" action="{{ route('garage.switch-branch', 0) }}" method="POST" class="hidden">@csrf</form>
                        </flux:menu.item>
                        <flux:menu.separator />
                        @endif
                        <flux:menu.group heading="My Branches" class="text-[9px] uppercase tracking-widest text-slate-400 px-3">
                            @foreach($accessibleBranches as $b)
                                <flux:menu.item
                                    class="{{ ($activeBranch->id ?? null) === $b->id ? 'bg-[var(--active)]/10 text-[var(--active-2)]' : '' }} rounded-lg font-bold text-[10px] uppercase"
                                    onclick="event.preventDefault(); document.getElementById('switch-branch-{{ $b->id }}').submit();"
                                >
                                    {{ $b->name }}
                                    <form id="switch-branch-{{ $b->id }}" action="{{ route('garage.switch-branch', $b->id) }}" method="POST" class="hidden">@csrf</form>
                                </flux:menu.item>
                            @endforeach
                        </flux:menu.group>
                    </flux:menu>
                </flux:dropdown>
            </div>
        @endif

        <div class="space-y-0.5">
            <div class="px-6 label-premium mb-2">{{ __('nav.Accueil') }}</div>
            @if($isAdminRoute)
                <flux:sidebar.item :href="route('dashboard')" class="sidebar-nav-item">
                    <i class="hgi-stroke hgi-arrow-left-02"></i>
                    <span>{{ __('nav.Retour App') }}</span>
                </flux:sidebar.item>
                <flux:sidebar.item :href="route('admin.dashboard')" :current="request()->routeIs('admin.dashboard')" class="sidebar-nav-item">
                    <i class="hgi-stroke hgi-layout-grid-01"></i>
                    <span>{{ __('nav.Dashboard') }}</span>
                </flux:sidebar.item>
            @else
                <flux:sidebar.item :href="route('dashboard')" :current="request()->routeIs('dashboard')" class="sidebar-nav-item">
                    <i class="hgi-stroke hgi-layout-grid-01"></i>
                    <span>{{ __('nav.Dashboard') }}</span>
                </flux:sidebar.item>
                @if($isAdmin)
                <flux:sidebar.item :href="route('admin.dashboard')" class="sidebar-nav-item">
                    <i class="hgi-stroke hgi-shield-01"></i>
                    <span>{{ __('nav.Mode Admin') }}</span>
                </flux:sidebar.item>
                @endif
            @endif
        </div>

        <div class="space-y-0.5">
            <div class="px-6 label-premium mb-2">{{ __('nav.Communication') }}</div>
            <flux:sidebar.item :href="$isAdminRoute && Route::has('admin.messages.index') ? route('admin.messages.index') : (Route::has('garage.messages.index') ? route('garage.messages.index') : route('pricing.index'))" :current="request()->routeIs('*.messages.index')" class="sidebar-nav-item">
                <i class="hgi-stroke hgi-chat-bubble-left-right"></i>
                <span>{{ __('nav.Messages') }}</span>
            </flux:sidebar.item>
        </div>

        @if($isAdminRoute)
            <div class="space-y-0.5">
                <div class="px-6 label-premium mb-2">{{ __('nav.Comptes') }}</div>
                <flux:sidebar.item :href="route('admin.users')" :current="request()->routeIs('admin.users')" class="sidebar-nav-item">
                    <i class="hgi-stroke hgi-user-group"></i>
                    <span>{{ __('nav.Utilisateurs') }}</span>
                </flux:sidebar.item>
                <flux:sidebar.item :href="route('admin.roles')" :current="request()->routeIs('admin.roles')" class="sidebar-nav-item">
                    <i class="hgi-stroke hgi-shield-keyhole"></i>
                    <span>{{ __('nav.Rôles') }}</span>
                </flux:sidebar.item>
            </div>
            <div class="space-y-0.5">
                <div class="px-6 label-premium mb-2">{{ __('nav.Contrôle') }}</div>
                <flux:sidebar.item :href="route('admin.reports')" :current="request()->routeIs('admin.reports')" class="sidebar-nav-item">
                    <i class="hgi-stroke hgi-alert-02"></i>
                    <span>{{ __('nav.Signalements') }}</span>
                </flux:sidebar.item>
                <flux:sidebar.item :href="route('admin.audit-logs')" :current="request()->routeIs('admin.audit-logs')" class="sidebar-nav-item">
                    <i class="hgi-stroke hgi-file-script"></i>
                    <span>{{ __('nav.Audit Logs') }}</span>
                </flux:sidebar.item>
                <flux:sidebar.item :href="route('admin.garage-subscriptions')" :current="request()->routeIs('admin.garage-subscriptions')" class="sidebar-nav-item">
                    <i class="hgi-stroke hgi-credit-card"></i>
                    <span>{{ __('nav.Abonnements') }}</span>
                </flux:sidebar.item>
            </div>
        @endif

        @if($isGarageUser && !$isAdminRoute)
        <div class="space-y-0.5">
            <div class="px-6 label-premium mb-2">{{ __('nav.Atelier') }}</div>
            <flux:sidebar.item :href="route('garage.repairs.index')" :current="request()->routeIs('garage.repairs.*')" class="sidebar-nav-item">
                <i class="hgi-stroke hgi-wrench-01"></i>
                <span>{{ __('Repairs') }}</span>
            </flux:sidebar.item>
            <flux:sidebar.item :href="route('garage.services.index')" :current="request()->routeIs('garage.services.*')" class="sidebar-nav-item">
                <i class="hgi-stroke hgi-tag-01"></i>
                <span>{{ __('Services & Pricing') }}</span>
            </flux:sidebar.item>
            <flux:sidebar.item :href="route('garage.appointments.index')" :current="request()->routeIs('garage.appointments.*')" class="sidebar-nav-item">
                <i class="hgi-stroke hgi-calendar-03"></i>
                <span>{{ __('Appointments') }}</span>
            </flux:sidebar.item>
            <flux:sidebar.item :href="route('garage.settings.index')" :current="request()->routeIs('garage.settings.*')" class="sidebar-nav-item">
                <i class="hgi-stroke hgi-settings-01"></i>
                <span>{{ __('Garage Settings') }}</span>
            </flux:sidebar.item>
        </div>
        @endif

        @if(!$isAdminRoute && ($isVehicleOwner || $isSparePartSeller) && $hasMarketplaceFeature)
        <div class="space-y-0.5">
            <div class="px-6 label-premium mb-2">{{ __('nav.E-Commerce') }}</div>
            <flux:sidebar.item href="#" class="sidebar-nav-item">
                <i class="hgi-stroke hgi-shopping-bag-01"></i>
                <span>{{ __('nav.Marketplace') }}</span>
            </flux:sidebar.item>
        </div>
        @endif
    </nav>

    <flux:spacer />

    <div class="px-4 py-4 mt-auto border-t border-slate-200/80 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/40">
        <div class="flex items-center gap-3 p-2 rounded-xl bg-white dark:bg-[#0F172A] shadow-sm border border-slate-200/60 dark:border-slate-800/80">
            <flux:avatar :user="auth()->user()" size="xs" class="rounded-lg shadow-sm" />
            <div class="flex flex-col min-w-0">
                <span class="text-[10px] font-black text-slate-900 dark:text-white uppercase truncate">{{ auth()->user()->name }}</span>
                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-tighter truncate">
                    {{ $isAdmin ? 'Admin' : ($user->roles->first()->name ?? 'Member') }}
                </span>
            </div>
            <flux:dropdown>
                <flux:button variant="ghost" size="xs" icon="chevron-up-down" class="ml-auto !p-1" />
                <flux:menu class="min-w-[180px] rounded-xl shadow-2xl">
                    <flux:menu.item icon="user" href="{{ route('profile.edit') }}" class="rounded-lg font-bold text-[10px] uppercase">{{ __('nav.My Profile') }}</flux:menu.item>
                    <flux:menu.item icon="credit-card" href="{{ route('subscriptions.index') }}" class="rounded-lg font-bold text-[10px] uppercase">{{ __('nav.Subscription') }}</flux:menu.item>
                    <flux:menu.separator />
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <flux:menu.item icon="arrow-right-start-on-rectangle" type="submit" variant="danger" class="rounded-lg font-bold text-[10px] uppercase">Logout</flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </div>
    </div>
</flux:sidebar>
