<x-layouts::app :title="__('garage.Nouveau rendez-vous')">
    <div class="flex flex-col gap-4 pb-0">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-base font-black uppercase text-zinc-900 dark:text-white tracking-tight">Nouveau rendez-vous</h2>
                <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest">Planifier une intervention pour {{ $branch->name }}.</p>
            </div>
            <flux:button href="{{ route('garage.appointments.index') }}" size="xs" class="rounded-lg font-black uppercase tracking-widest bg-zinc-100 dark:bg-white/5 text-zinc-600 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-white/10 border-none px-4 py-2">
                Retour
            </flux:button>
        </div>

        <div class="mt-2">
            <div class="bg-[var(--surface)] p-5 rounded-[16px] border border-zinc-100 dark:border-white/5 shadow-sm">
                <form method="POST" action="{{ route('garage.appointments.store') }}">
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
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="flex flex-col gap-1.5">
                                <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Date & Heure *</flux:label>
                                <flux:input type="datetime-local" name="scheduled_date" value="{{ old('scheduled_date') }}" class="rounded-lg text-xs font-bold" />
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Durée estimée (minutes)</flux:label>
                                <flux:input type="number" name="duration_minutes" value="{{ old('duration_minutes', 60) }}" class="rounded-lg text-xs font-bold" />
                            </div>
                        </div>

                        <div class="flex items-center gap-3 pt-2">
                            <flux:button type="submit" variant="primary" class="rounded-lg font-black uppercase tracking-widest bg-gradient-to-br from-[var(--active-2)] to-[var(--active)] text-white border-none px-6 py-2.5 shadow-md shadow-[var(--active)]/20">
                                Planifier le RDV
                            </flux:button>
                            <flux:button href="{{ route('garage.appointments.index') }}" variant="filled" class="rounded-lg font-black uppercase tracking-widest px-6 py-2.5">
                                Annuler
                            </flux:button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts::app>
