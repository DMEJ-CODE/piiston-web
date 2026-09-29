<x-layouts::app :title="__('garage.Modifier le devis')">
    <flux:heading size="xl">Modifier le devis</flux:heading>
    <flux:text class="mt-2">Modifier le devis #{{ $estimate->id }}.</flux:text>

    <div class="mt-6">
        <flux:card>
            <form method="POST" action="{{ route('garage.estimates.update', $estimate->id) }}">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <flux:label>Réparation *</flux:label>
                        <flux:select name="repair_order_id">
                            <option value="">-- Sélectionner --</option>
                            @foreach($repairs as $repair)
                                <option value="{{ $repair->id }}" {{ old('repair_order_id', $estimate->repair_order_id) == $repair->id ? 'selected' : '' }}>
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
                            <flux:label>Coût main d'œuvre *</flux:label>
                            <flux:input type="number" name="labor_cost" value="{{ old('labor_cost', $estimate->labor_cost) }}" min="0" step="0.01" />
                            @error('labor_cost')
                                <flux:text color="red">{{ $message }}</flux:text>
                            @enderror
                        </div>
                        <div>
                            <flux:label>Coût pièces *</flux:label>
                            <flux:input type="number" name="parts_cost" value="{{ old('parts_cost', $estimate->parts_cost) }}" min="0" step="0.01" />
                            @error('parts_cost')
                                <flux:text color="red">{{ $message }}</flux:text>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <flux:label>Montant total *</flux:label>
                        <flux:input type="number" name="total_amount" value="{{ old('total_amount', $estimate->total_amount) }}" min="0" step="0.01" />
                        @error('total_amount')
                            <flux:text color="red">{{ $message }}</flux:text>
                        @enderror
                    </div>

                    <div>
                        <flux:label>Valide jusqu'au *</flux:label>
                        <flux:input type="date" name="valid_until" value="{{ old('valid_until', $estimate->valid_until) }}" />
                        @error('valid_until')
                            <flux:text color="red">{{ $message }}</flux:text>
                        @enderror
                    </div>

                    <div>
                        <flux:label>Notes</flux:label>
                        <flux:textarea name="notes" rows="3">{{ old('notes', $estimate->notes) }}</flux:textarea>
                        @error('notes')
                            <flux:text color="red">{{ $message }}</flux:text>
                        @enderror
                    </div>

                    <div>
                        <flux:label>Statut</flux:label>
                        <flux:select name="status">
                            <option value="PENDING" {{ old('status', $estimate->status) === 'PENDING' ? 'selected' : '' }}>En attente</option>
                            <option value="APPROVED" {{ old('status', $estimate->status) === 'APPROVED' ? 'selected' : '' }}>Approuvé</option>
                            <option value="REJECTED" {{ old('status', $estimate->status) === 'REJECTED' ? 'selected' : '' }}>Rejeté</option>
                            <option value="EXPIRED" {{ old('status', $estimate->status) === 'EXPIRED' ? 'selected' : '' }}>Expiré</option>
                        </flux:select>
                        @error('status')
                            <flux:text color="red">{{ $message }}</flux:text>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <flux:button type="submit" variant="primary">Mettre à jour</flux:button>
                        <flux:button href="{{ route('garage.estimates.index') }}" variant="filled">Annuler</flux:button>
                        <flux:button href="{{ route('garage.estimates.destroy', $estimate->id) }}" variant="danger" onclick="event.preventDefault(); document.getElementById('delete-estimate-form').submit();">Supprimer</flux:button>
                    </div>
                </div>
            </form>
            <form id="delete-estimate-form" method="POST" action="{{ route('garage.estimates.destroy', $estimate->id) }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </flux:card>
    </div>
</x-layouts::app>
