{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-js">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO --}}
    <title>@yield('meta_title', config('amsparts.meta_title_default', config('amsparts.company_name', 'Parts Plus Innovation Solutions') . ' — Heavy Equipment Parts'))</title>
    <meta name="description" content="@yield('meta_description', config('amsparts.meta_description_default', 'New, used & rebuilt heavy equipment parts. Fast shipping nationwide.'))">
    @hasSection('meta_robots')
        <meta name="robots" content="@yield('meta_robots')">
    @else
        <meta name="robots" content="index, follow">
    @endif
    <link rel="canonical" href="@yield('canonical', url()->current())">

    {{-- Open Graph --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('meta_title', config('amsparts.company_name', 'Parts Plus Innovation Solutions'))">
    <meta property="og:description" content="@yield('meta_description', '')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">
    <meta property="og:site_name" content="{{ config('amsparts.company_name', 'Parts Plus Innovation Solutions') }}">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,400;0,600;0,700;0,800;1,700&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&display=swap"
        rel="stylesheet">

    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Styles --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ config('app.asset_version', '1.0') }}">
    @stack('styles')

    {{-- Analytics --}}
    @if ($gaId = config('amsparts.google_analytics_id'))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments)
            }
            gtag('js', new Date());
            gtag('config', '{{ $gaId }}');
        </script>
    @endif
    @if ($gtmId = config('amsparts.gtm_id'))
        <script>
            (function(w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    'gtm.start': new Date().getTime(),
                    event: 'gtm.js'
                });
                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s),
                    dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', '{{ $gtmId }}');
        </script>
    @endif
    {!! config('amsparts.custom_head_scripts') !!}
</head>

<body class="@yield('body_class')">

    @if ($gtmId = config('amsparts.gtm_id'))
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}" height="0"
                width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif

    {{-- ══════════ ANNOUNCEMENT BAR ══════════ --}}
    @php $announcement = config('amsparts.announcement_bar') @endphp
    @if ($announcement)
        <div class="announcement-bar" id="announcementBar">
            <div class="container">
                <span class="announcement-text">{{ $announcement }}</span>
                <button class="announcement-close" onclick="this.closest('.announcement-bar').remove()"
                    aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>
    @endif

    {{-- ══════════ HEADER ══════════ --}}
    <header class="site-header" id="siteHeader">
        <div class="header-inner container">

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="site-logo"
                aria-label="{{ config('amsparts.company_name', 'Parts Plus Innovation Solutions') }} Homepage">
                @if (config('amsparts.logo_url'))
                    <img src="{{ config('amsparts.logo_url') }}"
                        alt="{{ config('amsparts.company_name', 'Parts Plus Innovation Solutions') }}"
                        class="logo-img">
                @else
                    <span class="logo-mark">P</span>
                    <span class="logo-text">Parts Plus <em>Innovation Solutions</em></span>
                @endif
            </a>

            {{-- Desktop Search --}}
            <form class="header-search" action="{{ route('parts.index') }}" method="GET" role="search">
                <div class="header-search-inner">
                    <i class="fa-solid fa-magnifying-glass header-search-icon"></i>
                    <input type="text" name="search" class="header-search-input" value="{{ request('search') }}"
                        placeholder="Search by part number, keyword, or OEM…" autocomplete="off"
                        aria-label="Search parts">
                    <button type="submit" class="header-search-btn">Search</button>
                </div>
            </form>

            {{-- Header Right --}}
            <div class="header-right">
                <a href="tel:{{ config('amsparts.company.phone') }}" class="header-phone">
                    <i class="fa-solid fa-phone"></i>
                    <span>{{ config('amsparts.company.phone', '(917) 640-3410') }}</span>
                </a>
                {{-- Cart Icon (for Heavy Duty Tools) --}}
                <a href="{{ route('cart.index') }}" class="header-cart-btn" aria-label="View cart"
                    style="position:relative;display:inline-flex;align-items:center;gap:6px;padding:8px 10px;color:var(--gray-700);text-decoration:none;font-size:13px;font-weight:600;transition:color .15s;">
                    <i class="fa-solid fa-cart-shopping" style="font-size:17px;"></i>
                    @php $cartCount = session('cart') ? array_sum(array_column(session('cart'), 'quantity')) : 0; @endphp
                    <span data-cart-count
                        style="position:absolute;top:2px;right:2px;background:#ef4444;color:#fff;font-size:10px;font-weight:700;min-width:16px;height:16px;border-radius:8px;display:{{ $cartCount > 0 ? 'flex' : 'none' }};align-items:center;justify-content:center;padding:0 3px;">
                        {{ $cartCount }}
                    </span>
                </a>
                <a href="{{ route('quote.create') }}" class="btn-quote">
                    <i class="fa-solid fa-file-lines"></i>
                    <span>Get Quote</span>
                </a>
                <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Open menu"
                    aria-expanded="false">
                    <span class="hamburger">
                        <span></span><span></span><span></span>
                    </span>
                </button>
            </div>

        </div>

        {{-- ── Primary Nav ── --}}
        <nav class="primary-nav" id="primaryNav" aria-label="Main Navigation">
            <div class="container">
                <ul class="primary-nav-list">

                    {{-- Parts (Mega Menu by Make) --}}
                    <li class="nav-item nav-item--mega" data-mega="makes">
                        <a href="{{ route('parts.index') }}" class="nav-link">
                            Parts <i class="fa-solid fa-chevron-down nav-caret"></i>
                        </a>
                        <div class="mega-menu" id="megaMakes">
                            <div class="container">
                                <div class="mega-menu-inner">
                                    <div class="mega-col mega-col--featured">
                                        <div class="mega-col-title">Shop by Category</div>
                                        <ul class="mega-list">
                                            @foreach ($navCategories ?? [] as $cat)
                                                <li>
                                                    <a href="{{ route('categories.show', $cat->slug) }}"
                                                        class="mega-link">
                                                        <i class="fa-solid fa-angle-right"></i> {{ $cat->name }}
                                                        <span class="mega-count">{{ $cat->parts_count }}</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                            <li>
                                                <a href="{{ route('parts.index') }}"
                                                    class="mega-link mega-link--all">
                                                    View All Parts <i class="fa-solid fa-arrow-right"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="mega-col mega-col--makes">
                                        <div class="mega-col-title">Shop by Make</div>
                                        <div class="mega-makes-grid">
                                            @foreach ($navMakes ?? [] as $make)
                                                <a href="{{ route('makes.show', $make->slug) }}"
                                                    class="mega-make-item" title="{{ $make->name }} Parts">
                                                    @if ($make->logo)
                                                        <img src="{{ $make->logo->public_url }}"
                                                            alt="{{ $make->name }}" class="mega-make-logo">
                                                    @else
                                                        <span class="mega-make-name">{{ $make->name }}</span>
                                                    @endif
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="mega-col mega-col--promo">
                                        <div class="mega-promo">
                                            <div class="mega-promo-label">Can't Find It?</div>
                                            <div class="mega-promo-title">We'll Source It</div>
                                            <p>Submit a quote request with your part number, make, and model — we'll
                                                find it.</p>
                                            <a href="{{ route('quote.create') }}" class="mega-promo-btn">
                                                Request a Quote
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    {{-- Equipment Types --}}
                    <li class="nav-item nav-item--dropdown" data-dropdown="equipment">
                        <a href="#" class="nav-link">
                            By Equipment <i class="fa-solid fa-chevron-down nav-caret"></i>
                        </a>
                        <div class="dropdown-menu" id="dropdownEquipment">
                            @foreach ($navEquipmentTypes ?? [] as $type)
                                <a href="{{ route('equipment-types.show', $type->slug) }}" class="dropdown-item">
                                    {{ $type->name }}
                                </a>
                            @endforeach
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('parts.index') }}" class="dropdown-item dropdown-item--all">
                                All Equipment Parts
                            </a>
                        </div>
                    </li>

                    <li class="nav-item"><a href="{{ route('quote.create') }}" class="nav-link">Get a Quote</a></li>
                    <li class="nav-item">
                        <a href="{{ route('tools.index') }}"
                            class="nav-link {{ request()->routeIs('tools.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-hammer" style="font-size:11px;color:var(--orange);"></i> Tools
                        </a>
                    </li>
                    <li class="nav-item"><a href="{{ route('blog.index') }}" class="nav-link">Blog</a></li>
                    <li class="nav-item"><a href="{{ route('about') }}" class="nav-link">About</a></li>
                    <li class="nav-item"><a href="{{ route('contact') }}" class="nav-link">Contact</a></li>

                </ul>
            </div>
        </nav>

    </header>

    {{-- ══════════ MOBILE MENU DRAWER ══════════ --}}
    <div class="mobile-menu-overlay" id="mobileMenuOverlay" aria-hidden="true"></div>
    <nav class="mobile-menu" id="mobileMenu" aria-hidden="true" aria-label="Mobile Navigation">
        <div class="mobile-menu-header">
            <a href="{{ url('/') }}" class="site-logo site-logo--sm">
                <span class="logo-mark">P</span>
                <span class="logo-text">Parts Plus Innovation Solutions <em>Parts</em></span>
            </a>
            <button class="mobile-menu-close" id="mobileMenuClose" aria-label="Close menu">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        {{-- Mobile search --}}
        <form class="mobile-search" action="{{ route('parts.index') }}" method="GET">
            <input type="text" name="search" placeholder="Search parts…" value="{{ request('search') }}">
            <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>

        <ul class="mobile-nav-list">
            <li><a href="{{ route('parts.index') }}" class="mobile-nav-link">All Parts</a></li>

            <li class="mobile-nav-group">
                <button class="mobile-nav-toggle" aria-expanded="false">
                    Shop by Make <i class="fa-solid fa-chevron-down"></i>
                </button>
                <ul class="mobile-nav-sub">
                    @foreach ($navMakes ?? [] as $make)
                        <li><a href="{{ route('makes.show', $make->slug) }}"
                                class="mobile-nav-sub-link">{{ $make->name }}</a></li>
                    @endforeach
                </ul>
            </li>

            <li class="mobile-nav-group">
                <button class="mobile-nav-toggle" aria-expanded="false">
                    By Equipment Type <i class="fa-solid fa-chevron-down"></i>
                </button>
                <ul class="mobile-nav-sub">
                    @foreach ($navEquipmentTypes ?? [] as $type)
                        <li><a href="{{ route('equipment-types.show', $type->slug) }}"
                                class="mobile-nav-sub-link">{{ $type->name }}</a></li>
                    @endforeach
                </ul>
            </li>

            <li><a href="{{ route('quote.create') }}" class="mobile-nav-link">Get a Quote</a></li>
            <li><a href="{{ route('tools.index') }}"
                    class="mobile-nav-link {{ request()->routeIs('tools.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-hammer" style="font-size:11px;color:var(--orange);margin-right:4px;"></i>
                    Heavy Duty Tools
                </a></li>
            <li><a href="{{ route('blog.index') }}" class="mobile-nav-link">Blog</a></li>
            <li><a href="{{ route('about') }}" class="mobile-nav-link">About</a></li>
            <li><a href="{{ route('gallery') }}" class="mobile-nav-link">Gallery</a></li>
            <li><a href="{{ route('contact') }}" class="mobile-nav-link">Contact</a></li>
        </ul>

        <div class="mobile-menu-footer">
            <a href="tel:{{ config('amsparts.phone_main') }}" class="mobile-contact-link">
                <i class="fa-solid fa-phone"></i> {{ config('amsparts.phone_main') }}
            </a>
            <a href="mailto:{{ config('amsparts.email_sales') }}" class="mobile-contact-link">
                <i class="fa-solid fa-envelope"></i> {{ config('amsparts.email_sales') }}
            </a>
        </div>
    </nav>

    {{-- ══════════ FLASH MESSAGES ══════════ --}}
    @php $flashTypes = ['success','error','warning','info']; @endphp
    @if (collect($flashTypes)->contains(fn($type) => session($type)))
        <div class="flash-stack" id="flashStack">
            @foreach (['success', 'error', 'warning', 'info'] as $type)
                @if (session($type))
                    <div class="flash flash--{{ $type }}" role="alert">
                        <i
                            class="fa-solid fa-{{ match ($type) {'success' => 'circle-check','error' => 'circle-xmark','warning' => 'triangle-exclamation',default => 'circle-info'} }}"></i>
                        <span>{{ session($type) }}</span>
                        <button class="flash-close" onclick="this.closest('.flash').remove()" aria-label="Dismiss">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif
            @endforeach
        </div>
    @endif

    {{-- ══════════ MAIN CONTENT ══════════ --}}
    <main id="main" class="site-main @yield('main_class')">
        @yield('content')
    </main>

    {{-- ══════════ FOOTER ══════════ --}}
    <footer class="site-footer" id="siteFooter">

        {{-- CTA Strip --}}
        <div class="footer-cta-strip">
            <div class="container">
                <div class="footer-cta-inner">
                    <div class="footer-cta-text">
                        <h2 class="footer-cta-title">Can't find the part you need?</h2>
                        <p>Our parts specialists are ready to help. Submit a quote and we'll source it.</p>
                    </div>
                    <div class="footer-cta-actions">
                        <a href="{{ route('quote.create') }}" class="footer-cta-btn footer-cta-btn--primary">
                            <i class="fa-solid fa-file-lines"></i> Request a Free Quote
                        </a>
                        @if (config('amsparts.phone_main'))
                            <a href="tel:{{ config('amsparts.phone_main') }}"
                                class="footer-cta-btn footer-cta-btn--ghost">
                                <i class="fa-solid fa-phone"></i> {{ config('amsparts.phone_main') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer Main --}}
        <div class="footer-main">
            <div class="container">
                <div class="footer-grid">

                    {{-- Brand --}}
                    <div class="footer-col footer-col--brand">
                        <a href="{{ url('/') }}" class="site-logo site-logo--footer">
                            <span class="logo-mark">P</span>
                            <span class="logo-text">Parts Plus Innovation Solutions <em>Parts</em></span>
                        </a>
                        @if (config('amsparts.footer_tagline'))
                            <p class="footer-tagline">{{ config('amsparts.footer_tagline') }}</p>
                        @endif
                        <div class="footer-contact-list">
                            @if (config('amsparts.phone_main'))
                                <a href="tel:{{ config('amsparts.phone_main') }}" class="footer-contact-item">
                                    <i class="fa-solid fa-phone"></i>
                                    {{ config('amsparts.phone_main') }}
                                </a>
                            @endif
                            @if (config('amsparts.phone_tollfree'))
                                <a href="tel:{{ config('amsparts.phone_tollfree') }}" class="footer-contact-item">
                                    <i class="fa-solid fa-phone-volume"></i>
                                    {{ config('amsparts.phone_tollfree') }} <span style="opacity:.6;">(Toll
                                        Free)</span>
                                </a>
                            @endif
                            @if (config('amsparts.email_general'))
                                <a href="mailto:{{ config('amsparts.email_general') }}" class="footer-contact-item">
                                    <i class="fa-solid fa-envelope"></i>
                                    {{ config('amsparts.email_general') }}
                                </a>
                            @endif
                            @if (config('amsparts.business_hours'))
                                <div class="footer-contact-item footer-contact-item--plain">
                                    <i class="fa-solid fa-clock"></i>
                                    {{ config('amsparts.business_hours') }}
                                </div>
                            @endif
                        </div>
                        {{-- Social --}}
                        <div class="footer-social">
                            @if (config('amsparts.social_facebook'))
                                <a href="{{ config('amsparts.social_facebook') }}" target="_blank" rel="noopener"
                                    aria-label="Facebook" class="social-link">
                                    <i class="fa-brands fa-facebook-f"></i>
                                </a>
                            @endif
                            @if (config('amsparts.social_instagram'))
                                <a href="{{ config('amsparts.social_instagram') }}" target="_blank" rel="noopener"
                                    aria-label="Instagram" class="social-link">
                                    <i class="fa-brands fa-instagram"></i>
                                </a>
                            @endif
                            @if (config('amsparts.social_youtube'))
                                <a href="{{ config('amsparts.social_youtube') }}" target="_blank" rel="noopener"
                                    aria-label="YouTube" class="social-link">
                                    <i class="fa-brands fa-youtube"></i>
                                </a>
                            @endif
                            @if (config('amsparts.social_linkedin'))
                                <a href="{{ config('amsparts.social_linkedin') }}" target="_blank" rel="noopener"
                                    aria-label="LinkedIn" class="social-link">
                                    <i class="fa-brands fa-linkedin-in"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Parts by Make --}}
                    <div class="footer-col">
                        <div class="footer-col-title">Parts by Make</div>
                        <ul class="footer-links">
                            @foreach ($navMakes ?? [] as $make)
                                <li><a href="{{ route('makes.show', $make->slug) }}">{{ $make->name }}</a></li>
                            @endforeach
                            <li><a href="{{ route('parts.index') }}" class="footer-link-all">Browse All Brands →</a>
                            </li>
                        </ul>
                    </div>

                    {{-- Part Categories --}}
                    <div class="footer-col">
                        <div class="footer-col-title">Part Categories</div>
                        <ul class="footer-links">
                            @foreach ($navCategories ?? [] as $cat)
                                <li><a href="{{ route('categories.show', $cat->slug) }}">{{ $cat->name }}</a></li>
                            @endforeach
                            <li><a href="{{ route('parts.index') }}" class="footer-link-all">All Categories →</a>
                            </li>
                        </ul>
                    </div>

                    {{-- Company --}}
                    <div class="footer-col">
                        <div class="footer-col-title">Company</div>
                        <ul class="footer-links">
                            <li><a href="{{ route('about') }}">About Us</a></li>
                            <li><a href="{{ route('tools.index') }}">Heavy Duty Tools</a></li>
                            <li><a href="{{ route('contact') }}">Contact</a></li>
                            <li><a href="{{ route('blog.index') }}">Blog &amp; News</a></li>
                            <li><a href="{{ route('faqs') }}">FAQs</a></li>
                            <li><a href="{{ route('careers') }}">Careers</a></li>
                            <li><a href="{{ route('warranty') }}">Warranty</a></li>
                            <li><a href="{{ route('gallery') }}">Gallery</a></li>
                            <li><a href="{{ route('shipping') }}">Shipping Policy</a></li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>

        {{-- Newsletter Bar --}}
        @if (config('amsparts.enable_newsletter', true))
            <div class="footer-newsletter">
                <div class="container">
                    <div class="newsletter-bar">
                        <div class="newsletter-bar-text">
                            <i class="fa-solid fa-envelope-open-text"></i>
                            <span>Get parts updates &amp; industry news</span>
                        </div>
                        <form class="newsletter-form" action="{{ route('newsletter.subscribe') }}" method="POST"
                            id="newsletterForm">
                            @csrf
                            <input type="email" name="email" class="newsletter-input"
                                placeholder="your@email.com" required aria-label="Email for newsletter">
                            <button type="submit" class="newsletter-btn">Subscribe</button>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        {{-- Footer Bottom --}}
        <div class="footer-bottom">
            <div class="container">
                <div class="footer-bottom-inner">
                    <p class="footer-copyright">
                        {{ config('amsparts.copyright_text', '© ' . date('Y') . ' ' . config('amsparts.company_name', 'Parts Plus Innovation Solutions') . '. All rights reserved.') }}
                    </p>
                    <nav class="footer-legal-links" aria-label="Legal">
                        <a href="{{ route('privacy') }}">Privacy Policy</a>
                        <a href="{{ route('terms') }}">Terms of Use</a>
                        @if (config('amsparts.has_prop65'))
                            <a href="{{ route('prop65') }}">Prop 65</a>
                        @endif
                    </nav>
                    @if (config('amsparts.footer_disclaimer'))
                        <p class="footer-disclaimer">{{ config('amsparts.footer_disclaimer') }}</p>
                    @endif
                </div>
            </div>
        </div>

    </footer>

    {{-- ══════════ BACK TO TOP ══════════ --}}
    <button class="back-to-top" id="backToTop" aria-label="Back to top">
        <i class="fa-solid fa-chevron-up"></i>
    </button>

    {{-- ══════════ SCRIPTS ══════════ --}}
    <script src="{{ asset('js/app.js') }}?v={{ config('app.asset_version', '1.0') }}" defer></script>
    @stack('scripts')

    {{-- ══════════ MINI CART DRAWER ══════════ --}}
    <div class="mini-cart-overlay" id="miniCartOverlay" onclick="closeMiniCart()" aria-hidden="true"></div>
    <aside class="mini-cart-drawer" id="miniCartDrawer" aria-label="Cart" aria-hidden="true">
        <div class="mini-cart-header">
            <h3 class="mini-cart-title"><i class="fa-solid fa-cart-shopping"></i> Your Cart</h3>
            <button class="mini-cart-close" onclick="closeMiniCart()" aria-label="Close cart">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="mini-cart-body" id="miniCartBody">
            <div class="mini-cart-loading"><i class="fa-solid fa-spinner fa-spin"></i> Loading…</div>
        </div>
        <div class="mini-cart-footer" id="miniCartFooter" style="display:none;">
            <div class="mini-cart-subtotal">
                Subtotal: <strong id="miniCartSubtotal">$0.00</strong>
            </div>
            <a href="{{ route('cart.index') }}" class="mini-cart-view-btn">
                View Cart &amp; Checkout
            </a>
        </div>
    </aside>

    <style>
        .mini-cart-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 299;
            background: rgba(0, 0, 0, .45);
        }

        .mini-cart-overlay.open {
            display: block;
        }

        .mini-cart-drawer {
            position: fixed;
            top: 0;
            right: -400px;
            width: 380px;
            max-width: 100vw;
            height: 100vh;
            z-index: 300;
            background: var(--white);
            box-shadow: -4px 0 24px rgba(0, 0, 0, .15);
            display: flex;
            flex-direction: column;
            transition: right .3s cubic-bezier(.4, 0, .2, 1);
        }

        .mini-cart-drawer.open {
            right: 0;
        }

        .mini-cart-header {
            padding: 18px 20px;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        .mini-cart-title {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .mini-cart-title i {
            color: var(--orange);
        }

        .mini-cart-close {
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            color: var(--gray-500);
            padding: 4px;
        }

        .mini-cart-body {
            flex: 1;
            overflow-y: auto;
            padding: 0;
        }

        .mini-cart-loading {
            padding: 40px;
            text-align: center;
            color: var(--gray-400);
            font-size: 14px;
        }

        .mini-cart-empty {
            padding: 48px 20px;
            text-align: center;
            color: var(--gray-500);
        }

        .mini-cart-empty i {
            font-size: 2.5rem;
            margin-bottom: 12px;
            display: block;
            color: var(--gray-300);
        }

        .mini-cart-item {
            display: flex;
            gap: 12px;
            padding: 14px 20px;
            border-bottom: 1px solid var(--gray-100);
            align-items: center;
        }

        .mini-cart-item-img {
            width: 56px;
            height: 56px;
            border-radius: 6px;
            overflow: hidden;
            background: var(--gray-100);
            flex-shrink: 0;
            border: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .mini-cart-item-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .mini-cart-item-img i {
            color: var(--gray-300);
        }

        .mini-cart-item-info {
            flex: 1;
            min-width: 0;
        }

        .mini-cart-item-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-900);
            text-decoration: none;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .mini-cart-item-name:hover {
            color: var(--orange);
        }

        .mini-cart-item-meta {
            font-size: 11px;
            color: var(--gray-500);
            margin-top: 3px;
        }

        .mini-cart-item-price {
            font-size: 13px;
            font-weight: 700;
            color: var(--gray-900);
            white-space: nowrap;
            text-align: right;
        }

        .mini-cart-footer {
            padding: 16px 20px;
            border-top: 1px solid var(--gray-200);
            flex-shrink: 0;
            background: var(--white);
        }

        .mini-cart-subtotal {
            font-size: 14px;
            text-align: right;
            margin-bottom: 12px;
            color: var(--gray-700);
        }

        .mini-cart-subtotal strong {
            font-size: 18px;
            color: var(--gray-900);
        }

        .mini-cart-view-btn {
            display: block;
            width: 100%;
            padding: 13px;
            background: var(--orange);
            color: #fff;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
            font-size: 15px;
            font-weight: 700;
            transition: background .2s;
        }

        .mini-cart-view-btn:hover {
            background: #d95f00;
        }
    </style>

    <script>
        // ── Mini cart open/close ──────────────────────────────────
        function openMiniCart() {
            document.getElementById('miniCartDrawer').classList.add('open');
            document.getElementById('miniCartOverlay').classList.add('open');
            document.body.style.overflow = 'hidden';
            loadMiniCart();
        }

        function closeMiniCart() {
            document.getElementById('miniCartDrawer').classList.remove('open');
            document.getElementById('miniCartOverlay').classList.remove('open');
            document.body.style.overflow = '';
        }
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeMiniCart();
        });

        // Wire cart icon to open drawer instead of navigating on desktop
        document.querySelector('.header-cart-btn')?.addEventListener('click', function(e) {
            if (window.innerWidth >= 768) {
                e.preventDefault();
                openMiniCart();
            }
        });

        // ── Load mini cart content via AJAX ───────────────────────
        async function loadMiniCart() {
            const body = document.getElementById('miniCartBody');
            const footer = document.getElementById('miniCartFooter');
            body.innerHTML =
            '<div class="mini-cart-loading"><i class="fa-solid fa-spinner fa-spin"></i> Loading…</div>';

            try {
                const r = await fetch('{{ route('cart.summary') }}', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const data = await r.json();

                if (!data.items || data.items.length === 0) {
                    body.innerHTML = `
                    <div class="mini-cart-empty">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <p>Your cart is empty.</p>
                        <a href="{{ route('tools.index') }}" onclick="closeMiniCart()" style="color:var(--orange);font-size:13px;font-weight:600;">Browse Tools →</a>
                    </div>`;
                    footer.style.display = 'none';
                    return;
                }

                body.innerHTML = data.items.map(item => `
                <div class="mini-cart-item">
                    <div class="mini-cart-item-img">
                        ${item.image_url
                            ? `<img src="${item.image_url}" alt="${item.name}" loading="lazy">`
                            : '<i class="fa-solid fa-hammer"></i>'}
                    </div>
                    <div class="mini-cart-item-info">
                        <a href="${item.url}" class="mini-cart-item-name" onclick="closeMiniCart()">${item.name}</a>
                        <div class="mini-cart-item-meta">Qty: ${item.quantity} × $${item.price}</div>
                    </div>
                    <div class="mini-cart-item-price">$${item.line_total}</div>
                </div>
            `).join('');

                document.getElementById('miniCartSubtotal').textContent = '$' + data.subtotal;
                footer.style.display = 'block';

            } catch (err) {
                body.innerHTML = '<div class="mini-cart-loading">Unable to load cart.</div>';
            }
        }
    </script>

</body>

</html>
