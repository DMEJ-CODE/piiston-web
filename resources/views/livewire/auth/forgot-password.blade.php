<x-layouts::auth :title="__('Forget Password')">
    <div class="flex flex-col gap-4">
        <x-auth-header :title="__('Forget Password')" :description="__('Enter your email to receive a password reset link.')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-5">
            @csrf

            <!-- Email Address -->
            <div class="auth-input-group">
                <label for="email" class="auth-label">{{ __('Email') }}</label>
                <div class="auth-input-wrap">
                    <svg class="icon-svg auth-input-icon" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus placeholder="email@example.com" class="auth-input">
                </div>
            </div>

            <button type="submit" class="lp-btn lp-btn--primary lp-btn--lg w-full mt-2" data-test="email-password-reset-link-button">
                <span>{{ __('Reset Password') }}</span>
            </button>
        </form>

        <div class="auth-footer-text">
            <span>{{ __('Back to') }}</span>
            <a href="{{ route('login') }}" wire:navigate>{{ __('Log in') }}</a>
        </div>
    </div>
</x-layouts::auth>
