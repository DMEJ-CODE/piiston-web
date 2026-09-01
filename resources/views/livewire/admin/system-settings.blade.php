<div class="flex flex-col gap-6 pb-8">
    <x-admin.index-header
        title="Paramètres Système"
        subtitle="Configuration globale de la plateforme"
    />

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 lg:col-span-8">
            <div class="bg-[var(--surface)] p-6 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm">
                <form wire:submit="saveSettings" class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <flux:field>
                            <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Nom de la Plateforme</flux:label>
                            <flux:input type="text" wire:model="form.platform_name" placeholder="Piiston" class="rounded-xl border-zinc-100 dark:border-white/5" />
                        </flux:field>

                        <flux:field>
                            <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Email de Support</flux:label>
                            <flux:input type="email" wire:model="form.support_email" placeholder="support@piiston.com" class="rounded-xl border-zinc-100 dark:border-white/5" />
                        </flux:field>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <flux:field>
                            <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Mise à jour des CGU</flux:label>
                            <flux:input type="datetime-local" wire:model="form.terms_updated_at" class="rounded-xl border-zinc-100 dark:border-white/5" />
                        </flux:field>

                        <flux:field>
                            <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Mise à jour Confidentialité</flux:label>
                            <flux:input type="datetime-local" wire:model="form.privacy_updated_at" class="rounded-xl border-zinc-100 dark:border-white/5" />
                        </flux:field>
                    </div>

                    <div class="p-6 rounded-2xl bg-zinc-900 dark:bg-zinc-800 text-white shadow-xl border border-white/5">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <div class="size-10 rounded-xl bg-yellow-500/20 text-yellow-500 flex items-center justify-center">
                                    <i class="hgi-stroke hgi-construction text-xl"></i>
                                </div>
                                <div>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-white/50">Maintenance</span>
                                    <h4 class="text-sm font-black uppercase tracking-tight text-white">Mode Maintenance</h4>
                                </div>
                            </div>
                            <flux:switch wire:model="form.maintenance_mode" />
                        </div>

                        <flux:field>
                            <flux:label class="text-[9px] font-black uppercase tracking-[2px] text-white/30 mb-2">Message de maintenance</flux:label>
                            <flux:textarea wire:model="form.maintenance_message" placeholder="La plateforme est actuellement en maintenance..." rows="3" class="bg-white/5 border-white/10 text-white rounded-xl placeholder:text-white/20" />
                        </flux:field>
                    </div>

                    <div class="flex justify-end gap-3 pt-6 border-t border-zinc-50 dark:border-white/5">
                        <flux:button type="submit" variant="primary" class="rounded-xl font-black uppercase text-[10px] bg-gradient-to-br from-blue-600 to-blue-500 border-none shadow-lg px-8 py-2.5">
                            Enregistrer les Paramètres
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-4 space-y-6">
            <div class="bg-[var(--surface)] p-6 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm">
                <h5 class="text-[9px] font-black text-zinc-400 uppercase tracking-widest mb-4 px-1">Informations Serveur</h5>

                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 rounded-xl bg-zinc-50/50 dark:bg-white/[0.02]">
                        <span class="text-[9px] font-black text-zinc-500 uppercase">Version PHP</span>
                        <span class="text-[10px] font-bold text-zinc-900 dark:text-white">8.4.1</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-zinc-50/50 dark:bg-white/[0.02]">
                        <span class="text-[9px] font-black text-zinc-500 uppercase">Version Laravel</span>
                        <span class="text-[10px] font-bold text-zinc-900 dark:text-white">13.1.0</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-zinc-50/50 dark:bg-white/[0.02]">
                        <span class="text-[9px] font-black text-zinc-500 uppercase">Environnement</span>
                        <span class="text-[9px] font-black px-2 py-0.5 rounded bg-blue-100 text-blue-600 uppercase">Production</span>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-indigo-600 to-blue-700 p-6 rounded-3xl text-white shadow-xl relative overflow-hidden">
                <div class="relative z-10">
                    <i class="hgi-stroke hgi-information-circle text-2xl text-white/40 mb-3"></i>
                    <h4 class="text-sm font-black uppercase tracking-tight mb-2">Besoin d'aide ?</h4>
                    <p class="text-[9px] text-white/70 font-bold uppercase tracking-widest leading-relaxed">
                        Consultez la documentation technique pour configurer les intégrations tierces et les clés API.
                    </p>
                    <flux:button variant="ghost" class="mt-4 !rounded-xl !bg-white/10 !text-white !border-none !text-[9px] !font-black !uppercase !tracking-widest hover:!bg-white/20 w-full">
                        Documentation
                    </flux:button>
                </div>
                <!-- Decorative Circle -->
                <div class="absolute -right-10 -top-10 size-32 bg-white/10 rounded-full blur-2xl"></div>
            </div>
        </div>
    </div>
</div>
