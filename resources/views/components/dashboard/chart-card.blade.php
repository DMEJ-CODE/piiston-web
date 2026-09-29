@props([
    'title',
    'subtitle' => null,
    'chartId',
    'class' => ''
])

<div class="card-premium !p-5 {{ $class }}">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h5 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest">{{ $title }}</h5>
            @if($subtitle)
                <p class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase mt-1">{{ $subtitle }}</p>
            @endif
        </div>

        <button class="size-8 rounded-lg hover:bg-slate-100 dark:hover:bg-white/5 flex items-center justify-center text-slate-400 transition-colors">
            <flux:icon icon="ellipsis-horizontal" class="size-5" />
        </button>
    </div>

    <div id="{{ $chartId }}" class="w-full"></div>
</div>
