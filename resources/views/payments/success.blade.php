<x-layouts::app :title="__('payments.Payment confirmed')">
    <div class="mx-auto flex max-w-lg flex-col items-center gap-4 rounded-2xl border border-emerald-200 bg-[var(--surface)] p-8 text-center shadow-sm dark:border-emerald-500/20">
        <div class="flex size-14 items-center justify-center rounded-full bg-emerald-500/10 text-emerald-600"><flux:icon icon="check" class="size-7" /></div>
        <h1 class="text-lg font-black uppercase">Paiement confirmé</h1>
        <p class="text-sm text-zinc-500">Votre abonnement Piiston est maintenant actif.</p>
        <flux:button href="{{ route('dashboard') }}" variant="primary" class="rounded-xl">Retour au dashboard</flux:button>
    </div>
</x-layouts::app>
