<div class="flex flex-col lg:flex-row items-start gap-10">
    <!-- Sidebar Navigation — Mobile Menu Style -->
    <div class="w-full lg:w-72 shrink-0">
        <div class="space-y-6">
            <div>
                <h3 class="px-4 text-[10px] font-black uppercase tracking-[2px] text-[var(--active-2)] mb-4">{{ __('Mon Compte') }}</h3>
                <div class="bg-white dark:bg-slate-900 rounded-[28px] border border-slate-100 dark:border-white/5 shadow-premium overflow-hidden">
                    <nav class="flex flex-col divide-y divide-slate-50 dark:divide-white/[0.02]">
                        <a href="{{ route('profile.edit') }}"
                           @class([
                               'flex items-center gap-4 px-6 py-4 transition-all no-underline group',
                               'bg-slate-50/50 dark:bg-white/5' => request()->routeIs('profile.edit')
                           ])>
                            <div @class([
                                'size-10 rounded-2xl flex items-center justify-center shadow-sm transition-all group-hover:scale-110',
                                'bg-[var(--active-2)]/10 text-[var(--active-2)]' => request()->routeIs('profile.edit'),
                                'bg-slate-100/50 dark:bg-white/5 text-slate-400 group-hover:text-slate-900' => !request()->routeIs('profile.edit')
                            ])>
                                <i class="hgi-stroke hgi-user-circle text-lg"></i>
                            </div>
                            <div class="flex flex-col min-w-0">
                                <span @class(['text-[13px] uppercase tracking-tight', 'font-black text-slate-900 dark:text-white' => request()->routeIs('profile.edit'), 'font-bold text-slate-500' => !request()->routeIs('profile.edit')])>{{ __('Profil') }}</span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter truncate">{{ __('Nom, Email & Avatar') }}</span>
                            </div>
                            <i class="hgi-stroke hgi-arrow-right-01 ml-auto text-slate-300 text-sm"></i>
                        </a>

                        <a href="{{ route('security.edit') }}"
                           @class([
                               'flex items-center gap-4 px-6 py-4 transition-all no-underline group',
                               'bg-slate-50/50 dark:bg-white/5' => request()->routeIs('security.edit')
                           ])>
                            <div @class([
                                'size-10 rounded-2xl flex items-center justify-center shadow-sm transition-all group-hover:scale-110',
                                'bg-orange-500/10 text-orange-600' => request()->routeIs('security.edit'),
                                'bg-slate-100/50 dark:bg-white/5 text-slate-400 group-hover:text-slate-900' => !request()->routeIs('security.edit')
                            ])>
                                <i class="hgi-stroke hgi-shield-01 text-lg"></i>
                            </div>
                            <div class="flex flex-col min-w-0">
                                <span @class(['text-[13px] uppercase tracking-tight', 'font-black text-slate-900 dark:text-white' => request()->routeIs('security.edit'), 'font-bold text-slate-500' => !request()->routeIs('security.edit')])>{{ __('Sécurité') }}</span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter truncate">{{ __('Mot de passe & 2FA') }}</span>
                            </div>
                            <i class="hgi-stroke hgi-arrow-right-01 ml-auto text-slate-300 text-sm"></i>
                        </a>

                        <a href="{{ route('appearance.edit') }}"
                           @class([
                               'flex items-center gap-4 px-6 py-4 transition-all no-underline group',
                               'bg-slate-50/50 dark:bg-white/5' => request()->routeIs('appearance.edit')
                           ])>
                            <div @class([
                                'size-10 rounded-2xl flex items-center justify-center shadow-sm transition-all group-hover:scale-110',
                                'bg-purple-500/10 text-purple-600' => request()->routeIs('appearance.edit'),
                                'bg-slate-100/50 dark:bg-white/5 text-slate-400 group-hover:text-slate-900' => !request()->routeIs('appearance.edit')
                            ])>
                                <i class="hgi-stroke hgi-paint-brush-01 text-lg"></i>
                            </div>
                            <div class="flex flex-col min-w-0">
                                <span @class(['text-[13px] uppercase tracking-tight', 'font-black text-slate-900 dark:text-white' => request()->routeIs('appearance.edit'), 'font-bold text-slate-500' => !request()->routeIs('appearance.edit')])>{{ __('Apparence') }}</span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter truncate">{{ __('Thème & Couleurs') }}</span>
                            </div>
                            <i class="hgi-stroke hgi-arrow-right-01 ml-auto text-slate-300 text-sm"></i>
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Promotion / Help Card -->
            <div class="p-6 rounded-[28px] bg-slate-900 text-white relative overflow-hidden shadow-xl">
                <i class="hgi-stroke hgi-help-circle text-6xl absolute -right-4 -bottom-4 text-white/5 rotate-12"></i>
                <h4 class="text-xs font-black uppercase tracking-widest mb-2">{{ __('Centre d\'aide') }}</h4>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight leading-relaxed mb-4">Besoin d'assistance pour configurer votre compte ?</p>
                <flux:button variant="ghost" class="!rounded-xl !bg-white/10 !text-white !border-none !text-[9px] !font-black !uppercase w-full">{{ __('Consulter la FAQ') }}</flux:button>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="flex-1 w-full min-w-0">
        <div class="card-premium">
            <div class="mb-10 px-1">
                <div class="flex items-center gap-3 mb-2">
                    <div class="size-2 rounded-full bg-[var(--active-2)]"></div>
                    <h3 class="text-sm font-black text-zinc-900 dark:text-white uppercase tracking-widest">{{ $heading ?? '' }}</h3>
                </div>
                @if(isset($subheading))
                    <p class="text-[10px] text-zinc-400 font-bold uppercase tracking-widest ml-5">{{ $subheading }}</p>
                @endif
            </div>

            <div class="w-full">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
