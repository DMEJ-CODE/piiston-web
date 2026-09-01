<x-layouts::app :title="__('Modifier la commande')">
    <flux:heading size="xl">Modifier la commande</flux:heading>
    <flux:text class="mt-2">Modifier la commande #{{ $purchaseOrder->id }}.</flux:text>

    <div class="mt-6">
        <flux:card>
            <form method="POST" action="{{ route('garage.purchase-orders.update', $purchaseOrder->id) }}">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <flux:label>Fournisseur *</flux:label>
                        <flux:select name="supplier_id">
                            <option value="">-- Sélectionner --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id', $purchaseOrder->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </flux:select>
                        @error('supplier_id')
                            <flux:text color="red">{{ $message }}</flux:text>
                        @enderror
                    </div>

                    <div>
                        <flux:label>Statut</flux:label>
                        <flux:select name="status">
                            <option value="draft" {{ old('status', $purchaseOrder->status) === 'draft' ? 'selected' : '' }}>Brouillon</option>
                            <option value="ordered" {{ old('status', $purchaseOrder->status) === 'ordered' ? 'selected' : '' }}>Commandé</option>
                            <option value="received" {{ old('status', $purchaseOrder->status) === 'received' ? 'selected' : '' }}>Reçu</option>
                            <option value="cancelled" {{ old('status', $purchaseOrder->status) === 'cancelled' ? 'selected' : '' }}>Annulé</option>
                        </flux:select>
                        @error('status')
                            <flux:text color="red">{{ $message }}</flux:text>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <flux:button type="submit" variant="primary">Mettre à jour</flux:button>
                        <flux:button href="{{ route('garage.purchase-orders.index') }}" variant="filled">Annuler</flux:button>
                        <flux:button href="{{ route('garage.purchase-orders.destroy', $purchaseOrder->id) }}" variant="danger" onclick="event.preventDefault(); document.getElementById('delete-po-form').submit();">Supprimer</flux:button>
                    </div>
                </div>
            </form>
            <form id="delete-po-form" method="POST" action="{{ route('garage.purchase-orders.destroy', $purchaseOrder->id) }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </flux:card>
    </div>
</x-layouts::app>
