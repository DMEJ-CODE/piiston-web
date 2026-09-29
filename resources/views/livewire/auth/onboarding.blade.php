<div class="flex flex-col gap-6">
    <div class="text-center">
        <h1 class="auth-card__title">
            @if($step == 1) Secure Your Account @elseif($step == 2) Verify Phone @else Choose Your Profile @endif
        </h1>
        <p class="auth-card__desc">
            @if($step == 1) Select your country and enter your mobile number. @elseif($step == 2) Enter the 6-digit code sent to {{ $country_code }} {{ $phone }} @else How will you use Piiston on the web? @endif
        </p>
    </div>

    @if($step == 1)
        <div class="flex flex-col gap-5">
            <div class="auth-input-group">
                <label class="auth-label">{{ __('auth.Country') }}</label>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" type="button" class="w-full flex items-center gap-3 p-3.5 bg-[var(--bg-primary)] border border-[var(--border-color)] rounded-2xl text-left hover:border-[var(--accent-active2)] transition-all">
                        <span class="text-xl">{{ $country_flag }}</span>
                        <span class="flex-1 font-bold text-sm text-[var(--text-main)]">
                            {{ collect($countries)->firstWhere('code', $country_code)['name'] }}
                        </span>
                        <span class="text-xs font-bold text-[var(--text-muted)]">{{ $country_code }}</span>
                        <i class="hgi hgi-arrow-down-01 hgi-sm text-[var(--text-subtle)]"></i>
                    </button>

                    <div x-show="open" @click.away="open = false" class="absolute z-50 w-full mt-2 bg-[var(--bg-glass)] backdrop-blur-xl border border-[var(--border-color)] rounded-2xl shadow-xl overflow-hidden py-1">
                        @foreach($countries as $c)
                            <button wire:click="setCountry('{{ $c['code'] }}', '{{ $c['flag'] }}')" @click="open = false" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-[var(--accent-active)]/10 transition-all text-left">
                                <span class="text-lg">{{ $c['flag'] }}</span>
                                <span class="flex-1 text-sm font-bold text-[var(--text-main)]">{{ $c['name'] }}</span>
                                <span class="text-xs text-[var(--text-muted)]">{{ $c['code'] }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="auth-input-group">
                <label for="phone" class="auth-label">{{ __('auth.Phone Number') }}</label>
                <div class="auth-input-wrap">
                    <i class="hgi hgi-call auth-input-icon"></i>
                    <input id="phone" name="phone" type="tel" value="{{ old('phone', $phone) }}" required placeholder="677 000 000" class="auth-input" wire:model.defer="phone">
                </div>
                @error('phone')
                    <p class="text-[var(--accent-red)] text-xs font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="button" wire:click="sendOtp" class="lp-btn lp-btn--primary lp-btn--lg w-full mt-2">
                <span>{{ __('auth.Send Verification Code') }}</span>
            </button>
        </div>

    @elseif($step == 2)
        <div class="flex flex-col gap-6">
            <label class="text-[13px] font-bold text-zinc-500 text-center uppercase tracking-wide">{{ __('auth.Verification Code') }}</label>
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
                            class="w-11 h-12 text-center text-lg font-bold border-2 border-[var(--border-color)] rounded-xl bg-[var(--bg-primary)] text-[var(--text-main)] focus:border-[var(--accent-active2)] focus:ring-4 focus:ring-[rgba(125,154,183,0.15)] outline-none transition-all"
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

            <button type="button" wire:click="verifyOtp" class="lp-btn lp-btn--primary lp-btn--lg w-full mt-2">
                <span>{{ __('auth.Verify & Continue') }}</span>
            </button>

            <button type="button" wire:click="$set('step', 1)" class="text-xs font-bold text-[var(--accent-active2)] hover:underline text-center">
                {{ __('auth.Change Number') }}
            </button>
        </div>

    @elseif($step == 3)
        <div class="flex flex-col gap-4">
            @foreach($roles as $role)
                <button
                    type="button"
                    wire:click="selectRole('{{ $role['id'] }}')"
                    class="group relative flex items-center gap-5 p-5 rounded-3xl border-2 transition-all text-left
                        {{ $selectedRole === $role['id']
                            ? 'bg-[var(--bg-secondary)] border-[var(--accent-active)] shadow-lg scale-[1.02]'
                            : 'bg-[var(--bg-tertiary)] border-transparent hover:bg-[var(--bg-card-hover)] hover:border-[var(--border-color)]' }}"
                >
                    <div class="p-3 rounded-2xl {{ $selectedRole === $role['id'] ? 'bg-[var(--accent-active)]/10 text-[var(--accent-active)]' : 'bg-[var(--bg-primary)] text-[var(--text-muted)]' }}">
                        <i class="hgi hgi-circle size-5 text-slate-300"></i>@if($role['icon'] == 'wrench-screwdriver')
                                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                            @elseif($role['icon'] == 'truck')
                                <path d="M10 17h4V5H2v12h3"/><path d="M20 17h2v-3.34a4 4 0 0 0-1.17-2.83L19 9h-5v8h1"/><circle cx="7.5" cy="17.5" r="2.5"/><circle cx="16.5" cy="17.5" r="2.5"/>
                            @else
                                <circle cx="12" cy="12" r="10"/>
                            @endif
                        </svg>
                    </div>

                    <div class="flex-1">
                        <h4 class="text-sm font-black {{ $selectedRole === $role['id'] ? 'text-[var(--text-main)]' : 'text-[var(--text-muted)]' }}">
                            {{ $role['title'] }}
                        </h4>
                        <p class="text-[11px] font-medium leading-tight {{ $selectedRole === $role['id'] ? 'text-[var(--text-muted)]' : 'text-[var(--text-subtle)]' }}">
                            {{ $role['desc'] }}
                        </p>
                    </div>

                    @if($selectedRole === $role['id'])
                        <div class="text-[var(--accent-active)]">
                            <i class="hgi hgi-checkmark-circle-02 size-5 text-emerald-500"></i>
                        </div>
                    @endif
                </button>
            @endforeach

            <button type="button" wire:click="finish" class="lp-btn lp-btn--primary lp-btn--lg w-full mt-6" @disabled(!$selectedRole)">
                <span>{{ __('auth.Finish Setup') }}</span>
            </button>
        </div>
    @endif
</div>
