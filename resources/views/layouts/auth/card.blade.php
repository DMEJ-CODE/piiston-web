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
                    <i class="hgi hgi-sun-01 theme-icon-sun" style="display: none; font-size: 1.25rem;"></i>
                    <i class="hgi hgi-moon-01 theme-icon-moon" style="font-size: 1.25rem;"></i>
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
