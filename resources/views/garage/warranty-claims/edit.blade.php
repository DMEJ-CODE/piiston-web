<x-layouts::app :title="__('Modifier la réclamation')">
    <flux:heading size="xl">Modifier la réclamation</flux:heading>
    <flux:text class="mt-2">Modifier la réclamation #{{ $claim->id }}.</flux:text>

    <div class="mt-6">
        <flux:card>
            <form method="POST" action="{{ route('garage.warranty-claims.update', $claim->id) }}">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <flux:label>Réparation *</flux:label>
                        <flux:select name="repair_order_id">
                            <option value="">-- Sélectionner --</option>
                            @foreach($repairs as $repair)
                                <option value="{{ $repair->id }}" {{ old('repair_order_id', $claim->repair_order_id) == $repair->id ? 'selected' : '' }}>
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
                            <flux:input name="warranty_provider" value="{{ old('warranty_provider', $claim->warranty_provider) }}" />
                            @error('warranty_provider')
                                <flux:text color="red">{{ $message }}</flux:text>
                            @enderror
                        </div>
                        <div>
                            <flux:label>Référence garantie</flux:label>
                            <flux:input name="warranty_reference" value="{{ old('warranty_reference', $claim->warranty_reference) }}" />
                            @error('warranty_reference')
                                <flux:text color="red">{{ $message }}</flux:text>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <flux:label>Date expiration garantie</flux:label>
                        <flux:input type="date" name="warranty_expiry_date" value="{{ old('warranty_expiry_date', $claim->warranty_expiry_date) }}" />
                        @error('warranty_expiry_date')
                            <flux:text color="red">{{ $message }}</flux:text>
                        @enderror
                    </div>

                    <div>
                        <flux:label>Description de la réclamation *</flux:label>
                        <flux:textarea name="claim_description" rows="4">{{ old('claim_description', $claim->claim_description) }}</flux:textarea>
                        @error('claim_description')
                            <flux:text color="red">{{ $message }}</flux:text>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <flux:label>Montant réclamé (FCFA)</flux:label>
                            <flux:input type="number" name="claim_amount" value="{{ old('claim_amount', $claim->claim_amount) }}" min="0" step="0.01" />
                            @error('claim_amount')
                                <flux:text color="red">{{ $message }}</flux:text>
                            @enderror
                        </div>
                        <div>
                            <flux:label>Statut</flux:label>
                            <flux:select name="status">
                                <option value="PENDING" {{ old('status', $claim->status) === 'PENDING' ? 'selected' : '' }}>En attente</option>
                                <option value="APPROVED" {{ old('status', $claim->status) === 'APPROVED' ? 'selected' : '' }}>Approuvée</option>
                                <option value="REJECTED" {{ old('status', $claim->status) === 'REJECTED' ? 'selected' : '' }}>Rejetée</option>
                                <option value="RESOLVED" {{ old('status', $claim->status) === 'RESOLVED' ? 'selected' : '' }}>Résolue</option>
                            </flux:select>
                            @error('status')
                                <flux:text color="red">{{ $message }}</flux:text>
                            @enderror
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <flux:button type="submit" variant="primary">Mettre à jour</flux:button>
                        <flux:button href="{{ route('garage.warranty-claims.index') }}" variant="filled">Annuler</flux:button>
                        <flux:button href="{{ route('garage.warranty-claims.destroy', $claim->id) }}" variant="danger" onclick="event.preventDefault(); document.getElementById('delete-claim-form').submit();">Supprimer</flux:button>
                    </div>
                </div>
            </form>
            <form id="delete-claim-form" method="POST" action="{{ route('garage.warranty-claims.destroy', $claim->id) }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </flux:card>
    </div>
</x-layouts::app>
