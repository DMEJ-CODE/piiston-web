<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-900">
        <header class="py-6 px-10 flex items-center justify-between border-b border-zinc-100 dark:border-white/5 bg-[var(--surface)]">
            <x-app-logo />
            <div class="flex items-center gap-4">
                <span class="text-[10px] font-black uppercase text-zinc-400 tracking-widest">Guided Setup</span>
                <div class="h-4 w-px bg-zinc-200 dark:bg-white/10"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-[10px] font-black uppercase text-zinc-500 hover:text-zinc-900 dark:hover:text-white transition-colors">{{ __('nav.Log out') }}</button>
                </form>
            </div>
        </header>

        <main class="w-full">
            {{ $slot }}
        </main>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
