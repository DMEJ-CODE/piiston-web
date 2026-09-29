<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('settings.Security settings') }}</flux:heading>

    <x-settings.layout :heading="__('settings.Security & Password')" :subheading="__('settings.Manage your access and strong authentication')">

        <!-- Password Section -->
        <div class="mb-16">
            <h4 class="text-[11px] font-black uppercase tracking-[2px] text-slate-400 mb-8">{{ __('settings.Update password') }}</h4>
            <form method="POST" wire:submit="updatePassword" class="space-y-10">
                <flux:field>
                    <flux:label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">{{ __('settings.Current password') }}</flux:label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="hgi-stroke hgi-key-01 text-sm text-slate-400 group-focus-within:text-orange-500 transition-colors"></i>
                        </div>
                        <input wire:model="current_password" type="password" required autocomplete="current-password"
                            class="w-full pl-11 pr-4 py-3.5 bg-slate-50/50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl text-[13px] font-bold text-slate-900 dark:text-white focus:ring-4 focus:ring-orange-500/5 focus:border-orange-500 transition-all outline-none shadow-inner" />
                    </div>
                    <flux:error name="current_password" />
                </flux:field>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <flux:field>
                        <flux:label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">{{ __('settings.New password') }}</flux:label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="hgi-stroke hgi-shield-01 text-sm text-slate-400 group-focus-within:text-emerald-500 transition-colors"></i>
                            </div>
                            <input wire:model="password" type="password" required autocomplete="new-password"
                                class="w-full pl-11 pr-4 py-3.5 bg-slate-50/50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl text-[13px] font-bold text-slate-900 dark:text-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all outline-none shadow-inner" />
                        </div>
                        <flux:error name="password" />
                    </flux:field>

                    <flux:field>
                        <flux:label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">{{ __('settings.Confirm password') }}</flux:label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="hgi-stroke hgi-tick-02 text-sm text-slate-400 group-focus-within:text-emerald-500 transition-colors"></i>
                            </div>
                            <input wire:model="password_confirmation" type="password" required autocomplete="new-password"
                                class="w-full pl-11 pr-4 py-3.5 bg-slate-50/50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl text-[13px] font-bold text-slate-900 dark:text-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all outline-none shadow-inner" />
                        </div>
                        <flux:error name="password_confirmation" />
                    </flux:field>
                </div>

                <div class="flex justify-end">
                    <flux:button variant="primary" type="submit" class="rounded-2xl font-black uppercase text-[10px] bg-gradient-to-br from-orange-600 to-orange-400 border-none shadow-lg px-10 py-3.5 hover:scale-[1.02] active:scale-95 transition-all">
                        {{ __('settings.Update password') }}
                    </flux:button>
                </div>
            </form>
        </div>

        @if ($canManageTwoFactor)
            <div class="mt-16 pt-10 border-t border-slate-50 dark:border-white/5">
                <h4 class="text-[11px] font-black uppercase tracking-[2px] text-slate-400 mb-8">{{ __('settings.Two-factor authentication') }}</h4>

                <div @class([
                    'p-8 rounded-[32px] border transition-all',
                    'bg-emerald-500/5 border-emerald-500/10' => $twoFactorEnabled,
                    'bg-slate-50/50 dark:bg-white/[0.01] border-slate-100 dark:border-white/5' => !$twoFactorEnabled
                ])>
                    <div class="flex flex-col md:flex-row items-center gap-8">
                        <div @class([
                            'size-20 rounded-[28px] flex items-center justify-center shrink-0 shadow-lg',
                            'bg-emerald-500 text-white' => $twoFactorEnabled,
                            'bg-slate-900 text-white' => !$twoFactorEnabled
                        ])>
                            <i @class(['hgi-stroke text-3xl', 'hgi-shield-check' => $twoFactorEnabled, 'hgi-shield-01' => !$twoFactorEnabled])></i>
                        </div>

                        <div class="flex-1 text-center md:text-left">
                            <h5 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-tight mb-2">
                                {{ $twoFactorEnabled ? __('settings.2FA Enabled') : __('settings.2FA Disabled') }}
                            </h5>
                            <p class="text-xs text-slate-500 font-medium leading-relaxed max-w-lg">
                                {{ __('settings.Strong authentication adds an extra verification step at login to guarantee that only you can access your account.') }}
                            </p>
                        </div>

                        @if ($twoFactorEnabled)
                            <flux:button variant="danger" wire:click="disable" class="rounded-2xl font-black uppercase text-[10px] px-8 py-3 shadow-md">
                                {{ __('settings.Disable 2FA') }}
                            </flux:button>
                        @else
                            <flux:button variant="primary" wire:click="enable" class="rounded-2xl font-black uppercase text-[10px] bg-slate-900 dark:bg-white dark:text-slate-900 border-none px-10 py-3.5 shadow-xl hover:scale-105 transition-all">
                                {{ __('settings.Enable 2FA') }}
                            </flux:button>
                        @endif
                    </div>

                    @if ($twoFactorEnabled)
                        <div class="mt-10 pt-8 border-t border-emerald-500/10">
                            <livewire:settings.two-factor.recovery-codes :$requiresConfirmation />
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @if ($canManagePasskeys)
            <div class="mt-16 pt-10 border-t border-slate-50 dark:border-white/5">
                <h4 class="text-[11px] font-black uppercase tracking-[2px] text-slate-400 mb-8">{{ __('settings.Passkeys') }}</h4>

                <div class="space-y-4">
                    @forelse ($passkeys as $passkey)
                        <div class="flex items-center justify-between p-5 rounded-[24px] bg-white dark:bg-slate-900 border border-slate-100 dark:border-white/5 shadow-sm group hover:border-[var(--active-2)] transition-all">
                            <div class="flex items-center gap-5">
                                <div class="size-12 rounded-2xl bg-blue-50 dark:bg-blue-500/10 text-blue-600 flex items-center justify-center">
                                    <i class="hgi-stroke hgi-fingerprint text-2xl"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ $passkey['name'] }}</span>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ __('settings.Added :time', ['time' => $passkey['created_at_diff']]) }}</span>
                                        @if ($passkey['authenticator'])
                                            <span class="size-1 rounded-full bg-slate-200"></span>
                                            <span class="text-[9px] font-black text-blue-500 uppercase">{{ $passkey['authenticator'] }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <flux:button variant="ghost" size="xs" icon="trash" wire:click="confirmDelete({{ $passkey['id'] }})" class="text-red-500 opacity-0 group-hover:opacity-100 transition-all rounded-xl" />
                        </div>
                    @empty
                        <div class="p-16 rounded-[32px] border border-dashed border-slate-200 dark:border-slate-800 text-center flex flex-col items-center gap-4">
                            <div class="size-16 rounded-full bg-slate-50 dark:bg-white/5 flex items-center justify-center">
                                <i class="hgi-stroke hgi-face-id text-3xl text-slate-300"></i>
                            </div>
                            <div class="flex flex-col gap-1">
                                <p class="text-xs font-black text-slate-700 dark:text-slate-300 uppercase tracking-widest">{{ __('settings.No passkeys yet') }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase">{{ __('settings.Sign in with your fingerprint or Face ID') }}</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="mt-6 flex justify-center">
                    <x-passkey-registration />
                </div>
            </div>
        @endif
    </x-settings.layout>

    <!-- Modal 2FA -->
    @if ($canManageTwoFactor)
        <flux:modal wire:model="showModal" @close="closeModal" class="rounded-[40px] p-0 overflow-hidden" variant="large">
            <div class="relative p-10">
                <div class="flex flex-col items-center text-center mb-10">
                    <div class="size-20 rounded-3xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-xl shadow-blue-500/20 mb-6">
                        <i class="hgi-stroke hgi-qr-code text-white text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-black uppercase text-slate-900 dark:text-white tracking-tight">{{ $this->modalConfig['title'] }}</h3>
                    <p class="text-[11px] text-slate-500 font-bold uppercase tracking-widest mt-2 max-w-sm">{{ $this->modalConfig['description'] }}</p>
                </div>

                @if ($showVerificationStep)
                    <div class="space-y-10">
                        <div class="flex justify-center" x-data x-init="$nextTick(() => $el.querySelector('input')?.focus())">
                            <flux:otp name="code" wire:model="code" length="6" />
                        </div>

                        <div class="flex gap-4">
                            <flux:button variant="ghost" class="flex-1 !rounded-2xl !py-4 font-black uppercase text-[11px] border border-slate-200" wire:click="resetVerification">{{ __('settings.Back') }}</flux:button>
                            <flux:button variant="primary" class="flex-1 !rounded-2xl !py-4 font-black uppercase text-[11px] bg-slate-900 text-white border-none shadow-lg" wire:click="confirmTwoFactor" x-bind:disabled="$wire.code.length < 6">{{ __('settings.Confirm') }}</flux:button>
                        </div>
                    </div>
                @else
                    <div class="flex flex-col gap-10">
                        <div class="flex justify-center">
                            <div class="p-6 rounded-[32px] bg-white shadow-2xl border border-slate-100 ring-8 ring-slate-50">
                                @empty($qrCodeSvg)
                                    <div class="size-48 flex items-center justify-center"><flux:icon.loading class="animate-spin text-slate-200" /></div>
                                @else
                                    <div :style="($flux.appearance === 'dark' || ($flux.appearance === 'system' && $flux.dark)) ? 'filter: invert(1) brightness(1.2)' : ''">
                                        {!! $qrCodeSvg !!}
                                    </div>
                                @endempty
                            </div>
                        </div>

                        <flux:button variant="primary" class="w-full !rounded-2xl !py-4 font-black uppercase text-[11px] bg-slate-900 text-white border-none shadow-xl hover:scale-[1.02] active:scale-95 transition-all" wire:click="showVerificationIfNecessary" :disabled="$errors->has('setupData')">
                            {{ $this->modalConfig['buttonText'] }}
                        </flux:button>

                        <div class="flex flex-col gap-3">
                            <span class="text-[9px] font-black text-slate-400 uppercase text-center tracking-[2px]">{{ __('settings.Or use a manual code') }}</span>
                            <div class="flex items-center gap-3 p-4 rounded-2xl bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5" x-data="{ copied: false, async copy() { await navigator.clipboard.writeText('{{ $manualSetupKey }}'); this.copied = true; setTimeout(() => this.copied = false, 2000); } }">
                                <code class="flex-1 text-xs font-mono font-black text-slate-600 dark:text-slate-400 tracking-wider">{{ $manualSetupKey }}</code>
                                <button @click="copy()" class="p-2 rounded-xl hover:bg-white dark:hover:bg-slate-800 shadow-sm transition-all">
                                    <i class="hgi-stroke hgi-copy-01 text-slate-500" x-show="!copied"></i>
                                    <i class="hgi-stroke hgi-tick-02 text-green-500" x-show="copied"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </flux:modal>
    @endif
</x-settings.layout>
