<x-layouts::app :title="__('garage.Support Technique')">
    <x-garage.index-header
        title="Centre de Support"
        subtitle="Assistance technique et support pro - {{ $branch->name }}"
        actionText="Nouveau Ticket"
        actionUrl="#"
        actionIcon="plus-circle"
        searchPlaceholder="Rechercher dans vos tickets..."
    />

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Tickets Area -->
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-[var(--surface)] p-2 rounded-2xl border border-zinc-100 dark:border-white/5 shadow-card-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-left border-b border-zinc-50 dark:border-white/5 bg-zinc-50/50 dark:bg-white/[0.02]">
                                <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Ticket</th>
                                <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Sujet</th>
                                <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">Urgence</th>
                                <th class="py-3 px-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">État</th>
                                <th class="py-3 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-50 dark:divide-white/[0.02]">
                            @forelse($tickets ?? [] as $ticket)
                                <!-- Real data loop -->
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-zinc-400">
                                        <p class="text-[10px] font-black uppercase">Aucun ticket</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Ticket Form -->
            <flux:card class="p-6 rounded-[16px] border-none shadow-xl">
                <h3 class="text-[11px] font-black uppercase tracking-widest mb-6">Nouveau ticket support</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <flux:field>
                            <flux:label class="text-[9px] uppercase font-black">Objet</flux:label>
                            <flux:input placeholder="Sujet de votre demande" class="rounded-lg h-9 text-xs" />
                        </flux:field>
                        <flux:field>
                            <flux:label class="text-[9px] uppercase font-black">Niveau</flux:label>
                            <flux:select class="rounded-lg h-9 text-[10px]">
                                <option>Basse</option>
                                <option selected>Moyenne</option>
                                <option>Haute</option>
                            </flux:select>
                        </flux:field>
                    </div>
                    <flux:field>
                        <flux:label class="text-[9px] uppercase font-black">Description</flux:label>
                        <flux:textarea rows="3" placeholder="Votre message..." class="rounded-xl text-xs"></flux:textarea>
                    </flux:field>
                    <div class="flex justify-end">
                        <flux:button variant="primary" class="bg-gradient-to-br from-[var(--active-2)] to-[var(--active)] px-8 h-9 text-[10px] font-black uppercase tracking-widest border-none shadow-md rounded-lg">Envoyer</flux:button>
                    </div>
                </div>
            </flux:card>
        </div>

        <!-- Support Info Column -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-zinc-900 text-white p-6 rounded-[24px] shadow-lg relative overflow-hidden">
                <h4 class="text-sm font-black uppercase tracking-tight mb-2">Aide immédiate ?</h4>
                <p class="text-[9px] text-white/40 font-bold uppercase tracking-widest leading-relaxed">Assistance pro prioritaire par téléphone.</p>
                <div class="mt-6 p-4 bg-white/5 rounded-2xl border border-white/10 text-center">
                    <p class="text-xl font-black tracking-tighter">+237 655 000 000</p>
                </div>
            </div>

            <div class="bg-white dark:bg-white/5 p-6 rounded-[24px] border border-zinc-100 dark:border-white/5">
                <h4 class="text-[9px] font-black uppercase tracking-widest text-zinc-400 mb-4">FAQ Ressources</h4>
                <div class="space-y-2">
                    @foreach(['Gestion du personnel', 'Paiements Mobile Money', 'IA Diagnostic'] as $faq)
                    <a href="#" class="group flex items-center justify-between p-3 rounded-2xl border border-zinc-50 dark:border-white/5 hover:bg-zinc-50 transition-all">
                        <span class="text-[10px] font-bold text-zinc-600 dark:text-zinc-300">{{ $faq }}</span>
                        <flux:icon icon="chevron-right" class="size-3 text-zinc-300" />
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
