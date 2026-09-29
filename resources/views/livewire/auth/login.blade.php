<x-layouts::auth :title="__('auth.Access Your Account')">
    <div class="flex flex-col gap-4">
        <x-auth-header :title="__('auth.Access Your Account')" :description="__('auth.Sign in to manage your fleet and vehicles.')" />


        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <x-passkey-verify />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-4">
            @csrf

            <!-- Email Address -->
            <div class="auth-input-group">
                <label for="email" class="auth-label">{{ __('auth.Email') }}</label>
                <div class="auth-input-wrap">
                    <i class="hgi hgi-mail-01 auth-input-icon"></i>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="Enter Email Address" class="auth-input">
                </div>
            </div>

            <!-- Password -->
            <div class="auth-input-group">
                <div class="flex justify-between items-center mb-1">
                    <label for="password" class="auth-label">{{ __('auth.Password') }}</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="auth-forgot-link" wire:navigate>{{ __('auth.Forgot Password?') }}</a>
                    @endif
                </div>
                <div class="auth-input-wrap">
                    <i class="hgi hgi-lock auth-input-icon"></i>
                    <input id="password" name="password" type="password" required autocomplete="current-password" placeholder="••••••••••••" class="auth-input">
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center gap-2 my-1">
                <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }} class="auth-checkbox">
                <label for="remember" class="text-sm font-medium text-muted cursor-pointer" style="font-size: 13px;">{{ __('auth.Remember me') }}</label>
            </div>

            <button type="submit" class="lp-btn lp-btn--primary lp-btn--lg w-full mt-1" data-test="login-button">
                <span>{{ __('auth.Sign in') }}</span>
            </button>
        </form>

        <div class="auth-divider">
            <span>{{ __('auth.Or Continue With') }}</span>
        </div>

        <div class="auth-social-buttons">
            <a href="#" class="auth-social-btn" title="Sign in with Google">
                <i class="hgi hgi-google hgi-sm"></i>
                <span>Google</span>
            </a>
            <a href="#" class="auth-social-btn" title="Sign in with Apple">
                <i class="hgi hgi-apple hgi-sm"></i>
                <span>Apple</span>
            </a>
        </div>

        <div class="auth-footer-text">
            <span>{{ __('auth.Don\'t have an account?') }}</span>
            <a href="{{ route('register') }}" wire:navigate>{{ __('auth.Sign up') }}</a>
        </div>
    </div>
</x-layouts::auth>
