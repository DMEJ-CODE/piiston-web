<x-layouts::app :title="__('Réparation #'.$repair->id)">
    <div class="flex flex-col gap-4">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-black uppercase text-zinc-900 dark:text-white tracking-tight">Réparation #{{ $repair->id }}</h2>
                <p class="text-[9px] text-zinc-500 font-bold uppercase tracking-widest">{{ $repair->vehicle->license_plate ?? 'N/A' }} — {{ $repair->vehicle->brand->name ?? '' }}</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 rounded bg-zinc-100 dark:bg-white/5 text-[8px] font-black uppercase tracking-widest">{{ $repair->status }}</span>
                <flux:button href="{{ route('garage.repairs.index') }}" variant="filled" size="xs" class="font-black uppercase tracking-widest px-4">Retour</flux:button>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-4">
            <!-- Left -->
            <div class="col-span-12 lg:col-span-8 space-y-4">
                <div class="bg-[var(--surface)] p-4 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm">
                    <h3 class="text-[10px] font-black text-zinc-900 dark:text-white uppercase tracking-widest mb-4">Informations</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[8px] font-black text-zinc-400 uppercase">Client</span>
                            <span class="text-[11px] font-bold text-zinc-700">{{ $repair->garageCustomer->user->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[8px] font-black text-zinc-400 uppercase">Priorité</span>
                            <span class="text-[11px] font-black @if($repair->priority === 'URGENT') text-red-500 @else text-zinc-700 @endif">{{ $repair->priority }}</span>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[8px] font-black text-zinc-400 uppercase">Mécanicien</span>
                            <span class="text-[11px] font-bold text-zinc-700">{{ $repair->mechanic->name ?? '---' }}</span>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[8px] font-black text-zinc-400 uppercase">Bai</span>
                            <span class="text-[11px] font-bold text-zinc-700">{{ $repair->bay->name ?? '---' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Diagnosis -->
                <div class="bg-[var(--surface)] p-4 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-[10px] font-black text-zinc-900 dark:text-white uppercase tracking-widest">Diagnostic</h3>
                        @if(!$repair->diagnosis) <flux:button size="xs" variant="ghost" class="text-[8px] font-black uppercase text-blue-500">Ajouter</flux:button> @endif
                    </div>
                    @if($repair->diagnosis)
                        <div class="flex flex-col gap-2">
                            <p class="text-[11px] font-bold text-zinc-800">{{ $repair->diagnosis->detected_problem }}</p>
                            <span class="text-[8px] font-black uppercase px-2 py-0.5 w-fit rounded bg-amber-100 text-amber-700">{{ $repair->diagnosis->severity }}</span>
                        </div>
                    @else
                        <div class="py-4 text-center border-2 border-dashed border-zinc-50 rounded-xl">
                            <p class="text-[9px] font-black text-zinc-300 uppercase">En attente de diagnostic</p>
                        </div>
                    @endif
                </div>

                <!-- Tasks -->
                <div class="bg-[var(--surface)] p-4 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm">
                    <h3 class="text-[10px] font-black text-zinc-900 dark:text-white uppercase tracking-widest mb-4">Opérations</h3>
                    <div class="space-y-2">
                        @forelse($repair->tasks as $task)
                        <div class="flex items-center justify-between p-2 rounded-lg bg-zinc-50 dark:bg-white/5 border border-zinc-100">
                            <span class="text-[11px] font-bold text-zinc-800">{{ $task->title }}</span>
                            <span class="text-[8px] font-black uppercase px-2 py-0.5 rounded {{ $task->status == 'completed' ? 'bg-green-100 text-green-700' : 'bg-zinc-200' }}">{{ $task->status }}</span>
                        </div>
                        @empty
                        <p class="text-[9px] text-zinc-400 font-bold uppercase text-center">Aucune tâche</p>
                        @endforelse
                    </div>
                </div>

                <livewire:garage.repair-chat :repair="$repair" />
            </div>

            <!-- Right -->
            <div class="col-span-12 lg:col-span-4 space-y-4">
                <div class="bg-zinc-900 p-6 rounded-[20px] text-white shadow-xl relative overflow-hidden">
                    <h5 class="text-[8px] font-black uppercase tracking-[3px] text-white/40 text-center mb-6">Mise à jour Statut</h5>
                    <form method="POST" action="{{ route('garage.repairs.update-status', $repair->id) }}" class="space-y-3">
                        @csrf @method('PATCH')
                        <select name="status" class="w-full bg-white/5 border-white/10 text-white text-[10px] font-bold rounded-lg h-9">
                            @foreach(['REQUESTED', 'IN_PROGRESS', 'DIAGNOSIS', 'WAITING_PARTS', 'COMPLETED'] as $st)
                                <option value="{{ $st }}" {{ $repair->status === $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                        <flux:button type="submit" size="xs" class="w-full bg-blue-600 text-white border-none font-black h-9">Valider</flux:button>
                    </form>
                </div>

                <div class="flex flex-col gap-2">
                    <h5 class="text-[8px] font-black text-zinc-400 uppercase tracking-widest px-2">Actions</h5>
                    @if(!$repair->invoice)
                        <form method="POST" action="{{ route('garage.repairs.invoice.store', $repair->id) }}">
                            @csrf
                            <flux:button type="submit" variant="ghost" class="w-full justify-start gap-3 rounded-xl py-2 bg-[var(--surface)] border border-zinc-100">
                                <flux:icon icon="banknotes" class="size-3.5 text-green-500" />
                                <span class="text-[10px] font-bold uppercase">Facturer</span>
                            </flux:button>
                        </form>
                    @else
                        <flux:button href="{{ route('garage.repairs.invoice.print', $repair->id) }}" target="_blank" variant="ghost" class="w-full justify-start gap-3 rounded-xl py-2 bg-[var(--surface)] border border-zinc-100">
                            <flux:icon icon="printer" class="size-3.5 text-zinc-500" />
                            <span class="text-[10px] font-bold uppercase">Imprimer</span>
                        </flux:button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
