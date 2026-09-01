<x-layouts::auth :title="__('Two-factor authentication')">
    <div class="flex flex-col gap-4">
        <div
            class="relative w-full h-auto"
            x-cloak
            x-data="{
                showRecoveryInput: @js($errors->has('recovery_code')),
                code: '',
                recovery_code: '',
                focusOtp() {
                    this.$nextTick(() => this.$refs.otp?.querySelector('input')?.focus());
                },
                init() {
                    if (! this.showRecoveryInput) {
                        this.focusOtp();
                    }
                },
                toggleInput() {
                    this.showRecoveryInput = !this.showRecoveryInput;

                    this.code = '';
                    this.recovery_code = '';

                    $nextTick(() => {
                        this.showRecoveryInput
                            ? this.$refs.recovery_code?.focus()
                            : this.focusOtp();
                    });
                },
            }"
        >
            <div x-show="!showRecoveryInput">
                <x-auth-header
                    :title="__('Authentication code')"
                    :description="__('Enter the authentication code provided by your authenticator application.')"
                />
            </div>

            <div x-show="showRecoveryInput">
                <x-auth-header
                    :title="__('Recovery code')"
                    :description="__('Please confirm access to your account by entering one of your emergency recovery codes.')"
                />
            </div>

            <form method="POST" action="{{ route('two-factor.login.store') }}" class="flex flex-col gap-4">
                @csrf

                <div x-show="!showRecoveryInput" class="flex flex-col gap-3">
                    <label class="text-[13px] font-bold text-zinc-500 text-center uppercase tracking-wide">{{ __('Authentication code') }}</label>
                    <div class="flex justify-center">
                        <div class="flex gap-2" x-ref="otp" x-data="{
                            digits: [],
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
                                    name="code[]"
                                    inputmode="numeric"
                                    autocomplete="one-time-code"
                                    maxlength="1"
                                    class="w-11 h-12 text-center text-lg font-bold border-2 border-[var(--border-color)] rounded-xl bg-[var(--bg-primary)] text-[var(--text-main)] focus:border-[var(--accent-active2)] focus:ring-4 focus:ring-[rgba(125,154,183,0.15)] outline-none transition-all"
                                    :value="digits[{{ $i }}] || ''"
                                    x-on:input="
                                        const val = $event.target.value.replace(/[^0-9]/g, '');
                                        $event.target.value = val.slice(0, 1);
                                        digits[{{ $i }}] = val.slice(0, 1);
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
                                            const inputs = $event.target.parentElement.querySelectorAll('input');
                                            inputs.forEach((inp, i) => { inp.value = clean[i] || ''; });
                                            if (clean.length === 6 && inputs[5]) inputs[5].focus();
                                        }
                                    "
                                >
                            @endfor
                        </div>
                    </div>
                    @error('code')
                        <p class="text-center text-[var(--accent-red)] text-xs font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div x-show="showRecoveryInput">
                    <div class="auth-input-group">
                        <label for="recovery_code" class="auth-label">{{ __('Recovery code') }}</label>
                        <div class="auth-input-wrap">
                            <svg class="icon-svg auth-input-icon" viewBox="0 0 24 24"><path d="M21 2l-2 2m-2 2l-2 2m2-2l2 2m-4 4l-4 4M3 11a8 8 0 1 0 16 0 8 8 0 0 0-16 0z"/></svg>
                            <input id="recovery_code" name="recovery_code" type="text" x-ref="recovery_code" autocomplete="one-time-code" placeholder="Recovery code" class="auth-input" x-model="recovery_code">
                        </div>
                        @error('recovery_code')
                            <p class="text-[var(--accent-red)] text-xs font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="lp-btn lp-btn--primary lp-btn--lg w-full mt-2">
                    <span>{{ __('Continue') }}</span>
                </button>
            </form>

            <div class="auth-footer-text">
                <span class="opacity-50">{{ __('or you can') }}</span>
                <button type="button" @click="toggleInput()" class="font-bold underline hover:text-[var(--accent-active2)] text-center w-full">
                    <span x-show="!showRecoveryInput">{{ __('login using a recovery code') }}</span>
                    <span x-show="showRecoveryInput">{{ __('login using an authentication code') }}</span>
                </button>
            </div>
        </div>
    </div>
</x-layouts::auth>
