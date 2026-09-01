<x-layouts::app :title="__('Modifier l\'Annexe')">
    <div class="max-w-3xl mx-auto">
        <div class="mb-8">
            <flux:heading size="xl" class="font-black uppercase tracking-tight">Modifier l'Annexe</flux:heading>
            <flux:text class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">Mise à jour des informations de {{ $targetBranch->name }}.</flux:text>
        </div>

        <form method="POST" action="{{ route('garage.branches.update', $targetBranch->id) }}">
            @csrf
            @method('PUT')

            <flux:card class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <flux:field class="md:col-span-2">
                        <flux:label>Nom du Site / Atelier</flux:label>
                        <flux:input name="name" value="{{ old('name', $targetBranch->name) }}" required />
                    </flux:field>

                    <flux:field>
                        <flux:label>Email du Site</flux:label>
                        <flux:input type="email" name="email" value="{{ old('email', $targetBranch->email) }}" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Téléphone du Site</flux:label>
                        <flux:input name="phone" value="{{ old('phone', $targetBranch->phone) }}" />
                    </flux:field>

                    <flux:field class="md:col-span-2">
                        <flux:label>Adresse Physique</flux:label>
                        <flux:input name="address" value="{{ old('address', $targetBranch->address) }}" placeholder="Rue, points de repère..." />
                    </flux:field>

                    <flux:field>
                        <flux:label>Ville</flux:label>
                        <flux:input name="city" value="{{ old('city', $targetBranch->city) }}" placeholder="Ex: Douala" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Statut</flux:label>
                        <flux:select name="status">
                            <option value="1" {{ $targetBranch->status ? 'selected' : '' }}>Actif</option>
                            <option value="0" {{ !$targetBranch->status ? 'selected' : '' }}>Fermé</option>
                        </flux:select>
                    </flux:field>
                </div>

                <hr class="border-zinc-100 dark:border-white/5">

                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-xs font-black uppercase text-zinc-900 dark:text-white tracking-widest">Géolocalisation</h4>
                        <flux:button type="button" onclick="detectLocation()" variant="ghost" size="xs" class="text-[9px] font-black uppercase tracking-widest text-blue-600">
                            <flux:icon icon="map-pin" class="size-3 mr-2" /> Détecter ma position
                        </flux:button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <flux:field>
                            <flux:label>Latitude</flux:label>
                            <flux:input id="latitude" name="latitude" value="{{ old('latitude', $targetBranch->latitude) }}" placeholder="0.000000" type="number" step="any" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Longitude</flux:label>
                            <flux:input id="longitude" name="longitude" value="{{ old('longitude', $targetBranch->longitude) }}" placeholder="0.000000" type="number" step="any" />
                        </flux:field>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 pt-4">
                    <flux:button href="{{ route('garage.branches.index') }}" variant="ghost" class="text-[10px] font-black uppercase tracking-widest">Annuler</flux:button>
                    <flux:button type="submit" variant="primary" class="bg-gradient-to-br from-blue-600 to-indigo-700 px-8 font-black uppercase tracking-widest border-none">Enregistrer</flux:button>
                </div>
            </flux:card>
        </form>
    </div>

    <script>
        function detectLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;
                }, function(error) {
                    alert("Erreur lors de la détection de la position: " + error.message);
                });
            } else {
                alert("La géolocalisation n'est pas supportée par votre navigateur.");
            }
        }
    </script>
</x-layouts::app>
