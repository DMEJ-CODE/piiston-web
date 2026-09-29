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
                </div>
                
                <div class="split-auth-form-content">
                    {{ $slot }}
                </div>
            </div>
            
            <aside class="split-auth-visual-pane" aria-label="Piiston overview">
                <div class="auth-panel-orbit" aria-hidden="true">
                    <span class="auth-panel-orbit__ring auth-panel-orbit__ring--outer"></span>
                    <span class="auth-panel-orbit__ring auth-panel-orbit__ring--inner"></span>
                    <span class="auth-panel-orbit__node auth-panel-orbit__node--one"></span>
                    <span class="auth-panel-orbit__node auth-panel-orbit__node--two"></span>
                    <span class="auth-panel-orbit__node auth-panel-orbit__node--three"></span>
                    <span class="auth-panel-orbit__node auth-panel-orbit__node--four"></span>
                    <div class="auth-panel-orbit__core">
                        <img src="{{ asset('piiston/android-chrome-192x192.png') }}" alt="">
                    </div>
                </div>

                <div class="split-auth-visual-content">
                    <div class="auth-panel-brand">
                        <img src="{{ asset('piiston/android-chrome-192x192.png') }}" alt="" width="34" height="34">
                        <span>Piiston</span>
                    </div>

                    <div class="auth-panel-copy">
                        <p class="auth-panel-eyebrow">Built for the way Africa moves</p>
                        <h2>Drive with<br><em>confidence.</em></h2>
                        <p>Piiston brings your vehicle journey into one simple, reliable place.</p>
                    </div>

                    <p class="auth-panel-note">Your road. Connected.</p>
                </div>
            </aside>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
