<x-layouts::app :title="__('garage.Réception Véhicule')">
    <div x-data="{
        step: 1,
        checklist: {
            spare_tire: false,
            jack: false,
            extinguisher: false,
            triangle: false,
            documents: false
        },
        signature: '',
        initCanvas() {
            const canvas = this.$refs.canvas;
            const ctx = canvas.getContext('2d');
            let drawing = false;

            canvas.addEventListener('mousedown', () => drawing = true);
            canvas.addEventListener('mouseup', () => {
                drawing = false;
                this.signature = canvas.toDataURL();
            });
            canvas.addEventListener('mousemove', (e) => {
                if (!drawing) return;
                const rect = canvas.getBoundingClientRect();
                ctx.lineWidth = 2;
                ctx.lineCap = 'round';
                ctx.strokeStyle = '#000';
                ctx.lineTo(e.clientX - rect.left, e.clientY - rect.top);
                ctx.stroke();
                ctx.beginPath();
                ctx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
            });
        },
        clearCanvas() {
            const canvas = this.$refs.canvas;
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.beginPath();
            this.signature = '';
        }
    }" x-init="initCanvas()">
        <div class="flex items-center justify-between mb-6">
            <div>
                <flux:heading size="xl" class="font-black uppercase tracking-tight">Réception Véhicule</flux:heading>
                <flux:text class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">Flux de réception professionnel - {{ $branch->name }}</flux:text>
            </div>
            <div class="flex items-center gap-2">
                <template x-for="s in 4">
                    <div :class="step >= s ? 'bg-[var(--active-2)]' : 'bg-zinc-200 dark:bg-white/10'" class="size-2 rounded-full transition-all duration-300"></div>
                </template>
            </div>
        </div>

        <form method="POST" action="{{ route('garage.check-ins.store') }}" id="receptionForm">
            @csrf
            <input type="hidden" name="signature_data" x-model="signature">

            <!-- Step 1: Client & Vehicle Info -->
            <div x-show="step === 1" x-transition>
                <flux:card class="space-y-6">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="size-8 rounded-lg bg-blue-500/10 text-blue-500 flex items-center justify-center font-black">1</div>
                        <h3 class="text-sm font-black uppercase tracking-wider">Informations de base</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <flux:field>
                            <flux:label>Client *</flux:label>
                            <flux:select name="customer_id" required>
                                <option value="">Choisir un client</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->user->name ?? 'Client #'.$customer->id }}</option>
                                @endforeach
                            </flux:select>
                        </flux:field>

                        <flux:field>
                            <flux:label>Véhicule *</flux:label>
                            <flux:select name="vehicle_id" required>
                                <option value="">Choisir un véhicule</option>
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}">{{ $vehicle->brand->name }} {{ $vehicle->model->name }} ({{ $vehicle->license_plate }})</option>
                                @endforeach
                            </flux:select>
                        </flux:field>

                        <flux:field>
                            <flux:label>Kilométrage (KM) *</flux:label>
                            <flux:input type="number" name="mileage" placeholder="Ex: 45000" required />
                        </flux:field>

                        <flux:field>
                            <flux:label>Niveau Carburant</flux:label>
                            <flux:select name="fuel_level">
                                <option value="empty">Vide</option>
                                <option value="1/4">1/4</option>
                                <option value="1/2">1/2</option>
                                <option value="3/4">3/4</option>
                                <option value="full">Plein</option>
                            </flux:select>
                        </flux:field>
                    </div>

                    <div class="flex justify-end mt-8">
                        <flux:button @click="step = 2" variant="primary" class="font-black uppercase tracking-widest px-8">Suivant</flux:button>
                    </div>
                </flux:card>
            </div>

            <!-- Step 2: Inspection Checklist -->
            <div x-show="step === 2" x-transition>
                <flux:card class="space-y-6">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="size-8 rounded-lg bg-orange-500/10 text-orange-500 flex items-center justify-center font-black">2</div>
                        <h3 class="text-sm font-black uppercase tracking-wider">Inspection & Équipements</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Équipements Présents</flux:label>
                            <div class="grid grid-cols-1 gap-3">
                                <label class="flex items-center gap-3 p-3 rounded-xl border border-zinc-100 dark:border-white/5 cursor-pointer hover:bg-zinc-50 dark:hover:bg-white/[0.02] transition-colors">
                                    <input type="checkbox" name="checklist[spare_tire]" x-model="checklist.spare_tire" class="rounded text-[var(--active-2)]">
                                    <span class="text-xs font-bold uppercase text-zinc-600 dark:text-zinc-300">Roue de secours</span>
                                </label>
                                <label class="flex items-center gap-3 p-3 rounded-xl border border-zinc-100 dark:border-white/5 cursor-pointer hover:bg-zinc-50 dark:hover:bg-white/[0.02] transition-colors">
                                    <input type="checkbox" name="checklist[jack]" x-model="checklist.jack" class="rounded text-[var(--active-2)]">
                                    <span class="text-xs font-bold uppercase text-zinc-600 dark:text-zinc-300">Cric / Outillage</span>
                                </label>
                                <label class="flex items-center gap-3 p-3 rounded-xl border border-zinc-100 dark:border-white/5 cursor-pointer hover:bg-zinc-50 dark:hover:bg-white/[0.02] transition-colors">
                                    <input type="checkbox" name="checklist[extinguisher]" x-model="checklist.extinguisher" class="rounded text-[var(--active-2)]">
                                    <span class="text-xs font-bold uppercase text-zinc-600 dark:text-zinc-300">Extincteur</span>
                                </label>
                                <label class="flex items-center gap-3 p-3 rounded-xl border border-zinc-100 dark:border-white/5 cursor-pointer hover:bg-zinc-50 dark:hover:bg-white/[0.02] transition-colors">
                                    <input type="checkbox" name="checklist[documents]" x-model="checklist.documents" class="rounded text-[var(--active-2)]">
                                    <span class="text-xs font-bold uppercase text-zinc-600 dark:text-zinc-300">Documents du véhicule</span>
                                </label>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-400">État de la carrosserie / Dommages</flux:label>
                            <flux:textarea name="vehicle_condition" placeholder="Décrivez les rayures, bosses ou dommages visibles..." rows="6"></flux:textarea>
                        </div>
                    </div>

                    <div class="flex justify-between mt-8">
                        <flux:button @click="step = 1" variant="filled" class="font-black uppercase tracking-widest px-8">Précédent</flux:button>
                        <flux:button @click="step = 3" variant="primary" class="font-black uppercase tracking-widest px-8">Suivant</flux:button>
                    </div>
                </flux:card>
            </div>

            <!-- Step 3: Media (Upload Photos) -->
            <div x-show="step === 3" x-transition>
                <flux:card class="space-y-6">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="size-8 rounded-lg bg-purple-500/10 text-purple-500 flex items-center justify-center font-black">3</div>
                        <h3 class="text-sm font-black uppercase tracking-wider">Photos & Documents</h3>
                    </div>

                    <div class="border-2 border-dashed border-zinc-200 dark:border-white/10 rounded-2xl p-12 text-center flex flex-col items-center gap-4">
                        <div class="size-16 rounded-full bg-zinc-100 dark:bg-white/5 flex items-center justify-center">
                            <flux:icon icon="camera" class="size-8 text-zinc-400" />
                        </div>
                        <div>
                            <p class="text-sm font-bold text-zinc-700 dark:text-zinc-300">Prendre des photos du véhicule</p>
                            <p class="text-[10px] text-zinc-500 font-medium mt-1 uppercase tracking-widest">Minimum 4 photos conseillées (Avant, Arrière, Côtés)</p>
                        </div>
                        <input type="file" multiple class="hidden" id="vehicle_photos" accept="image/*">
                        <flux:button onclick="document.getElementById('vehicle_photos').click()" size="sm" variant="filled" class="font-black uppercase tracking-widest">Ajouter des fichiers</flux:button>
                    </div>

                    <div class="flex justify-between mt-8">
                        <flux:button @click="step = 2" variant="filled" class="font-black uppercase tracking-widest px-8">Précédent</flux:button>
                        <flux:button @click="step = 4" variant="primary" class="font-black uppercase tracking-widest px-8">Suivant</flux:button>
                    </div>
                </flux:card>
            </div>

            <!-- Step 4: Summary & Signature -->
            <div x-show="step === 4" x-transition>
                <flux:card class="space-y-6">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="size-8 rounded-lg bg-green-500/10 text-green-500 flex items-center justify-center font-black">4</div>
                        <h3 class="text-sm font-black uppercase tracking-wider">Confirmation & Signature</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Résumé</flux:label>
                            <div class="bg-zinc-50 dark:bg-white/[0.01] rounded-2xl p-4 border border-zinc-100 dark:border-white/5 space-y-3">
                                <div class="flex justify-between text-xs">
                                    <span class="text-zinc-500 font-bold uppercase">Créer Ordre de Réparation ?</span>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="create_repair_order" value="1" class="sr-only peer" checked>
                                        <div class="w-9 h-5 bg-zinc-200 peer-focus:outline-none rounded-full peer dark:bg-zinc-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-[var(--active-2)]"></div>
                                    </label>
                                </div>
                                <flux:textarea name="notes" placeholder="Notes additionnelles ou plaintes du client..." rows="4"></flux:textarea>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <flux:label class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Signature du Client</flux:label>
                                <button type="button" @click="clearCanvas()" class="text-[9px] font-black uppercase text-red-500 hover:underline">Effacer</button>
                            </div>
                            <div class="bg-white rounded-2xl border-2 border-zinc-200 dark:border-white/10 overflow-hidden relative">
                                <canvas x-ref="canvas" width="400" height="200" class="w-full h-[200px] cursor-crosshair bg-white"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between mt-8">
                        <flux:button @click="step = 3" variant="filled" class="font-black uppercase tracking-widest px-8">Précédent</flux:button>
                        <flux:button type="submit" variant="primary" class="font-black uppercase tracking-widest bg-gradient-to-br from-green-500 to-emerald-600 border-none px-12">Finaliser la Réception</flux:button>
                    </div>
                </flux:card>
            </div>
        </form>
    </div>
</x-layouts::app>
