<x-layouts::app :title="__('Nouveau Client')">
    <div class="max-w-4xl mx-auto py-6">
        <div class="flex items-center justify-between mb-8">
            <div>
                <flux:heading size="xl" class="font-black uppercase tracking-tight text-zinc-900">Enregistrer un Client</flux:heading>
                <flux:text class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">Ajout à la base de données de {{ $branch->name }}</flux:text>
            </div>
            <flux:button href="{{ route('garage.customers.index') }}" variant="filled" class="font-black uppercase tracking-widest px-6">Retour</flux:button>
        </div>

        <form method="POST" action="{{ route('garage.customers.store') }}">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Info Card -->
                <div class="lg:col-span-8 space-y-6">
                    <flux:card class="space-y-6 rounded-[32px] p-8 border-none shadow-2xl shadow-zinc-200/20">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <flux:field>
                                <flux:label>Prénom / Nom complet *</flux:label>
                                <flux:input name="name" placeholder="Ex: Jean Dupont" required class="rounded-xl h-12" />
                            </flux:field>
                            <flux:field>
                                <flux:label>Type de Client</flux:label>
                                <flux:select name="customer_type" class="rounded-xl h-12">
                                    <option value="INDIVIDUAL">👤 Particulier</option>
                                    <option value="BUSINESS">🏢 Entreprise / Flotte</option>
                                </flux:select>
                            </flux:field>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <flux:field>
                                <flux:label>Téléphone *</flux:label>
                                <flux:input name="phone" placeholder="+237 ..." required class="rounded-xl h-12" />
                            </flux:field>
                            <flux:field>
                                <flux:label>Email (Optionnel)</flux:label>
                                <flux:input type="email" name="email" placeholder="client@exemple.com" class="rounded-xl h-12" />
                            </flux:field>
                        </div>

                        <div class="pt-4">
                            <flux:field>
                                <flux:label>Notes internes / Historique</flux:label>
                                <flux:textarea name="notes" rows="4" placeholder="Observations particulières, remises négociées..." class="rounded-2xl"></flux:textarea>
                            </flux:field>
                        </div>
                    </flux:card>

                    <div class="flex justify-end gap-3">
                        <flux:button href="{{ route('garage.customers.index') }}" variant="filled" class="rounded-xl font-black uppercase tracking-widest px-8 h-12">Annuler</flux:button>
                        <flux:button type="submit" variant="primary" class="bg-gradient-to-br from-[var(--active-2)] to-[var(--active)] px-12 h-12 font-black uppercase tracking-widest border-none shadow-xl shadow-[var(--active)]/30 rounded-xl">Enregistrer le Client</flux:button>
                    </div>
                </div>

                <!-- Link Account Panel -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-zinc-900 text-white p-8 rounded-[40px] shadow-2xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-10">
                            <flux:icon icon="link" class="size-20" />
                        </div>
                        <h4 class="text-sm font-black uppercase tracking-tight mb-2 text-blue-400">Lier un compte Piiston</h4>
                        <p class="text-[10px] text-white/40 font-bold uppercase tracking-widest leading-loose">Connectez ce client à son compte utilisateur pour synchroniser ses véhicules et notifications.</p>

                        <div class="mt-8 space-y-4">
                            <flux:select name="user_id" class="bg-white/5 border-white/10 text-white rounded-xl h-12">
                                <option value="">-- Aucun compte lié --</option>
                            </flux:select>
                        </div>
                    </div>

                    <div class="bg-blue-50 dark:bg-blue-900/10 p-6 rounded-[32px] border border-blue-100 dark:border-blue-900/20">
                        <div class="flex gap-4">
                            <flux:icon icon="information-circle" class="size-5 text-blue-500 shrink-0" />
                            <div>
                                <h5 class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-1">Astuce Pro</h5>
                                <p class="text-[9px] text-blue-500 leading-relaxed font-bold uppercase">L'email du client permettra de lui envoyer ses factures et devis automatiquement par la plateforme.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layouts::app>
