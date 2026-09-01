<x-layouts::app :title="__('Calendrier des RDV')">
    <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" class="font-black uppercase tracking-tight">Planning de l'Atelier</flux:heading>
                <flux:text class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">Vue visuelle des rendez-vous et réparations.</flux:text>
            </div>
            <div class="flex gap-2">
                <flux:button href="{{ route('garage.appointments.create') }}" size="xs" variant="primary" class="font-black uppercase tracking-widest px-4">+ Nouveau RDV</flux:button>
                <flux:button href="{{ route('garage.appointments.index') }}" size="xs" variant="filled" class="font-black uppercase tracking-widest px-4">Vue Liste</flux:button>
            </div>
        </div>

        <!-- Simple Grid Calendar Placeholder (Real implementation would use a library like FullCalendar or a dedicated Livewire component) -->
        <flux:card class="p-0 overflow-hidden border-none shadow-2xl">
            <div class="grid grid-cols-7 border-b border-zinc-100 dark:border-white/5">
                @foreach(['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'] as $day)
                <div class="py-4 text-center text-[10px] font-black uppercase tracking-widest text-zinc-400 border-r border-zinc-100 dark:border-white/5 last:border-0">{{ $day }}</div>
                @endforeach
            </div>

            <div class="grid grid-cols-7 grid-rows-5 h-[600px]">
                @for($i = 1; $i <= 35; $i++)
                <div class="border-r border-b border-zinc-100 dark:border-white/5 p-2 last:border-r-0 flex flex-col gap-1 overflow-y-auto hover:bg-zinc-50 dark:hover:bg-white/[0.01] transition-all">
                    <span class="text-[10px] font-bold text-zinc-300">{{ $i <= 31 ? $i : '' }}</span>

                    @if($i == 12)
                    <div class="p-2 rounded-lg bg-blue-500/10 border border-blue-500/20 flex flex-col gap-0.5 cursor-pointer hover:scale-105 transition-transform">
                        <span class="text-[8px] font-black text-blue-600 uppercase leading-none">RO #1245</span>
                        <span class="text-[9px] font-bold text-zinc-700 dark:text-zinc-200 truncate">Toyota Corolla - Freins</span>
                    </div>
                    @endif

                    @if($i == 15)
                    <div class="p-2 rounded-lg bg-orange-500/10 border border-orange-500/20 flex flex-col gap-0.5 cursor-pointer hover:scale-105 transition-transform">
                        <span class="text-[8px] font-black text-orange-600 uppercase leading-none">RDV - 09:30</span>
                        <span class="text-[9px] font-bold text-zinc-700 dark:text-zinc-200 truncate">M. Jean-Pierre</span>
                    </div>
                    @endif
                </div>
                @endfor
            </div>
        </flux:card>

        <div class="flex items-center gap-6 px-4">
            <div class="flex items-center gap-2">
                <div class="size-2 rounded-full bg-blue-500"></div>
                <span class="text-[10px] font-bold uppercase tracking-widest text-zinc-400">Réparations</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="size-2 rounded-full bg-orange-500"></div>
                <span class="text-[10px] font-bold uppercase tracking-widest text-zinc-400">Rendez-vous</span>
            </div>
        </div>
    </div>
</x-layouts::app>
