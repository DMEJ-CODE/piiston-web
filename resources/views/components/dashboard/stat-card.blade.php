@props([
    'title',
    'value',
    'icon',
    'color' => 'var(--active)',
    'trend' => null,
    'chartId' => null,
    'isNegative' => false
])

<div class="card-premium !p-4 group cursor-default">
    <div class="flex items-center justify-between mb-3">
        <!-- Physical Icon Container -->
        <div class="size-9 rounded-xl flex items-center justify-center border border-white dark:border-slate-800 shadow-sm"
             style="background: linear-gradient(135deg, {{ $color }}20 0%, {{ $color }}10 100%); color: {{ $color }};">
            <i class="hgi-stroke hgi-{{ $icon }} text-lg group-hover:scale-110 transition-transform"></i>
        </div>

        @if($trend)
        <div class="flex items-center gap-1.5 px-3 py-1 rounded-full border {{ $isNegative ? 'bg-red-500/5 text-red-600 border-red-500/10' : 'bg-emerald-500/5 text-emerald-600 border-emerald-500/10' }}">
            <i class="hgi-stroke hgi-{{ $isNegative ? 'arrow-down-01' : 'arrow-up-01' }} text-[10px]"></i>
            <span class="text-[10px] font-black uppercase tracking-tight">{{ $trend }}</span>
        </div>
        @endif
    </div>

    <div class="flex items-end justify-between gap-4">
        <div class="flex flex-col gap-1 min-w-0">
            <span class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[2px] truncate">{{ $title }}</span>
            <div class="text-xl font-black text-slate-900 dark:text-white tracking-tight truncate leading-none">{{ $value }}</div>
        </div>

        @if($chartId)
            <div id="{{ $chartId }}" class="w-16 h-10 opacity-60 group-hover:opacity-100 transition-opacity"></div>
        @endif
    </div>
</div>
