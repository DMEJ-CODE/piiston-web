<x-layouts::auth :title="__('auth.Create an Account')">
    <div class="flex flex-col gap-4">
        <x-auth-header :title="__('auth.Create an Account')" :description="__('auth.Enter your details below to create your account.')" />


        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-4">
            @csrf

            <!-- Name -->
            <div class="auth-input-group">
                <label for="name" class="auth-label">{{ __('auth.Full Name') }}</label>
                <div class="auth-input-wrap">
                    <i class="hgi hgi-user auth-input-icon"></i>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Full name" class="auth-input">
                </div>
            </div>

            <!-- Email Address -->
            <div class="auth-input-group">
                <label for="email" class="auth-label">{{ __('auth.Email') }}</label>
                <div class="auth-input-wrap">
                    <i class="hgi hgi-mail-01 auth-input-icon"></i>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" placeholder="email@example.com" class="auth-input">
                </div>
            </div>

            <!-- Password -->
            <div class="auth-input-group">
                <label for="password" class="auth-label">{{ __('auth.Password') }}</label>
                <div class="auth-input-wrap">
                    <i class="hgi hgi-lock auth-input-icon"></i>
                    <input id="password" name="password" type="password" required autocomplete="new-password" placeholder="••••••••••••" class="auth-input">
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="auth-input-group">
                <label for="password_confirmation" class="auth-label">{{ __('auth.Confirm Password') }}</label>
                <div class="auth-input-wrap">
                    <i class="hgi hgi-shield-check auth-input-icon"></i>
                    <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" placeholder="Confirm password" class="auth-input">
                </div>
            </div>

            <button type="submit" class="lp-btn lp-btn--primary lp-btn--lg w-full mt-2" data-test="register-user-button">
                <span>{{ __('auth.Create account') }}</span>
            </button>
        </form>

        <div class="auth-divider">
            <span>{{ __('auth.Or Continue With') }}</span>
        </div>

        <div class="auth-social-buttons">
            <a href="#" class="auth-social-btn" title="Sign up with Google">
                <i class="hgi hgi-google hgi-sm"></i>
                <span>Google</span>
            </a>
            <a href="#" class="auth-social-btn" title="Sign up with Passkey">
                <i class="hgi hgi-magic-wand-01 hgi-sm"></i>
                <span>Passkey</span>
            </a>
        </div>

        <div class="auth-footer-text">
            <span>{{ __('auth.Already have an account?') }}</span>
            <a href="{{ route('login') }}" wire:navigate>{{ __('auth.Log in') }}</a>
        </div>
    </div>
</x-layouts::auth>
