<x-layouts::app :title="__('nav.Abonnement')">
    <div class="mx-auto flex max-w-5xl flex-col gap-4 pb-6">
        <div><h1 class="text-lg font-black uppercase tracking-tight">Mon abonnement</h1><p class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">Choisissez ou changez votre formule Piiston.</p></div>
        @if($subscription?->status === 'active')<div class="rounded-xl border border-emerald-200 bg-emerald-500/5 p-3 text-xs font-bold text-emerald-700">Formule active : {{ $subscription->plan->name }} jusqu’au {{ optional($subscription->end_date)->format('d/m/Y') }}</div>@endif
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            @forelse($plans as $plan)
                <div class="rounded-2xl border {{ $subscription?->plan_id === $plan->id ? 'border-blue-500' : 'border-zinc-100 dark:border-white/5' }} bg-[var(--surface)] p-4 shadow-sm">
                    <h2 class="text-base font-black uppercase">{{ $plan->name }}</h2><p class="mt-1 text-[10px] text-zinc-500">{{ $plan->description }}</p>
                    <div class="mt-4 text-xl font-black">{{ number_format($plan->price, 0, ',', ' ') }} {{ $plan->currency?->code ?? 'XAF' }}<span class="text-[10px] text-zinc-400"> / {{ $plan->duration }}</span></div>
                    <ul class="mt-3 space-y-1">@foreach($plan->features ?? [] as $feature)<li class="text-[10px] font-bold text-zinc-500">✓ {{ str_replace('_', ' ', ucfirst($feature)) }}</li>@endforeach</ul>
                    @if($subscription?->plan_id !== $plan->id)<form method="POST" action="{{ route('subscriptions.checkout') }}" class="mt-4">@csrf<input type="hidden" name="plan_id" value="{{ $plan->id }}"><flux:button type="submit" variant="primary" class="w-full rounded-xl">Choisir cette formule</flux:button></form>@endif
                </div>
            @empty
                <p class="text-sm text-zinc-500">Aucune formule disponible.</p>
            @endforelse
        </div>
    </div>
</x-layouts::app>
