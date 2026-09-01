@props([
    'title',
    'subtitle' => null,
    'actionText' => null,
    'actionClick' => null,
    'actionUrl' => null,
    'actionIcon' => 'plus',
    'searchModel' => null,
    'searchPlaceholder' => 'Rechercher...'
])

<div class="flex flex-col gap-5 mb-8">
    <div class="flex items-center justify-between">
        <div class="flex flex-col gap-1">
            <h2 class="!text-sm font-black uppercase text-zinc-900 dark:text-white tracking-tight">{{ $title }}</h2>
            @if($subtitle)
                <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest">{{ $subtitle }}</p>
            @endif
        </div>

        <div class="flex gap-2">
            @if($actionClick)
                <flux:button
                    wire:click="{{ $actionClick }}"
                    size="xs"
                    class="rounded-xl font-black uppercase tracking-widest bg-gradient-to-br from-blue-600 to-blue-500 text-white border-none shadow-md px-5 py-2.5 transition-all hover:scale-[1.02]"
                >
                    <flux:icon icon="{{ $actionIcon }}" class="size-3 mr-2" />
                    {{ $actionText }}
                </flux:button>
            @elseif($actionUrl)
                <flux:button
                    href="{{ $actionUrl }}"
                    size="xs"
                    class="rounded-xl font-black uppercase tracking-widest bg-gradient-to-br from-blue-600 to-blue-500 text-white border-none shadow-md px-5 py-2.5 transition-all hover:scale-[1.02]"
                >
                    <flux:icon icon="{{ $actionIcon }}" class="size-3 mr-2" />
                    {{ $actionText }}
                </flux:button>
            @endif
        </div>
    </div>

    @if($searchModel)
    <div class="relative group">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <i class="hgi-stroke hgi-search-01 text-sm text-zinc-400 group-focus-within:text-blue-500 transition-colors"></i>
        </div>
        <input type="text" wire:model.live="{{ $searchModel }}" placeholder="{{ $searchPlaceholder }}"
            class="block w-full pl-11 pr-4 py-3 bg-[var(--surface)] dark:bg-white/5 border border-zinc-100 dark:border-white/5 rounded-2xl text-[11px] font-bold text-zinc-900 dark:text-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all shadow-sm">
    </div>
    @endif
</div>
