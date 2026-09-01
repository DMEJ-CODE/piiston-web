@php
    $user = auth()->user();
    $name = $user->name;
    $email = $user->email;
    $initials = substr($name, 0, 1);
@endphp

<div class="relative mb-10 w-full rounded-[32px] overflow-hidden bg-white dark:bg-slate-900 border border-slate-100 dark:border-white/5 shadow-premium">
    <!-- Gradient Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-[var(--active-2)]/10 to-transparent pointer-events-none"></div>

    <div class="relative z-10 px-8 py-10 flex flex-col md:flex-row items-center gap-8">
        <!-- Avatar Section -->
        <div class="relative">
            <div class="size-24 rounded-full bg-white dark:bg-slate-800 p-1 shadow-md border border-slate-100 dark:border-white/5">
                <div class="size-full rounded-full bg-[var(--active)]/10 flex items-center justify-center overflow-hidden">
                    <span class="text-3xl font-black text-[var(--active-2)]">{{ $initials }}</span>
                </div>
            </div>
            <div class="absolute bottom-1 right-1 size-8 rounded-full bg-[var(--active-2)] border-4 border-white dark:border-slate-900 flex items-center justify-center shadow-lg">
                <i class="hgi-stroke hgi-camera-01 text-white text-xs"></i>
            </div>
        </div>

        <!-- Info Section -->
        <div class="flex flex-col items-center md:items-start gap-1">
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-black uppercase text-slate-900 dark:text-white tracking-tight">{{ $name }}</h2>
                <span class="px-2 py-0.5 rounded-lg bg-emerald-500/10 text-emerald-600 text-[9px] font-black uppercase tracking-widest border border-emerald-500/10">Membre Pro</span>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $email }}</p>

            <div class="mt-4 flex gap-4">
                <div class="flex flex-col">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Inscrit depuis</span>
                    <span class="text-xs font-black text-slate-700 dark:text-slate-300 uppercase">{{ $user->created_at->format('M Y') }}</span>
                </div>
                <div class="w-px h-8 bg-slate-100 dark:bg-white/5"></div>
                <div class="flex flex-col">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Rôle</span>
                    <span class="text-xs font-black text-slate-700 dark:text-slate-300 uppercase">{{ $user->roles->first()->name ?? 'Utilisateur' }}</span>
                </div>
            </div>
        </div>

        <div class="md:ml-auto flex gap-3">
            <flux:button variant="ghost" class="rounded-xl border border-slate-200 dark:border-white/5 bg-white dark:bg-white/5 shadow-sm">
                <i class="hgi-stroke hgi-share-01 mr-2 text-sm"></i>
                <span class="text-[10px] font-black uppercase tracking-widest">Partager</span>
            </flux:button>
        </div>
    </div>
</div>
