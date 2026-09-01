<x-layouts::app :title="__('Nouvelle réparation')">
    <div class="flex flex-col gap-4 pb-0">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-base font-black uppercase text-zinc-900 dark:text-white tracking-tight">Nouvelle réparation</h2>
                <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest">Créer un ordre de réparation pour {{ $branch->name }}.</p>
            </div>
            <flux:button href="{{ route('garage.repairs.index') }}" size="xs" class="rounded-lg font-black uppercase tracking-widest bg-zinc-100 dark:bg-white/5 text-zinc-600 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-white/10 border-none px-4 py-2">
                Retour
            </flux:button>
        </div>

        <div class="mt-2">
            <div class="bg-[var(--surface)] p-5 rounded-[16px] border border-zinc-100 dark:border-white/5 shadow-sm">
                <form method="POST" action="{{ route('garage.repairs.store') }}">
                    @csrf

                    <div class="flex flex-col gap-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="flex flex-col gap-1.5">
                                <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Client *</flux:label>
                                <flux:select name="customer_id" class="rounded-lg text-xs font-bold">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->user->name ?? 'Client #'.$customer->id }}
                                        </option>
                                    @endforeach
                                </flux:select>
                                @error('customer_id')
                                    <p class="text-[10px] font-bold text-red-500 uppercase">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Véhicule *</flux:label>
                                <flux:select name="vehicle_id" class="rounded-lg text-xs font-bold">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                            {{ $vehicle->brand->name ?? '' }} {{ $vehicle->model->name ?? '' }} ({{ $vehicle->license_plate }})
                                        </option>
                                    @endforeach
                                </flux:select>
                                @error('vehicle_id')
                                    <p class="text-[10px] font-bold text-red-500 uppercase">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Description du problème *</flux:label>
                            <flux:textarea name="problem_description" rows="3" class="rounded-lg text-xs font-medium" placeholder="Décrivez les symptômes signalés par le client...">{{ old('problem_description') }}</flux:textarea>
                            @error('problem_description')
                                <p class="text-[10px] font-bold text-red-500 uppercase">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="flex flex-col gap-1.5">
                                <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Priorité</flux:label>
                                <flux:select name="priority" class="rounded-lg text-xs font-bold uppercase">
                                    <option value="LOW" {{ old('priority') === 'LOW' ? 'selected' : '' }}>Basse</option>
                                    <option value="MEDIUM" {{ old('priority', 'MEDIUM') === 'MEDIUM' ? 'selected' : '' }}>Moyenne</option>
                                    <option value="HIGH" {{ old('priority') === 'HIGH' ? 'selected' : '' }}>Haute</option>
                                    <option value="URGENT" {{ old('priority') === 'URGENT' ? 'selected' : '' }}>Urgente</option>
                                </flux:select>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Mécanicien assigné</flux:label>
                                <flux:select name="assigned_mechanic_id" class="rounded-lg text-xs font-bold">
                                    <option value="">-- Non assigné --</option>
                                    @foreach($mechanics as $mechanic)
                                        <option value="{{ $mechanic->user_id }}" {{ old('assigned_mechanic_id') == $mechanic->user_id ? 'selected' : '' }}>
                                            {{ $mechanic->user->name ?? 'Mécanicien #'.$mechanic->id }}
                                        </option>
                                    @endforeach
                                </flux:select>
                            </div>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Bay d'atelier</flux:label>
                            <flux:select name="workshop_bay_id" class="rounded-lg text-xs font-bold">
                                <option value="">-- Sélectionner un emplacement --</option>
                                @foreach($bays as $bay)
                                    <option value="{{ $bay->id }}" {{ old('workshop_bay_id') == $bay->id ? 'selected' : '' }}>
                                        {{ $bay->name }} ({{ $bay->bay_type }})
                                    </option>
                                @endforeach
                            </flux:select>
                        </div>

                        <div class="flex items-center gap-3 pt-2">
                            <flux:button type="submit" variant="primary" class="rounded-lg font-black uppercase tracking-widest bg-gradient-to-br from-[var(--active-2)] to-[var(--active)] text-white border-none px-6 py-2.5 shadow-md shadow-[var(--active)]/20">
                                Créer la réparation
                            </flux:button>
                            <flux:button href="{{ route('garage.repairs.index') }}" variant="filled" class="rounded-lg font-black uppercase tracking-widest px-6 py-2.5">
                                Annuler
                            </flux:button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts::app>
