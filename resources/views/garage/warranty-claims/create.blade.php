<x-layouts::app :title="__('Nouvelle réclamation garantie')">
    <flux:heading size="xl">Nouvelle réclamation garantie</flux:heading>
    <flux:text class="mt-2">Créer une réclamation pour {{ $branch->name }}.</flux:text>

    <div class="mt-6">
        <flux:card>
            <form method="POST" action="{{ route('garage.warranty-claims.store') }}">
                @csrf

                <div class="space-y-4">
                    <div>
                        <flux:label>Réparation *</flux:label>
                        <flux:select name="repair_order_id">
                            <option value="">-- Sélectionner --</option>
                            @foreach($repairs as $repair)
                                <option value="{{ $repair->id }}" {{ old('repair_order_id') == $repair->id ? 'selected' : '' }}>
                                    #{{ $repair->id }} - {{ $repair->vehicle->license_plate ?? 'N/A' }} ({{ $repair->garageCustomer->user->name ?? 'N/A' }})
                                </option>
                            @endforeach
                        </flux:select>
                        @error('repair_order_id')
                            <flux:text color="red">{{ $message }}</flux:text>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <flux:label>Fournisseur garantie</flux:label>
                            <flux:input name="warranty_provider" value="{{ old('warranty_provider') }}" />
                            @error('warranty_provider')
                                <flux:text color="red">{{ $message }}</flux:text>
                            @enderror
                        </div>
                        <div>
                            <flux:label>Référence garantie</flux:label>
                            <flux:input name="warranty_reference" value="{{ old('warranty_reference') }}" />
                            @error('warranty_reference')
                                <flux:text color="red">{{ $message }}</flux:text>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <flux:label>Date expiration garantie</flux:label>
                        <flux:input type="date" name="warranty_expiry_date" value="{{ old('warranty_expiry_date') }}" />
                        @error('warranty_expiry_date')
                            <flux:text color="red">{{ $message }}</flux:text>
                        @enderror
                    </div>

                    <div>
                        <flux:label>Description de la réclamation *</flux:label>
                        <flux:textarea name="claim_description" rows="4">{{ old('claim_description') }}</flux:textarea>
                        @error('claim_description')
                            <flux:text color="red">{{ $message }}</flux:text>
                        @enderror
                    </div>

                    <div>
                        <flux:label>Montant réclamé (FCFA)</flux:label>
                        <flux:input type="number" name="claim_amount" value="{{ old('claim_amount') }}" min="0" step="0.01" />
                        @error('claim_amount')
                            <flux:text color="red">{{ $message }}</flux:text>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <flux:button type="submit" variant="primary">Créer la réclamation</flux:button>
                        <flux:button href="{{ route('garage.warranty-claims.index') }}" variant="filled">Annuler</flux:button>
                    </div>
                </div>
            </form>
        </flux:card>
    </div>
</x-layouts::app>
