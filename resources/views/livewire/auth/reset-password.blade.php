<x-layouts::auth :title="__('Reset password')">
    <div class="flex flex-col gap-4">
        <x-auth-header :title="__('Reset password')" :description="__('Please enter your new password below')" />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-4">
            @csrf
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <div class="auth-input-group">
                <label for="email" class="auth-label">{{ __('Email') }}</label>
                <div class="auth-input-wrap">
                    <svg class="icon-svg auth-input-icon" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <input id="email" name="email" type="email" value="{{ request('email') }}" required autocomplete="email" placeholder="email@example.com" class="auth-input">
                </div>
                @error('email')
                    <p class="text-[var(--accent-red)] text-xs font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth-input-group">
                <label for="password" class="auth-label">{{ __('Password') }}</label>
                <div class="auth-input-wrap">
                    <svg class="icon-svg auth-input-icon" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <input id="password" name="password" type="password" required autocomplete="new-password" placeholder="••••••••" class="auth-input">
                </div>
                @error('password')
                    <p class="text-[var(--accent-red)] text-xs font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth-input-group">
                <label for="password_confirmation" class="auth-label">{{ __('Confirm Password') }}</label>
                <div class="auth-input-wrap">
                    <svg class="icon-svg auth-input-icon" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" placeholder="••••••••" class="auth-input">
                </div>
                @error('password_confirmation')
                    <p class="text-[var(--accent-red)] text-xs font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="lp-btn lp-btn--primary lp-btn--lg w-full mt-1" data-test="reset-password-button">
                <span>{{ __('Reset password') }}</span>
            </button>
        </form>
    </div>
</x-layouts::auth>
