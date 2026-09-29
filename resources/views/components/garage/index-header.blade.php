@props([
    'title',
    'subtitle' => null,
    'actionText' => null,
    'actionUrl' => null,
    'actionIcon' => 'plus',
    'secondaryActionText' => null,
    'secondaryActionUrl' => null,
    'secondaryActionIcon' => 'truck',
    'searchPlaceholder' => 'Search...',
    'filters' => [],
    'canExport' => false,
    'exportUrl' => null
])

<div class="flex flex-col gap-4 mb-6">
    <!-- Top Row: Title & Main Action -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex flex-col gap-0.5">
            <h2 class="text-sm font-black uppercase tracking-tight text-slate-900 dark:text-white">{{ $title }}</h2>
            @if($subtitle)
                <p class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest">{{ $subtitle }}</p>
            @endif
        </div>

        <div class="flex items-center gap-2">
            @if($secondaryActionUrl && $secondaryActionText)
                <a href="{{ $secondaryActionUrl }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl font-black uppercase tracking-widest text-slate-700 dark:text-slate-200 text-[9px] border border-slate-200 dark:border-white/10 bg-white dark:bg-white/5 hover:bg-slate-50 dark:hover:bg-white/10 transition-all shadow-sm">
                    <flux:icon icon="{{ $secondaryActionIcon }}" class="size-3 mr-1" />
                    {{ $secondaryActionText }}
                </a>
            @endif
            @if($canExport)
                <a href="{{ $exportUrl ?? '#' }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 text-[9px] border border-slate-200 dark:border-white/10 bg-white dark:bg-white/5 hover:bg-slate-50 dark:hover:bg-white/10 transition-all">
                    <flux:icon icon="arrow-down-tray" class="size-3" />
                    Export
                </a>
            @endif

            @if($actionUrl && $actionText)
                <a href="{{ $actionUrl }}"
                    class="btn-premium-primary !px-5 !py-2.5 !text-[9px] !rounded-[18px]">
                    <flux:icon icon="{{ $actionIcon }}" class="size-3 mr-1.5" />
                    {{ $actionText }}
                </a>
            @endif
        </div>
    </div>

    <!-- Search & Filters Row — Glassmorphism Bar -->
    <div class="bg-white/80 dark:bg-[#0F172A]/80 backdrop-blur-xl border border-slate-200/80 dark:border-white/5 rounded-2xl px-4 py-3 shadow-sm">
        <form action="{{ url()->current() }}" method="GET" class="flex flex-col md:flex-row items-center gap-3">
            <!-- Search Input -->
            <div class="relative flex-1 w-full group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="hgi-stroke hgi-search-01 text-sm text-slate-400 group-focus-within:text-[var(--active-2)] transition-colors"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ $searchPlaceholder }}"
                    class="block w-full pl-11 pr-4 py-2 bg-slate-50/80 dark:bg-white/5 border border-slate-200/80 dark:border-white/10 rounded-xl text-[11px] font-bold text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-4 focus:ring-[var(--active-2-rgb)]/10 focus:border-[var(--active-2)] outline-none transition-all">
            </div>

            <!-- Dynamic Filters -->
            @if(count($filters))
            <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                @foreach($filters as $filter)
                    <div class="flex-1 min-w-[140px]">
                        <select name="{{ $filter['name'] }}" onchange="this.form.submit()"
                            class="block w-full px-3 py-2 bg-slate-50/80 dark:bg-white/5 border border-slate-200/80 dark:border-white/10 rounded-xl text-[9px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-300 outline-none cursor-pointer hover:border-[var(--active-2)] transition-all appearance-none">
                            <option value="">{{ $filter['label'] }}</option>
                            @foreach($filter['options'] as $value => $label)
                                <option value="{{ $value }}" {{ request($filter['name']) == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                @endforeach
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex gap-2 w-full md:w-auto shrink-0">
                <button type="submit"
                    class="flex-1 md:flex-none inline-flex items-center justify-center gap-1.5 px-5 py-2 rounded-xl font-black uppercase tracking-widest text-[9px] text-white transition-all"
                    style="background-color: var(--active-2); box-shadow: var(--shadow-button-mobile);">
                    Filter
                </button>

                @if(request()->anyFilled(array_merge(['search'], array_column($filters, 'name'))))
                    <a href="{{ url()->current() }}"
                        class="inline-flex items-center justify-center px-3 py-2 rounded-xl font-black text-[9px] text-red-500 border border-red-500/20 bg-red-500/5 hover:bg-red-500/10 transition-all"
                        title="Réinitialiser">
                        <i class="hgi-stroke hgi-cancel-01 text-sm"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>
