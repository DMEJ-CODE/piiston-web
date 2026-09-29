<x-layouts::app :title="__('garage.Paramètres Garage')">
    <div x-data="{ tab: 'general' }" class="flex flex-col gap-4">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="lg" class="font-black uppercase tracking-tight">Garage Settings & Management</flux:heading>
                <flux:text class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">Configuration and management modules for {{ $branch->name }}.</flux:text>
            </div>
        </div>

        <!-- Management Hub Grid -->
        <div class="grid grid-cols-2 md:grid-cols-6 gap-3">
            <a href="{{ route('garage.services.index') }}" class="p-3.5 bg-white dark:bg-white/5 border border-zinc-100 dark:border-white/5 rounded-2xl flex flex-col items-center justify-center text-center hover:border-cyan-500/50 transition-all group shadow-sm">
                <i class="hgi-stroke hgi-tag-01 size-5 text-cyan-500 mb-1.5 group-hover:scale-110 transition-transform"></i>
                <span class="text-[10px] font-black uppercase text-zinc-900 dark:text-white tracking-tight">Services</span>
            </a>
            <a href="{{ route('garage.customers.index') }}" class="p-3.5 bg-white dark:bg-white/5 border border-zinc-100 dark:border-white/5 rounded-2xl flex flex-col items-center justify-center text-center hover:border-blue-500/50 transition-all group shadow-sm">
                <i class="hgi-stroke hgi-user-group size-5 text-blue-500 mb-1.5 group-hover:scale-110 transition-transform"></i>
                <span class="text-[10px] font-black uppercase text-zinc-900 dark:text-white tracking-tight">Customers</span>
            </a>
            <a href="{{ route('garage.employees.index') }}" class="p-3.5 bg-white dark:bg-white/5 border border-zinc-100 dark:border-white/5 rounded-2xl flex flex-col items-center justify-center text-center hover:border-indigo-500/50 transition-all group shadow-sm">
                <i class="hgi-stroke hgi-user size-5 text-indigo-500 mb-1.5 group-hover:scale-110 transition-transform"></i>
                <span class="text-[10px] font-black uppercase text-zinc-900 dark:text-white tracking-tight">Employees</span>
            </a>
            <a href="{{ route('garage.inventory.index') }}" class="p-3.5 bg-white dark:bg-white/5 border border-zinc-100 dark:border-white/5 rounded-2xl flex flex-col items-center justify-center text-center hover:border-amber-500/50 transition-all group shadow-sm">
                <i class="hgi-stroke hgi-package-01 size-5 text-amber-500 mb-1.5 group-hover:scale-110 transition-transform"></i>
                <span class="text-[10px] font-black uppercase text-zinc-900 dark:text-white tracking-tight">Inventory</span>
            </a>
            <a href="{{ route('garage.marketplace.index') }}" class="p-3.5 bg-white dark:bg-white/5 border border-zinc-100 dark:border-white/5 rounded-2xl flex flex-col items-center justify-center text-center hover:border-emerald-500/50 transition-all group shadow-sm">
                <i class="hgi-stroke hgi-shopping-cart-01 size-5 text-emerald-500 mb-1.5 group-hover:scale-110 transition-transform"></i>
                <span class="text-[10px] font-black uppercase text-zinc-900 dark:text-white tracking-tight">Marketplace</span>
            </a>
            <a href="{{ route('garage.branches.index') }}" class="p-3.5 bg-white dark:bg-white/5 border border-zinc-100 dark:border-white/5 rounded-2xl flex flex-col items-center justify-center text-center hover:border-purple-500/50 transition-all group shadow-sm">
                <i class="hgi-stroke hgi-building-02 size-5 text-purple-500 mb-1.5 group-hover:scale-110 transition-transform"></i>
                <span class="text-[10px] font-black uppercase text-zinc-900 dark:text-white tracking-tight">Branches</span>
            </a>
        </div>

        <!-- Tabs -->
        <div class="flex flex-wrap items-center gap-1 p-1 bg-zinc-100 dark:bg-white/5 rounded-xl w-fit">
            <button @click="tab = 'general'" :class="tab === 'general' ? 'bg-white dark:bg-white/10 shadow-sm text-zinc-900 dark:text-white' : 'text-zinc-500 hover:text-zinc-700'" class="px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all">General</button>
            <button @click="tab = 'hours'" :class="tab === 'hours' ? 'bg-white dark:bg-white/10 shadow-sm text-zinc-900 dark:text-white' : 'text-zinc-500 hover:text-zinc-700'" class="px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all">Hours</button>
            <button @click="tab = 'branding'" :class="tab === 'branding' ? 'bg-white dark:bg-white/10 shadow-sm text-zinc-900 dark:text-white' : 'text-zinc-500 hover:text-zinc-700'" class="px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all">Branding</button>
            <button @click="tab = 'location'; $nextTick(() => initSettingsMap())" :class="tab === 'location' ? 'bg-white dark:bg-white/10 shadow-sm text-zinc-900 dark:text-white' : 'text-zinc-500 hover:text-zinc-700'" class="px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all">Location</button>
            <button @click="tab = 'billing'" :class="tab === 'billing' ? 'bg-white dark:bg-white/10 shadow-sm text-zinc-900 dark:text-white' : 'text-zinc-500 hover:text-zinc-700'" class="px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all">Subscription</button>
        </div>

        <!-- General / Hours / Branding / Billing Form -->
        <form x-show="tab !== 'location'" method="POST" action="{{ route('garage.settings.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <!-- General Tab -->
            <div x-show="tab === 'general'" class="space-y-4">
                <flux:card class="!p-4 rounded-2xl">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <flux:field>
                            <flux:label>Garage Name</flux:label>
                            <flux:input name="name" value="{{ old('name', $branch->name) }}" required />
                        </flux:field>
                        <flux:field>
                            <flux:label>Professional Phone</flux:label>
                            <flux:input name="phone" value="{{ old('phone', $branch->phone) }}" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Contact Email</flux:label>
                            <flux:input type="email" name="email" value="{{ old('email', $branch->email) }}" />
                        </flux:field>
                        <div class="md:col-span-2">
                            <label class="flex items-center gap-3 p-3 bg-zinc-50 dark:bg-white/5 rounded-xl border border-zinc-100 dark:border-white/5 cursor-pointer">
                                <input type="checkbox" name="status" value="1" {{ old('status', $branch->status) ? 'checked' : '' }} class="size-5 rounded text-[var(--active-2)]">
                                <div>
                                    <span class="block text-xs font-black uppercase text-zinc-900 dark:text-white">Online Garage</span>
                                    <span class="block text-[10px] text-zinc-500 font-medium">If unchecked, your garage will not be visible on the map for customers.</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </flux:card>
            </div>

            <!-- Business Hours Tab -->
            <div x-show="tab === 'hours'" class="space-y-4">
                <flux:card class="!p-4 rounded-2xl">
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-4">Business Hours</h3>
                    <div class="grid grid-cols-1 gap-2">
                        @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                        <div class="flex flex-wrap items-center justify-between gap-2 p-3 bg-zinc-50 dark:bg-white/5 rounded-xl border border-zinc-100 dark:border-white/5">
                            <span class="text-xs font-bold uppercase w-24">{{ $day }}</span>
                            <div class="flex items-center gap-2">
                                <flux:input type="time" name="business_hours[{{ $day }}][open]" value="{{ $branch->business_hours[$day]['open'] ?? '08:00' }}" class="!w-32" />
                                <span class="text-zinc-400">to</span>
                                <flux:input type="time" name="business_hours[{{ $day }}][close]" value="{{ $branch->business_hours[$day]['close'] ?? '18:00' }}" class="!w-32" />
                            </div>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="business_hours[{{ $day }}][closed]" {{ isset($branch->business_hours[$day]['closed']) ? 'checked' : '' }} class="rounded text-red-500">
                                <span class="text-[10px] font-bold uppercase text-red-500">Closed</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </flux:card>
            </div>

            <!-- Branding Tab -->
            <div x-show="tab === 'branding'" class="space-y-4">
                <flux:card class="!p-4 rounded-2xl">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-4">
                            <flux:label>Garage Logo</flux:label>
                            <div class="size-32 rounded-3xl bg-zinc-100 dark:bg-white/5 border-2 border-dashed border-zinc-200 dark:border-white/10 flex items-center justify-center relative overflow-hidden group">
                                @if($branch->logo_path)
                                    <img src="{{ Storage::url($branch->logo_path) }}" class="absolute inset-0 size-full object-cover">
                                @else
                                    <flux:icon icon="photo" class="size-8 text-zinc-300" />
                                @endif
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all cursor-pointer">
                                    <span class="text-[9px] font-black text-white uppercase">Change</span>
                                </div>
                                <input type="file" name="logo" class="absolute inset-0 opacity-0 cursor-pointer">
                            </div>
                        </div>
                    </div>
                </flux:card>
            </div>

            <!-- Billing Tab -->
            <div x-show="tab === 'billing'" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @forelse($plans as $plan)
                        <div class="bg-[var(--surface)] p-4 rounded-2xl border {{ $subscription?->plan_id === $plan->id ? 'border-blue-500' : 'border-zinc-100 dark:border-white/5' }} relative">
                            @if($subscription?->plan_id === $plan->id)<span class="absolute top-3 right-3 bg-blue-500 text-white text-[8px] font-black uppercase px-2 py-1 rounded-full">Current</span>@endif
                            <h3 class="text-base font-black uppercase tracking-tight">{{ $plan->name }}</h3>
                            <p class="text-[10px] text-zinc-500 mt-1">{{ $plan->description }}</p>
                            <div class="mt-3 text-xl font-black">{{ number_format($plan->monthly_price, 0, ',', ' ') }} F <span class="text-[10px] font-bold text-zinc-400">/mo</span></div>
                            <p class="mt-2 text-[9px] text-zinc-500">{{ $plan->max_branches }} branch(es) · {{ $plan->max_employees }} employee(s)</p>
                        </div>
                    @empty
                        <p class="text-xs text-zinc-500">No plan available.</p>
                    @endforelse
                </div>
            </div>

            <div class="mt-4 flex justify-end">
                <button type="submit" class="btn-premium-primary !px-10">Save Settings</button>
            </div>
        </form>

        <!-- Dedicated Location Form -->
        <form x-show="tab === 'location'" method="POST" action="{{ route('garage.settings.update-location') }}" x-init="$watch('tab', value => { if (value === 'location') { setTimeout(() => { initSettingsMap(); }, 150); } })">
            @csrf
            @method('PATCH')

            <flux:card class="!p-4 rounded-2xl space-y-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xs font-black uppercase tracking-widest text-zinc-800 dark:text-zinc-200">GPS Coordinates & OpenStreetMap</h3>
                    <flux:button type="button" onclick="detectLocation()" variant="ghost" size="xs" class="rounded-lg text-[9px] font-black uppercase tracking-widest text-blue-600">
                        <flux:icon icon="map-pin" class="size-3 mr-2" /> My Location
                    </flux:button>
                </div>

                <p class="text-xs text-zinc-500 mb-3">Click on the map or drag the marker to set your garage's geographical location.</p>
                <div id="settings-map" class="w-full rounded-2xl border border-zinc-200 dark:border-white/10 mb-4 shadow-sm" style="height: 340px; min-height: 340px; position: relative; z-index: 1;"></div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="latitude_input" class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-1">Latitude</label>
                        <input id="latitude_input" name="latitude" type="text" value="{{ old('latitude', $branch->latitude) }}" placeholder="0.000000" class="w-full px-4 py-2.5 rounded-xl border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 text-zinc-900 dark:text-white font-mono text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required />
                    </div>
                    <div>
                        <label for="longitude_input" class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-1">Longitude</label>
                        <input id="longitude_input" name="longitude" type="text" value="{{ old('longitude', $branch->longitude) }}" placeholder="0.000000" class="w-full px-4 py-2.5 rounded-xl border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 text-zinc-900 dark:text-white font-mono text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required />
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="btn-premium-primary !px-8 flex items-center gap-2">
                        <i class="hgi-stroke hgi-location-01 size-4"></i>
                        Save Geolocation
                    </button>
                </div>
            </flux:card>
        </form>

        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script>
            let settingsMap = null;
            let settingsMarker = null;

            function initSettingsMap() {
                if (settingsMap) {
                    setTimeout(() => { settingsMap.invalidateSize(); }, 100);
                    return;
                }

                const mapEl = document.getElementById('settings-map');
                if (!mapEl) return;

                const latInput = document.getElementById('latitude_input');
                const lngInput = document.getElementById('longitude_input');

                let defaultLat = parseFloat(latInput?.value) || {{ $branch->latitude ?? 4.0511 }};
                let defaultLng = parseFloat(lngInput?.value) || {{ $branch->longitude ?? 9.7679 }};

                settingsMap = L.map('settings-map', {
                    center: [defaultLat, defaultLng],
                    zoom: 14,
                    zoomControl: true
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(settingsMap);

                settingsMarker = L.marker([defaultLat, defaultLng], {draggable: true}).addTo(settingsMap);

                function updateInputs(lat, lng) {
                    const latVal = lat.toFixed(6);
                    const lngVal = lng.toFixed(6);

                    if (latInput) latInput.value = latVal;
                    if (lngInput) lngInput.value = lngVal;
                }

                settingsMap.on('click', function(e) {
                    settingsMarker.setLatLng(e.latlng);
                    updateInputs(e.latlng.lat, e.latlng.lng);
                });

                settingsMarker.on('dragend', function(e) {
                    const pos = settingsMarker.getLatLng();
                    updateInputs(pos.lat, pos.lng);
                });

                setTimeout(() => {
                    settingsMap.invalidateSize();
                }, 200);
            }

            window.detectLocation = function() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        const latInput = document.getElementById('latitude_input');
                        const lngInput = document.getElementById('longitude_input');
                        if (latInput) latInput.value = lat.toFixed(6);
                        if (lngInput) lngInput.value = lng.toFixed(6);

                        if (!settingsMap) {
                            initSettingsMap();
                        } else {
                            settingsMap.setView([lat, lng], 15);
                            settingsMarker.setLatLng([lat, lng]);
                        }
                    }, function(error) {
                        alert("Error detecting location: " + error.message);
                    });
                } else {
                    alert("Geolocation is not supported by your browser.");
                }
            };
        </script>
    </div>
</x-layouts::app>
