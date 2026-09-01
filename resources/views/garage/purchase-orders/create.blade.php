<x-layouts::app :title="__('Nouvelle commande fournisseur')">
    <flux:heading size="xl">Nouvelle commande fournisseur</flux:heading>
    <flux:text class="mt-2">Créer une commande pour {{ $branch->name }}.</flux:text>

    <div class="mt-6">
        <flux:card>
            <form method="POST" action="{{ route('garage.purchase-orders.store') }}">
                @csrf

                <div class="space-y-4">
                    <div>
                        <flux:label>Fournisseur *</flux:label>
                        <flux:select name="supplier_id">
                            <option value="">-- Sélectionner --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </flux:select>
                        @error('supplier_id')
                            <flux:text color="red">{{ $message }}</flux:text>
                        @enderror
                    </div>

                    <div>
                        <flux:label>Articles</flux:label>
                        <div id="items-container" class="space-y-2">
                            <div class="item-row grid grid-cols-12 gap-2">
                                <div class="col-span-5">
                                    <flux:select name="items[0][part_id]" class="w-full">
                                        <option value="">-- Pièce --</option>
                                        @foreach($parts as $part)
                                            <option value="{{ $part->id }}" {{ old("items.0.part_id") == $part->id ? 'selected' : '' }}>
                                                {{ $part->name }}
                                            </option>
                                        @endforeach
                                    </flux:select>
                                </div>
                                <div class="col-span-3">
                                    <flux:input type="number" name="items[0][quantity]" placeholder="Qté" value="{{ old('items.0.quantity', 1) }}" min="1" class="w-full" />
                                </div>
                                <div class="col-span-3">
                                    <flux:input type="number" name="items[0][unit_price]" placeholder="Prix unitaire" value="{{ old('items.0.unit_price') }}" min="0" step="0.01" class="w-full" />
                                </div>
                            </div>
                        </div>
                        @error('items')
                            <flux:text color="red">{{ $message }}</flux:text>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <flux:button type="submit" variant="primary">Créer la commande</flux:button>
                        <flux:button href="{{ route('garage.purchase-orders.index') }}" variant="filled">Annuler</flux:button>
                    </div>
                </div>
            </form>
        </flux:card>
    </div>
</x-layouts::app>
