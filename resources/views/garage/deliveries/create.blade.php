<x-layouts::app :title="__('garage.Livraison Véhicule')">
    <div class="max-w-4xl mx-auto py-6">
        <div class="flex items-center justify-between mb-8">
            <div>
                <flux:heading size="xl" class="font-black uppercase tracking-tight text-zinc-900">Livraison Véhicule</flux:heading>
                <flux:text class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">Validation finale et remise des clés - RO #{{ $repair->id }}</flux:text>
            </div>
            <span class="px-4 py-1.5 rounded-full bg-emerald-500/10 text-emerald-600 text-[10px] font-black uppercase tracking-widest border border-emerald-500/20">Prêt pour Livraison</span>
        </div>

        <form method="POST" action="{{ route('garage.repairs.delivery.store', $repair->id) }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <!-- Left Info Card -->
                <div class="md:col-span-4 space-y-6">
                    <flux:card class="bg-zinc-900 text-white border-none overflow-hidden relative">
                        <div class="absolute top-0 right-0 p-4 opacity-10">
                            <flux:icon icon="truck" class="size-16" />
                        </div>
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-white/40 mb-4">Véhicule</h3>
                        <div class="space-y-1">
                            <p class="text-lg font-black tracking-tighter">{{ $repair->vehicle->brand->name }} {{ $repair->vehicle->model->name }}</p>
                            <p class="text-xs font-bold text-blue-400 uppercase tracking-widest">{{ $repair->vehicle->license_plate }}</p>
                        </div>
                        <div class="mt-8 pt-6 border-t border-white/10 space-y-4">
                            <div>
                                <span class="text-[9px] font-black text-white/30 uppercase tracking-widest">Client</span>
                                <p class="text-sm font-bold">{{ $repair->garageCustomer->user->name }}</p>
                            </div>
                            <div>
                                <span class="text-[9px] font-black text-white/30 uppercase tracking-widest">Kilométrage Entrée</span>
                                <p class="text-sm font-bold">{{ $repair->checkIn->mileage ?? 'N/A' }} KM</p>
                            </div>
                        </div>
                    </flux:card>

                    <div class="bg-zinc-100 dark:bg-white/5 p-6 rounded-3xl border border-zinc-200 dark:border-white/10">
                        <h4 class="text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-4">Checklist de sortie</h4>
                        <div class="space-y-3">
                            @foreach(['Nettoyage effectué', 'Niveaux vérifiés', 'Pièces remplacées prêtes', 'Documents à jour'] as $item)
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="checklist[]" value="{{ $item }}" class="rounded text-emerald-500">
                                <span class="text-xs font-bold text-zinc-600 dark:text-zinc-300 uppercase tracking-tight">{{ $item }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right Form Card -->
                <div class="md:col-span-8">
                    <flux:card class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <flux:field>
                                <flux:label>Kilométrage à la livraison *</flux:label>
                                <flux:input type="number" name="mileage_at_delivery" value="{{ old('mileage_at_delivery', ($repair->checkIn->mileage ?? 0) + 1) }}" required />
                            </flux:field>

                            <flux:field>
                                <flux:label>Date de remise</flux:label>
                                <flux:input type="datetime-local" name="delivery_date" value="{{ now()->format('Y-m-d\TH:i') }}" />
                            </flux:field>
                        </div>

                        <flux:field>
                            <flux:label>Notes de livraison / Observations client</flux:label>
                            <flux:textarea name="notes" placeholder="Le client a-t-il des remarques particulières ?" rows="4"></flux:textarea>
                        </flux:field>

                        <div class="pt-6 border-t border-zinc-100 dark:border-white/5">
                            <flux:label class="mb-4 text-center block text-[10px] font-black uppercase tracking-widest text-zinc-400">Signature de Remise (Optionnel)</flux:label>
                            <div class="bg-zinc-50 dark:bg-white/5 rounded-2xl border-2 border-dashed border-zinc-200 dark:border-white/10 h-40 flex items-center justify-center">
                                <span class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Zone de signature client</span>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-6">
                            <flux:button href="{{ route('garage.repairs.show', $repair->id) }}" variant="filled">Annuler</flux:button>
                            <flux:button type="submit" variant="primary" class="bg-emerald-600 hover:bg-emerald-700 px-10 font-black uppercase tracking-widest">Valider la Livraison</flux:button>
                        </div>
                    </flux:card>
                </div>
            </div>
        </form>
    </div>
</x-layouts::app>
