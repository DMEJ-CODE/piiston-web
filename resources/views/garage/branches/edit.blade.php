<x-layouts::app :title="__('garage.Modifier l\'Annexe')">
    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    @endpush

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
                        <h4 class="text-xs font-black uppercase text-zinc-900 dark:text-white tracking-widest">Géolocalisation & Carte OpenStreetMap</h4>
                        <flux:button type="button" onclick="detectLocation()" variant="ghost" size="xs" class="text-[9px] font-black uppercase tracking-widest text-blue-600">
                            <flux:icon icon="map-pin" class="size-3 mr-2" /> Ma position
                        </flux:button>
                    </div>

                    <p class="text-xs text-zinc-500 mb-3">Cliquez sur la carte ou déplacez le repère pour définir l'adresse par défaut du garage.</p>
                    <div id="map" class="h-72 w-full rounded-xl border border-zinc-200 dark:border-white/10 z-0 mb-4"></div>

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

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const defaultLat = {{ $targetBranch->latitude ?? 4.0511 }};
            const defaultLng = {{ $targetBranch->longitude ?? 9.7679 }};

            const map = L.map('map').setView([defaultLat, defaultLng], 14);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            let marker = L.marker([defaultLat, defaultLng], {draggable: true}).addTo(map);

            function updateInputs(lat, lng) {
                document.getElementById('latitude').value = lat.toFixed(6);
                document.getElementById('longitude').value = lng.toFixed(6);
            }

            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                updateInputs(e.latlng.lat, e.latlng.lng);
            });

            marker.on('dragend', function(e) {
                const pos = marker.getLatLng();
                updateInputs(pos.lat, pos.lng);
            });

            window.detectLocation = function() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        map.setView([lat, lng], 15);
                        marker.setLatLng([lat, lng]);
                        updateInputs(lat, lng);
                    }, function(error) {
                        alert("Erreur lors de la détection de la position: " + error.message);
                    });
                } else {
                    alert("La géolocalisation n'est pas supportée par votre navigateur.");
                }
            };
        });
    </script>
</x-layouts::app>
