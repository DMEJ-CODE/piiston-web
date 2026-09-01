<x-layouts::app :title="__('Nouvelle Pièce')">
    <div class="max-w-4xl mx-auto py-6">
        <div class="flex items-center justify-between mb-8">
            <div>
                <flux:heading size="xl" class="font-black uppercase tracking-tight text-zinc-900">Nouvelle Pièce</flux:heading>
                <flux:text class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">Ajouter un article au stock - {{ $branch->name }}</flux:text>
            </div>
            <flux:button href="{{ route('garage.inventory.index') }}" variant="filled" class="font-black uppercase tracking-widest">Retour</flux:button>
        </div>

        <form method="POST" action="{{ route('garage.inventory.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                <!-- Main Info -->
                <div class="md:col-span-8 space-y-6">
                    <flux:card class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <flux:field>
                                <flux:label>Nom de la pièce *</flux:label>
                                <flux:input name="name" value="{{ old('name') }}" placeholder="Ex: Plaquettes de frein Brembo" required />
                            </flux:field>
                            <flux:field>
                                <flux:label>Référence (SKU)</flux:label>
                                <flux:input name="part_number" value="{{ old('part_number') }}" placeholder="Ex: BR-4509-X" />
                            </flux:field>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <flux:field>
                                <flux:label>Catégorie</flux:label>
                                <flux:select name="category">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach(['FREINAGE', 'MOTEUR', 'FILTRATION', 'ÉLECTRIQUE', 'SUSPENSION', 'LUBRIFIANT', 'PNEUMATIQUE'] as $cat)
                                        <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </flux:select>
                            </flux:field>
                            <flux:field>
                                <flux:label>Marque / Fabricant</flux:label>
                                <flux:input name="manufacturer" value="{{ old('manufacturer') }}" placeholder="Ex: Bosch" />
                            </flux:field>
                        </div>

                        <flux:field>
                            <flux:label>Description / Compatibilité véhicules</flux:label>
                            <flux:textarea name="description" rows="3" placeholder="Décrivez les modèles compatibles ou caractéristiques techniques...">{{ old('description') }}</flux:textarea>
                        </flux:field>
                    </flux:card>

                    <div class="flex justify-end gap-3 pt-4">
                        <flux:button href="{{ route('garage.inventory.index') }}" variant="filled">Annuler</flux:button>
                        <flux:button type="submit" variant="primary" class="bg-gradient-to-br from-[var(--active-2)] to-[var(--active)] px-10 font-black uppercase tracking-widest border-none">Enregistrer la pièce</flux:button>
                    </div>
                </div>

                <!-- Stock & Pricing -->
                <div class="md:col-span-4 space-y-6">
                    <div class="bg-zinc-100 dark:bg-white/5 p-6 rounded-[32px] border border-zinc-200 dark:border-white/10 space-y-6">
                        <h4 class="text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-2">Gestion du Stock</h4>

                        <flux:field>
                            <flux:label>Prix de vente (F) *</flux:label>
                            <flux:input type="number" name="selling_price" value="{{ old('selling_price') }}" placeholder="0" required />
                        </flux:field>

                        <div class="h-px bg-zinc-200 dark:bg-white/10"></div>

                        <flux:field>
                            <flux:label>Quantité initiale</flux:label>
                            <flux:input type="number" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Seuil d'alerte (Min)</flux:label>
                            <flux:input type="number" name="minimum_stock" value="{{ old('minimum_stock', 2) }}" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Emplacement</flux:label>
                            <flux:input name="storage_location" value="{{ old('storage_location') }}" placeholder="Ex: Étagère A1" />
                        </flux:field>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layouts::app>
