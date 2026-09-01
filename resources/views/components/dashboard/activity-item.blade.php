@props([
    'title',
    'description',
    'time',
    'icon' => 'clock'
])

<div class="flex items-center gap-4 p-2.5 rounded-2xl bg-[var(--surface)] border border-zinc-50 dark:border-white/5 shadow-card-sm cursor-pointer group">
    <div class="p-2.5 rounded-xl bg-[var(--active)]/10 text-[var(--active)] flex-shrink-0 group-hover:scale-105 transition-transform border border-[var(--active)]/20">
        <flux:icon :icon="$icon" variant="outline" class="size-5" />
    </div>

    <div class="flex-1 min-w-0">
        <h4 class="text-sm font-extrabold text-zinc-900 dark:text-white truncate tracking-tight">
            {{ $title }}
        </h4>
        <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 truncate mt-0.5">
            {{ $description }}
        </p>
    </div>

    <div class="text-[10px] font-semibold text-zinc-400 dark:text-zinc-500 whitespace-nowrap bg-zinc-100 dark:bg-white/5 px-2.5 py-1 rounded-lg">
        {{ $time }}
    </div>
</div>
