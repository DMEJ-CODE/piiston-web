<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Paramètres du profil') }}</flux:heading>

    <x-settings.layout :heading="__('Informations Personnelles')" :subheading="__('Mettez à jour vos identifiants et avatar')">
        <form wire:submit="updateProfileInformation" class="space-y-10">
            <!-- Form Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-10">
                <flux:field>
                    <flux:label class="text-[10px] font-black uppercase tracking-[2px] text-slate-400 mb-2 pl-1">{{ __('Nom complet') }}</flux:label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="hgi-stroke hgi-user text-sm text-slate-400 group-focus-within:text-[var(--active-2)] transition-colors"></i>
                        </div>
                        <input wire:model="name" type="text" required autofocus autocomplete="name"
                            class="w-full pl-11 pr-4 py-3.5 bg-slate-50/50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl text-[13px] font-bold text-slate-900 dark:text-white focus:ring-4 focus:ring-[var(--active-2)]/5 focus:border-[var(--active-2)] transition-all outline-none shadow-inner" />
                    </div>
                    <flux:error name="name" />
                </flux:field>

                <flux:field>
                    <flux:label class="text-[10px] font-black uppercase tracking-[2px] text-slate-400 mb-2 pl-1">{{ __('Adresse Email') }}</flux:label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="hgi-stroke hgi-mail-01 text-sm text-slate-400 group-focus-within:text-[var(--active-2)] transition-colors"></i>
                        </div>
                        <input wire:model="email" type="email" required autocomplete="email"
                            class="w-full pl-11 pr-4 py-3.5 bg-slate-50/50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl text-[13px] font-bold text-slate-900 dark:text-white focus:ring-4 focus:ring-[var(--active-2)]/5 focus:border-[var(--active-2)] transition-all outline-none shadow-inner" />
                    </div>
                    <flux:error name="email" />
                </flux:field>
            </div>

            @if ($this->hasUnverifiedEmail)
                <div class="p-6 rounded-[24px] bg-amber-500/5 border border-amber-500/10 flex items-center gap-5">
                    <div class="size-12 rounded-2xl bg-amber-500/10 text-amber-600 flex items-center justify-center shrink-0">
                        <i class="hgi-stroke hgi-alert-01 text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-xs font-black text-amber-700 uppercase tracking-tight">{{ __('Email non vérifié') }}</h4>
                        <p class="text-[10px] font-bold text-amber-600/80 uppercase mt-0.5 leading-relaxed">
                            {{ __('Veuillez confirmer votre adresse pour sécuriser votre compte.') }}
                        </p>
                    </div>
                    <flux:button variant="ghost" size="sm" class="rounded-xl font-black text-[9px] uppercase border border-amber-500/20 text-amber-700" wire:click.prevent="resendVerificationNotification">
                        {{ __('Renvoyer') }}
                    </flux:button>
                </div>
            @endif

            <div class="flex justify-end pt-4">
                <flux:button variant="primary" type="submit" class="rounded-2xl font-black uppercase text-[10px] bg-gradient-to-br from-[var(--active-2)] to-[var(--active)] border-none shadow-lg px-10 py-3.5 hover:scale-[1.02] active:scale-95 transition-all">
                    {{ __('Mettre à jour mon profil') }}
                </flux:button>
            </div>
        </form>

        @if ($this->showDeleteUser)
            <div class="mt-20 pt-10 border-t border-slate-50 dark:border-white/5">
                <livewire:settings.delete-user-form />
            </div>
        @endif
    </x-settings.layout>
</section>
