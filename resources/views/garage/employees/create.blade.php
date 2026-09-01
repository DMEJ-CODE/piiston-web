<x-layouts::app :title="__('Inviter un collaborateur')">
    <div class="max-w-2xl mx-auto py-6">
        <flux:heading size="xl" class="font-black uppercase tracking-tight">Inviter un collaborateur</flux:heading>
        <flux:text class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">Un lien sera envoyé par email pour rejoindre l'équipe.</flux:text>

        <flux:card class="mt-8">
            <form method="POST" action="{{ route('garage.employees.store') }}" class="space-y-6">
                @csrf

                <flux:field>
                    <flux:label>Adresse Email *</flux:label>
                    <flux:input type="email" name="email" placeholder="collaborateur@exemple.com" required />
                </flux:field>

                <flux:field>
                    <flux:label>Rôle interne *</flux:label>
                    <flux:select name="role" required>
                        <option value="MECHANIC">Mécanicien</option>
                        <option value="MANAGER">Responsable d'Atelier</option>
                        <option value="RECEPTIONIST">Réceptionniste</option>
                        <option value="ACCOUNTANT">Comptable</option>
                    </flux:select>
                </flux:field>

                <div class="pt-6 border-t border-zinc-100 dark:border-white/5 flex justify-end gap-3">
                    <flux:button href="{{ route('garage.employees.index') }}" variant="filled">Annuler</flux:button>
                    <flux:button type="submit" variant="primary" class="bg-blue-600 hover:bg-blue-700 px-8 font-black uppercase tracking-widest">Envoyer l'invitation</flux:button>
                </div>
            </form>
        </flux:card>

        <div class="mt-8 p-6 bg-zinc-50 dark:bg-white/5 rounded-3xl border border-zinc-200 dark:border-white/10">
            <h4 class="text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-4">À propos des rôles</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <p class="text-xs font-black text-zinc-700 dark:text-zinc-200">Mécanicien</p>
                    <p class="text-[10px] text-zinc-500 leading-relaxed">Accès aux réparations assignées, diagnostics et saisie des tâches.</p>
                </div>
                <div class="space-y-1">
                    <p class="text-xs font-black text-zinc-700 dark:text-zinc-200">Manager</p>
                    <p class="text-[10px] text-zinc-500 leading-relaxed">Contrôle total sur la branche, les employés et la validation finale.</p>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
