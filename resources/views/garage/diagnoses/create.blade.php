<x-layouts::app :title="__('garage.Nouveau Diagnostic')">
    <div class="max-w-4xl mx-auto py-6">
        <div class="flex items-center justify-between mb-8">
            <div>
                <flux:heading size="xl" class="font-black uppercase tracking-tight text-zinc-900">Nouveau Diagnostic</flux:heading>
                <flux:text class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">Expertise technique approfondie - {{ $branch->name }}</flux:text>
            </div>
            <flux:button href="{{ route('garage.diagnoses.index') }}" variant="filled" class="font-black uppercase tracking-widest">Retour</flux:button>
        </div>

        <form method="POST" action="{{ route('garage.diagnoses.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                <!-- Main Form -->
                <div class="md:col-span-8 space-y-6">
                    <flux:card class="space-y-6">
                        <flux:field>
                            <flux:label>Ordre de Réparation lié *</flux:label>
                            <flux:select name="repair_order_id" required>
                                <option value="">Choisir une intervention en cours</option>
                                @foreach($repairs as $repair)
                                    <option value="{{ $repair->id }}">{{ $repair->vehicle->license_plate }} - {{ $repair->garageCustomer->user->name }} (RO #{{ $repair->id }})</option>
                                @endforeach
                            </flux:select>
                        </flux:field>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <flux:field>
                                <flux:label>Gravité estimée</flux:label>
                                <flux:select name="severity">
                                    <option value="low">Basse (Entretien)</option>
                                    <option value="medium" selected>Moyenne (Réparation standard)</option>
                                    <option value="high">Haute (Risque sécurité)</option>
                                    <option value="critical">Critique (Véhicule immobilisé)</option>
                                </flux:select>
                            </flux:field>
                        </div>

                        <flux:field>
                            <flux:label>Problème détecté (Diagnostic) *</flux:label>
                            <flux:textarea name="detected_problem" rows="3" placeholder="Ex: Fuite de liquide de refroidissement au niveau du joint de culasse..." required></flux:textarea>
                        </flux:field>

                        <flux:field>
                            <flux:label>Symptômes observés</flux:label>
                            <flux:textarea name="symptoms" rows="2" placeholder="Fumée blanche, surchauffe moteur..."></flux:textarea>
                        </flux:field>

                        <flux:field>
                            <flux:label>Solution recommandée</flux:label>
                            <flux:textarea name="solution" rows="3" placeholder="Remplacement du joint de culasse et surfaçage..."></flux:textarea>
                        </flux:field>
                    </flux:card>
                </div>

                <!-- Side Panel (AI & Media) -->
                <div class="md:col-span-4 space-y-6">
                    <div class="bg-blue-600 text-white p-6 rounded-[32px] shadow-2xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-10">
                            <flux:icon icon="cpu-chip" class="size-16" />
                        </div>
                        <h4 class="text-sm font-black uppercase tracking-tight mb-2">Assistant IA</h4>
                        <p class="text-[10px] text-blue-100 font-bold uppercase tracking-widest leading-relaxed">L'IA peut vous suggérer des causes basées sur les symptômes.</p>
                        <flux:button size="xs" variant="ghost" class="mt-4 w-full bg-white/20 border-none text-white font-black uppercase tracking-widest">Lancer l'analyse</flux:button>
                    </div>

                    <div class="bg-white dark:bg-white/5 p-6 rounded-[32px] border border-zinc-100 dark:border-white/5">
                        <h4 class="text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-4">Photos du Diagnostic</h4>
                        <div class="border-2 border-dashed border-zinc-100 dark:border-white/10 rounded-2xl p-8 text-center flex flex-col items-center gap-2">
                            <flux:icon icon="camera" class="size-6 text-zinc-300" />
                            <span class="text-[9px] font-bold text-zinc-400 uppercase">Ajouter des preuves</span>
                        </div>
                    </div>

                    <flux:button type="submit" variant="primary" class="w-full h-14 bg-gradient-to-br from-blue-600 to-indigo-700 text-white border-none font-black uppercase tracking-widest shadow-2xl shadow-blue-500/30">
                        Finaliser le Diagnostic
                    </flux:button>
                </div>
            </div>
        </form>
    </div>
</x-layouts::app>
