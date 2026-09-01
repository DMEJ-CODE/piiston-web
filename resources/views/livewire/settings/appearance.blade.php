<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Paramètres d\'apparence') }}</flux:heading>

    <x-settings.layout :heading="__('Thème de l\'interface')" :subheading="__('Choisissez l\'ambiance visuelle de votre espace de travail')">

        <div class="mb-16">
            <h4 class="text-[11px] font-black uppercase tracking-[2px] text-slate-400 mb-8">{{ __('Mode d\'affichage') }}</h4>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6" x-data>
                <!-- Light Mode Card -->
                <button type="button" @click="$flux.appearance = 'light'"
                    @class([
                        'p-8 rounded-[32px] border-2 transition-all flex flex-col items-center gap-4 group',
                        'bg-[var(--active)]/5 border-[var(--active)] shadow-lg shadow-[var(--active)]/10' => true, /* Logic will be handled by JS/Alpine */
                        'bg-white dark:bg-white/5 border-slate-100 dark:border-white/5 hover:border-slate-200' => false
                    ])
                    :class="$flux.appearance === 'light' ? 'bg-[var(--active)]/5 border-[var(--active)] shadow-lg' : 'bg-white dark:bg-white/5 border-slate-100 dark:border-white/5 hover:border-slate-200'">
                    <div class="size-16 rounded-3xl bg-white dark:bg-slate-800 flex items-center justify-center shadow-sm border border-slate-100 dark:border-white/5 transition-all group-hover:scale-110">
                        <i class="hgi-stroke hgi-sun-01 text-3xl" :class="$flux.appearance === 'light' ? 'text-[var(--active)]' : 'text-slate-400'"></i>
                    </div>
                    <span class="text-xs font-black uppercase tracking-widest" :class="$flux.appearance === 'light' ? 'text-[var(--active-2)]' : 'text-slate-500'">{{ __('Clair') }}</span>
                </button>

                <!-- Dark Mode Card -->
                <button type="button" @click="$flux.appearance = 'dark'"
                    @class([
                        'p-8 rounded-[32px] border-2 transition-all flex flex-col items-center gap-4 group'
                    ])
                    :class="$flux.appearance === 'dark' ? 'bg-[var(--active)]/5 border-[var(--active)] shadow-lg' : 'bg-white dark:bg-white/5 border-slate-100 dark:border-white/5 hover:border-slate-200'">
                    <div class="size-16 rounded-3xl bg-white dark:bg-slate-800 flex items-center justify-center shadow-sm border border-slate-100 dark:border-white/5 transition-all group-hover:scale-110">
                        <i class="hgi-stroke hgi-moon-01 text-3xl" :class="$flux.appearance === 'dark' ? 'text-[var(--active)]' : 'text-slate-400'"></i>
                    </div>
                    <span class="text-xs font-black uppercase tracking-widest" :class="$flux.appearance === 'dark' ? 'text-[var(--active-2)]' : 'text-slate-500'">{{ __('Sombre') }}</span>
                </button>

                <!-- System Mode Card -->
                <button type="button" @click="$flux.appearance = 'system'"
                    @class([
                        'p-8 rounded-[32px] border-2 transition-all flex flex-col items-center gap-4 group'
                    ])
                    :class="$flux.appearance === 'system' ? 'bg-[var(--active)]/5 border-[var(--active)] shadow-lg' : 'bg-white dark:bg-white/5 border-slate-100 dark:border-white/5 hover:border-slate-200'">
                    <div class="size-16 rounded-3xl bg-white dark:bg-slate-800 flex items-center justify-center shadow-sm border border-slate-100 dark:border-white/5 transition-all group-hover:scale-110">
                        <i class="hgi-stroke hgi-computer-desktop text-3xl" :class="$flux.appearance === 'system' ? 'text-[var(--active)]' : 'text-slate-400'"></i>
                    </div>
                    <span class="text-xs font-black uppercase tracking-widest" :class="$flux.appearance === 'system' ? 'text-[var(--active-2)]' : 'text-slate-500'">{{ __('Système') }}</span>
                </button>
            </div>
        </div>

        <div class="mt-16 pt-10 border-t border-slate-50 dark:border-white/5">
            <h4 class="text-[11px] font-black uppercase tracking-[2px] text-slate-400 mb-8">{{ __('Couleurs de marque') }}</h4>

            <div class="space-y-4">
                @foreach([
                    ['name' => 'Bleu Piiston', 'color' => '#315A7D', 'active' => true],
                    ['name' => 'Violet Royal', 'color' => '#8B5CF6', 'active' => false],
                    ['name' => 'Vert Émeraude', 'color' => '#10B981', 'active' => false]
                ] as $color)
                    <div class="flex items-center justify-between p-5 rounded-[24px] bg-white dark:bg-slate-900 border border-slate-100 dark:border-white/5 shadow-sm group hover:border-slate-200 transition-all">
                        <div class="flex items-center gap-5">
                            <div class="size-8 rounded-full border-4 border-white dark:border-slate-800 shadow-md ring-1 ring-slate-100 dark:ring-white/5" style="background-color: {{ $color['color'] }}"></div>
                            <span class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ $color['name'] }}</span>
                        </div>

                        @if($color['active'])
                            <div class="size-6 rounded-full bg-emerald-500 flex items-center justify-center">
                                <i class="hgi-stroke hgi-tick-02 text-white text-xs"></i>
                            </div>
                        @else
                            <button type="button" class="text-[9px] font-black uppercase tracking-widest text-slate-300 hover:text-[var(--active)] transition-colors">{{ __('Appliquer') }}</button>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </x-settings.layout>
</section>
