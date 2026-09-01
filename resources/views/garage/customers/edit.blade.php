<x-layouts::app :title="__('Modifier Client')">
    <div class="flex flex-col gap-4 pb-0">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-base font-black uppercase text-zinc-900 dark:text-white tracking-tight">Modifier Client</h2>
                <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest">{{ $customer->user->name ?? 'Client #'.$customer->id }}</p>
            </div>
            <flux:button href="{{ route('garage.customers.index') }}" size="xs" class="rounded-lg font-black uppercase tracking-widest bg-zinc-100 dark:bg-white/5 text-zinc-600 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-white/10 border-none px-4 py-2">
                Retour
            </flux:button>
        </div>

        <div class="mt-2">
            <div class="bg-[var(--surface)] p-5 rounded-[16px] border border-zinc-100 dark:border-white/5 shadow-sm">
                <form method="POST" action="{{ route('garage.customers.update', $customer->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="flex flex-col gap-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="flex flex-col gap-1.5">
                                <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Type de Client *</flux:label>
                                <flux:select name="customer_type" class="rounded-lg text-xs font-bold uppercase">
                                    <option value="INDIVIDUAL" {{ old('customer_type', $customer->customer_type) === 'INDIVIDUAL' ? 'selected' : '' }}>Particulier</option>
                                    <option value="BUSINESS" {{ old('customer_type', $customer->customer_type) === 'BUSINESS' ? 'selected' : '' }}>Entreprise / Flotte</option>
                                </flux:select>
                            </div>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Notes & Informations complémentaires</flux:label>
                            <flux:textarea name="notes" rows="3" class="rounded-lg text-xs font-medium">{{ old('notes', $customer->notes) }}</flux:textarea>
                        </div>

                        <div class="flex items-center gap-3 pt-2">
                            <flux:button type="submit" variant="primary" class="rounded-lg font-black uppercase tracking-widest bg-gradient-to-br from-[var(--active-2)] to-[var(--active)] text-white border-none px-6 py-2.5 shadow-md shadow-[var(--active)]/20">
                                Mettre à jour
                            </flux:button>
                            <flux:button href="{{ route('garage.customers.index') }}" variant="filled" class="rounded-lg font-black uppercase tracking-widest px-6 py-2.5">
                                Annuler
                            </flux:button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts::app>
