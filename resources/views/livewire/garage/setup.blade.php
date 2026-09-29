<div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="mb-10 text-center">
        <h1 class="text-xl font-black uppercase tracking-tighter text-zinc-900 dark:text-white mb-2">
            @if($step == 1) Configure your Garage @elseif($step == 2) Company Profile @else Your Headquarters @endif
        </h1>
        <p class="text-zinc-500 font-medium max-w-lg mx-auto">
            @if($step == 1) Choose how you want to manage your activities on PIISTON. @elseif($step == 2) Complete your company's legal and commercial information. @else Define the coordinates of your first workshop. @endif
        </p>

        <!-- Progress Steps -->
        <div class="flex items-center justify-center gap-4 mt-8">
            @foreach([1, 2, 3] as $s)
                <div class="flex items-center gap-2">
                    <div class="size-8 rounded-full flex items-center justify-center text-xs font-black {{ $step >= $s ? 'bg-[var(--active-2)] text-white' : 'bg-zinc-100 dark:bg-white/5 text-zinc-400' }}">
                        {{ $s }}
                    </div>
                    @if($s < 3)
                        <div class="w-8 h-px bg-zinc-200 dark:bg-white/10"></div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-[var(--surface)] rounded-[32px] border border-zinc-100 dark:border-white/5 shadow-xl overflow-hidden">
        @if($step == 1)
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <button wire:click="selectType(false)" class="group flex flex-col items-center gap-6 p-8 rounded-[24px] border-2 border-transparent bg-zinc-50 dark:bg-white/[0.02] hover:border-[var(--active-2)]/30 transition-all text-center">
                    <div class="size-20 rounded-[20px] bg-[var(--active)]/10 text-[var(--active-2)] flex items-center justify-center group-hover:scale-110 transition-transform">
                        <flux:icon icon="wrench-screwdriver" class="size-10" />
                    </div>
                    <div>
<h4 class="text-base font-black text-zinc-900 dark:text-white uppercase tracking-tight">Single Garage</h4>
<p class="text-xs text-zinc-500 font-medium leading-relaxed mt-2">A single workshop managed by yourself. Ideal for independent mechanics.</p>
                    </div>
                </button>

                <button wire:click="selectType(true)" class="group flex flex-col items-center gap-6 p-8 rounded-[24px] border-2 border-transparent bg-zinc-50 dark:bg-white/[0.02] hover:border-purple-500/30 transition-all text-center">
                    <div class="size-20 rounded-[20px] bg-purple-500/10 text-purple-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <flux:icon icon="building-office-2" class="size-10" />
                    </div>
                    <div>
<h4 class="text-base font-black text-zinc-900 dark:text-white uppercase tracking-tight">Branch Network</h4>
<p class="text-xs text-zinc-500 font-medium leading-relaxed mt-2">Manage multiple sites (Branches), delegate local management and track global stats.</p>
                    </div>
                </button>
            </div>

        @elseif($step == 2)
            <form wire:submit="createCompany" class="p-8 space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                    <!-- Logo Upload -->
                    <div class="md:col-span-12 flex flex-col items-center gap-4 mb-4">
                        <div class="relative group">
                            <div class="size-24 rounded-3xl bg-zinc-100 dark:bg-white/5 border-2 border-dashed border-zinc-200 dark:border-white/10 flex items-center justify-center overflow-hidden">
                                @if($company_logo)
                                    <img src="{{ $company_logo->temporaryUrl() }}" class="w-full h-full object-cover">
                                @else
                                    <flux:icon icon="photo" class="size-8 text-zinc-300" />
                                @endif
                            </div>
                            <label class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer rounded-3xl">
                                <flux:icon icon="camera" class="size-6 text-white" />
                                <input type="file" wire:model="company_logo" class="hidden">
                            </label>
                        </div>
                        <span class="text-[10px] font-black uppercase text-zinc-400 tracking-widest">Company Logo</span>
                    </div>

                    <!-- Basic Info -->
                    <div class="md:col-span-8">
                        <flux:input wire:model="company_name" label="COMMERCIAL NAME" placeholder="Ex: Expert Auto Services" required />
                    </div>
                    <div class="md:col-span-4">
                        <flux:select wire:model="country_id" label="COUNTRY">
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </flux:select>
                    </div>

                    <!-- Legal Info -->
                    <div class="md:col-span-6">
                        <flux:input wire:model="company_legal_name" label="LEGAL NAME" placeholder="Full legal name" />
                    </div>
                    <div class="md:col-span-3">
                        <flux:input wire:model="company_registration_number" label="COMMERCIAL REGISTRY #" placeholder="Commercial Registry..." />
                    </div>
                    <div class="md:col-span-3">
                        <flux:input wire:model="company_tax_number" label="TAX ID (TIN)" placeholder="Tax ID..." />
                    </div>

                    <!-- Contact & Web -->
                    <div class="md:col-span-4">
                        <flux:input wire:model="company_email" label="PROFESSIONAL EMAIL" type="email" required />
                    </div>
                    <div class="md:col-span-4">
                        <flux:input wire:model="company_phone" label="PHONE" required />
                    </div>
                    <div class="md:col-span-4">
                        <flux:input wire:model="company_website" label="WEBSITE" placeholder="https://..." />
                    </div>

                    <div class="md:col-span-12">
                        <flux:textarea wire:model="company_description" label="DESCRIPTION" placeholder="Briefly describe your services and specialties..." rows="3" />
                    </div>
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-zinc-100 dark:border-white/5">
                    <button type="button" wire:click="$set('step', 1)" class="text-xs font-black uppercase text-zinc-400 hover:text-zinc-600 transition-colors">Back</button>
                    <flux:button type="submit" variant="primary" class="px-10">Continue</flux:button>
                </div>
            </form>

        @elseif($step == 3)
            <form wire:submit="createBranch" class="p-8 space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                    <div class="md:col-span-12 bg-blue-500/5 border border-blue-500/10 p-4 rounded-2xl flex gap-4 items-center">
                        <div class="size-10 rounded-xl bg-blue-500/10 text-blue-500 flex items-center justify-center shrink-0">
                            <flux:icon icon="information-circle" />
                        </div>
                        <p class="text-xs text-blue-600 dark:text-blue-400 font-medium leading-relaxed">
                            C'est ici que vos clients viendront déposer leurs véhicules. Vous pourrez ajouter d'autres annexes plus tard.
                        </p>
                    </div>

                    <div class="md:col-span-12">
                        <flux:input wire:model="branch_name" label="SITE / WORKSHOP NAME" placeholder="Ex: Main Headquarters, Downtown Branch..." required />
                    </div>

                    <div class="md:col-span-6">
                        <flux:input wire:model="branch_email" label="SITE EMAIL" type="email" />
                    </div>
                    <div class="md:col-span-6">
                        <flux:input wire:model="branch_phone" label="SITE PHONE" />
                    </div>

                    <div class="md:col-span-8">
                        <flux:input wire:model="branch_address" label="PHYSICAL ADDRESS" placeholder="Street, district, landmarks..." />
                    </div>
                    <div class="md:col-span-4">
                        <flux:input wire:model="branch_city" label="CITY" placeholder="Ex: New York" />
                    </div>

                    <!-- Geolocation -->
                    <div class="md:col-span-12 mt-4">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-xs font-black uppercase text-zinc-900 dark:text-white tracking-widest">Geolocation</h4>
                            <button type="button" onclick="detectLocation()" class="flex items-center gap-2 text-[10px] font-black uppercase text-[var(--active-2)] hover:opacity-80 transition-opacity">
                                <flux:icon icon="map-pin" class="size-3" />
                                Detect my location
                            </button>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <flux:input wire:model="latitude" label="LATITUDE" placeholder="0.000000" type="number" step="any" />
                            <flux:input wire:model="longitude" label="LONGITUDE" placeholder="0.000000" type="number" step="any" />
                        </div>
                        <p class="text-[10px] text-zinc-500 font-medium mt-2">Geolocation allows your customers to find you on the map.</p>
                    </div>
                </div>

                <script>
                    function detectLocation() {
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(function(position) {
                                @this.set('latitude', position.coords.latitude);
                                @this.set('longitude', position.coords.longitude);
                            }, function(error) {
                                alert("Error detecting location: " + error.message);
                            });
                        } else {
                            alert("Geolocation is not supported by your browser.");
                        }
                    }
                </script>

                <div class="flex items-center justify-between pt-6 border-t border-zinc-100 dark:border-white/5">
                    <button type="button" wire:click="$set('step', 2)" class="text-xs font-black uppercase text-zinc-400 hover:text-zinc-600 transition-colors">Back</button>
                    <flux:button type="submit" variant="primary" class="px-10">Complete my setup</flux:button>
                </div>
            </form>
        @endif
    </div>

    <div class="mt-8 text-center">
        <p class="text-[10px] font-black uppercase text-zinc-400 tracking-widest">Piiston — Digitalizing the automotive industry</p>
    </div>
</div>
