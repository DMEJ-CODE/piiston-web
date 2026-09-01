<x-layouts::auth :title="__('Create an Account')">
    <div class="flex flex-col gap-4">
        <x-auth-header :title="__('Create an Account')" :description="__('Enter your details below to create your account.')" />


        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-4">
            @csrf

            <!-- Name -->
            <div class="auth-input-group">
                <label for="name" class="auth-label">{{ __('Full Name') }}</label>
                <div class="auth-input-wrap">
                    <svg class="icon-svg auth-input-icon" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Full name" class="auth-input">
                </div>
            </div>

            <!-- Email Address -->
            <div class="auth-input-group">
                <label for="email" class="auth-label">{{ __('Email') }}</label>
                <div class="auth-input-wrap">
                    <svg class="icon-svg auth-input-icon" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" placeholder="email@example.com" class="auth-input">
                </div>
            </div>

            <!-- Password -->
            <div class="auth-input-group">
                <label for="password" class="auth-label">{{ __('Password') }}</label>
                <div class="auth-input-wrap">
                    <svg class="icon-svg auth-input-icon" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <input id="password" name="password" type="password" required autocomplete="new-password" placeholder="••••••••••••" class="auth-input">
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="auth-input-group">
                <label for="password_confirmation" class="auth-label">{{ __('Confirm Password') }}</label>
                <div class="auth-input-wrap">
                    <svg class="icon-svg auth-input-icon" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" placeholder="Confirm password" class="auth-input">
                </div>
            </div>

            <button type="submit" class="lp-btn lp-btn--primary lp-btn--lg w-full mt-2" data-test="register-user-button">
                <span>{{ __('Create account') }}</span>
            </button>
        </form>

        <div class="auth-divider">
            <span>{{ __('Or Continue With') }}</span>
        </div>

        <div class="auth-social-buttons">
            <a href="#" class="auth-social-btn" title="Sign up with Google">
                <svg class="icon-svg icon-svg--sm" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
                <span>Google</span>
            </a>
            <a href="#" class="auth-social-btn" title="Sign up with Passkey">
                <svg class="icon-svg icon-svg--sm" viewBox="0 0 24 24"><path d="M21 2l-2 2m-2 2l-2 2m2-2l2 2m-4 4l-4 4M3 11a8 8 0 1 0 16 0 8 8 0 0 0-16 0z"/></svg>
                <span>Passkey</span>
            </a>
        </div>

        <div class="auth-footer-text">
            <span>{{ __('Already have an account?') }}</span>
            <a href="{{ route('login') }}" wire:navigate>{{ __('Log in') }}</a>
        </div>
    </div>
</x-layouts::auth>
