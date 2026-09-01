<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        @vite(['resources/js/landing.js'])
    </head>
    <body class="landing-page auth-page">
        <!-- Ambient multi-color gradient background orbs (matching Flutter AuthBackground) -->
        <div class="auth-bg-glow-1"></div>
        <div class="auth-bg-glow-2"></div>

        <div class="auth-card-wrap">
            <!-- Floating Logo Badge (matching Flutter login.dart logo header) -->
            <a href="{{ route('home') }}" class="auth-logo-badge" title="Piiston Homepage" wire:navigate>
                <img src="{{ asset('piiston/android-chrome-192x192.png') }}" alt="Piiston Logo">
            </a>

            <div class="auth-card">
                <button id="theme-toggle-btn" class="theme-toggle-btn auth-card__theme-btn" aria-label="Toggle Dark/Light Mode" style="position: absolute; top: 18px; right: 18px;">
                    <svg class="icon-svg theme-icon-sun" style="display: none;" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>
                    <svg class="icon-svg theme-icon-moon" viewBox="0 0 24 24"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
                </button>

                {{ $slot }}
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
