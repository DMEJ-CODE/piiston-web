<x-layouts::app :title="__('Nouveau Devis')">
    <div class="max-w-4xl mx-auto py-6">
        <div class="flex items-center justify-between mb-8">
            <div>
                <flux:heading size="xl" class="font-black uppercase tracking-tight text-zinc-900">Créer un Devis</flux:heading>
                <flux:text class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">Proposition commerciale professionnelle - {{ $branch->name }}</flux:text>
            </div>
            <flux:button href="{{ route('garage.estimates.index') }}" variant="filled" class="font-black uppercase tracking-widest">Retour</flux:button>
        </div>

        <form method="POST" action="{{ route('garage.estimates.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                <!-- Left: Repair Info -->
                <div class="md:col-span-8 space-y-6">
                    <flux:card class="space-y-6">
                        <flux:field>
                            <flux:label>Ordre de Réparation lié *</flux:label>
                            <flux:select name="repair_order_id" required>
                                <option value="">-- Sélectionner une intervention --</option>
                                @foreach($repairs as $repair)
                                    <option value="{{ $repair->id }}">{{ $repair->vehicle->license_plate }} - {{ $repair->garageCustomer->user->name }} (RO #{{ $repair->id }})</option>
                                @endforeach
                            </flux:select>
                            <p class="text-[9px] text-zinc-400 font-bold uppercase mt-2">Seules les interventions au statut "DIAGNOSIS" sont affichées.</p>
                        </flux:field>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <flux:field>
                                <flux:label>Coût Main d'œuvre (F) *</flux:label>
                                <flux:input type="number" name="labor_cost" placeholder="0" required />
                            </flux:field>
                            <flux:field>
                                <flux:label>Coût Pièces (F) *</flux:label>
                                <flux:input type="number" name="parts_cost" placeholder="0" required />
                            </flux:field>
                        </div>

                        <div class="pt-6 border-t border-zinc-100 dark:border-white/5">
                            <flux:field>
                                <flux:label>Notes au client / Conditions</flux:label>
                                <flux:textarea name="notes" rows="4" placeholder="Détails sur les travaux, garantie sur pièces, etc."></flux:textarea>
                            </flux:field>
                        </div>
                    </flux:card>
                </div>

                <!-- Right: Summary & Validation -->
                <div class="md:col-span-4 space-y-6">
                    <div class="bg-zinc-900 text-white p-8 rounded-[32px] shadow-2xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-10">
                            <flux:icon icon="calculator" class="size-20" />
                        </div>
                        <h4 class="text-[10px] font-black uppercase tracking-[3px] text-white/40 mb-6">Total Estimé</h4>

                        <flux:field>
                            <flux:label class="text-white/60">Montant Total Net (F)</flux:label>
                            <flux:input type="number" name="total_amount" class="bg-white/5 border-white/10 text-white font-black text-2xl" placeholder="0" required />
                        </flux:field>

                        <div class="mt-8 space-y-4">
                            <flux:field>
                                <flux:label class="text-white/60">Valide jusqu'au</flux:label>
                                <flux:input type="date" name="valid_until" class="bg-white/5 border-white/10 text-white" value="{{ now()->addDays(15)->format('Y-m-d') }}" required />
                            </flux:field>
                        </div>
                    </div>

                    <flux:button type="submit" variant="primary" class="w-full h-14 bg-gradient-to-br from-[var(--active-2)] to-[var(--active)] text-white border-none font-black uppercase tracking-widest shadow-2xl shadow-[var(--active)]/30">
                        Générer le Devis
                    </flux:button>
                </div>
            </div>
        </form>
    </div>
</x-layouts::app>
