<x-layouts::auth :title="__('auth.Reset password')">
    <div class="flex flex-col gap-4">
        <x-auth-header :title="__('auth.Reset password')" :description="__('auth.Please enter your new password below')" />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-4">
            @csrf
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <div class="auth-input-group">
                <label for="email" class="auth-label">{{ __('auth.Email') }}</label>
                <div class="auth-input-wrap">
                    <i class="hgi hgi-mail-01 auth-input-icon"></i>
                    <input id="email" name="email" type="email" value="{{ request('email') }}" required autocomplete="email" placeholder="email@example.com" class="auth-input">
                </div>
                @error('email')
                    <p class="text-[var(--accent-red)] text-xs font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth-input-group">
                <label for="password" class="auth-label">{{ __('auth.Password') }}</label>
                <div class="auth-input-wrap">
                    <i class="hgi hgi-lock auth-input-icon"></i>
                    <input id="password" name="password" type="password" required autocomplete="new-password" placeholder="••••••••" class="auth-input">
                </div>
                @error('password')
                    <p class="text-[var(--accent-red)] text-xs font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth-input-group">
                <label for="password_confirmation" class="auth-label">{{ __('auth.Confirm Password') }}</label>
                <div class="auth-input-wrap">
                    <i class="hgi hgi-lock auth-input-icon"></i>
                    <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" placeholder="••••••••" class="auth-input">
                </div>
                @error('password_confirmation')
                    <p class="text-[var(--accent-red)] text-xs font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="lp-btn lp-btn--primary lp-btn--lg w-full mt-1" data-test="reset-password-button">
                <span>{{ __('auth.Reset password') }}</span>
            </button>
        </form>
    </div>
</x-layouts::auth>
