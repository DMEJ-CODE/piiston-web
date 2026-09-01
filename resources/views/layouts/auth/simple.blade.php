<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        @vite(['resources/js/landing.js'])
    </head>
    <body class="landing-page auth-page">
        <div class="split-auth-container">
            <!-- Left Pane: Form -->
            <div class="split-auth-form-pane">
                <div class="split-auth-form-header">
                    <a href="{{ route('home') }}" class="split-auth-logo" title="Piiston Homepage" wire:navigate>
                        <img src="{{ asset('piiston/android-chrome-192x192.png') }}" alt="Piiston Logo" class="split-auth-logo-img">
                        <span>Piiston</span>
                    </a>
                    <button id="theme-toggle-btn" class="theme-toggle-btn" aria-label="Toggle Dark/Light Mode">
                        <svg class="icon-svg theme-icon-sun" style="display: none !important;" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>
                        <svg class="icon-svg theme-icon-moon" viewBox="0 0 24 24"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
                    </button>
                </div>
                
                <div class="split-auth-form-content">
                    {{ $slot }}
                </div>
            </div>
            
            <!-- Right Pane: Visual -->
            <div class="split-auth-visual-pane">
                <div class="auth-bg-glow-1"></div>
                <div class="auth-bg-glow-2"></div>
                
                <div class="split-auth-visual-content">
                    <h2>Manage your fleet like a pro.</h2>
                    <p>Join thousands of businesses optimizing their operations with Piiston.</p>
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
