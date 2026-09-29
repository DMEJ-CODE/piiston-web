<x-layouts::app :title="__('garage.Modifier l\'enregistrement')">
    <flux:heading size="xl">Modifier l'enregistrement</flux:heading>
    <flux:text class="mt-2">Modifier l'enregistrement du {{ $checkIn->check_in_time?->format('d/m/Y H:i') }}.</flux:text>

    <div class="mt-6">
        <flux:card>
            <form method="POST" action="{{ route('garage.check-ins.update', $checkIn->id) }}">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <flux:label>Client *</flux:label>
                        <flux:select name="customer_id">
                            <option value="">-- Sélectionner --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id', $checkIn->customer_id) == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->user->name ?? 'Client #'.$customer->id }}
                                </option>
                            @endforeach
                        </flux:select>
                        @error('customer_id')
                            <flux:text color="red">{{ $message }}</flux:text>
                        @enderror
                    </div>

                    <div>
                        <flux:label>Véhicule *</flux:label>
                        <flux:select name="vehicle_id">
                            <option value="">-- Sélectionner --</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" {{ old('vehicle_id', $checkIn->vehicle_id) == $vehicle->id ? 'selected' : '' }}>
                                    {{ $vehicle->brand->name ?? '' }} {{ $vehicle->model->name ?? '' }} ({{ $vehicle->license_plate }})
                                </option>
                            @endforeach
                        </flux:select>
                        @error('vehicle_id')
                            <flux:text color="red">{{ $message }}</flux:text>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <flux:label>Kilométrage</flux:label>
                            <flux:input type="number" name="mileage" value="{{ old('mileage', $checkIn->mileage) }}" min="0" />
                            @error('mileage')
                                <flux:text color="red">{{ $message }}</flux:text>
                            @enderror
                        </div>
                        <div>
                            <flux:label>Niveau carburant</flux:label>
                            <flux:select name="fuel_level">
                                <option value="">-- Sélectionner --</option>
                                <option value="empty" {{ old('fuel_level', $checkIn->fuel_level) === 'empty' ? 'selected' : '' }}>Vide</option>
                                <option value="quarter" {{ old('fuel_level', $checkIn->fuel_level) === 'quarter' ? 'selected' : '' }}>1/4</option>
                                <option value="half" {{ old('fuel_level', $checkIn->fuel_level) === 'half' ? 'selected' : '' }}>1/2</option>
                                <option value="three_quarters" {{ old('fuel_level', $checkIn->fuel_level) === 'three_quarters' ? 'selected' : '' }}>3/4</option>
                                <option value="full" {{ old('fuel_level', $checkIn->fuel_level) === 'full' ? 'selected' : '' }}>Plein</option>
                            </flux:select>
                            @error('fuel_level')
                                <flux:text color="red">{{ $message }}</flux:text>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <flux:label>Notes</flux:label>
                        <flux:textarea name="notes" rows="3">{{ old('notes', $checkIn->notes) }}</flux:textarea>
                        @error('notes')
                            <flux:text color="red">{{ $message }}</flux:text>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <flux:button type="submit" variant="primary">Mettre à jour</flux:button>
                        <flux:button href="{{ route('garage.check-ins.index') }}" variant="filled">Annuler</flux:button>
                        <flux:button href="{{ route('garage.check-ins.destroy', $checkIn->id) }}" variant="danger" onclick="event.preventDefault(); document.getElementById('delete-checkin-form').submit();">Supprimer</flux:button>
                    </div>
                </div>
            </form>
            <form id="delete-checkin-form" method="POST" action="{{ route('garage.check-ins.destroy', $checkIn->id) }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </flux:card>
    </div>
</x-layouts::app>
