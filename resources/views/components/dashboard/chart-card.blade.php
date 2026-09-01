@props([
    'title',
    'subtitle' => null,
    'chartId',
    'class' => ''
])

<div class="card-premium !p-4 {{ $class }}">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h5 class="text-xs font-black text-zinc-900 dark:text-white uppercase tracking-widest">{{ $title }}</h3>
            @if($subtitle)
                <p class="text-[10px] text-zinc-400 font-bold uppercase mt-1">{{ $subtitle }}</p>
            @endif
        </div>

        <button class="size-8 rounded-lg hover:bg-zinc-100 dark:hover:bg-white/5 flex items-center justify-center text-zinc-400 transition-colors">
            <flux:icon icon="ellipsis-horizontal" class="size-5" />
        </button>
    </div>

    <div id="{{ $chartId }}" class="w-full"></div>
</div>
