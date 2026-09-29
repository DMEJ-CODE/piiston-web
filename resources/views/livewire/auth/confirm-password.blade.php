<x-layouts::auth :title="__('auth.Confirm password')">
    <div class="flex flex-col gap-4">
        <x-auth-header
            :title="__('auth.Confirm password')"
            :description="__('auth.This is a secure area of the application. Please confirm your password before continuing.')"
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.confirm.store') }}" class="flex flex-col gap-4">
            @csrf

            <div class="auth-input-group">
                <label for="password" class="auth-label">{{ __('auth.Password') }}</label>
                <div class="auth-input-wrap">
                    <i class="hgi hgi-lock auth-input-icon"></i>
                    <input id="password" name="password" type="password" required autocomplete="current-password" placeholder="••••••••" class="auth-input">
                </div>
                @error('password')
                    <p class="text-[var(--accent-red)] text-xs font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="lp-btn lp-btn--primary lp-btn--lg w-full mt-1" data-test="confirm-password-button">
                <span>{{ __('auth.Confirm') }}</span>
            </button>
        </form>
    </div>
</x-layouts::auth>
