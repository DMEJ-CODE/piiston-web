<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Piiston — The Complete Automotive Ecosystem for Africa</title>

    <meta name="description" content="Piiston is the all-in-one automotive platform connecting vehicle owners, garages, mechanics, spare part sellers, fleet managers, and insurance partners across Africa.">
    <meta name="keywords" content="automobile, garage management, mechanic, spare parts, fleet management, Africa, vehicle maintenance, AI diagnostic">

    <link rel="icon" href="{{ asset('piiston/favicon.ico') }}" sizes="any">
    <link rel="icon" href="{{ asset('piiston/favicon-32x32.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('piiston/apple-touch-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.hugeicons.com/font/hgi-stroke-rounded.css" crossorigin="anonymous" />

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/landing.js'])
    @fonts
</head>
<body class="landing-page">

    <!-- 1. HEADER -->
    <header class="lp-header">
        <div class="lp-header__inner">
            <a href="#hero" class="lp-logo">
                <img src="{{ asset('piiston/android-chrome-192x192.png') }}" alt="Piiston Logo" class="lp-logo__img">
                <span class="lp-logo__text">Piiston</span>
            </a>

            <nav class="lp-nav">
                <a href="#hero" class="lp-nav__link">Home</a>
                <a href="#solutions" class="lp-nav__link">Solutions</a>
                <a href="#ecosystem" class="lp-nav__link">Ecosystem</a>
                <a href="#marketplace" class="lp-nav__link">Marketplace</a>
                <a href="#fleet" class="lp-nav__link">Fleet</a>
                <a href="#ai-piiston" class="lp-nav__link">AI</a>
                <a href="#africa-coverage" class="lp-nav__link">Coverage</a>
                <a href="#comparison" class="lp-nav__link">Why Us</a>
                <a href="#blog" class="lp-nav__link">Blog</a>
                <a href="#contact" class="lp-nav__link">Contact</a>
                <a href="#faq" class="lp-nav__link">FAQ</a>
            </nav>

            <div class="lp-header__actions">
                <button id="theme-toggle-btn" class="theme-toggle-btn" aria-label="Toggle Dark/Light Mode">
                    <i class="hgi-stroke hgi-sun-01 theme-icon-sun hgi-sm" style="display: none !important;"></i>
                    <i class="hgi-stroke hgi-moon-01 theme-icon-moon hgi-sm"></i>
                </button>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}" class="lp-btn lp-btn--primary lp-btn--sm">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="lp-btn lp-btn--ghost lp-btn--sm">Sign In</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="lp-btn lp-btn--primary lp-btn--sm">Get Started</a>
                        @endif
                    @endauth
                @else
                    <a href="#" class="lp-btn lp-btn--ghost lp-btn--sm">Sign In</a>
                    <a href="#cta-banner" class="lp-btn lp-btn--primary lp-btn--sm">Get Started</a>
                @endif

                <button class="lp-menu-toggle" aria-label="Toggle menu">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </header>    <!-- Mobile Nav Overlay -->
    <div class="lp-mobile-nav-overlay" id="mobileNavOverlay"></div>

    <!-- Mobile Drawer -->
    <div class="lp-mobile-nav" id="mobileNav" role="dialog" aria-modal="true" aria-label="Navigation menu">
        <!-- Drawer Header -->
        <div class="lp-mobile-nav__header">
            <a href="#hero" class="lp-mobile-nav__header-logo" id="mobileNavLogo">
                <img src="{{ asset('piiston/android-chrome-192x192.png') }}" alt="Piiston">
                <span>Piiston</span>
            </a>
            <button class="lp-mobile-nav__close" id="mobileNavClose" aria-label="Close menu">
                <i class="hgi-stroke hgi-cancel-01 hgi-sm"></i>
            </button>
        </div>

        <!-- Drawer Body -->
        <div class="lp-mobile-nav__body">
            <a href="#hero" class="lp-mobile-nav__link">
                <i class="hgi-stroke hgi-home-01 hgi-sm"></i>
                Home
            </a>
            <a href="#solutions" class="lp-mobile-nav__link">
                <i class="hgi-stroke hgi-configuration-01 hgi-sm"></i>
                Solutions
            </a>
            <a href="#ecosystem" class="lp-mobile-nav__link">
                <i class="hgi-stroke hgi-globe hgi-sm"></i>
                Ecosystem
            </a>
            <a href="#marketplace" class="lp-mobile-nav__link">
                <i class="hgi-stroke hgi-store-01 hgi-sm"></i>
                Marketplace
            </a>
            <a href="#fleet" class="lp-mobile-nav__link">
                <i class="hgi-stroke hgi-truck hgi-sm"></i>
                Fleet Management
            </a>
            <a href="#ai-piiston" class="lp-mobile-nav__link">
                <i class="hgi-stroke hgi-brain-02 hgi-sm"></i>
                AI Diagnostics
            </a>
            <a href="#africa-coverage" class="lp-mobile-nav__link">
                <i class="hgi-stroke hgi-global hgi-sm"></i>
                Africa Coverage
            </a>
            <a href="#comparison" class="lp-mobile-nav__link">
                <i class="hgi-stroke hgi-help-circle hgi-sm"></i>
                Why Piiston
            </a>
            <a href="#blog" class="lp-mobile-nav__link">
                <i class="hgi-stroke hgi-book-open-01 hgi-sm"></i>
                Blog &amp; News
            </a>
            <a href="#contact" class="lp-mobile-nav__link">
                <i class="hgi-stroke hgi-mail-01 hgi-sm"></i>
                Contact
            </a>
            <a href="#faq" class="lp-mobile-nav__link">
                <i class="hgi-stroke hgi-bubble-chat-question hgi-sm"></i>
                FAQ
            </a>
        </div>

        <!-- Drawer Footer CTAs -->
        <div class="lp-mobile-nav__footer">
            @if (Route::has('login'))
                @auth
                    <a href="{{ route('dashboard') }}" class="lp-btn lp-btn--primary">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="lp-btn lp-btn--ghost">Sign In</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="lp-btn lp-btn--primary">Get Started Free →</a>
                    @endif
                @endauth
            @else
                <a href="#" class="lp-btn lp-btn--ghost">Sign In</a>
                <a href="#cta-banner" class="lp-btn lp-btn--primary">Get Started Free →</a>
            @endif
        </div>
    </div>

</body>
</html>
    <section id="hero">
        <div class="hero-glow-1"></div>
        <div class="hero-glow-2"></div>
        <div class="hero-slats-overlay"></div>
        <div class="hero__inner">
            <div class="hero__content reveal">
                <div class="hero__label">
                    <span class="hero__label-dot"></span>
                    Unified Automotive Ecosystem
                </div>

                <h1 class="hero__title">
                    The Complete <em>Automotive Ecosystem</em> for Africa
                </h1>

                <p class="hero__sub">
                    One intelligent platform seamlessly connecting vehicle owners, certified mechanics, garages, spare part sellers, and fleet managers into a single digital pulse.
                </p>

                <div class="hero__cta lp-btn-group" style="display: flex; gap: 14px; flex-wrap: wrap;">
                    <a href="{{ route('register') ?? '#cta-banner' }}" class="lp-btn lp-btn--primary lp-btn--lg">
                        <span>Get Started</span>
                        <i class="hgi-stroke hgi-arrow-right-01 hgi-sm"></i>
                    </a>
                    <a href="#ecosystem" class="lp-btn lp-btn--ghost lp-btn--lg">
                        <i class="hgi-stroke hgi-play-unlocked-01 hgi-sm"></i>
                        <span>Watch Demo</span>
                    </a>
                </div>

                <div class="hero__stats">
                    <div class="hero__stat">
                        <span class="hero__stat-num" data-counter="12500">12,500+</span>
                        <span class="hero__stat-label">Vehicles Managed</span>
                    </div>
                    <div class="hero__stat">
                        <span class="hero__stat-num" data-counter="840">840+</span>
                        <span class="hero__stat-label">Certified Garages</span>
                    </div>
                    <div class="hero__stat">
                        <span class="hero__stat-num" data-counter="3200">3,200+</span>
                        <span class="hero__stat-label">Mechanics</span>
                    </div>
                    <div class="hero__stat">
                        <span class="hero__stat-num" data-counter="45000">45,000+</span>
                        <span class="hero__stat-label">Spare Parts Listed</span>
                    </div>
                </div>
            </div>

            <div class="hero__visual reveal reveal-right" style="position: relative;">
                <div class="phone-mockup">
                    <div class="phone-screen">
                        <div style="padding: 16px;">
                            <div style="font-size: 11px; opacity: 0.6; margin-bottom: 2px;">Hello Samuel 👋</div>
                            <div style="font-size: 15px; font-weight: 700; margin-bottom: 14px;">Toyota RAV4 — CE 489 AK</div>

                            <div style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 14px; margin-bottom: 12px;">
                                <div style="font-size: 10px; opacity: 0.6; text-transform: uppercase; letter-spacing: 0.5px;">Next Oil Service</div>
                                <div style="font-size: 18px; font-weight: 800; margin-top: 2px;">In 1,240 km</div>
                                <div style="font-size: 10px; color: var(--accent-active); margin-top: 2px;">Total Quartz 9000</div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                                <div class="work-details-trigger" data-work-details='{"vehicle":"Toyota RAV4 — CE 489 AK","garage":"AutoTech Workshop Douala","vin":"VIN: JTEHH20V7001429","status":"● Work In Progress","fill":"66%"}' style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.15); border-radius: 12px; padding: 10px; cursor: pointer;">
                                    <i class="hgi-stroke hgi-wrench-01 hgi-sm" style="color: var(--accent-active);"></i>
                                    <div style="font-size: 10px; opacity: 0.6; margin-top: 4px;">Active Repair</div>
                                    <div style="font-size: 12px; font-weight: 700; color: var(--accent-active2);">AutoTech ➔</div>
                                </div>
                                <div style="background: rgba(255,255,255,0.04); border-radius: 12px; padding: 10px;">
                                    <i class="hgi-stroke hgi-shield-check hgi-sm" style="color: var(--accent-green);"></i>
                                    <div style="font-size: 10px; opacity: 0.6; margin-top: 4px;">Insurance</div>
                                    <div style="font-size: 12px; font-weight: 700; color: var(--accent-green);">Valid</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dashboard-float">
                    <div style="font-size: 10px; color: var(--text-subtle); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; margin-bottom: 8px;">Live Fleet Telematics</div>
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; font-weight: 700; margin-bottom: 6px;">
                        <span>Truck #08 (Douala)</span>
                        <span style="color: var(--accent-green); font-size: 10px;">● Optimal</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; font-weight: 700;">
                        <span>Van #14 (Yaoundé)</span>
                        <span style="color: var(--accent-amber); font-size: 10px;">● Check Brake</span>
                    </div>
                </div>

                <div class="notification-float work-details-trigger" data-work-details='{"vehicle":"Toyota RAV4 — CE 489 AK","garage":"AutoTech Workshop Douala","vin":"VIN: JTEHH20V7001429","status":"● Quote Approved","fill":"50%"}' style="cursor: pointer;">
                    <div style="width: 32px; height: 32px; background: var(--accent-active2); color: #FFF; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="hgi-stroke hgi-notification-03 hgi-sm"></i>
                    </div>
                    <div>
                        <div style="font-size: 11px; font-weight: 700;">Diagnostic Complete</div>
                        <div style="font-size: 10px; color: var(--text-muted);">View live work order details ➔</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. TRUSTED BY -->
    <section id="trusted-by">
        <hr class="lp-divider">
        <div class="py-4">
            <div style="text-align: center; font-size: 11px; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase; color: var(--text-subtle); margin-bottom: 20px;">
                Trusted by industry leaders, certified workshops &amp; fleets across Africa
            </div>
            <div class="marquee-track">
                <!-- Original set -->
                <div class="marquee-inner" aria-hidden="false">
                    <div class="marquee-item"><i class="hgi-stroke hgi-garage"></i> AutoTech Africa</div>
                    <div class="marquee-item"><i class="hgi-stroke hgi-truck"></i> LogisTrans Afrik</div>
                    <div class="marquee-item"><i class="hgi-stroke hgi-mortarboard-01"></i> AICS Institute</div>
                    <div class="marquee-item"><i class="hgi-stroke hgi-shield-check"></i> Activa Insurance</div>
                    <div class="marquee-item"><i class="hgi-stroke hgi-settings-01"></i> Bosch Partner</div>
                    <div class="marquee-item"><i class="hgi-stroke hgi-truck-delivery"></i> FleetPro Senegal</div>
                    <div class="marquee-item"><i class="hgi-stroke hgi-package-01"></i> Parts Connect</div>
                    <div class="marquee-item"><i class="hgi-stroke hgi-earth"></i> Afri-Moto Group</div>
                </div>
                <!-- Duplicate set for seamless loop -->
                <div class="marquee-inner" aria-hidden="true">
                    <div class="marquee-item"><i class="hgi-stroke hgi-garage"></i> AutoTech Africa</div>
                    <div class="marquee-item"><i class="hgi-stroke hgi-truck"></i> LogisTrans Afrik</div>
                    <div class="marquee-item"><i class="hgi-stroke hgi-mortarboard-01"></i> AICS Institute</div>
                    <div class="marquee-item"><i class="hgi-stroke hgi-shield-check"></i> Activa Insurance</div>
                    <div class="marquee-item"><i class="hgi-stroke hgi-settings-01"></i> Bosch Partner</div>
                    <div class="marquee-item"><i class="hgi-stroke hgi-truck-delivery"></i> FleetPro Senegal</div>
                    <div class="marquee-item"><i class="hgi-stroke hgi-package-01"></i> Parts Connect</div>
                    <div class="marquee-item"><i class="hgi-stroke hgi-earth"></i> Afri-Moto Group</div>
                </div>
            </div>
        </div>
        <hr class="lp-divider">
    </section>

    <!-- 4. WHY PIISTON -->
    <section id="why-piiston" class="lp-section-vh section-bg-b">
        <div class="lp-container">
            <div style="text-align: center; max-width: 680px; margin: 0 auto 36px;" class="reveal">
                <div class="section-eyebrow section-eyebrow--brand">Problem &amp; Solution</div>
                <h2 class="section-title section-title--brand">The African Automotive Sector Was Fractured. Until Now.</h2>
                <p class="section-subtitle">Every player faced friction, paper logs, and zero transparency. Piiston bridges everyone into one digital pulse.</p>
            </div>

            <div class="why__problems">
                <div class="problem-card reveal">
                    <div class="problem-card__icon-wrap">
                        <i class="hgi-stroke hgi-car-01 hgi-lg"></i>
                    </div>
                    <div class="problem-card__role">Car Owners</div>
                    <div class="problem-card__title">Lost History</div>
                    <div class="problem-card__desc">No centralized logbook, risk of fake spare parts, opaque pricing.</div>
                </div>

                <div class="problem-card reveal">
                    <div class="problem-card__icon-wrap">
                        <i class="hgi-stroke hgi-garage hgi-lg"></i>
                    </div>
                    <div class="problem-card__role">Garages</div>
                    <div class="problem-card__title">Manual Ops</div>
                    <div class="problem-card__desc">Paper job cards, delayed client approvals, untracked inventory.</div>
                </div>

                <div class="problem-card reveal">
                    <div class="problem-card__icon-wrap">
                        <i class="hgi-stroke hgi-wrench-01 hgi-lg"></i>
                    </div>
                    <div class="problem-card__role">Mechanics</div>
                    <div class="problem-card__title">Zero Visibility</div>
                    <div class="problem-card__desc">No portfolio to showcase skills, manual diagnostic guesswork.</div>
                </div>

                <div class="problem-card reveal">
                    <div class="problem-card__icon-wrap">
                        <i class="hgi-stroke hgi-store-01 hgi-lg"></i>
                    </div>
                    <div class="problem-card__role">Parts Sellers</div>
                    <div class="problem-card__title">Hard Sales</div>
                    <div class="problem-card__desc">Walk-ins only, unorganized stock, slow payment recovery.</div>
                </div>

                <div class="problem-card reveal">
                    <div class="problem-card__icon-wrap">
                        <i class="hgi-stroke hgi-truck hgi-lg"></i>
                    </div>
                    <div class="problem-card__role">Fleet Managers</div>
                    <div class="problem-card__title">High Costs</div>
                    <div class="problem-card__desc">Fuel leaks, unexpected breakdowns, lack of GPS telematics.</div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. ECOSYSTEM ORBITAL -->
    <section id="ecosystem" class="lp-section-vh section-bg-a">
        <div class="lp-container">
            <div style="text-align: center; max-width: 640px; margin: 0 auto 16px;" class="reveal">
                <div class="section-eyebrow section-eyebrow--brand">Connected Architecture</div>
                <h2 class="section-title section-title--brand">The Piiston Orbital Ecosystem</h2>
                <p class="section-subtitle">Hover over any satellite node to see how every player is synchronized.</p>
            </div>

            <div class="ecosystem__arena reveal">
                <div class="ecosystem__center">
                    <span style="font-size: 15px; font-weight: 900; letter-spacing: -0.5px;">PIISTON</span>
                    <span style="font-size: 9px; opacity: 0.7; font-weight: 600;">CORE HUB</span>
                </div>

                <div class="orbit-ring orbit-ring--1"></div>
                <div class="orbit-ring orbit-ring--2"></div>
                <div class="orbit-ring orbit-ring--3"></div>

                <div class="orbit-node" style="top: 15%; left: 45%;">
                    <div class="orbit-node__bubble"><i class="hgi-stroke hgi-car-01"></i></div>
                    <span class="orbit-node__label">Car Owners</span>
                    <div class="ecosystem__tooltip">
                        <div class="ecosystem__tooltip-title">Vehicle Owner Vault</div>
                        <div class="ecosystem__tooltip-desc">Digital passport, service alerts, certified garage booking & MoMo payments.</div>
                    </div>
                </div>

                <div class="orbit-node" style="top: 35%; left: 78%;">
                    <div class="orbit-node__bubble"><i class="hgi-stroke hgi-garage"></i></div>
                    <span class="orbit-node__label">Garages</span>
                    <div class="ecosystem__tooltip">
                        <div class="ecosystem__tooltip-title">Garage ERP</div>
                        <div class="ecosystem__tooltip-desc">Workshop lifts, mechanics task dispatch & automated digital quotes.</div>
                    </div>
                </div>

                <div class="orbit-node" style="top: 72%; left: 65%;">
                    <div class="orbit-node__bubble"><i class="hgi-stroke hgi-wrench-01"></i></div>
                    <span class="orbit-node__label">Mechanics</span>
                    <div class="ecosystem__tooltip">
                        <div class="ecosystem__tooltip-title">Technician App</div>
                        <div class="ecosystem__tooltip-desc">AI diagnostic guides, photo/video repair evidence & job cards.</div>
                    </div>
                </div>

                <div class="orbit-node" style="top: 72%; left: 25%;">
                    <div class="orbit-node__bubble"><i class="hgi-stroke hgi-store-01"></i></div>
                    <span class="orbit-node__label">Marketplace</span>
                    <div class="ecosystem__tooltip">
                        <div class="ecosystem__tooltip-title">OEM Parts Market</div>
                        <div class="ecosystem__tooltip-desc">Verified auto parts, express courier delivery & Escrow protection.</div>
                    </div>
                </div>

                <div class="orbit-node" style="top: 35%; left: 12%;">
                    <div class="orbit-node__bubble"><i class="hgi-stroke hgi-truck"></i></div>
                    <span class="orbit-node__label">Fleets</span>
                    <div class="ecosystem__tooltip">
                        <div class="ecosystem__tooltip-title">Fleet Telematics</div>
                        <div class="ecosystem__tooltip-desc">GPS tracking, fuel consumption logs & driver safety scoring.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. SOLUTIONS BY ROLE (100vh Section) -->
    <section id="solutions" class="lp-section-vh section-bg-c">
        <div class="lp-container">
            <div style="text-align: center; max-width: 680px; margin: 0 auto 32px;" class="reveal">
                <div class="section-eyebrow section-eyebrow--brand">Tailored Portals</div>
                <h2 class="section-title section-title--brand">Solutions Built for Every Role</h2>
                <p class="section-subtitle">Select your profile below to explore dedicated features.</p>
            </div>

            <div class="solutions__tabs reveal">
                <button class="solutions__tab is-active" data-role="owner">
                    <i class="hgi-stroke hgi-car-01 hgi-sm"></i>
                    Car Owner
                </button>
                <button class="solutions__tab" data-role="garage">
                    <i class="hgi-stroke hgi-garage hgi-sm"></i>
                    Garage Owner
                </button>
                <button class="solutions__tab" data-role="mechanic">
                    <i class="hgi-stroke hgi-wrench-01 hgi-sm"></i>
                    Mechanic
                </button>
                <button class="solutions__tab" data-role="seller">
                    <i class="hgi-stroke hgi-store-01 hgi-sm"></i>
                    Parts Seller
                </button>
                <button class="solutions__tab" data-role="fleet">
                    <i class="hgi-stroke hgi-truck hgi-sm"></i>
                    Fleet Manager
                </button>
            </div>

            <!-- Panel 1: Owner -->
            <div class="solutions__panel is-active" data-role="owner">
                <div class="role-card">
                    <div class="role-card__header">
                        <div class="role-card__icon-wrap">
                            <i class="hgi-stroke hgi-car-01 hgi-lg"></i>
                        </div>
                        <div>
                            <div class="role-card__title">Vehicle Owner Portal</div>
                            <div class="role-card__subtitle">Complete digital control over your private car maintenance</div>
                        </div>
                    </div>

                    <div style="margin-bottom: 24px;">
                        <div class="role-feature"><span class="role-feature__check">✓</span> <span>Manage multiple vehicles in a single unified dashboard</span></div>
                        <div class="role-feature"><span class="role-feature__check">✓</span> <span>100% digital maintenance logbook & breakdown history</span></div>
                        <div class="role-feature"><span class="role-feature__check">✓</span> <span>Automated reminders for insurance, oil change & inspection</span></div>
                        <div class="role-feature"><span class="role-feature__check">✓</span> <span>Find certified nearby garages with transparent pricing</span></div>
                    </div>

                    <a href="{{ route('register') ?? '#cta-banner' }}" class="lp-btn lp-btn--primary">
                        <span>Explore Vehicle Owner Portal</span>
                        <i class="hgi-stroke hgi-arrow-right-01 hgi-sm"></i>
                    </a>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-card); padding: 24px; text-align: center;">
                        <div style="font-size: 28px; font-weight: 900; color: var(--accent-active2);">98%</div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">Reduction in counterfeit parts</div>
                    </div>
                    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-card); padding: 24px; text-align: center;">
                        <div style="font-size: 28px; font-weight: 900; color: var(--accent-green);">+25%</div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">Higher vehicle resale value</div>
                    </div>
                </div>
            </div>

            <!-- Panel 2: Garage -->
            <div class="solutions__panel" data-role="garage">
                <div class="role-card">
                    <div class="role-card__header">
                        <div class="role-card__icon-wrap">
                            <i class="hgi-stroke hgi-garage hgi-lg"></i>
                        </div>
                        <div>
                            <div class="role-card__title">Garage ERP Management</div>
                            <div class="role-card__subtitle">Digitize your workshop, mechanics & customer invoicing</div>
                        </div>
                    </div>

                    <div style="margin-bottom: 24px;">
                        <div class="role-feature"><span class="role-feature__check">✓</span> <span>Interactive workshop planning & appointment scheduling</span></div>
                        <div class="role-feature"><span class="role-feature__check">✓</span> <span>Digital quote generation linked directly to client WhatsApp</span></div>
                        <div class="role-feature"><span class="role-feature__check">✓</span> <span>Real-time spare parts inventory with auto-reorder alerts</span></div>
                    </div>

                    <a href="{{ route('register') ?? '#cta-banner' }}" class="lp-btn lp-btn--primary">Explore Garage ERP</a>
                </div>
            </div>

            <!-- Panel 3: Mechanic -->
            <div class="solutions__panel" data-role="mechanic">
                <div class="role-card">
                    <div class="role-card__header">
                        <div class="role-card__icon-wrap">
                            <i class="hgi-stroke hgi-wrench-01 hgi-lg"></i>
                        </div>
                        <div>
                            <div class="role-card__title">Certified Technician Tools</div>
                            <div class="role-card__subtitle">Smart diagnostic guidance & digital job cards</div>
                        </div>
                    </div>

                    <div style="margin-bottom: 24px;">
                        <div class="role-feature"><span class="role-feature__check">✓</span> <span>AI-assisted OBD breakdown fault code decoder</span></div>
                        <div class="role-feature"><span class="role-feature__check">✓</span> <span>Photo & video repair evidence upload to client chat</span></div>
                    </div>

                    <a href="{{ route('register') ?? '#cta-banner' }}" class="lp-btn lp-btn--primary">Join Mechanic Network</a>
                </div>
            </div>

            <!-- Panel 4: Seller -->
            <div class="solutions__panel" data-role="seller">
                <div class="role-card">
                    <div class="role-card__header">
                        <div class="role-card__icon-wrap">
                            <i class="hgi-stroke hgi-store-01 hgi-lg"></i>
                        </div>
                        <div>
                            <div class="role-card__title">Spare Parts Storefront</div>
                            <div class="role-card__subtitle">Sell OEM parts directly to garages & drivers</div>
                        </div>
                    </div>

                    <div style="margin-bottom: 24px;">
                        <div class="role-feature"><span class="role-feature__check">✓</span> <span>Digital B2B & B2C parts catalog with OEM reference numbers</span></div>
                        <div class="role-feature"><span class="role-feature__check">✓</span> <span>Instant Mobile Money payouts with Escrow protection</span></div>
                    </div>

                    <a href="{{ route('register') ?? '#cta-banner' }}" class="lp-btn lp-btn--primary">Open Storefront</a>
                </div>
            </div>

            <!-- Panel 5: Fleet -->
            <div class="solutions__panel" data-role="fleet">
                <div class="role-card">
                    <div class="role-card__header">
                        <div class="role-card__icon-wrap">
                            <i class="hgi-stroke hgi-truck hgi-lg"></i>
                        </div>
                        <div>
                            <div class="role-card__title">Commercial Fleet Telematics</div>
                            <div class="role-card__subtitle">GPS tracking, fuel monitoring & driver safety scores</div>
                        </div>
                    </div>

                    <div style="margin-bottom: 24px;">
                        <div class="role-feature"><span class="role-feature__check">✓</span> <span>Real-time GPS tracking & geofence anomaly alerts</span></div>
                        <div class="role-feature"><span class="role-feature__check">✓</span> <span>Fuel consumption tracking & preventive maintenance schedules</span></div>
                    </div>

                    <a href="{{ route('register') ?? '#cta-banner' }}" class="lp-btn lp-btn--primary">Launch Fleet Suite</a>
                </div>
            </div>
        </div>
    </section>

    <!-- 7. MODULES PIISTON ARCHITECTURE HUB -->
    <section id="modules" class="lp-section-vh section-bg-b">
        <div class="lp-container">
            <div style="text-align: center; max-width: 680px; margin: 0 auto 36px;" class="reveal">
                <div class="section-eyebrow section-eyebrow--brand">
                    <i class="hgi-stroke hgi-layers-01 hgi-xs"></i>
                    Modular Architecture
                </div>
                <h2 class="section-title section-title--brand">20 Micro-Modules Built for Scale</h2>
                <p class="section-subtitle">Grouped into 4 core platform pillars — deploy individually or orchestrate as a complete suite.</p>
            </div>

            <!-- 4 Visual Pillar Switcher Cards -->
            <div class="pillar-switcher reveal">
                <div class="pillar-card is-active" data-pillar="pillar-core">
                    <div class="pillar-card__badge">01 / 04</div>
                    <div class="pillar-card__icon-wrap">
                        <i class="hgi-stroke hgi-shield-keyhole hgi-sm"></i>
                    </div>
                    <div class="pillar-card__title">Security &amp; Core</div>
                    <div class="pillar-card__sub">5 Modules • Auth, VIN, Claims</div>
                </div>

                <div class="pillar-card" data-pillar="pillar-workshop">
                    <div class="pillar-card__badge">02 / 04</div>
                    <div class="pillar-card__icon-wrap">
                        <i class="hgi-stroke hgi-wrench-01 hgi-sm"></i>
                    </div>
                    <div class="pillar-card__title">Garage &amp; Workshop</div>
                    <div class="pillar-card__sub">5 Modules • ERP, OBD, Quotes</div>
                </div>

                <div class="pillar-card" data-pillar="pillar-commerce">
                    <div class="pillar-card__badge">03 / 04</div>
                    <div class="pillar-card__icon-wrap">
                        <i class="hgi-stroke hgi-store-01 hgi-sm"></i>
                    </div>
                    <div class="pillar-card__title">Market &amp; Payments</div>
                    <div class="pillar-card__sub">5 Modules • Escrow, OEM, FX</div>
                </div>

                <div class="pillar-card" data-pillar="pillar-telematics">
                    <div class="pillar-card__badge">04 / 04</div>
                    <div class="pillar-card__icon-wrap">
                        <i class="hgi-stroke hgi-ai-brain-01 hgi-sm"></i>
                    </div>
                    <div class="pillar-card__title">Telematics &amp; AI</div>
                    <div class="pillar-card__sub">5 Modules • GPS, ML, WhatsApp</div>
                </div>
            </div>

            <!-- Pillar Detail Panels Container -->
            <div class="pillar-panels reveal">

                <!-- PILLAR 1: SECURITY & CORE -->
                <div class="pillar-panel is-active" id="pillar-core">
                    <div class="pillar-panel__grid">
                        <!-- Featured Banner -->
                        <div class="pillar-hero-card pillar-hero-card--brand">
                            <div class="pillar-hero__header">
                                <span class="pillar-hero__pill">Pillar 01 — Core System</span>
                                <span class="pillar-hero__pulse">● Live Microservices</span>
                            </div>
                            <h3 class="pillar-hero__title">Security, Identity &amp; Vehicle History</h3>
                            <p class="pillar-hero__desc">Guarantees tamper-proof user authentication, automated VIN decoding, insurance verification, and immutable OHADA audit logging.</p>

                            <div class="pillar-hero__stats">
                                <div class="pillar-stat">
                                    <div class="pillar-stat__val">FIDO2 / WebAuthn</div>
                                    <div class="pillar-stat__lbl">Passkey Enabled</div>
                                </div>
                                <div class="pillar-stat">
                                    <div class="pillar-stat__val">17-Digit VIN</div>
                                    <div class="pillar-stat__lbl">Auto Decoding</div>
                                </div>
                                <div class="pillar-stat">
                                    <div class="pillar-stat__val">OHADA Standard</div>
                                    <div class="pillar-stat__lbl">Tax Compliance</div>
                                </div>
                            </div>
                        </div>

                        <!-- 5 Module Cards -->
                        <div class="pillar-modules-list">
                            <div class="pillar-module-item">
                                <div class="p-mod__icon"><i class="hgi-stroke hgi-shield-keyhole"></i></div>
                                <div class="p-mod__content">
                                    <div class="p-mod__top">
                                        <span class="p-mod__num">Module #01</span>
                                        <span class="p-mod__tag">Security</span>
                                    </div>
                                    <div class="p-mod__title">Auth &amp; Passkey Security</div>
                                    <div class="p-mod__desc">Biometric Passkey login, TOTP 2FA, and SMS OTP authentication with rate-limiting protection.</div>
                                </div>
                            </div>

                            <div class="pillar-module-item">
                                <div class="p-mod__icon"><i class="hgi-stroke hgi-car-01"></i></div>
                                <div class="p-mod__content">
                                    <div class="p-mod__top">
                                        <span class="p-mod__num">Module #02</span>
                                        <span class="p-mod__tag">Passport</span>
                                    </div>
                                    <div class="p-mod__title">Vehicle Passport &amp; VIN Decoder</div>
                                    <div class="p-mod__desc">Automated 17-digit VIN decoding, digital logbooks, and ownership transfer audit logs.</div>
                                </div>
                            </div>

                            <div class="pillar-module-item">
                                <div class="p-mod__icon"><i class="hgi-stroke hgi-shield-check"></i></div>
                                <div class="p-mod__content">
                                    <div class="p-mod__top">
                                        <span class="p-mod__num">Module #11</span>
                                        <span class="p-mod__tag">Insurance</span>
                                    </div>
                                    <div class="p-mod__title">Insurance Claim Portal</div>
                                    <div class="p-mod__desc">Streamlined claim filing, damage photo assessment, policy verification, and repair estimates.</div>
                                </div>
                            </div>

                            <div class="pillar-module-item">
                                <div class="p-mod__icon"><i class="hgi-stroke hgi-file-01"></i></div>
                                <div class="p-mod__content">
                                    <div class="p-mod__top">
                                        <span class="p-mod__num">Module #17</span>
                                        <span class="p-mod__tag">Audit</span>
                                    </div>
                                    <div class="p-mod__title">OHADA Tax &amp; Audit Trail</div>
                                    <div class="p-mod__desc">Automated VAT calculation engine conforming to CEMAC &amp; UEMOA accounting standards.</div>
                                </div>
                            </div>

                            <div class="pillar-module-item">
                                <div class="p-mod__icon"><i class="hgi-stroke hgi-user-01"></i></div>
                                <div class="p-mod__content">
                                    <div class="p-mod__top">
                                        <span class="p-mod__num">Module #19</span>
                                        <span class="p-mod__tag">Portal</span>
                                    </div>
                                    <div class="p-mod__title">Car Owner Care Portal</div>
                                    <div class="p-mod__desc">Dedicated web &amp; mobile app for drivers to schedule maintenance, view history, and store invoices.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PILLAR 2: GARAGE & WORKSHOP -->
                <div class="pillar-panel" id="pillar-workshop">
                    <div class="pillar-panel__grid">
                        <!-- Featured Banner -->
                        <div class="pillar-hero-card pillar-hero-card--blue">
                            <div class="pillar-hero__header">
                                <span class="pillar-hero__pill">Pillar 02 — Workshop Suite</span>
                                <span class="pillar-hero__pulse">● Live Microservices</span>
                            </div>
                            <h3 class="pillar-hero__title">End-to-End Garage Digitization</h3>
                            <p class="pillar-hero__desc">From customer check-in to digital job dispatch, diagnostic OBD fault decoding, barcode stock tracking, and digital customer approvals.</p>

                            <div class="pillar-hero__stats">
                                <div class="pillar-stat">
                                    <div class="pillar-stat__val">Job Cards</div>
                                    <div class="pillar-stat__lbl">Digital Dispatch</div>
                                </div>
                                <div class="pillar-stat">
                                    <div class="pillar-stat__val">150-Point</div>
                                    <div class="pillar-stat__lbl">Inspection Engine</div>
                                </div>
                                <div class="pillar-stat">
                                    <div class="pillar-stat__val">Barcode / QR</div>
                                    <div class="pillar-stat__lbl">Parts Scanner</div>
                                </div>
                            </div>
                        </div>

                        <!-- 5 Module Cards -->
                        <div class="pillar-modules-list">
                            <div class="pillar-module-item">
                                <div class="p-mod__icon"><i class="hgi-stroke hgi-garage"></i></div>
                                <div class="p-mod__content">
                                    <div class="p-mod__top">
                                        <span class="p-mod__num">Module #03</span>
                                        <span class="p-mod__tag">ERP</span>
                                    </div>
                                    <div class="p-mod__title">Garage ERP &amp; Job Dispatch</div>
                                    <div class="p-mod__desc">Digital job cards, bay allocation, mechanic shift dispatching, and workshop productivity tracking.</div>
                                </div>
                            </div>

                            <div class="pillar-module-item">
                                <div class="p-mod__icon"><i class="hgi-stroke hgi-wrench-01"></i></div>
                                <div class="p-mod__content">
                                    <div class="p-mod__top">
                                        <span class="p-mod__num">Module #04</span>
                                        <span class="p-mod__tag">Mechanic</span>
                                    </div>
                                    <div class="p-mod__title">Mechanic Suite &amp; OBD Guidance</div>
                                    <div class="p-mod__desc">Mobile OBD II fault decoder, task checklists, diagnostic procedure guides, and repair timer.</div>
                                </div>
                            </div>

                            <div class="pillar-module-item">
                                <div class="p-mod__icon"><i class="hgi-stroke hgi-file-edit"></i></div>
                                <div class="p-mod__content">
                                    <div class="p-mod__top">
                                        <span class="p-mod__num">Module #05</span>
                                        <span class="p-mod__tag">Workflow</span>
                                    </div>
                                    <div class="p-mod__title">Repair Workflow &amp; Invoicing</div>
                                    <div class="p-mod__desc">Instant quote generation, client WhatsApp approval link, labor calculation, and PDF invoices.</div>
                                </div>
                            </div>

                            <div class="pillar-module-item">
                                <div class="p-mod__icon"><i class="hgi-stroke hgi-package-01"></i></div>
                                <div class="p-mod__content">
                                    <div class="p-mod__top">
                                        <span class="p-mod__num">Module #13</span>
                                        <span class="p-mod__tag">Inventory</span>
                                    </div>
                                    <div class="p-mod__title">Inventory &amp; Barcode Scanner</div>
                                    <div class="p-mod__desc">Real-time stock audit, smartphone camera barcode scanner, and automated low-stock alerts.</div>
                                </div>
                            </div>

                            <div class="pillar-module-item">
                                <div class="p-mod__icon"><i class="hgi-stroke hgi-checkmark-circle-02"></i></div>
                                <div class="p-mod__content">
                                    <div class="p-mod__top">
                                        <span class="p-mod__num">Module #14</span>
                                        <span class="p-mod__tag">Inspection</span>
                                    </div>
                                    <div class="p-mod__title">150-Point Digital Inspection</div>
                                    <div class="p-mod__desc">Comprehensive vehicle health scorecards, pre-purchase inspection sheets, and PDF export.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PILLAR 3: MARKET & PAYMENTS -->
                <div class="pillar-panel" id="pillar-commerce">
                    <div class="pillar-panel__grid">
                        <!-- Featured Banner -->
                        <div class="pillar-hero-card pillar-hero-card--green">
                            <div class="pillar-hero__header">
                                <span class="pillar-hero__pill">Pillar 03 — Commerce Suite</span>
                                <span class="pillar-hero__pulse">● Live Microservices</span>
                            </div>
                            <h3 class="pillar-hero__title">OEM Parts Commerce &amp; Escrow Payments</h3>
                            <p class="pillar-hero__desc">Connects spare part stores to garages and vehicle owners with Escrow Mobile Money protection, multi-currency conversion, and developer APIs.</p>

                            <div class="pillar-hero__stats">
                                <div class="pillar-stat">
                                    <div class="pillar-stat__val">MTN &amp; Orange</div>
                                    <div class="pillar-stat__lbl">Mobile Escrow</div>
                                </div>
                                <div class="pillar-stat">
                                    <div class="pillar-stat__val">45,000+ SKUs</div>
                                    <div class="pillar-stat__lbl">OEM Parts Catalog</div>
                                </div>
                                <div class="pillar-stat">
                                    <div class="pillar-stat__val">XAF / XOF / GHS</div>
                                    <div class="pillar-stat__lbl">FX Converter</div>
                                </div>
                            </div>
                        </div>

                        <!-- 5 Module Cards -->
                        <div class="pillar-modules-list">
                            <div class="pillar-module-item">
                                <div class="p-mod__icon"><i class="hgi-stroke hgi-store-01"></i></div>
                                <div class="p-mod__content">
                                    <div class="p-mod__top">
                                        <span class="p-mod__num">Module #06</span>
                                        <span class="p-mod__tag">Catalog</span>
                                    </div>
                                    <div class="p-mod__title">Spare Parts Marketplace</div>
                                    <div class="p-mod__desc">B2B &amp; B2C OEM spare parts storefront, stock syncing, and express delivery dispatch.</div>
                                </div>
                            </div>

                            <div class="pillar-module-item">
                                <div class="p-mod__icon"><i class="hgi-stroke hgi-credit-card"></i></div>
                                <div class="p-mod__content">
                                    <div class="p-mod__top">
                                        <span class="p-mod__num">Module #07</span>
                                        <span class="p-mod__tag">Escrow</span>
                                    </div>
                                    <div class="p-mod__title">Escrow &amp; Mobile Money Payments</div>
                                    <div class="p-mod__desc">Multi-party payments supporting MTN MoMo, Orange Money, and Wave with delivery escrow hold.</div>
                                </div>
                            </div>

                            <div class="pillar-module-item">
                                <div class="p-mod__icon"><i class="hgi-stroke hgi-money-bag-01"></i></div>
                                <div class="p-mod__content">
                                    <div class="p-mod__top">
                                        <span class="p-mod__num">Module #16</span>
                                        <span class="p-mod__tag">Currency</span>
                                    </div>
                                    <div class="p-mod__title">Multi-Currency Engine</div>
                                    <div class="p-mod__desc">Automated exchange rates across XAF, XOF, GHS, NGN, and USD for regional African commerce.</div>
                                </div>
                            </div>

                            <div class="pillar-module-item">
                                <div class="p-mod__icon"><i class="hgi-stroke hgi-chart-bar-01"></i></div>
                                <div class="p-mod__content">
                                    <div class="p-mod__top">
                                        <span class="p-mod__num">Module #18</span>
                                        <span class="p-mod__tag">Analytics</span>
                                    </div>
                                    <div class="p-mod__title">Analytics &amp; Executive Telemetry</div>
                                    <div class="p-mod__desc">Business intelligence dashboard displaying parts sales velocity, workshop margins, and growth metrics.</div>
                                </div>
                            </div>

                            <div class="pillar-module-item">
                                <div class="p-mod__icon"><i class="hgi-stroke hgi-code-01"></i></div>
                                <div class="p-mod__content">
                                    <div class="p-mod__top">
                                        <span class="p-mod__num">Module #20</span>
                                        <span class="p-mod__tag">API</span>
                                    </div>
                                    <div class="p-mod__title">Developer API &amp; Webhooks</div>
                                    <div class="p-mod__desc">RESTful API gateway and webhooks allowing enterprise ERPs, insurance systems, and telematics to integrate.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PILLAR 4: TELEMATICS & AI -->
                <div class="pillar-panel" id="pillar-telematics">
                    <div class="pillar-panel__grid">
                        <!-- Featured Banner -->
                        <div class="pillar-hero-card pillar-hero-card--dark">
                            <div class="pillar-hero__header">
                                <span class="pillar-hero__pill">Pillar 04 — Telematics &amp; AI</span>
                                <span class="pillar-hero__pulse">● Live Microservices</span>
                            </div>
                            <h3 class="pillar-hero__title">GPS Tracking, ML Diagnostics &amp; SOS</h3>
                            <p class="pillar-hero__desc">Combines real-time commercial fleet telemetry with machine learning failure prediction, driver safety scoring, and WhatsApp media sharing.</p>

                            <div class="pillar-hero__stats">
                                <div class="pillar-stat">
                                    <div class="pillar-stat__val">Realtime GPS</div>
                                    <div class="pillar-stat__lbl">5s Ping Interval</div>
                                </div>
                                <div class="pillar-stat">
                                    <div class="pillar-stat__val">ML Predictive</div>
                                    <div class="pillar-stat__lbl">Failure AI</div>
                                </div>
                                <div class="pillar-stat">
                                    <div class="pillar-stat__val">WhatsApp API</div>
                                    <div class="pillar-stat__lbl">Photo Evidence</div>
                                </div>
                            </div>
                        </div>

                        <!-- 5 Module Cards -->
                        <div class="pillar-modules-list">
                            <div class="pillar-module-item">
                                <div class="p-mod__icon"><i class="hgi-stroke hgi-truck"></i></div>
                                <div class="p-mod__content">
                                    <div class="p-mod__top">
                                        <span class="p-mod__num">Module #08</span>
                                        <span class="p-mod__tag">GPS</span>
                                    </div>
                                    <div class="p-mod__title">Fleet Telematics &amp; GPS</div>
                                    <div class="p-mod__desc">Live vehicle tracking, geofence boundary alerts, speed governors, and fuel tank sensor telemetry.</div>
                                </div>
                            </div>

                            <div class="pillar-module-item">
                                <div class="p-mod__icon"><i class="hgi-stroke hgi-ai-brain-01"></i></div>
                                <div class="p-mod__content">
                                    <div class="p-mod__top">
                                        <span class="p-mod__num">Module #09</span>
                                        <span class="p-mod__tag">AI</span>
                                    </div>
                                    <div class="p-mod__title">AI Breakdown Diagnostics</div>
                                    <div class="p-mod__desc">Machine learning models predicting component wear and breakdown risk before road failure occurs.</div>
                                </div>
                            </div>

                            <div class="pillar-module-item">
                                <div class="p-mod__icon"><i class="hgi-stroke hgi-bubble-chat-02"></i></div>
                                <div class="p-mod__content">
                                    <div class="p-mod__top">
                                        <span class="p-mod__num">Module #10</span>
                                        <span class="p-mod__tag">WhatsApp</span>
                                    </div>
                                    <div class="p-mod__title">WhatsApp Evidence Bridge</div>
                                    <div class="p-mod__desc">Native messaging integration allowing mechanics to send repair photos directly to client chats.</div>
                                </div>
                            </div>

                            <div class="pillar-module-item">
                                <div class="p-mod__icon"><i class="hgi-stroke hgi-zap"></i></div>
                                <div class="p-mod__content">
                                    <div class="p-mod__top">
                                        <span class="p-mod__num">Module #12</span>
                                        <span class="p-mod__tag">Rescue</span>
                                    </div>
                                    <div class="p-mod__title">Emergency Towing SOS</div>
                                    <div class="p-mod__desc">One-tap roadside SOS locator dispatching nearest available flatbed tow truck with live GPS route.</div>
                                </div>
                            </div>

                            <div class="pillar-module-item">
                                <div class="p-mod__icon"><i class="hgi-stroke hgi-location-01"></i></div>
                                <div class="p-mod__content">
                                    <div class="p-mod__top">
                                        <span class="p-mod__num">Module #15</span>
                                        <span class="p-mod__tag">Safety</span>
                                    </div>
                                    <div class="p-mod__title">Driver Safety Telematics</div>
                                    <div class="p-mod__desc">Monitors harsh braking, acceleration spikes, and idling hours to generate driver safety scorecards.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 8. MARKETPLACE -->
    <section id="marketplace" class="lp-section-vh section-bg-a">
        <div class="lp-container">
            <div style="text-align: center; max-width: 640px; margin: 0 auto 28px;" class="reveal">
                <div class="section-eyebrow section-eyebrow--green">Genuine Spare Parts</div>
                <h2 class="section-title section-title--green">Africa's Largest Spare Parts Market</h2>
                <p class="section-subtitle">Verified OEM components with Mobile Money escrow protection & express delivery.</p>
            </div>

            <!-- Search Bar -->
            <div class="market-search reveal">
                <i class="hgi-stroke hgi-search-01 market-search__icon"></i>
                <input type="text" class="market-search__input" placeholder="Search by OEM reference number, part name or car model...">
                <button class="lp-btn lp-btn--primary lp-btn--sm">
                    <i class="hgi-stroke hgi-search-01 hgi-xs"></i>
                    <span>Search Catalog</span>
                </button>
            </div>

            <!-- Category Chips -->
            <div class="market-categories-grid reveal">
                <div class="market-cat is-active">
                    <i class="hgi-stroke hgi-settings-01 market-cat__icon"></i>
                    <span class="market-cat__name">Engines</span>
                </div>
                <div class="market-cat">
                    <i class="hgi-stroke hgi-tire market-cat__icon"></i>
                    <span class="market-cat__name">Brakes</span>
                </div>
                <div class="market-cat">
                    <i class="hgi-stroke hgi-tire market-cat__icon"></i>
                    <span class="market-cat__name">Tires</span>
                </div>
                <div class="market-cat">
                    <i class="hgi-stroke hgi-oil-barrel market-cat__icon"></i>
                    <span class="market-cat__name">Oils</span>
                </div>
                <div class="market-cat">
                    <i class="hgi-stroke hgi-battery-full market-cat__icon"></i>
                    <span class="market-cat__name">Batteries</span>
                </div>
                <div class="market-cat">
                    <i class="hgi-stroke hgi-sun-02 market-cat__icon"></i>
                    <span class="market-cat__name">Lighting</span>
                </div>
                <div class="market-cat">
                    <i class="hgi-stroke hgi-wrench-01 market-cat__icon"></i>
                    <span class="market-cat__name">Tools</span>
                </div>
                <div class="market-cat">
                    <i class="hgi-stroke hgi-zap market-cat__icon"></i>
                    <span class="market-cat__name">Electronics</span>
                </div>
                <div class="market-cat">
                    <i class="hgi-stroke hgi-car-01 market-cat__icon"></i>
                    <span class="market-cat__name">Bodywork</span>
                </div>
            </div>

            <!-- Product Cards -->
            <div class="market-products">
                <div class="product-card reveal">
                    <div class="product-card__img">
                        <span class="product-card__badge product-card__badge--oem">
                            <i class="hgi-stroke hgi-shield-check hgi-xs"></i>
                            OEM Verified
                        </span>
                        <i class="hgi-stroke hgi-tire hgi-xl"></i>
                    </div>
                    <div class="product-card__body">
                        <div class="product-card__rating">
                            <i class="hgi-stroke hgi-star hgi-xs product-card__star"></i>
                            <span>4.9 (124)</span>
                        </div>
                        <div class="product-card__name">Brembo Front Brake Pads</div>
                        <div class="product-card__seller">AutoParts Direct • Douala</div>
                        <div class="product-card__footer">
                            <span class="product-card__price">35,000 XAF</span>
                            <button class="product-card__btn" aria-label="Order Part">
                                <i class="hgi-stroke hgi-shopping-cart-01 hgi-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="product-card reveal">
                    <div class="product-card__img">
                        <span class="product-card__badge product-card__badge--express">
                            <i class="hgi-stroke hgi-zap hgi-xs"></i>
                            Express
                        </span>
                        <i class="hgi-stroke hgi-battery-full hgi-xl"></i>
                    </div>
                    <div class="product-card__body">
                        <div class="product-card__rating">
                            <i class="hgi-stroke hgi-star hgi-xs product-card__star"></i>
                            <span>4.8 (89)</span>
                        </div>
                        <div class="product-card__name">Varta Blue Dynamic Battery</div>
                        <div class="product-card__seller">PowerBattery • Yaoundé</div>
                        <div class="product-card__footer">
                            <span class="product-card__price">62,000 XAF</span>
                            <button class="product-card__btn" aria-label="Order Part">
                                <i class="hgi-stroke hgi-shopping-cart-01 hgi-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="product-card reveal">
                    <div class="product-card__img">
                        <span class="product-card__badge product-card__badge--oem">
                            <i class="hgi-stroke hgi-shield-check hgi-xs"></i>
                            Official
                        </span>
                        <i class="hgi-stroke hgi-oil-barrel hgi-xl"></i>
                    </div>
                    <div class="product-card__body">
                        <div class="product-card__rating">
                            <i class="hgi-stroke hgi-star hgi-xs product-card__star"></i>
                            <span>5.0 (310)</span>
                        </div>
                        <div class="product-card__name">Total Quartz 9000 (5L)</div>
                        <div class="product-card__seller">TotalEnergies Hub</div>
                        <div class="product-card__footer">
                            <span class="product-card__price">28,500 XAF</span>
                            <button class="product-card__btn" aria-label="Order Part">
                                <i class="hgi-stroke hgi-shopping-cart-01 hgi-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="product-card reveal">
                    <div class="product-card__img">
                        <span class="product-card__badge product-card__badge--express">
                            <i class="hgi-stroke hgi-zap hgi-xs"></i>
                            Escrow
                        </span>
                        <i class="hgi-stroke hgi-tire hgi-xl"></i>
                    </div>
                    <div class="product-card__body">
                        <div class="product-card__rating">
                            <i class="hgi-stroke hgi-star hgi-xs product-card__star"></i>
                            <span>4.9 (176)</span>
                        </div>
                        <div class="product-card__name">Michelin Primacy 4 — 215/60</div>
                        <div class="product-card__seller">AfrikTires Center</div>
                        <div class="product-card__footer">
                            <span class="product-card__price">58,000 XAF</span>
                            <button class="product-card__btn" aria-label="Order Part">
                                <i class="hgi-stroke hgi-shopping-cart-01 hgi-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 9. AI PIISTON (100vh Section) -->
    <section id="ai-piiston" class="lp-section-vh section-bg-dark" style="background: var(--bg-dark-accent); color: #FFF;">
        <div class="lp-container">
            <div style="text-align: center; max-width: 640px; margin: 0 auto 36px;" class="reveal">
                <div class="section-eyebrow section-eyebrow--brand">
                    <i class="hgi-stroke hgi-earth hgi-sm"></i>
                    Machine Learning Engine
                </div>
                <h2 class="section-title section-title--light">Piiston AI Diagnostics</h2>
                <p class="section-subtitle" style="color: rgba(255,255,255,0.6);">Trained on thousands of African road conditions and breakdown logs to predict failures before they happen.</p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                <div style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; padding: 24px;" class="reveal">
                    <i class="hgi-stroke hgi-ai-brain-01 hgi-lg" style="color: var(--active); margin-bottom: 14px;"></i>
                    <div style="font-size: 17px; font-weight: 700; margin-bottom: 6px;">Predictive Maintenance</div>
                    <div style="font-size: 13px; color: rgba(255,255,255,0.5); line-height: 1.5;">Foresee alternator, clutch kit or timing belt wear before getting stranded.</div>
                </div>

                <div style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; padding: 24px;" class="reveal">
                    <i class="hgi-stroke hgi-camera-01 hgi-lg" style="color: var(--active); margin-bottom: 14px;"></i>
                    <div style="font-size: 17px; font-weight: 700; margin-bottom: 6px;">Visual Part OCR Recognition</div>
                    <div style="font-size: 13px; color: rgba(255,255,255,0.5); line-height: 1.5;">Snap a photo of any unlabelled worn component to identify its exact OEM number.</div>
                </div>

                <div style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; padding: 24px;" class="reveal">
                    <i class="hgi-stroke hgi-bubble-chat-02 hgi-lg" style="color: var(--success); margin-bottom: 14px;"></i>
                    <div style="font-size: 17px; font-weight: 700; margin-bottom: 6px;">Voice Assistant</div>
                    <div style="font-size: 13px; color: rgba(255,255,255,0.5); line-height: 1.5;">Ask questions in English or French regarding dashboard warning lights.</div>
                </div>
            </div>
        </div>
    </section>

    <!-- 10. FLEET MANAGEMENT -->
    <section id="fleet" class="lp-section-vh section-bg-b">
        <div class="lp-container">
            <div style="text-align: center; max-width: 680px; margin: 0 auto 32px;" class="reveal">
                <div class="section-eyebrow section-eyebrow--brand">Fleet Telematics</div>
                <h2 class="section-title section-title--brand">Enterprise Fleet Management</h2>
                <p class="section-subtitle">Real-time GPS tracking, driver scoring & fuel consumption telematics.</p>
            </div>

            <div class="fleet-dashboard reveal">
                <div class="fleet-dash-header">
                    <span style="font-weight: 800; font-size: 15px;">Active Fleet Dispatch Control</span>
                    <div style="display: flex; gap: 8px;">
                        <button class="lp-btn lp-btn--ghost lp-btn--sm" style="color: #FFF; border-color: rgba(255,255,255,0.25);">Export Data</button>
                        <button class="lp-btn lp-btn--primary lp-btn--sm">Add Vehicle</button>
                    </div>
                </div>

                <div class="fleet-kpis">
                    <div class="fleet-kpi">
                        <div class="fleet-kpi__icon"><i class="hgi-stroke hgi-truck hgi-sm"></i></div>
                        <span class="fleet-kpi__val">142</span>
                        <div class="fleet-kpi__label">Active Fleet</div>
                    </div>
                    <div class="fleet-kpi">
                        <div class="fleet-kpi__icon"><i class="hgi-stroke hgi-checkmark-circle-02 hgi-sm" style="color: var(--success);"></i></div>
                        <span class="fleet-kpi__val" style="color: var(--accent-green);">128</span>
                        <div class="fleet-kpi__label">Operational</div>
                    </div>
                    <div class="fleet-kpi">
                        <div class="fleet-kpi__icon"><i class="hgi-stroke hgi-wrench-01 hgi-sm" style="color: var(--warning);"></i></div>
                        <span class="fleet-kpi__val" style="color: var(--accent-amber);">9</span>
                        <div class="fleet-kpi__label">In Workshop</div>
                    </div>
                    <div class="fleet-kpi">
                        <div class="fleet-kpi__icon"><i class="hgi-stroke hgi-oil-barrel hgi-sm"></i></div>
                        <span class="fleet-kpi__val">14,200 L</span>
                        <div class="fleet-kpi__label">Fuel Consumed</div>
                    </div>
                    <div class="fleet-kpi">
                        <div class="fleet-kpi__icon"><i class="hgi-stroke hgi-star hgi-sm" style="color: var(--active);"></i></div>
                        <span class="fleet-kpi__val" style="color: var(--accent-brand);">94.8%</span>
                        <div class="fleet-kpi__label">Safety Score</div>
                    </div>
                </div>

                <div class="fleet-table">
                    <table class="fleet-table-inner">
                        <thead>
                            <tr>
                                <th>Vehicle / License</th>
                                <th>Assigned Driver</th>
                                <th>Active Route</th>
                                <th>Efficiency</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="fleet-row work-details-trigger" data-work-details='{"vehicle":"Volvo FH16 • CE 142 AB","garage":"Douala Transit Hub Garage","vin":"VIN: VLV-FH16-2025-01","status":"● Active Hauling","fill":"80%"}' style="cursor: pointer;">
                                <td>Volvo FH16 • CE 142 AB <span style="font-size: 10px; color: var(--accent-active2);">[View Work]</span></td>
                                <td>Emmanuel N.</td>
                                <td>Douala to Yaoundé</td>
                                <td>32 L / 100 km</td>
                                <td><span class="fleet-row__status status--active">● Active</span></td>
                            </tr>
                            <tr class="fleet-row work-details-trigger" data-work-details='{"vehicle":"Mercedes Actros • LT 892 XA","garage":"Dakar Depot Workshop","vin":"VIN: MB-ACT-892-2024","status":"● Active Depot","fill":"75%"}' style="cursor: pointer;">
                                <td>Mercedes Actros • LT 892 XA <span style="font-size: 10px; color: var(--accent-active2);">[View Work]</span></td>
                                <td>Alain K.</td>
                                <td>Dakar Transit Depot</td>
                                <td>29.5 L / 100 km</td>
                                <td><span class="fleet-row__status status--active">● Active</span></td>
                            </tr>
                            <tr class="fleet-row work-details-trigger" data-work-details='{"vehicle":"Toyota Hilux • CE 990 PO","garage":"Piiston Service Center","vin":"VIN: TOY-HIL-990-2024","status":"🛠️ Service Due","fill":"35%"}' style="cursor: pointer;">
                                <td>Toyota Hilux • CE 990 PO <span style="font-size: 10px; color: var(--accent-amber);">[View Work]</span></td>
                                <td>Paul B.</td>
                                <td>Local Dispatch Hub</td>
                                <td style="color: var(--accent-amber); font-weight: 700;">Service Due</td>
                                <td><span class="fleet-row__status status--repair">🛠️ Service</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- 11. AFRICA MAP COVERAGE -->
    <section id="africa-coverage" class="lp-section-vh section-bg-a">
        <div class="lp-container" style="text-align: center;">
            <div style="max-width: 640px; margin: 0 auto 28px;" class="reveal">
                <div class="section-eyebrow section-eyebrow--green">Continental Expansion</div>
                <h2 class="section-title section-title--green">Regional Coverage & Compliance</h2>
                <p class="section-subtitle">Fully integrated with local payment providers and currencies across Central and West Africa.</p>
            </div>

            <div class="compliance-layout reveal">
                <!-- Left Column: Regulated zones and details -->
                <div class="compliance-zones">
                    <div class="compliance-zone-card">
                        <div class="zone-header">
                            <div class="zone-flag-icon">
                                <i class="hgi-stroke hgi-earth"></i>
                            </div>
                            <div class="zone-title-group">
                                <h3>West Africa (UEMOA)</h3>
                                <span class="zone-regulator">BCEAO Regulated</span>
                            </div>
                            <span class="zone-badge badge-active">Active Gateway</span>
                        </div>
                        <div class="zone-details">
                            <div class="zone-detail-row">
                                <span class="detail-label">Countries:</span>
                                <span class="detail-value">Senegal, Ivory Coast, Benin, Togo, Mali</span>
                            </div>
                            <div class="zone-detail-row">
                                <span class="detail-label">Currencies:</span>
                                <span class="detail-value text-highlight">XOF (CFA Franc)</span>
                            </div>
                            <div class="zone-detail-row">
                                <span class="detail-label">Supported Methods:</span>
                                <div class="method-pills">
                                    <span class="method-pill">Orange Money</span>
                                    <span class="method-pill">MTN MoMo</span>
                                    <span class="method-pill">Wave</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="compliance-zone-card">
                        <div class="zone-header">
                            <div class="zone-flag-icon">
                                <i class="hgi-stroke hgi-shield-check"></i>
                            </div>
                            <div class="zone-title-group">
                                <h3>Central Africa (CEMAC)</h3>
                                <span class="zone-regulator">BEAC Regulated</span>
                            </div>
                            <span class="zone-badge badge-active">Active Gateway</span>
                        </div>
                        <div class="zone-details">
                            <div class="zone-detail-row">
                                <span class="detail-label">Countries:</span>
                                <span class="detail-value">Cameroon, Gabon, Congo</span>
                            </div>
                            <div class="zone-detail-row">
                                <span class="detail-label">Currencies:</span>
                                <span class="detail-value text-highlight">XAF (CFA Franc)</span>
                            </div>
                            <div class="zone-detail-row">
                                <span class="detail-label">Supported Methods:</span>
                                <div class="method-pills">
                                    <span class="method-pill">MTN MoMo</span>
                                    <span class="method-pill">Orange Money</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="compliance-zone-card">
                        <div class="zone-header">
                            <div class="zone-flag-icon">
                                <i class="hgi-stroke hgi-activity-02"></i>
                            </div>
                            <div class="zone-title-group">
                                <h3>East Africa & Anglophone</h3>
                                <span class="zone-regulator">Central Bank Registered</span>
                            </div>
                            <span class="zone-badge badge-active">Active Gateway</span>
                        </div>
                        <div class="zone-details">
                            <div class="zone-detail-row">
                                <span class="detail-label">Countries:</span>
                                <span class="detail-value">Kenya, Nigeria, Ghana</span>
                            </div>
                            <div class="zone-detail-row">
                                <span class="detail-label">Currencies:</span>
                                <span class="detail-value text-highlight">KES, NGN, GHS</span>
                            </div>
                            <div class="zone-detail-row">
                                <span class="detail-label">Supported Methods:</span>
                                <div class="method-pills">
                                    <span class="method-pill">M-Pesa</span>
                                    <span class="method-pill">Flutterwave</span>
                                    <span class="method-pill">Bank Transfer</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Interactive/Live Settlement Dashboard View -->
                <div class="compliance-dashboard-view">
                    <div class="mock-dash-card">
                        <div class="dash-card-header">
                            <div class="dash-card-logo">
                                <i class="hgi-stroke hgi-lock hgi-sm"></i>
                                <span>PIISTON CLEARING HOUSE</span>
                            </div>
                            <span class="live-status-pulse">Live</span>
                        </div>

                        <div class="mock-tx-view">
                            <span class="tx-subtitle">Transaction Settlement Ledger</span>
                            <div class="tx-amount-display">
                                <span class="tx-currency">XOF</span>
                                <span class="tx-value">125,000</span>
                            </div>

                            <div class="tx-flow-visual">
                                <div class="flow-node">
                                    <div class="flow-icon">
                                        <i class="hgi-stroke hgi-smart-phone-01 hgi-sm" style="color: rgba(255, 255, 255, 0.85);"></i>
                                    </div>
                                    <span class="flow-label">Customer Pay</span>
                                </div>
                                <div class="flow-arrow">
                                    <div class="arrow-dot"></div>
                                </div>
                                <div class="flow-node">
                                    <div class="flow-icon">
                                        <i class="hgi-stroke hgi-zap hgi-sm" style="color: var(--active);"></i>
                                    </div>
                                    <span class="flow-label">Piiston API</span>
                                </div>
                                <div class="flow-arrow">
                                    <div class="arrow-dot"></div>
                                </div>
                                <div class="flow-node highlight">
                                    <div class="flow-icon">
                                        <i class="hgi-stroke hgi-credit-card hgi-sm" style="color: #81c784;"></i>
                                    </div>
                                    <span class="flow-label">Settled Wallet</span>
                                </div>
                            </div>

                            <div class="tx-metadata">
                                <div class="meta-item">
                                    <span class="meta-label">Origin Network:</span>
                                    <span class="meta-value">Orange Money (Senegal)</span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Settlement Latency:</span>
                                    <span class="meta-value text-green">142ms</span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Compliance Code:</span>
                                    <span class="meta-value font-mono">TX-UEMOA-9921A</span>
                                </div>
                            </div>
                        </div>

                        <div class="dash-card-footer">
                            <span class="compliance-tag"><i class="hgi-stroke hgi-shield-check hgi-xs"></i> CEMAC Audited</span>
                            <span class="compliance-tag"><i class="hgi-stroke hgi-shield-check hgi-xs"></i> UEMOA Compliant</span>
                            <span class="compliance-tag"><i class="hgi-stroke hgi-shield-check hgi-xs"></i> PCI-DSS v4.0</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="africa-features reveal">
                <div class="africa-feat">
                    <div class="africa-feat__icon"><i class="hgi-stroke hgi-money-bag-01"></i></div>
                    <div style="font-size: 15px; font-weight: 700; color: var(--text-main); margin-bottom: 4px;">Multi-Currency Settlement</div>
                    <div style="font-size: 12px; color: var(--text-muted);">Manage operations in XAF, XOF, KES, NGN, and USD instantly.</div>
                </div>
                <div class="africa-feat">
                    <div class="africa-feat__icon"><i class="hgi-stroke hgi-moon-01"></i></div>
                    <div style="font-size: 15px; font-weight: 700; color: var(--text-main); margin-bottom: 4px;">Multi-Lingual App</div>
                    <div style="font-size: 12px; color: var(--text-muted);">Support for French, English, and Swahili localized user experiences.</div>
                </div>
                <div class="africa-feat">
                    <div class="africa-feat__icon"><i class="hgi-stroke hgi-smart-phone-01"></i></div>
                    <div style="font-size: 15px; font-weight: 700; color: var(--text-main); margin-bottom: 4px;">Mobile Money Pay</div>
                    <div style="font-size: 12px; color: var(--text-muted);">Directly integration with Orange Money, MTN MoMo, Wave, M-Pesa.</div>
                </div>
            </div>
        </div>
    </section>

    <!-- 12. WHY PIISTON STANDS ALONE -->
    <section id="comparison" class="lp-section-vh section-bg-b">
        <div class="lp-container">
            <div style="text-align: center; max-width: 640px; margin: 0 auto 32px;" class="reveal">
                <div class="section-eyebrow section-eyebrow--brand">Unmatched Advantage</div>
                <h2 class="section-title section-title--brand">Why Piiston Stands Alone</h2>
                <p class="section-subtitle">Compare Piiston's connected automotive ecosystem against legacy paper methods and generic software.</p>
            </div>

            <div class="comparison-table-wrap reveal">
                <table class="comparison-table">
                    <thead>
                        <tr>
                            <th>Capability / Feature</th>
                            <th class="comparison-table__piiston-head">
                                <i class="hgi-stroke hgi-zap hgi-sm"></i>
                                Piiston Platform
                            </th>
                            <th>
                                <i class="hgi-stroke hgi-file-01 hgi-sm"></i>
                                Legacy Paper Logs
                            </th>
                            <th>
                                <i class="hgi-stroke hgi-computer hgi-sm"></i>
                                Generic Point Tools
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Digital Vehicle Passport & History</td>
                            <td class="comparison-table__piiston-cell">
                                <span class="check-yes">
                                    <i class="hgi-stroke hgi-checkmark-circle-02 hgi-xs"></i>
                                    Included (Free)
                                </span>
                            </td>
                            <td>
                                <span class="check-no">
                                    <i class="hgi-stroke hgi-cancel-01 hgi-xs"></i>
                                    Lost / Damaged
                                </span>
                            </td>
                            <td>
                                <span class="check-no">
                                    <i class="hgi-stroke hgi-cancel-01 hgi-xs"></i>
                                    Not Available
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Integrated OEM Parts Marketplace</td>
                            <td class="comparison-table__piiston-cell">
                                <span class="check-yes">
                                    <i class="hgi-stroke hgi-checkmark-circle-02 hgi-xs"></i>
                                    Direct OEM Catalog
                                </span>
                            </td>
                            <td>
                                <span class="check-no">
                                    <i class="hgi-stroke hgi-cancel-01 hgi-xs"></i>
                                    Manual Calling
                                </span>
                            </td>
                            <td>
                                <span class="check-no">
                                    <i class="hgi-stroke hgi-cancel-01 hgi-xs"></i>
                                    Not Integrated
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>AI Diagnostic & Failure Prediction Engine</td>
                            <td class="comparison-table__piiston-cell">
                                <span class="check-yes">
                                    <i class="hgi-stroke hgi-checkmark-circle-02 hgi-xs"></i>
                                    African-Trained ML
                                </span>
                            </td>
                            <td>
                                <span class="check-no">
                                    <i class="hgi-stroke hgi-cancel-01 hgi-xs"></i>
                                    Trial & Error
                                </span>
                            </td>
                            <td>
                                <span class="check-no">
                                    <i class="hgi-stroke hgi-cancel-01 hgi-xs"></i>
                                    Not Available
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Native Mobile Money (MoMo/Orange/Wave)</td>
                            <td class="comparison-table__piiston-cell">
                                <span class="check-yes">
                                    <i class="hgi-stroke hgi-checkmark-circle-02 hgi-xs"></i>
                                    Native + Escrow
                                </span>
                            </td>
                            <td>
                                <span class="check-no">
                                    <i class="hgi-stroke hgi-cancel-01 hgi-xs"></i>
                                    High Cash Risk
                                </span>
                            </td>
                            <td>
                                <span class="check-no">
                                    <i class="hgi-stroke hgi-cancel-01 hgi-xs"></i>
                                    Paid Plugin
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>WhatsApp Interactive Invoice & Approval</td>
                            <td class="comparison-table__piiston-cell">
                                <span class="check-yes">
                                    <i class="hgi-stroke hgi-checkmark-circle-02 hgi-xs"></i>
                                    Automated Bridge
                                </span>
                            </td>
                            <td>
                                <span class="check-no">
                                    <i class="hgi-stroke hgi-cancel-01 hgi-xs"></i>
                                    Paper Delivery
                                </span>
                            </td>
                            <td>
                                <span class="check-no">
                                    <i class="hgi-stroke hgi-cancel-01 hgi-xs"></i>
                                    Email Only
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- 13. BLOG & NEWS -->
    <section id="blog" class="lp-section-vh section-bg-c">
        <div class="lp-container">
            <div style="text-align: center; max-width: 640px; margin: 0 auto 32px;" class="reveal">
                <div class="section-eyebrow section-eyebrow--brand">News & Guide</div>
                <h2 class="section-title section-title--brand">Latest Articles & Maintenance Guides</h2>
                <p class="section-subtitle">Expert advice on vehicle care, auto tech, and fleet management.</p>
            </div>

            <div class="blog-grid reveal">
                <div class="blog-card">
                    <div class="blog-card__img"><i class="hgi-stroke hgi-tire hgi-xl"></i></div>
                    <div class="blog-card__body">
                        <span class="blog-card__tag">Maintenance Guide</span>
                        <div class="blog-card__title">5 Signs Your Brake Pads Need Replacement</div>
                        <div class="blog-card__excerpt">Squealing noises or soft pedal feel? Learn how to spot brake wear early to avoid rotor damage.</div>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: var(--text-subtle);">
                            <span>Aug 4, 2026</span>
                            <a href="#" style="color: var(--accent-active2); font-weight: 700;">Read More →</a>
                        </div>
                    </div>
                </div>

                <div class="blog-card">
                    <div class="blog-card__img"><i class="hgi-stroke hgi-garage hgi-xl"></i></div>
                    <div class="blog-card__body">
                        <span class="blog-card__tag">Garage Business</span>
                        <div class="blog-card__title">How AutoTech Douala Scaled Ops with Piiston ERP</div>
                        <div class="blog-card__excerpt">Exploring the transformation from paper scheduling cards to digital quotes and instant WhatsApp approvals.</div>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: var(--text-subtle);">
                            <span>Jul 28, 2026</span>
                            <a href="#" style="color: var(--accent-active2); font-weight: 700;">Read More →</a>
                        </div>
                    </div>
                </div>

                <div class="blog-card">
                    <div class="blog-card__img"><i class="hgi-stroke hgi-shield-check hgi-xl"></i></div>
                    <div class="blog-card__body">
                        <span class="blog-card__tag">Parts Integrity</span>
                        <div class="blog-card__title">Identifying Counterfeit Spare Parts in Central Africa</div>
                        <div class="blog-card__excerpt">Fake components can damage your engine. Learn how Piiston verifies sellers and tracks parts.</div>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: var(--text-subtle);">
                            <span>Jul 15, 2026</span>
                            <a href="#" style="color: var(--accent-active2); font-weight: 700;">Read More →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 14. CONTACT US -->
    <section id="contact" class="lp-section-vh section-bg-a">
        <div class="lp-container">
            <div style="text-align: center; max-width: 640px; margin: 0 auto 32px;" class="reveal">
                <div class="section-eyebrow section-eyebrow--brand">Connect With Us</div>
                <h2 class="section-title section-title--brand">Get in Touch With Piiston</h2>
                <p class="section-subtitle">Have questions or need enterprise fleet consultation? Send us a message.</p>
            </div>

            <div class="contact-grid">
                <div class="reveal">
                    <h3 style="font-size: 22px; font-weight: 800; margin-bottom: 14px;">Contact Information</h3>
                    <p style="font-size: 15px; color: var(--text-muted); line-height: 1.6; margin-bottom: 28px;">
                        Our support and corporate onboarding teams are available to assist you in digitizing your garage, parts store, or commercial fleet assets.
                    </p>

                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; background: rgba(155,184,211,0.15); color: var(--accent-active2); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="hgi-stroke hgi-mail-01"></i>
                            </div>
                            <div>
                                <div style="font-size: 11px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Email Us</div>
                                <div style="font-size: 14px; font-weight: 700;">support@piiston.com</div>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; background: rgba(155,184,211,0.15); color: var(--accent-active2); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="hgi-stroke hgi-call"></i>
                            </div>
                            <div>
                                <div style="font-size: 11px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Call Us</div>
                                <div style="font-size: 14px; font-weight: 700;">+237 600 000 000</div>
                            </div>
                        </div>
                    </div>
                </div>

                <form class="contact-form reveal" action="{{ route('contact.submit') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" required placeholder="Your name">
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required placeholder="your@email.com">
                    </div>
                    <div class="form-group">
                        <label for="role">Your Role</label>
                        <select id="role" name="role">
                            <option value="owner">Vehicle Owner</option>
                            <option value="garage">Garage Owner</option>
                            <option value="fleet">Fleet Manager</option>
                            <option value="partner">Enterprise Partner</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="4" required placeholder="How can we help you?"></textarea>
                    </div>
                    <button type="submit" class="lp-btn lp-btn--primary" style="width: 100%;">Send Message</button>
                </form>
            </div>
        </div>
    </section>

    <!-- 15. FAQ ACCORDION -->
    <section id="faq" class="lp-section-vh section-bg-b">
        <div class="lp-container">
            <div style="text-align: center; max-width: 640px; margin: 0 auto 32px;" class="reveal">
                <div class="section-eyebrow section-eyebrow--brand">Got Questions?</div>
                <h2 class="section-title section-title--brand">Frequently Asked Questions</h2>
                <p class="section-subtitle">Everything you need to know about the Piiston automotive ecosystem.</p>
            </div>

            <div class="faq-list reveal">
                <div class="faq-item">
                    <button class="faq-item__q">
                        <span>1. Is Piiston free to use for car owners?</span>
                        <i class="hgi-stroke hgi-arrow-down-01 hgi-sm"></i>
                    </button>
                    <div class="faq-item__a">
                        Yes! Vehicle owners can register unlimited vehicles, track digital logbooks, receive maintenance alerts, and request repair quotes completely free of charge. You only pay when purchasing parts or confirming a garage service.
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-item__q">
                        <span>2. Which African countries and currencies are supported?</span>
                        <i class="hgi-stroke hgi-arrow-down-01 hgi-sm"></i>
                    </button>
                    <div class="faq-item__a">
                        Piiston is currently operational across Cameroon, Ivory Coast, Senegal, Nigeria, and Kenya. We natively support XAF, XOF, NGN, KES, and USD with localized Mobile Money gateways (MTN MoMo, Orange Money, Wave, M-Pesa).
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-item__q">
                        <span>3. How does Piiston ensure genuine OEM spare parts?</span>
                        <i class="hgi-stroke hgi-arrow-down-01 hgi-sm"></i>
                    </button>
                    <div class="faq-item__a">
                        Every seller on Piiston undergoes a rigorous 5-point verification process including physical store inspection and manufacturer distributor check. Payments are held in Escrow and released only after part delivery inspection.
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-item__q">
                        <span>4. How do garages & mechanics integrate with WhatsApp?</span>
                        <i class="hgi-stroke hgi-arrow-down-01 hgi-sm"></i>
                    </button>
                    <div class="faq-item__a">
                        Piiston automatically generates interactive WhatsApp digital quotes. Clients receive a link via WhatsApp where they can view breakdown photos, approve specific repair items, and make instant Mobile Money deposits.
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-item__q">
                        <span>5. Can fleet operators track vehicles and fuel consumption in real time?</span>
                        <i class="hgi-stroke hgi-arrow-down-01 hgi-sm"></i>
                    </button>
                    <div class="faq-item__a">
                        Yes! Piiston Fleet Telematics provides live OBD-II and GPS hardware integration, giving fleet managers instant alerts on fuel anomalies, speed scoring, driver behavior, and scheduled maintenance requirements.
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-item__q">
                        <span>6. How secure is my payment and vehicle diagnostic data?</span>
                        <i class="hgi-stroke hgi-arrow-down-01 hgi-sm"></i>
                    </button>
                    <div class="faq-item__a">
                        Your security is paramount. Piiston uses AES-256 encryption, TLS 1.3 in transit, PCI-DSS compliant payment gateways, and strictly complies with CEMAC, ECOWAS, and international GDPR data protection regulations.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 16. CALL TO ACTION -->
    <section id="cta-banner" style="background: var(--bg-dark-accent); color: #FFF; padding: 80px 0; text-align: center;">
        <div class="lp-container">
            <div class="reveal">
                <div class="hero__label" style="color: var(--accent-active);">Join the Revolution</div>
                <h2 style="font-size: clamp(30px, 4.5vw, 52px); font-weight: 900; margin-bottom: 16px; color: #FFF;">Ready to Transform Your Automotive Business?</h2>
                <p style="font-size: 16px; color: rgba(255,255,255,0.6); max-width: 580px; margin: 0 auto 32px;">
                    Join thousands of drivers, certified workshops, mechanics, and fleet managers operating on Africa's #1 automotive ecosystem.
                </p>

                <div style="display: flex; justify-content: center; gap: 14px; flex-wrap: wrap;">
                    <a href="{{ route('register') ?? '#' }}" class="lp-btn lp-btn--primary lp-btn--lg">Get Started Free</a>
                    <a href="#solutions" class="lp-btn lp-btn--ghost lp-btn--lg" style="color: #FFF; border-color: rgba(255,255,255,0.2);">Book a Demo</a>
                </div>
            </div>
        </div>
    </section>

    <!-- 17. ENHANCED RICH FOOTER -->
    <footer id="site-footer">
        <div class="footer__watermark" aria-hidden="true">Piiston</div>
        <div class="lp-container">

            <!-- Newsletter Subscription Strip -->
            <div class="footer__newsletter reveal">
                <div class="footer__newsletter-text">
                    <h3>Subscribe to Automotive Insights Africa</h3>
                    <p>Get weekly updates on spare parts market rates, garage ERP tips, and fleet management guides.</p>
                </div>
                <form class="footer__newsletter-form" action="{{ route('newsletter.subscribe') }}" method="POST">
                    @csrf
                    <input type="email" name="email" placeholder="Enter your business email..." required>
                    <button type="submit" class="lp-btn lp-btn--primary lp-btn--sm">Subscribe</button>
                </form>
            </div>

            <!-- Footer Columns -->
            <div class="footer__top">
                <div class="footer-brand-col">
                    <div class="footer-logo">
                        <img src="{{ asset('piiston/android-chrome-192x192.png') }}" width="36" height="36" alt="Piiston Logo" style="border-radius: 10px;">
                        <span>Piiston</span>
                    </div>
                    <p class="footer-brand-desc">
                        The unified automotive intelligence ecosystem for Africa. Seamlessly connecting drivers, certified garages, mechanics, spare part sellers, and fleet managers into one synchronized digital pulse.
                    </p>
                    <!-- App Download Buttons -->
                    <div class="footer__app-buttons">
                        <a href="#" class="footer__app-btn">
                            <i class="hgi-stroke hgi-apple hgi-sm"></i>
                            <div>
                                <span style="font-size: 9px; display: block; opacity: 0.7;">Download on the</span>
                                <span style="font-size: 12px; font-weight: 700;">App Store</span>
                            </div>
                        </a>
                        <a href="#" class="footer__app-btn">
                            <i class="hgi-stroke hgi-play-circle hgi-sm"></i>
                            <div>
                                <span style="font-size: 9px; display: block; opacity: 0.7;">GET IT ON</span>
                                <span style="font-size: 12px; font-weight: 700;">Google Play</span>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="footer-col">
                    <div class="footer-col__title">Product Suite</div>
                    <div class="footer-col__links">
                        <a href="#hero" class="footer-col__link">Vehicle Vault</a>
                        <a href="#solutions" class="footer-col__link">Garage ERP</a>
                        <a href="#marketplace" class="footer-col__link">Parts Marketplace</a>
                        <a href="#fleet" class="footer-col__link">Fleet Telematics</a>
                        <a href="#ai-piiston" class="footer-col__link">AI Diagnostics</a>
                        <a href="#africa-coverage" class="footer-col__link">Multi-Currency Pay</a>
                    </div>
                </div>

                <div class="footer-col">
                    <div class="footer-col__title">Solutions by Role</div>
                    <div class="footer-col__links">
                        <a href="#solutions" class="footer-col__link">Car Owners Portal</a>
                        <a href="#solutions" class="footer-col__link">Workshop Lifts ERP</a>
                        <a href="#solutions" class="footer-col__link">Technician App</a>
                        <a href="#solutions" class="footer-col__link">Spare Parts Merchants</a>
                        <a href="#fleet" class="footer-col__link">Logistics & Fleets</a>
                        <a href="#contact" class="footer-col__link">Insurance Partners</a>
                    </div>
                </div>

                <div class="footer-col">
                    <div class="footer-col__title">Regional Hubs</div>
                    <div class="footer-col__links">
                        <a href="#africa-coverage" class="footer-col__link">Douala (HQ)</a>
                        <a href="#africa-coverage" class="footer-col__link">Yaoundé</a>
                        <a href="#africa-coverage" class="footer-col__link">Abidjan</a>
                        <a href="#africa-coverage" class="footer-col__link">Lagos</a>
                        <a href="#africa-coverage" class="footer-col__link">Nairobi</a>
                        <a href="#africa-coverage" class="footer-col__link">Dakar</a>
                    </div>
                </div>

                <div class="footer-col">
                    <div class="footer-col__title">Resources</div>
                    <div class="footer-col__links">
                        <a href="#faq" class="footer-col__link">Help Center & FAQ</a>
                        <a href="#blog" class="footer-col__link">Articles & News</a>
                        <a href="#comparison" class="footer-col__link">Platform Comparison</a>
                        <a href="#" class="footer-col__link">API Documentation</a>
                        <a href="#" class="footer-col__link">System Status</a>
                    </div>
                </div>

                <div class="footer-col">
                    <div class="footer-col__title">Legal & Security</div>
                    <div class="footer-col__links">
                        <a href="#" class="footer-col__link">Privacy Policy</a>
                        <a href="#" class="footer-col__link">Terms of Service</a>
                        <a href="#" class="footer-col__link">Escrow Guarantee</a>
                        <a href="#" class="footer-col__link">GDPR Compliance</a>
                        <a href="#" class="footer-col__link">Security Vault</a>
                    </div>
                </div>
            </div>

            <!-- Payment Methods Strip -->
            <div class="footer__payments">
                <span class="footer__payments-label">Supported Payment Gateways & Mobile Money:</span>
                <div class="footer__payments-icons">
                    <span class="pay-badge">MTN MoMo</span>
                    <span class="pay-badge">Orange Money</span>
                    <span class="pay-badge">Wave</span>
                    <span class="pay-badge">M-Pesa</span>
                    <span class="pay-badge">Visa</span>
                    <span class="pay-badge">Mastercard</span>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer__bottom">
                <div>
                    &copy; {{ date('Y') }} Piiston Technologies Ltd. All rights reserved. Registered across CEMAC & ECOWAS.
                </div>

                <div class="footer__badges">
                    <span class="footer__badge">
                        <i class="hgi-stroke hgi-shield-check hgi-xs"></i>
                        AES-256 Cloud Encrypted
                    </span>
                    <span class="footer__badge">
                        <i class="hgi-stroke hgi-earth hgi-xs"></i>
                        Multi-Currency Engine
                    </span>
                    <span class="footer__badge">
                        <i class="hgi-stroke hgi-zap hgi-xs"></i>
                        99.99% Enterprise Uptime
                    </span>
                </div>

                <div class="footer__socials">
                    <a href="#" class="footer__social-link" aria-label="LinkedIn">
                        <i class="hgi-stroke hgi-linkedin-01 hgi-xs"></i>
                    </a>
                    <a href="#" class="footer__social-link" aria-label="X (Twitter)">
                        <i class="hgi-stroke hgi-new-twitter hgi-xs"></i>
                    </a>
                    <a href="#" class="footer__social-link" aria-label="GitHub">
                        <i class="hgi-stroke hgi-github hgi-xs"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- WORK DETAILS MODAL (INTERACTIVE ANATOMY) -->
    <div id="work-details-modal" class="work-modal" role="dialog" aria-modal="true" aria-labelledby="work-modal-title">
        <div class="work-modal__backdrop"></div>
        <div class="work-modal__dialog">
            <div class="work-modal__header">
                <div class="work-modal__title-group">
                    <span class="work-modal__sub modal-garage-name">AutoTech Douala • Certified Workshop</span>
                    <h3 id="work-modal-title" class="work-modal__title modal-vehicle-title">Toyota RAV4 — CE 489 AK</h3>
                </div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span class="fleet-row__status status--active modal-status-pill">● Work In Progress</span>
                    <button class="work-modal__close work-modal-close-btn" aria-label="Close Work Details Modal">
                        <i class="hgi-stroke hgi-cancel-01 hgi-sm"></i>
                    </button>
                </div>
            </div>

            <div class="work-modal__body">
                <!-- Live Stage Tracker -->
                <div class="stage-tracker-wrap">
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; font-weight: 700;">
                        <span style="color: var(--text-main);">Live Repair Workflow Stage</span>
                        <span style="color: var(--accent-active2);" class="modal-vin-code">VIN: JTEHH20V7001429</span>
                    </div>

                    <div class="stage-tracker">
                        <div class="stage-tracker__line-fill" style="width: 66%;"></div>
                        <div class="stage-step is-done" data-step="1">
                            <div class="stage-step__icon">✓</div>
                            <span class="stage-step__label">Diagnosis</span>
                        </div>
                        <div class="stage-step is-done" data-step="2">
                            <div class="stage-step__icon">✓</div>
                            <span class="stage-step__label">Parts Sent</span>
                        </div>
                        <div class="stage-step is-current" data-step="3">
                            <div class="stage-step__icon">⚙️</div>
                            <span class="stage-step__label">In Repair</span>
                        </div>
                        <div class="stage-step" data-step="4">
                            <div class="stage-step__icon">💳</div>
                            <span class="stage-step__label">Escrow Release</span>
                        </div>
                    </div>
                </div>

                <!-- Sub-Tabs Navigation -->
                <div class="work-modal__tabs">
                    <button class="work-modal__tab is-active" data-modal-tab="overview">Overview & Details</button>
                    <button class="work-modal__tab" data-modal-tab="parts">Parts & Costs</button>
                    <button class="work-modal__tab" data-modal-tab="timeline">Diagnostic Log</button>
                </div>

                <!-- Tab Panel 1: Overview -->
                <div class="work-modal__panel is-active" data-modal-panel="overview">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 20px;">
                        <div style="background: var(--bg-tertiary); padding: 14px; border-radius: 14px;">
                            <div style="font-size: 11px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Lead Mechanic</div>
                            <div style="font-size: 14px; font-weight: 800; margin-top: 2px;">Alain K. (Master Tech)</div>
                        </div>
                        <div style="background: var(--bg-tertiary); padding: 14px; border-radius: 14px;">
                            <div style="font-size: 11px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Estimated Completion</div>
                            <div style="font-size: 14px; font-weight: 800; margin-top: 2px; color: var(--accent-green);">Today at 17:30</div>
                        </div>
                    </div>
                    <div style="font-size: 13px; color: var(--text-main); line-height: 1.5; background: var(--bg-tertiary); padding: 16px; border-radius: 14px;">
                        <strong>Work Summary:</strong> Periodic 50,000 km full engine oil maintenance, replacement of OEM oil & air filter elements, and front brake pad calibration. Photo evidence verified via Piiston Diagnostic OCR.
                    </div>
                </div>

                <!-- Tab Panel 2: Parts & Costs -->
                <div class="work-modal__panel" data-modal-panel="parts">
                    <table class="work-cost-table">
                        <thead>
                            <tr>
                                <th>Item Description</th>
                                <th>Category</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Total Quartz 9000 Synthetic 5W40 (4L)</td>
                                <td><span style="font-size: 11px; background: rgba(155,184,211,0.2); padding: 2px 8px; border-radius: 6px;">OEM Fluids</span></td>
                                <td>1</td>
                                <td>35,000 XAF</td>
                            </tr>
                            <tr>
                                <td>Toyota OEM Engine Oil Filter Element</td>
                                <td><span style="font-size: 11px; background: rgba(155,184,211,0.2); padding: 2px 8px; border-radius: 6px;">Spare Parts</span></td>
                                <td>1</td>
                                <td>8,500 XAF</td>
                            </tr>
                            <tr>
                                <td>Certified Mechanic Labor & Calibration</td>
                                <td><span style="font-size: 11px; background: rgba(155,184,211,0.2); padding: 2px 8px; border-radius: 6px;">Service</span></td>
                                <td>1</td>
                                <td>15,000 XAF</td>
                            </tr>
                            <tr class="work-cost-total">
                                <td colspan="3">Total Work Order Value</td>
                                <td>58,500 XAF</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Tab Panel 3: Diagnostic Log -->
                <div class="work-modal__panel" data-modal-panel="timeline">
                    <div class="work-timeline">
                        <div class="work-timeline-item">
                            <span class="work-timeline-item__time">14:10</span>
                            <div class="work-timeline-item__text">
                                <strong>Part Installation:</strong> OEM Synthetic Oil & Filter installed by Alain K. Pressure test cleared.
                            </div>
                        </div>
                        <div class="work-timeline-item">
                            <span class="work-timeline-item__time">11:45</span>
                            <div class="work-timeline-item__text">
                                <strong>Parts Delivered:</strong> Express part courier arrived at AutoTech workshop with Escrow OTP confirmation.
                            </div>
                        </div>
                        <div class="work-timeline-item">
                            <span class="work-timeline-item__time">09:30</span>
                            <div class="work-timeline-item__text">
                                <strong>Vehicle Check-in & AI OCR Scan:</strong> VIN JTEHH20V7001429 verified via Piiston Mobile App.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="work-modal__footer">
                <a href="https://wa.me/?text=Hi%20AutoTech,%20I%20am%20checking%20my%20work%20order" target="_blank" class="lp-btn lp-btn--ghost lp-btn--sm" style="gap: 6px;">
                    <i class="hgi-stroke hgi-bubble-chat-02 hgi-xs"></i>
                    WhatsApp Garage
                </a>
                <button class="lp-btn lp-btn--primary lp-btn--sm work-modal-pay-btn">
                    <span>Approve & Lock MoMo Escrow</span>
                    <i class="hgi-stroke hgi-checkmark-circle-02 hgi-xs"></i>
                </button>
            </div>
        </div>
    </div>

    @include('partials.cookie-consent')

</body>
</html>
