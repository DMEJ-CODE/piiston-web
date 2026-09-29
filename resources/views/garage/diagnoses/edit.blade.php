<x-layouts::app :title="__('garage.Modifier le diagnostic')">
    <flux:heading size="xl">Modifier le diagnostic</flux:heading>
    <flux:text class="mt-2">Modifier le diagnostic #{{ $diagnosis->id }}.</flux:text>

    <div class="mt-6">
        <flux:card>
            <form method="POST" action="{{ route('garage.diagnoses.update', $diagnosis->id) }}">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <flux:label>Réparation *</flux:label>
                        <flux:select name="repair_order_id">
                            <option value="">-- Sélectionner --</option>
                            @foreach($repairs as $repair)
                                <option value="{{ $repair->id }}" {{ old('repair_order_id', $diagnosis->repair_order_id) == $repair->id ? 'selected' : '' }}>
                                    #{{ $repair->id }} - {{ $repair->vehicle->license_plate ?? 'N/A' }} ({{ $repair->garageCustomer->user->name ?? 'N/A' }})
                                </option>
                            @endforeach
                        </flux:select>
                        @error('repair_order_id')
                            <flux:text color="red">{{ $message }}</flux:text>
                        @enderror
                    </div>

                    <div>
                        <flux:label>Type de diagnostic *</flux:label>
                        <flux:select name="diagnosis_type">
                            <option value="">-- Sélectionner --</option>
                            <option value="MECHANICAL" {{ old('diagnosis_type', $diagnosis->diagnosis_type) === 'MECHANICAL' ? 'selected' : '' }}>Mécanique</option>
                            <option value="ELECTRICAL" {{ old('diagnosis_type', $diagnosis->diagnosis_type) === 'ELECTRICAL' ? 'selected' : '' }}>Électrique</option>
                            <option value="ELECTRONIC" {{ old('diagnosis_type', $diagnosis->diagnosis_type) === 'ELECTRONIC' ? 'selected' : '' }}>Électronique</option>
                            <option value="BODY" {{ old('diagnosis_type', $diagnosis->diagnosis_type) === 'BODY' ? 'selected' : '' }}>Carrosserie</option>
                            <option value="OTHER" {{ old('diagnosis_type', $diagnosis->diagnosis_type) === 'OTHER' ? 'selected' : '' }}>Autre</option>
                        </flux:select>
                        @error('diagnosis_type')
                            <flux:text color="red">{{ $message }}</flux:text>
                        @enderror
                    </div>

                    <div>
                        <flux:label>Description *</flux:label>
                        <flux:textarea name="description" rows="4">{{ old('description', $diagnosis->description) }}</flux:textarea>
                        @error('description')
                            <flux:text color="red">{{ $message }}</flux:text>
                        @enderror
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <flux:label>Durée estimée (h)</flux:label>
                            <flux:input type="number" name="estimated_duration" value="{{ old('estimated_duration', $diagnosis->estimated_duration) }}" min="0" />
                            @error('estimated_duration')
                                <flux:text color="red">{{ $message }}</flux:text>
                            @enderror
                        </div>
                        <div>
                            <flux:label>Coût estimé (FCFA)</flux:label>
                            <flux:input type="number" name="estimated_cost" value="{{ old('estimated_cost', $diagnosis->estimated_cost) }}" min="0" step="0.01" />
                            @error('estimated_cost')
                                <flux:text color="red">{{ $message }}</flux:text>
                            @enderror
                        </div>
                        <div>
                            <flux:label>Statut</flux:label>
                            <flux:select name="status">
                                <option value="PENDING" {{ old('status', $diagnosis->status) === 'PENDING' ? 'selected' : '' }}>En attente</option>
                                <option value="COMPLETED" {{ old('status', $diagnosis->status) === 'COMPLETED' ? 'selected' : '' }}>Terminé</option>
                                <option value="REVIEWED" {{ old('status', $diagnosis->status) === 'REVIEWED' ? 'selected' : '' }}>Révisé</option>
                            </flux:select>
                            @error('status')
                                <flux:text color="red">{{ $message }}</flux:text>
                            @enderror
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <flux:button type="submit" variant="primary">Mettre à jour</flux:button>
                        <flux:button href="{{ route('garage.diagnoses.index') }}" variant="filled">Annuler</flux:button>
                        <flux:button href="{{ route('garage.diagnoses.destroy', $diagnosis->id) }}" variant="danger" onclick="event.preventDefault(); document.getElementById('delete-diagnosis-form').submit();">Supprimer</flux:button>
                    </div>
                </div>
            </form>
            <form id="delete-diagnosis-form" method="POST" action="{{ route('garage.diagnoses.destroy', $diagnosis->id) }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </flux:card>
    </div>
</x-layouts::app>
