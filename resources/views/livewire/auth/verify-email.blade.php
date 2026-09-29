<x-layouts::auth :title="__('auth.Email verification')">
    <div class="flex flex-col gap-4">
        <x-auth-header :title="__('auth.Verify your email')" :description="__('auth.Please check your inbox to verify your account')" />

        <p class="text-center text-sm text-[var(--text-muted)]">
            {{ __('auth.Please verify your email address by clicking on the link we just emailed to you.') }}
        </p>

        @if (session('status') == 'verification-link-sent')
            <p class="text-center text-sm font-medium text-green-600 dark:text-green-400">
                {{ __('auth.A new verification link has been sent to the email address you provided during registration.') }}
            </p>
        @endif

        <div class="flex flex-col items-center justify-between space-y-3">
            <form method="POST" action="{{ route('verification.send') }}" class="w-full">
                @csrf
                <button type="submit" class="lp-btn lp-btn--primary lp-btn--lg w-full">
                    <span>{{ __('auth.Resend verification email') }}</span>
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="lp-btn lp-btn--ghost lp-btn--lg w-full text-sm cursor-pointer" data-test="logout-button">
                    <span>{{ __('auth.Log out') }}</span>
                </button>
            </form>
        </div>
    </div>
</x-layouts::auth>
