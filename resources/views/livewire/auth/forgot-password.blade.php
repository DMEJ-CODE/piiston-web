<x-layouts::auth :title="__('auth.Forget Password')">
    <div class="flex flex-col gap-4">
        <x-auth-header :title="__('auth.Forget Password')" :description="__('auth.Enter your email to receive a password reset link.')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-5">
            @csrf

            <!-- Email Address -->
            <div class="auth-input-group">
                <label for="email" class="auth-label">{{ __('auth.Email') }}</label>
                <div class="auth-input-wrap">
                    <i class="hgi hgi-mail-01 auth-input-icon"></i>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus placeholder="email@example.com" class="auth-input">
                </div>
            </div>

            <button type="submit" class="lp-btn lp-btn--primary lp-btn--lg w-full mt-2" data-test="email-password-reset-link-button">
                <span>{{ __('auth.Reset Password') }}</span>
            </button>
        </form>

        <div class="auth-footer-text">
            <span>{{ __('auth.Back to') }}</span>
            <a href="{{ route('login') }}" wire:navigate>{{ __('auth.Log in') }}</a>
        </div>
    </div>
</x-layouts::auth>
