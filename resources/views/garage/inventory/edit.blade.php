<x-layouts::app :title="__('garage.Modifier Pièce')">
    <div class="flex flex-col gap-4 pb-0">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-base font-black uppercase text-zinc-900 dark:text-white tracking-tight">Modifier Pièce</h2>
                <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest">{{ $part->name }} (Ref: {{ $part->part_number ?? '---' }})</p>
            </div>
            <flux:button href="{{ route('garage.inventory.index') }}" size="xs" class="rounded-lg font-black uppercase tracking-widest bg-zinc-100 dark:bg-white/5 text-zinc-600 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-white/10 border-none px-4 py-2">
                Retour
            </flux:button>
        </div>

        <div class="mt-2">
            <div class="bg-[var(--surface)] p-5 rounded-[16px] border border-zinc-100 dark:border-white/5 shadow-sm">
                <form method="POST" action="{{ route('garage.inventory.update', $part->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="flex flex-col gap-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="flex flex-col gap-1.5">
                                <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Nom de la pièce *</flux:label>
                                <flux:input name="name" value="{{ old('name', $part->name) }}" class="rounded-lg text-xs font-bold" />
                                @error('name') <p class="text-[9px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Référence (SKU)</flux:label>
                                <flux:input name="part_number" value="{{ old('part_number', $part->part_number) }}" class="rounded-lg text-xs font-bold" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div class="flex flex-col gap-1.5">
                                <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Catégorie</flux:label>
                                <flux:select name="category" class="rounded-lg text-xs font-bold uppercase">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach(['FREINAGE', 'MOTEUR', 'FILTRATION', 'ÉLECTRIQUE', 'SUSPENSION', 'LUBRIFIANT', 'PNEUMATIQUE'] as $cat)
                                        <option value="{{ $cat }}" {{ old('category', $part->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </flux:select>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Marque / Fabricant</flux:label>
                                <flux:input name="manufacturer" value="{{ old('manufacturer', $part->manufacturer) }}" class="rounded-lg text-xs font-bold" />
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Emplacement Stock</flux:label>
                                <flux:input name="storage_location" value="{{ old('storage_location', $part->storage_location) }}" class="rounded-lg text-xs font-bold" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div class="flex flex-col gap-1.5">
                                <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Quantité en stock</flux:label>
                                <flux:input type="number" name="stock_quantity" value="{{ old('stock_quantity', $part->stock_quantity) }}" class="rounded-lg text-xs font-bold" />
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Seuil d'alerte (Min)</flux:label>
                                <flux:input type="number" name="minimum_stock" value="{{ old('minimum_stock', $part->minimum_stock) }}" class="rounded-lg text-xs font-bold" />
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Prix de vente (F)</flux:label>
                                <flux:input type="number" name="selling_price" value="{{ old('selling_price', $part->selling_price) }}" class="rounded-lg text-xs font-bold" />
                            </div>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Description / Compatibilité</flux:label>
                            <flux:textarea name="description" rows="2" class="rounded-lg text-xs font-medium">{{ old('description', $part->description) }}</flux:textarea>
                        </div>

                        <div class="flex items-center gap-3 pt-2">
                            <flux:button type="submit" variant="primary" class="rounded-lg font-black uppercase tracking-widest bg-gradient-to-br from-[var(--active-2)] to-[var(--active)] text-white border-none px-6 py-2.5 shadow-md shadow-[var(--active)]/20">
                                Mettre à jour
                            </flux:button>
                            <flux:button href="{{ route('garage.inventory.index') }}" variant="filled" class="rounded-lg font-black uppercase tracking-widest px-6 py-2.5">
                                Annuler
                            </flux:button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts::app>
