<div class="flex flex-col gap-4">
    <x-auth-header
        :title="match($step) { 1 => __('auth.Create an Account'), 2 => __('auth.Secure Account'), 3 => __('auth.Verify Phone'), default => __('auth.Choose Your Profile') }"
        :description="match($step) { 1 => __('auth.Enter your details below to create your account.'), 2 => __('auth.Select your country and enter your mobile number.'), 3 => __('auth.Enter the 6-digit code sent to') . ' ' . $country_code . ' ' . $phone, default => __('auth.How will you use Piiston on the web?') }"
    />

    <x-auth-session-status class="text-center" :status="session('status')" />

    @if($step == 1)
        <div class="flex flex-col gap-4">
            <div class="auth-input-group">
                <label for="name" class="auth-label">{{ __('auth.Full Name') }}</label>
                <div class="auth-input-wrap">
                    <i class="hgi hgi-user auth-input-icon"></i>
                    <input id="name" name="name" type="text" value="{{ old('name', $name) }}" required autofocus autocomplete="name" placeholder="Full name" class="auth-input" wire:model.defer="name">
                </div>
                @error('name')
                    <p class="text-[var(--accent-red)] text-xs font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth-input-group">
                <label for="email" class="auth-label">{{ __('auth.Email') }}</label>
                <div class="auth-input-wrap">
                    <i class="hgi hgi-mail-01 auth-input-icon"></i>
                    <input id="email" name="email" type="email" value="{{ old('email', $email) }}" required autocomplete="email" placeholder="email@example.com" class="auth-input" wire:model.defer="email">
                </div>
                @error('email')
                    <p class="text-[var(--accent-red)] text-xs font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth-input-group">
                <label for="password" class="auth-label">{{ __('auth.Password') }}</label>
                <div class="auth-input-wrap">
                    <i class="hgi hgi-lock auth-input-icon"></i>
                    <input id="password" name="password" type="password" required autocomplete="new-password" placeholder="••••••••••••" class="auth-input" wire:model.defer="password">
                </div>
                @error('password')
                    <p class="text-[var(--accent-red)] text-xs font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth-input-group">
                <label for="password_confirmation" class="auth-label">{{ __('auth.Confirm Password') }}</label>
                <div class="auth-input-wrap">
                    <i class="hgi hgi-shield-check auth-input-icon"></i>
                    <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" placeholder="Confirm password" class="auth-input" wire:model.defer="password_confirmation">
                </div>
            </div>

            <button type="button" wire:click="nextStep" class="lp-btn lp-btn--primary lp-btn--lg w-full mt-2" data-test="register-flow-continue-button">
                <span>{{ __('auth.Continue') }}</span>
            </button>

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

    @elseif($step == 2)
        <div class="flex flex-col gap-4">
            <div class="auth-input-group">
                <label for="country" class="auth-label">{{ __('auth.Country') }}</label>
                <div class="auth-input-wrap" x-data="{ open: false }">
                    <span class="absolute left-3.5 z-10 text-xl pointer-events-none">{{ $country_flag }}</span>
                    <button @click="open = !open" type="button" class="auth-input text-left flex items-center gap-3" style="padding-left: 44px;">
                        <span class="flex-1 font-bold text-sm text-[var(--text-main)]">
                            {{ collect($countries)->firstWhere('code', $country_code)['name'] }}
                        </span>
                        <span class="text-xs font-bold text-[var(--text-subtle)]">{{ $country_code }}</span>
                        <i class="hgi hgi-arrow-down-01 hgi-sm text-[var(--text-subtle)]"></i>
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
                <label for="phone" class="auth-label">{{ __('auth.Phone Number') }}</label>
                <div class="auth-input-wrap">
                    <i class="hgi hgi-call auth-input-icon"></i>
                    <input id="phone" name="phone" type="tel" value="{{ old('phone', $phone) }}" required autocomplete="tel" placeholder="677 000 000" class="auth-input" wire:model.defer="phone">
                </div>
                @error('phone')
                    <p class="text-[var(--accent-red)] text-xs font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="button" wire:click="sendOtp" class="lp-btn lp-btn--primary lp-btn--lg w-full mt-2">
                <span>{{ __('auth.Verify Number') }}</span>
            </button>

            <button type="button" wire:click="$set('step', 1)" class="auth-forgot-link text-center">
                {{ __('auth.Back to Personal Info') }}
            </button>
        </div>

    @elseif($step == 3)
        <div class="flex flex-col gap-4">
            <div class="flex justify-center">
                <div class="flex gap-2" x-data="{
                    digits: {{ json_encode(str_split($otp ?? '')) }},
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
                {{ __('auth.Change Number') }}
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
                        <i class="hgi hgi-circle size-5 text-slate-300"></i>@if($role['icon'] == 'wrench-screwdriver')
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
                            <i class="hgi hgi-checkmark-circle-02 size-5 text-emerald-500"></i>
                        </div>
                    @endif
                </button>
            @endforeach

            @error('selectedRole')
                <p class="text-[var(--accent-red)] text-xs font-bold mt-1">{{ $message }}</p>
            @enderror

            <button type="button" wire:click="finish" class="lp-btn lp-btn--primary lp-btn--lg w-full mt-2" @disabled(!$selectedRole)>
                <span>{{ __('auth.Complete Registration') }}</span>
            </button>
        </div>
    @endif
</div>
