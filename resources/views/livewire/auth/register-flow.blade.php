<div class="flex flex-col gap-4">
    <x-auth-header
        :title="match($step) { 1 => __('Create an Account'), 2 => __('Secure Account'), 3 => __('Verify Phone'), default => __('Choose Your Profile') }"
        :description="match($step) { 1 => __('Enter your details below to create your account.'), 2 => __('Select your country and enter your mobile number.'), 3 => __('Enter the 6-digit code sent to') . ' ' . $country_code . ' ' . $phone, default => __('How will you use Piiston on the web?') }"
    />

    <x-auth-session-status class="text-center" :status="session('status')" />

    @if($step == 1)
        <div class="flex flex-col gap-4">
            <div class="auth-input-group">
                <label for="name" class="auth-label">{{ __('Full Name') }}</label>
                <div class="auth-input-wrap">
                    <svg class="icon-svg auth-input-icon" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <input id="name" name="name" type="text" value="{{ old('name', $name) }}" required autofocus autocomplete="name" placeholder="Full name" class="auth-input" wire:model.defer="name">
                </div>
                @error('name')
                    <p class="text-[var(--accent-red)] text-xs font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth-input-group">
                <label for="email" class="auth-label">{{ __('Email') }}</label>
                <div class="auth-input-wrap">
                    <svg class="icon-svg auth-input-icon" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <input id="email" name="email" type="email" value="{{ old('email', $email) }}" required autocomplete="email" placeholder="email@example.com" class="auth-input" wire:model.defer="email">
                </div>
                @error('email')
                    <p class="text-[var(--accent-red)] text-xs font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth-input-group">
                <label for="password" class="auth-label">{{ __('Password') }}</label>
                <div class="auth-input-wrap">
                    <svg class="icon-svg auth-input-icon" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <input id="password" name="password" type="password" required autocomplete="new-password" placeholder="••••••••••••" class="auth-input" wire:model.defer="password">
                </div>
                @error('password')
                    <p class="text-[var(--accent-red)] text-xs font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth-input-group">
                <label for="password_confirmation" class="auth-label">{{ __('Confirm Password') }}</label>
                <div class="auth-input-wrap">
                    <svg class="icon-svg auth-input-icon" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" placeholder="Confirm password" class="auth-input" wire:model.defer="password_confirmation">
                </div>
            </div>

            <button type="button" wire:click="nextStep" class="lp-btn lp-btn--primary lp-btn--lg w-full mt-2" data-test="register-flow-continue-button">
                <span>{{ __('Continue') }}</span>
            </button>

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

    @elseif($step == 2)
        <div class="flex flex-col gap-4">
            <div class="auth-input-group">
                <label for="country" class="auth-label">{{ __('Country') }}</label>
                <div class="auth-input-wrap" x-data="{ open: false }">
                    <span class="absolute left-3.5 z-10 text-xl pointer-events-none">{{ $country_flag }}</span>
                    <button @click="open = !open" type="button" class="auth-input text-left flex items-center gap-3" style="padding-left: 44px;">
                        <span class="flex-1 font-bold text-sm text-[var(--text-main)]">
                            {{ collect($countries)->firstWhere('code', $country_code)['name'] }}
                        </span>
                        <span class="text-xs font-bold text-[var(--text-subtle)]">{{ $country_code }}</span>
                        <svg class="icon-svg icon-svg--sm text-[var(--text-subtle)]" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
                    </button>

                    <div x-show="open" x-cloak @click.away="open = false" class="absolute z-50 w-full mt-2 top-full bg-[var(--bg-glass)] backdrop-blur-xl border border-[var(--border-color)] rounded-2xl shadow-xl overflow-hidden py-1">
                        @foreach($countries as $c)
                            <button wire:click="setCountry('{{ $c['code'] }}', '{{ $c['flag'] }}')" @click="open = false" type="button" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-[var(--accent-active)]/10 transition-all text-left">
                                <span class="text-lg">{{ $c['flag'] }}</span>
                                <span class="flex-1 text-sm font-bold text-[var(--text-main)]">{{ $c['name'] }}</span>
                                <span class="text-xs text-[var(--text-subtle)]">{{ $c['code'] }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="auth-input-group">
                <label for="phone" class="auth-label">{{ __('Phone Number') }}</label>
                <div class="auth-input-wrap">
                    <svg class="icon-svg auth-input-icon" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <input id="phone" name="phone" type="tel" value="{{ old('phone', $phone) }}" required autocomplete="tel" placeholder="677 000 000" class="auth-input" wire:model.defer="phone">
                </div>
                @error('phone')
                    <p class="text-[var(--accent-red)] text-xs font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="button" wire:click="sendOtp" class="lp-btn lp-btn--primary lp-btn--lg w-full mt-2">
                <span>{{ __('Verify Number') }}</span>
            </button>

            <button type="button" wire:click="$set('step', 1)" class="auth-forgot-link text-center">
                {{ __('Back to Personal Info') }}
            </button>
        </div>

    @elseif($step == 3)
        <div class="flex flex-col gap-4">
            <div class="flex justify-center">
                <div class="flex gap-2" x-data="{
                    digits: @json(str_split($otp ?? '')),
                    get otp() { return this.digits.join(''); },
                    set otp(val) { this.digits = val.split(''); },
                    focusNext(el) {
                        const inputs = el.parentElement.querySelectorAll('input');
                        const idx = Array.from(inputs).indexOf(el);
                        if (idx < inputs.length - 1) inputs[idx + 1].focus();
                    },
                    focusPrev(el) {
                        const inputs = el.parentElement.querySelectorAll('input');
                        const idx = Array.from(inputs).indexOf(el);
                        if (idx > 0) inputs[idx - 1].focus();
                    }
                }">
                    @for ($i = 0; $i < 6; $i++)
                        <input
                            type="text"
                            inputmode="numeric"
                            autocomplete="one-time-code"
                            maxlength="1"
                            class="auth-input otp-input"
                            :value="digits[{{ $i }}] || ''"
                            x-on:input="
                                const val = $event.target.value.replace(/[^0-9]/g, '');
                                $event.target.value = val.slice(0, 1);
                                digits[{{ $i }}] = val.slice(0, 1);
                                $wire.set('otp', otp);
                                if (val) $nextTick(() => focusNext($event.target));
                            "
                            x-on:keydown.backspace="
                                if (!$event.target.value) $nextTick(() => focusPrev($event.target));
                            "
                            x-on:paste="
                                const paste = ($event.clipboardData || window.clipboardData).getData('text');
                                const clean = paste.replace(/[^0-9]/g, '').slice(0, 6);
                                if (clean.length) {
                                    for (let i = 0; i < 6; i++) digits[i] = clean[i] || '';
                                    $wire.set('otp', clean);
                                    const inputs = $event.target.parentElement.querySelectorAll('input');
                                    inputs.forEach((inp, i) => { inp.value = clean[i] || ''; });
                                    if (clean.length === 6 && inputs[5]) inputs[5].focus();
                                }
                            "
                        >
                    @endfor
                </div>
            </div>

            @if($errors->has('otp'))
                <p class="text-center text-[var(--accent-red)] text-xs font-bold">{{ $errors->first('otp') }}</p>
            @endif

            <button type="button" wire:click="$set('step', 2)" class="auth-forgot-link text-center">
                {{ __('Change Number') }}
            </button>
        </div>

    @elseif($step == 4)
        <div class="flex flex-col gap-4">
            @foreach($roles as $role)
                <button
                    type="button"
                    wire:click="selectRole('{{ $role['id'] }}')"
                    class="flex items-center gap-4 p-4 rounded-2xl border-2 transition-all text-left w-full
                        {{ $selectedRole === $role['id']
                            ? 'border-[var(--accent-active2)] bg-[var(--accent-active)]/5'
                            : 'border-[var(--border-color)] bg-[rgba(0,0,0,0.03)] hover:border-[var(--accent-active2)]/40' }}"
                >
                    <div class="p-2 rounded-xl {{ $selectedRole === $role['id'] ? 'bg-[var(--accent-active)]/10 text-[var(--accent-active2)]' : 'bg-[rgba(0,0,0,0.05)] text-[var(--text-muted)]' }}">
                        <svg class="icon-svg size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            @if($role['icon'] == 'wrench-screwdriver')
                                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                            @elseif($role['icon'] == 'truck')
                                <rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                            @else
                                <circle cx="12" cy="12" r="10"/>
                            @endif
                        </svg>
                    </div>

                    <div class="flex-1">
                        <h4 class="text-sm font-bold {{ $selectedRole === $role['id'] ? 'text-[var(--text-main)]' : 'text-[var(--text-muted)]' }}">
                            {{ $role['title'] }}
                        </h4>
                        <p class="text-xs text-[var(--text-subtle)]">
                            {{ $role['desc'] }}
                        </p>
                    </div>

                    @if($selectedRole === $role['id'])
                        <div class="text-[var(--accent-active2)]">
                            <svg class="icon-svg size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        </div>
                    @endif
                </button>
            @endforeach

            @error('selectedRole')
                <p class="text-[var(--accent-red)] text-xs font-bold mt-1">{{ $message }}</p>
            @enderror

            <button type="button" wire:click="finish" class="lp-btn lp-btn--primary lp-btn--lg w-full mt-2" @disabled(!$selectedRole)>
                <span>{{ __('Complete Registration') }}</span>
            </button>
        </div>
    @endif
</div>
