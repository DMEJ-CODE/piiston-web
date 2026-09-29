<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-[var(--background)] text-slate-900 dark:text-slate-100 font-sans antialiased selection:bg-[var(--active)] selection:text-white">
        <x-layouts.sidebar />

        <x-layouts.header />

        <x-layouts.mobile-header />

        <flux:main class="!max-w-none !w-full px-4">
            {{ $slot }}
        </flux:main>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @stack('scripts')
        @fluxScripts
    </body>
</html>
