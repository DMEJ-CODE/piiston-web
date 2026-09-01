<x-layouts::app :title="__('Paramètres Garage')">
    <div x-data="{ tab: 'general' }" class="flex flex-col gap-6">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" class="font-black uppercase tracking-tight">Paramètres du Garage</flux:heading>
                <flux:text class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">Configuration de {{ $branch->name }}.</flux:text>
            </div>
        </div>

        <!-- Tabs -->
        <div class="flex items-center gap-1 p-1 bg-zinc-100 dark:bg-white/5 rounded-2xl w-fit">
            <button @click="tab = 'general'" :class="tab === 'general' ? 'bg-white dark:bg-white/10 shadow-sm text-zinc-900 dark:text-white' : 'text-zinc-500 hover:text-zinc-700'" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Général</button>
            <button @click="tab = 'hours'" :class="tab === 'hours' ? 'bg-white dark:bg-white/10 shadow-sm text-zinc-900 dark:text-white' : 'text-zinc-500 hover:text-zinc-700'" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Horaires</button>
            <button @click="tab = 'branding'" :class="tab === 'branding' ? 'bg-white dark:bg-white/10 shadow-sm text-zinc-900 dark:text-white' : 'text-zinc-500 hover:text-zinc-700'" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Branding</button>
            <button @click="tab = 'location'" :class="tab === 'location' ? 'bg-white dark:bg-white/10 shadow-sm text-zinc-900 dark:text-white' : 'text-zinc-500 hover:text-zinc-700'" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Localisation</button>
            <button @click="tab = 'billing'" :class="tab === 'billing' ? 'bg-white dark:bg-white/10 shadow-sm text-zinc-900 dark:text-white' : 'text-zinc-500 hover:text-zinc-700'" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Abonnement</button>
        </div>

        <form method="POST" action="{{ route('garage.settings.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <!-- General Tab -->
            <div x-show="tab === 'general'" class="space-y-6">
                <flux:card>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <flux:field>
                            <flux:label>Nom du Garage</flux:label>
                            <flux:input name="name" value="{{ old('name', $branch->name) }}" required />
                        </flux:field>
                        <flux:field>
                            <flux:label>Téléphone Professionnel</flux:label>
                            <flux:input name="phone" value="{{ old('phone', $branch->phone) }}" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Email de contact</flux:label>
                            <flux:input type="email" name="email" value="{{ old('email', $branch->email) }}" />
                        </flux:field>
                        <div class="md:col-span-2">
                            <label class="flex items-center gap-3 p-4 bg-zinc-50 dark:bg-white/5 rounded-2xl border border-zinc-100 dark:border-white/5 cursor-pointer">
                                <input type="checkbox" name="status" value="1" {{ old('status', $branch->status) ? 'checked' : '' }} class="size-5 rounded text-[var(--active-2)]">
                                <div>
                                    <span class="block text-xs font-black uppercase text-zinc-900 dark:text-white">Garage en ligne</span>
                                    <span class="block text-[10px] text-zinc-500 font-medium">Si décoché, votre garage ne sera pas visible sur la carte pour les clients.</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </flux:card>
            </div>

            <!-- Business Hours Tab -->
            <div x-show="tab === 'hours'" class="space-y-6">
                <flux:card>
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-6">Heures d'ouverture</h3>
                    <div class="grid grid-cols-1 gap-4">
                        @foreach(['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'] as $day)
                        <div class="flex items-center justify-between p-4 bg-zinc-50 dark:bg-white/5 rounded-2xl border border-zinc-100 dark:border-white/5">
                            <span class="text-xs font-bold uppercase w-24">{{ $day }}</span>
                            <div class="flex items-center gap-4">
                                <flux:input type="time" name="business_hours[{{ $day }}][open]" value="{{ $branch->business_hours[$day]['open'] ?? '08:00' }}" class="!w-32" />
                                <span class="text-zinc-400">à</span>
                                <flux:input type="time" name="business_hours[{{ $day }}][close]" value="{{ $branch->business_hours[$day]['close'] ?? '18:00' }}" class="!w-32" />
                            </div>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="business_hours[{{ $day }}][closed]" {{ isset($branch->business_hours[$day]['closed']) ? 'checked' : '' }} class="rounded text-red-500">
                                <span class="text-[10px] font-bold uppercase text-red-500">Fermé</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </flux:card>
            </div>

            <!-- Location Tab -->
            <div x-show="tab === 'location'" class="space-y-6">
                <flux:card>
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Coordonnées GPS</h3>
                        <flux:button type="button" onclick="detectLocation()" variant="ghost" size="xs" class="text-[9px] font-black uppercase tracking-widest text-blue-600">
                            <flux:icon icon="map-pin" class="size-3 mr-2" /> Détecter ma position
                        </flux:button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <flux:field>
                            <flux:label>Latitude</flux:label>
                            <flux:input id="latitude" name="latitude" value="{{ old('latitude', $branch->latitude) }}" placeholder="0.000000" type="number" step="any" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Longitude</flux:label>
                            <flux:input id="longitude" name="longitude" value="{{ old('longitude', $branch->longitude) }}" placeholder="0.000000" type="number" step="any" />
                        </flux:field>
                    </div>
                    <flux:text class="mt-4 text-[10px] text-zinc-500 font-medium leading-relaxed">
                        Ces coordonnées permettent à vos clients de visualiser votre atelier sur la carte interactive de l'application mobile et de calculer un itinéraire précis.
                    </flux:text>
                </flux:card>
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

            <!-- Branding Tab -->
            <div x-show="tab === 'branding'" class="space-y-6">
                <flux:card>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <div class="space-y-4">
                            <flux:label>Logo du Garage</flux:label>
                            <div class="size-32 rounded-3xl bg-zinc-100 dark:bg-white/5 border-2 border-dashed border-zinc-200 dark:border-white/10 flex items-center justify-center relative overflow-hidden group">
                                @if($branch->logo_path)
                                    <img src="{{ Storage::url($branch->logo_path) }}" class="absolute inset-0 size-full object-cover">
                                @else
                                    <flux:icon icon="photo" class="size-8 text-zinc-300" />
                                @endif
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all cursor-pointer">
                                    <span class="text-[9px] font-black text-white uppercase">Modifier</span>
                                </div>
                                <input type="file" name="logo" class="absolute inset-0 opacity-0 cursor-pointer">
                            </div>
                        </div>
                    </div>
                </flux:card>
            </div>

            <!-- Billing Tab -->
            <div x-show="tab === 'billing'" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach([\App\Models\Garages\GarageSubscriptionPlan::all()] as $plan)
                        <!-- This is a stub loop, real one would loop over $plans -->
                    @endforeach

                    <div class="bg-zinc-900 text-white p-8 rounded-[32px] border-4 border-blue-500 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4">
                            <span class="bg-blue-500 text-white text-[8px] font-black uppercase px-2 py-1 rounded-full">Actuel</span>
                        </div>
                        <h3 class="text-xl font-black uppercase tracking-tight">Pack Premium</h3>
                        <p class="text-white/40 text-[10px] font-bold uppercase tracking-widest mt-1">Illimité</p>
                        <div class="mt-8">
                            <span class="text-3xl font-black">25 000 F</span>
                            <span class="text-white/40 text-xs">/mois</span>
                        </div>
                        <ul class="mt-8 space-y-3">
                            <li class="flex items-center gap-2 text-[10px] font-bold uppercase"><flux:icon icon="check" class="size-3 text-blue-400" /> Réparations Illimitées</li>
                            <li class="flex items-center gap-2 text-[10px] font-bold uppercase"><flux:icon icon="check" class="size-3 text-blue-400" /> Assistant IA Complet</li>
                            <li class="flex items-center gap-2 text-[10px] font-bold uppercase"><flux:icon icon="check" class="size-3 text-blue-400" /> Support 24/7</li>
                        </ul>
                        <flux:button class="mt-8 w-full bg-white text-zinc-900 border-none font-black uppercase tracking-widest">Gérer l'offre</flux:button>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <flux:button type="submit" variant="primary" class="bg-gradient-to-br from-blue-600 to-indigo-700 px-12 font-black uppercase tracking-widest border-none h-12 shadow-xl">Enregistrer tout</flux:button>
            </div>
        </form>
    </div>
</x-layouts::app>
