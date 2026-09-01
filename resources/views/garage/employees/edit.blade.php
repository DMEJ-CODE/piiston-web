<x-layouts::app :title="__('Modifier l\'employé')">
    <flux:heading size="xl">Modifier l'employé</flux:heading>
    <flux:text class="mt-2">Modifier les informations de {{ $employee->user->name ?? 'l\'employé' }}.</flux:text>

    <div class="mt-6">
        <flux:card>
            <form method="POST" action="{{ route('garage.employees.update', $employee->id) }}">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <flux:label>Utilisateur Piiston *</flux:label>
                        <flux:select name="user_id">
                            <option value="">-- Sélectionner --</option>
                            @foreach(\App\Models\User::all() as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $employee->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </flux:select>
                        @error('user_id')
                            <flux:text color="red">{{ $message }}</flux:text>
                        @enderror
                    </div>

                    <div>
                        <flux:label>Département</flux:label>
                        <flux:select name="department_id">
                            <option value="">-- Sélectionner --</option>
                            @foreach(\App\Models\Garages\GarageDepartment::where('branch_id', $branch->id)->get() as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id', $employee->department_id) == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </flux:select>
                        @error('department_id')
                            <flux:text color="red">{{ $message }}</flux:text>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <flux:label>Numéro employé</flux:label>
                            <flux:input name="employee_number" value="{{ old('employee_number', $employee->employee_number) }}" />
                            @error('employee_number')
                                <flux:text color="red">{{ $message }}</flux:text>
                            @enderror
                        </div>
                        <div>
                            <flux:label>Poste</flux:label>
                            <flux:input name="position" value="{{ old('position', $employee->position) }}" />
                            @error('position')
                                <flux:text color="red">{{ $message }}</flux:text>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <flux:label>Date d'embauche</flux:label>
                            <flux:input type="date" name="hire_date" value="{{ old('hire_date', $employee->hire_date) }}" />
                            @error('hire_date')
                                <flux:text color="red">{{ $message }}</flux:text>
                            @enderror
                        </div>
                        <div>
                            <flux:label>Salaire</flux:label>
                            <flux:input type="number" name="salary" value="{{ old('salary', $employee->salary) }}" min="0" step="0.01" />
                            @error('salary')
                                <flux:text color="red">{{ $message }}</flux:text>
                            @enderror
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <flux:button type="submit" variant="primary">Mettre à jour</flux:button>
                        <flux:button href="{{ route('garage.employees.index') }}" variant="filled">Annuler</flux:button>
                        <flux:button href="{{ route('garage.employees.destroy', $employee->id) }}" variant="danger" onclick="event.preventDefault(); document.getElementById('delete-employee-form').submit();">Supprimer</flux:button>
                    </div>
                </div>
            </form>
            <form id="delete-employee-form" method="POST" action="{{ route('garage.employees.destroy', $employee->id) }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </flux:card>
    </div>
</x-layouts::app>
