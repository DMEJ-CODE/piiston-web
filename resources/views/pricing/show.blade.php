<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $plan->name }} | Piiston</title>
    <link rel="icon" href="{{ asset('piiston/favicon.ico') }}" sizes="any">
    <link rel="stylesheet" href="https://cdn.hugeicons.com/font/hgi-stroke-rounded.css" crossorigin="anonymous">
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/landing.js']) @fonts
</head>
<body class="landing-page">
    <header class="lp-header"><div class="lp-header__inner">
        <a href="{{ route('home') }}" class="lp-logo"><img src="{{ asset('piiston/android-chrome-192x192.png') }}" alt="Piiston Logo" class="lp-logo__img"><span class="lp-logo__text">Piiston</span></a>
        <nav class="lp-nav"><a href="{{ route('home') }}#solutions" class="lp-nav__link">Solutions</a><a href="{{ route('pricing.index') }}" class="lp-nav__link is-active">Pricing</a></nav>
        <div class="lp-header__actions">@auth<a href="{{ route('dashboard') }}" class="lp-btn lp-btn--primary lp-btn--sm">Dashboard</a>@else<a href="{{ route('login') }}" class="lp-btn lp-btn--ghost lp-btn--sm">Sign In</a>@endauth</div>
    </div></header>
    <main class="lp-section-vh" style="padding-top: 140px; background: var(--bg-section-c);"><div class="lp-container">
        <a href="{{ route('pricing.index') }}" class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-widest text-[var(--active)] transition hover:text-[var(--primary-dark)]"><i class="hgi-stroke hgi-arrow-left-01"></i> All plans</a>
        <div class="mt-8 grid gap-6 lg:grid-cols-[1.35fr_0.75fr]">
            <section class="card-premium">
                <p class="label-premium">Plan overview</p><h1 class="mt-4 text-3xl font-black uppercase tracking-tight text-slate-900 dark:text-white">{{ $plan->name }}</h1>
                <p class="mt-5 max-w-2xl text-base leading-relaxed text-slate-500 dark:text-slate-300">{{ $plan->description ?: 'A focused operating system for teams that want every repair, customer and payment in one place.' }}</p>
                <div class="mt-8 flex flex-wrap items-end gap-3 border-y border-slate-200 py-6 dark:border-slate-800"><span class="text-5xl font-black tracking-tight text-[var(--primary-dark)] dark:text-white">{{ number_format($plan->price, 0, ',', ' ') }}</span><span class="pb-2 text-sm font-bold text-slate-500">{{ $plan->currency?->code ?? 'XAF' }} / {{ $plan->duration }}</span></div>
                <div class="mt-8"><p class="label-premium">Included in this plan</p><ul class="mt-5 grid gap-4 sm:grid-cols-2">
                    @forelse(($plan->features ?? []) as $feature)<li class="flex items-start gap-3 text-sm text-slate-600 dark:text-slate-300"><span class="flex size-6 shrink-0 items-center justify-center rounded-full bg-[var(--primary-light)] text-[var(--active)]"><i class="hgi-stroke hgi-checkmark-02 text-sm"></i></span><span>{{ str_replace('_', ' ', ucfirst($feature)) }}</span></li>@empty<li class="text-sm text-slate-500">Everything you need to start managing operations with Piiston.</li>@endforelse
                </ul></div>
            </section>
            <aside class="flex flex-col rounded-[28px] bg-[var(--primary-dark)] p-7 text-white shadow-[0_24px_50px_rgba(23,43,58,0.2)]">
                <div class="flex size-12 items-center justify-center rounded-2xl bg-white/10 text-[var(--primary-light)]"><i class="hgi-stroke hgi-credit-card hgi-lg"></i></div><p class="mt-7 text-[10px] font-black uppercase tracking-[0.3em] text-[var(--primary-light)]">Secure checkout</p><h2 class="mt-3 text-2xl font-black uppercase tracking-tight">Activate {{ $plan->name }}</h2><p class="mt-4 text-sm leading-relaxed text-slate-300">Continue to secure payment and get access to your Piiston workspace immediately after confirmation.</p>
                <form method="POST" action="{{ route('pricing.checkout', $plan) }}" class="mt-auto pt-10">@csrf<button type="submit" class="lp-btn lp-btn--primary w-full justify-center">@auth Continue to payment @else Log in to pay @endauth <i class="hgi-stroke hgi-arrow-right-01"></i></button></form>
                @guest<p class="mt-4 text-center text-xs text-slate-400">You will return here after signing in.</p>@endguest
            </aside>
        </div>
    </div></main>
</body>
</html>
