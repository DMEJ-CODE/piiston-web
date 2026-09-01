<div class="w-full p-4 md:p-5 rounded-2xl text-white shadow-lg relative overflow-hidden mb-4"
     style="background: linear-gradient(135deg, var(--accent-active2), var(--accent-active));">

    <!-- Background Decor (Circles like mobile) -->
    <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-white/10 blur-2xl"></div>
    <div class="absolute -bottom-10 -left-10 w-32 h-32 rounded-full bg-white/5 blur-xl"></div>

    <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="max-w-xl">
            <h1 class="text-xl md:text-2xl font-black mb-1 tracking-tight">
                Welcome Back, {{ auth()->user()->name }}!
            </h1>
            <p class="text-white/80 text-xs md:text-sm font-medium leading-relaxed">
                Your automotive ecosystem is performing at optimal levels today. All systems are synchronized.
            </p>
        </div>

        <div class="hidden md:flex p-3 bg-white/15 rounded-2xl backdrop-blur-md border border-white/20">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
        </div>
    </div>
</div>
