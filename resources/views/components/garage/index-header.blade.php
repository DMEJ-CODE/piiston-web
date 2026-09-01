@props([
    'title',
    'subtitle' => null,
    'actionText' => null,
    'actionUrl' => null,
    'actionIcon' => 'plus',
    'searchPlaceholder' => 'Rechercher...',
    'filters' => [],
    'canExport' => false,
    'exportUrl' => null
])

<div class="flex flex-col gap-5 mb-6">
    <!-- Top Row: Title & Main Action -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex flex-col gap-1">
            <h2 class="!text-sm font-black uppercase text-zinc-900 dark:text-white tracking-tight">{{ $title }}</h2>
            @if($subtitle)
                <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest">{{ $subtitle }}</p>
            @endif
        </div>

        <div class="flex items-center gap-3">
            @if($canExport)
                <flux:button href="{{ $exportUrl ?? '#' }}" variant="ghost" size="xs" class="rounded-xl font-black uppercase tracking-widest text-zinc-500 px-4 py-2 border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5">
                    <flux:icon icon="arrow-down-tray" class="size-3 mr-2" />
                    Exporter
                </flux:button>
            @endif

            @if($actionUrl && $actionText)
                <flux:button href="{{ $actionUrl }}" variant="primary" size="xs" class="rounded-xl font-black uppercase tracking-widest bg-gradient-to-br from-[var(--active-2)] to-[var(--active)] text-white border-none shadow-md shadow-[var(--active)]/20 px-6 py-2.5 transition-all hover:scale-[1.02]">
                    <flux:icon icon="{{ $actionIcon }}" class="size-3 mr-2" />
                    {{ $actionText }}
                </flux:button>
            @endif
        </div>
    </div>

    <!-- Search & Filters Row -->
    <div class="bg-[var(--surface)] p-2.5 rounded-[20px] border border-zinc-100 dark:border-white/5 shadow-sm flex flex-col md:flex-row items-center gap-3">
        <form action="{{ url()->current() }}" method="GET" class="flex-1 flex flex-col md:flex-row items-center gap-3 w-full">
            <!-- Search Input -->
            <div class="relative flex-1 w-full group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <flux:icon icon="magnifying-glass" class="size-4 text-zinc-400 group-focus-within:text-[var(--active-2)]" />
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ $searchPlaceholder }}"
                    class="block w-full pl-11 pr-4 py-2.5 bg-zinc-50 dark:bg-white/5 border border-zinc-100 dark:border-white/5 rounded-xl text-[11px] font-bold text-zinc-900 dark:text-white focus:ring-4 focus:ring-[var(--active-2)]/10 outline-none transition-all placeholder:text-zinc-400">
            </div>

            <!-- Dynamic Filters -->
            <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                @foreach($filters as $filter)
                    <div class="flex-1 min-w-[140px]">
                        <select name="{{ $filter['name'] }}" onchange="this.form.submit()"
                            class="block w-full px-4 py-2.5 bg-zinc-50 dark:bg-white/5 border border-zinc-100 dark:border-white/5 rounded-xl text-[10px] font-black uppercase tracking-widest text-zinc-600 dark:text-zinc-300 outline-none cursor-pointer hover:bg-zinc-100 dark:hover:bg-white/10 transition-all appearance-none">
                            <option value="">{{ $filter['label'] }}</option>
                            @foreach($filter['options'] as $value => $label)
                                <option value="{{ $value }}" {{ request($filter['name']) == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                @endforeach
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2 w-full md:w-auto">
                <flux:button type="submit" variant="filled" class="flex-1 md:flex-none rounded-xl font-black uppercase tracking-widest h-10 px-6 bg-zinc-900 dark:bg-white/10 text-white text-[11px]">
                    Filtrer
                </flux:button>

                @if(request()->anyFilled(array_merge(['search'], array_column($filters, 'name'))))
                    <flux:button href="{{ url()->current() }}" variant="ghost" class="rounded-xl font-black uppercase tracking-widest text-red-500 h-10 px-3 hover:bg-red-50" title="Réinitialiser">
                        <flux:icon icon="x-mark" class="size-4" />
                    </flux:button>
                @endif
            </div>
        </form>
    </div>
</div>
