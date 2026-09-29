<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing | Piiston</title>
    <link rel="icon" href="{{ asset('piiston/favicon.ico') }}" sizes="any">
    <link rel="stylesheet" href="https://cdn.hugeicons.com/font/hgi-stroke-rounded.css" crossorigin="anonymous">
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/landing.js']) @fonts
</head>
<body class="landing-page">
    <header class="lp-header"><div class="lp-header__inner">
        <a href="{{ route('home') }}" class="lp-logo"><img src="{{ asset('piiston/android-chrome-192x192.png') }}" alt="Piiston Logo" class="lp-logo__img"><span class="lp-logo__text">Piiston</span></a>
        <nav class="lp-nav"><a href="{{ route('home') }}#solutions" class="lp-nav__link">Solutions</a><a href="{{ route('home') }}#marketplace" class="lp-nav__link">Marketplace</a><a href="{{ route('home') }}#fleet" class="lp-nav__link">Fleet</a><a href="{{ route('pricing.index') }}" class="lp-nav__link is-active">Pricing</a></nav>
        <div class="lp-header__actions">@auth<a href="{{ route('dashboard') }}" class="lp-btn lp-btn--primary lp-btn--sm">Dashboard</a>@else<a href="{{ route('login') }}" class="lp-btn lp-btn--ghost lp-btn--sm">Sign In</a><a href="{{ route('register') }}" class="lp-btn lp-btn--primary lp-btn--sm">Get Started</a>@endauth</div>
    </div></header>
    <main><section class="lp-section-vh" style="padding-top: 140px; background: var(--bg-section-c);"><div class="lp-container">
        <div class="lp-section-header" style="max-width: 720px; margin-bottom: 48px;">
            <p class="section-eyebrow section-eyebrow--brand">PIISTON PLANS</p><h1 class="section-title section-title--brand">Choose the plan that fits your business</h1>
            <p class="section-subtitle">One connected workspace for repairs, customers, inventory and payments. Choose the rhythm that fits your business.</p>
        </div>
        @if($plans->isEmpty())
            <div class="card-premium text-center"><p class="label-premium">Plans coming soon</p><p class="mt-3 text-sm text-slate-500 dark:text-slate-300">Our subscription plans are being prepared. Please check back shortly.</p></div>
        @else
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                @foreach($plans as $plan)
                    @php($isFeatured = $loop->iteration === (int) ceil($loop->count / 2))
                    <article class="relative flex h-full flex-col rounded-[28px] border p-7 transition duration-300 hover:-translate-y-1 {{ $isFeatured ? 'border-[var(--active)] bg-[var(--primary-dark)] text-white shadow-[0_24px_50px_rgba(23,43,58,0.22)]' : 'border-slate-200 bg-[var(--surface)] shadow-[var(--shadow-card)] dark:border-slate-800' }}">
                        @if($isFeatured)<span class="absolute -top-3 left-7 rounded-full bg-[var(--active)] px-3 py-1 text-[9px] font-black uppercase tracking-[0.2em] text-white">Recommended</span>@endif
                        <p class="label-premium {{ $isFeatured ? '!text-slate-300' : '' }}">{{ $plan->name }}</p>
                        <div class="mt-6 flex items-end gap-2"><span class="text-4xl font-black tracking-tight">{{ number_format($plan->price, 0, ',', ' ') }}</span><span class="pb-1 text-xs font-bold {{ $isFeatured ? 'text-slate-300' : 'text-slate-500' }}">{{ $plan->currency?->code ?? 'XAF' }} / {{ $plan->duration }}</span></div>
                        <p class="mt-5 min-h-12 text-sm leading-relaxed {{ $isFeatured ? 'text-slate-300' : 'text-slate-500 dark:text-slate-300' }}">{{ $plan->description ?: 'The essential tools to run a more organised automotive business.' }}</p>
                        <ul class="mt-6 flex flex-col gap-3 text-sm {{ $isFeatured ? 'text-slate-200' : 'text-slate-600 dark:text-slate-300' }}">
                            @forelse(($plan->features ?? []) as $feature)<li class="flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 mt-0.5 text-[var(--active)]"></i><span>{{ str_replace('_', ' ', ucfirst($feature)) }}</span></li>@empty<li class="flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 mt-0.5 text-[var(--active)]"></i><span>Full Piiston workspace included</span></li>@endforelse
                        </ul>
                        <a href="{{ route('pricing.show', $plan) }}" class="lp-btn mt-8 w-full justify-center {{ $isFeatured ? 'lp-btn--primary' : 'lp-btn--ghost' }}">View plan <i class="hgi-stroke hgi-arrow-right-01"></i></a>
                    </article>
                @endforeach
            </div>
        @endif
    </div></section></main>
</body>
</html>
