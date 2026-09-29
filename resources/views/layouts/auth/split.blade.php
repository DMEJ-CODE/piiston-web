<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        @vite(['resources/js/landing.js'])
    </head>
    <body class="landing-page auth-page" style="padding: 0;">
        <div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0 w-full">
            <div class="relative hidden h-full flex-col p-10 text-white lg:flex" style="background: var(--bg-dark-accent); border-right: 1px solid var(--border-color);">
                <a href="{{ route('home') }}" class="relative z-20 flex items-center gap-3 text-xl font-extrabold text-white" wire:navigate>
                    <img src="{{ asset('piiston/android-chrome-192x192.png') }}" width="36" height="36" style="border-radius: 10px;">
                    <span>Piiston</span>
                </a>

                <div class="relative z-20 mt-auto max-w-lg">
                    <div class="hero__label" style="color: var(--accent-active); border-color: rgba(155,184,211,0.3); margin-bottom: 14px;">
                        Unified Automotive Ecosystem
                    </div>
                    <h2 style="font-size: 32px; font-weight: 900; line-height: 1.2; margin-bottom: 12px; color: #FFF;">
                        The Complete Automotive Platform for Africa
                    </h2>
                    <p style="font-size: 14px; color: rgba(255,255,255,0.6); line-height: 1.6;">
                        Connecting drivers, certified garages, mechanics, spare part sellers, and fleet managers into a single digital pulse.
                    </p>
                </div>
            </div>

            <div class="w-full flex justify-center items-center p-6 lg:p-12">
                <div class="auth-card-wrap">
                    <div class="auth-card">
                        <div class="auth-card__header">
                            <button id="theme-toggle-btn" class="theme-toggle-btn auth-card__theme-btn" aria-label="Toggle Dark/Light Mode">
                                <i class="hgi hgi-sun-01 theme-icon-sun" style="display: none; font-size: 1.25rem;"></i>
                                <i class="hgi hgi-moon-01 theme-icon-moon" style="font-size: 1.25rem;"></i>
                            </button>

                            <a href="{{ route('home') }}" class="auth-card__logo lg:hidden" wire:navigate>
                                <img src="{{ asset('piiston/android-chrome-192x192.png') }}" alt="Piiston Logo" class="auth-card__logo-img">
                                <span>Piiston</span>
                            </a>
                        </div>

                        {{ $slot }}
                    </div>
                </div>
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
