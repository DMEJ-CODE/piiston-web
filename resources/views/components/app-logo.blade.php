@props([
    'sidebar' => false,
])

@if($sidebar)
    <a {{ $attributes->class(['flex items-center gap-3 px-3 py-2 group']) }}>
        <div class="flex items-center justify-center size-9 rounded-xl bg-gradient-to-br from-[#7D9AB7] to-[#9BB8D3] p-2 shadow-sm shadow-[#9BB8D3]/20 ring-1 ring-white/20 dark:ring-white/10">
            <img src="{{ asset('piiston/android-chrome-192x192.png') }}" class="size-5 object-cover rounded" alt="Piiston">
        </div>
        <span class="text-[15px] font-extrabold tracking-tight text-zinc-800 dark:text-zinc-100 in-data-flux-sidebar-collapsed-desktop:hidden group-hover:text-[var(--active)] transition-colors">Piiston</span>
    </a>
@else
    <flux:brand name="Piiston" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
            <img src="{{ asset('piiston/android-chrome-192x192.png') }}" class="size-6 rounded-md">
        </x-slot>
    </flux:brand>
@endif
