@extends('layouts.app')

@section('meta_title',
    config('amsparts.company_name', 'Parts Plus Innovation Solutions') .
    ' — Heavy Equipment Parts |
    New, Used & Rebuilt')
@section('meta_description',
    'Your #1 source for new, used, and rebuilt heavy equipment parts. All major makes:
    Caterpillar, Komatsu, John Deere, Bobcat and more. Fast shipping nationwide.')
@section('body_class', 'page-home')

<style>
    /* ── Hero ─────────────────────────────────────────────── */
    .hero {
        position: relative;
        background: var(--ink);
        overflow: hidden;
        min-height: 600px;
        display: flex;
        align-items: center;
    }

    .hero-bg {
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 80% 60% at 70% 50%, rgba(224, 92, 26, .12) 0%, transparent 70%),
            linear-gradient(135deg, #0E0E10 0%, #1a1a1e 100%);
    }

    .hero-bg-pattern {
        position: absolute;
        inset: 0;
        opacity: .04;
        background-image:
            repeating-linear-gradient(0deg, transparent, transparent 40px, rgba(255, 255, 255, .5) 40px, rgba(255, 255, 255, .5) 41px),
            repeating-linear-gradient(90deg, transparent, transparent 40px, rgba(255, 255, 255, .5) 40px, rgba(255, 255, 255, .5) 41px);
    }

    .hero-accent-line {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: var(--orange);
    }

    .hero-inner {
        position: relative;
        z-index: 2;
        display: grid;
        grid-template-columns: 1fr 440px;
        gap: 52px;
        align-items: center;
        padding: 72px 0 80px;
    }

    .hero-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .12em;
        color: var(--orange);
        margin-bottom: 16px;
    }

    .hero-label::before {
        content: '';
        width: 28px;
        height: 2px;
        background: var(--orange);
    }

    .hero-title {
        font-family: var(--font-display);
        font-size: clamp(3rem, 6vw, 5rem);
        font-weight: 800;
        line-height: 1.02;
        color: var(--white);
        margin-bottom: 6px;
        letter-spacing: -.02em;
    }

    .hero-title em {
        color: var(--orange);
        font-style: normal;
        display: block;
    }

    .hero-sub {
        font-size: 16px;
        color: var(--gray-400);
        line-height: 1.7;
        margin-bottom: 28px;
        max-width: 520px;
    }

    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 40px;
    }

    /* Condition tabs */
    .condition-tabs {
        display: flex;
        gap: 4px;
        background: rgba(255, 255, 255, .06);
        border: 1px solid rgba(255, 255, 255, .1);
        border-radius: var(--radius-full);
        padding: 4px;
        width: fit-content;
        margin-bottom: 40px;
    }

    .condition-tab {
        padding: 7px 18px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        border-radius: var(--radius-full);
        color: var(--gray-400);
        transition: background var(--transition), color var(--transition);
        cursor: pointer;
        border: none;
        background: none;
    }

    .condition-tab.active,
    .condition-tab:hover {
        background: var(--orange);
        color: var(--white);
    }

    /* Hero trust row */
    .hero-trust {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .hero-trust-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: var(--gray-500);
    }

    .hero-trust-item i {
        color: var(--orange);
        font-size: 14px;
    }

    .hero-trust-item strong {
        color: var(--gray-300);
    }

    /* Quote card */
    .hero-quote-card {
        background: rgba(255, 255, 255, .04);
        border: 1px solid rgba(255, 255, 255, .1);
        border-radius: var(--radius-xl);
        padding: 28px;
        backdrop-filter: blur(8px);
    }

    .hero-quote-card-title {
        font-family: var(--font-display);
        font-size: 18px;
        font-weight: 800;
        color: var(--white);
        margin-bottom: 4px;
    }

    .hero-quote-card-sub {
        font-size: 12px;
        color: var(--gray-500);
        margin-bottom: 20px;
    }

    .hero-quote-form {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .hero-form-select {
        width: 100%;
        height: 42px;
        padding: 0 14px;
        background: rgba(255, 255, 255, .08);
        border: 1px solid rgba(255, 255, 255, .12);
        border-radius: var(--radius);
        color: var(--white);
        font-size: 13px;
        font-family: var(--font-body);
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='7'%3E%3Cpath fill='%23A1A1AA' d='M0 0l5 7 5-7z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        transition: border-color var(--transition);
    }

    .hero-form-select:focus {
        outline: none;
        border-color: var(--orange);
    }

    .hero-form-select option {
        background: var(--gray-900);
    }

    .hero-form-input {
        width: 100%;
        height: 42px;
        padding: 0 14px;
        background: rgba(255, 255, 255, .08);
        border: 1px solid rgba(255, 255, 255, .12);
        border-radius: var(--radius);
        color: var(--white);
        font-size: 13px;
        font-family: var(--font-body);
        transition: border-color var(--transition);
    }

    .hero-form-input:focus {
        outline: none;
        border-color: var(--orange);
    }

    .hero-form-input::placeholder {
        color: var(--gray-600);
    }

    .hero-form-divider {
        text-align: center;
        font-size: 11px;
        color: var(--gray-600);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .hero-form-divider::before,
    .hero-form-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: rgba(255, 255, 255, .08);
    }

    .hero-phone-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        color: var(--gray-400);
        font-size: 13px;
        font-weight: 500;
        transition: color var(--transition);
    }

    .hero-phone-link:hover {
        color: var(--white);
    }

    .hero-phone-link i {
        color: var(--orange);
    }
</style>

@section('content')

    {{-- ══════════ HERO ══════════ --}}
    <section class="hero">
        <div class="hero-bg"></div>
        <div class="hero-bg-pattern"></div>
        <div class="hero-accent-line"></div>
        <div class="container">
            <div class="hero-inner">

                {{-- Left: Copy --}}
                <div data-reveal>
                    <div class="hero-label">
                        <i class="fa-solid fa-gear"></i>
                        HEAVY EQUIPMENT & RENTAL SPECIALISTS
                    </div>

                    <h1 class="hero-title">
                        Right Part.<br>
                        <em>Right Price.</em>
                        Right Now.
                    </h1>

                    <p class="hero-sub">
                        Your trusted source for high-quality engine parts, fuel systems, and equipment rentals. We specialize in fast sourcing and global exports to keep your projects moving without delay.
                    </p>

                    {{-- Condition tabs --}}
                    <div class="condition-tabs" role="tablist" aria-label="Part condition">
                        <button class="condition-tab active" role="tab" data-href="{{ route('parts.index') }}"
                            aria-selected="true">All Parts</button>
                        <button class="condition-tab" role="tab" data-href="{{ route('parts.new') }}">New</button>
                        <button class="condition-tab" role="tab" data-href="{{ route('parts.used') }}">Used</button>
                        <button class="condition-tab" role="tab"
                            data-href="{{ route('parts.rebuilt') }}">Rebuilt</button>
                    </div>

                    <div class="hero-actions">
                        <a href="{{ route('parts.index') }}" class="btn btn-primary btn-lg">
                            <i class="fa-solid fa-magnifying-glass"></i> View Parts
                        </a>
                        <a href="{{ route('quote.create') }}" class="btn btn-outline-white btn-lg">
                            <i class="fa-solid fa-file-lines"></i> Request a Quote
                        </a>
                    </div>

                    <div class="hero-trust">
                        <div class="hero-trust-item"><i class="fa-solid fa-truck-fast"></i><span><strong>Fast
                                    Shipping</strong> Nationwide</span></div>
                        <div class="hero-trust-item"><i
                                class="fa-solid fa-shield-halved"></i><span><strong>Warranty</strong> Up to 3 Years</span>
                        </div>
                        <div class="hero-trust-item"><i class="fa-solid fa-headset"></i><span><strong>Expert</strong> Parts
                                Specialists</span></div>
                        <div class="hero-trust-item"><i class="fa-solid fa-globe"></i><span><strong>Worldwide</strong>
                                Shipping</span></div>
                    </div>
                </div>

                {{-- Right: Quick Quote Card --}}
                <div class="hero-quote-card animate-fade-up animate-delay-2">
                    <div class="hero-quote-card-title">
                        <i class="fa-solid fa-bolt" style="color:var(--orange)"></i>
                        Quick Parts Inquiry
                    </div>
                    <p class="hero-quote-card-sub">Tell us what you need — we’ll find it fast.</p>

                    <form class="hero-quote-form" action="{{ route('quote.store') }}" method="POST" id="heroQuoteForm">
                        @csrf
                        <select name="make_id" class="hero-form-select" id="heroMakeSelect">
                            <option value="">Select Make / Brand</option>
                            @foreach ($makes as $make)
                                <option value="{{ $make->id }}">{{ $make->name }}</option>
                            @endforeach
                        </select>

                        <select name="model_id" class="hero-form-select" id="heroModelSelect" disabled>
                            <option value="">Select Model (optional)</option>
                        </select>

                        <input type="text" name="part_description" class="hero-form-input"
                            placeholder="Part name or number…" required aria-label="Part description">

                        <input type="email" name="email" class="hero-form-input" placeholder="Your email address"
                            required aria-label="Email address">

                        <input type="tel" name="phone" class="hero-form-input" placeholder="Phone number" required
                            aria-label="Phone number">

                        {{-- source tracking --}}
                        <input type="hidden" name="source" value="homepage_hero">

                        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                            <i class="fa-solid fa-paper-plane"></i> Submit Quote Request
                        </button>
                    </form>

                    <div class="hero-form-divider" style="margin-top:16px;">or call us directly</div>
                    @if (config('amsparts.phone_main'))
                        <a href="tel:{{ config('amsparts.phone_main') }}" class="hero-phone-link"
                            style="margin-top:10px;display:flex;">
                            <i class="fa-solid fa-phone"></i>
                            {{ config('amsparts.phone_main') }}
                        </a>
                    @endif
                </div>

            </div>
        </div>
    </section>

    {{-- ══════════ STATS STRIP ══════════ --}}
    <div class="stats-strip">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item" data-reveal>
                    <div class="stat-value">{{ number_format($stats['total_parts']) }}+</div>
                    <div class="stat-label">Parts In Inventory</div>
                </div>
                <div class="stat-item" data-reveal data-reveal-delay="80">
                    <div class="stat-value">{{ $stats['total_makes'] }}+</div>
                    <div class="stat-label">Equipment Brands</div>
                </div>
                <div class="stat-item" data-reveal data-reveal-delay="160">
                    <div class="stat-value">{{ $stats['fleets_served'] }}</div>
                    <div class="stat-label">Fleets Served</div>
                </div>
                <div class="stat-item" data-reveal data-reveal-delay="240">
                    <div class="stat-value">{{ $stats['years_experience'] }}+</div>
                    <div class="stat-label">Years Experience</div>
                </div>
                <div class="stat-item" data-reveal data-reveal-delay="320">
                    <div class="stat-value">3yr</div>
                    <div class="stat-label">Max Warranty</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════ SHOP BY MAKE ══════════ --}}
    <section class="section section--warm">
        <div class="container">
            <div class="section-header" data-reveal>
                <span class="section-label">Browse by Brand</span>
                <h2 class="section-title">Shop by Equipment Make</h2>
                <p class="section-subtitle">Find the exact parts for your specific machinery. We source and supply high-quality components for all major industry leaders—from heavy excavators to compact skid steers.
                </p>
            </div>

            <div class="makes-grid" data-reveal>
                @foreach ($makes as $make)
                    <a href="{{ route('makes.show', $make->slug) }}" class="make-card"
                        title="{{ $make->name }} Parts">
                        @if ($make->logo)
                            <img src="{{ $make->logo->public_url }}" alt="{{ $make->name }}" loading="lazy">
                        @else
                            <span
                                style="font-family:var(--font-display);font-size:16px;font-weight:700;color:var(--gray-700);">
                                {{ $make->name }}
                            </span>
                        @endif
                        <div class="make-card-count">{{ number_format($make->parts_count) }} parts</div>
                    </a>
                @endforeach
            </div>

            <div style="text-align:center;margin-top:28px;" data-reveal>
                <a href="{{ route('makes.index') }}" class="btn btn-ghost">
                    Explore All Brands <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- ══════════ SHOP BY EQUIPMENT TYPE ══════════ --}}
    <section class="section section--gray">
        <div class="container">
            <div class="section-header" data-reveal>
                <span class="section-label">BROWSE BY MACHINE</span>
                <h2 class="section-title">Shop by Equipment Type</h2>
                <p class="section-subtitle">Easily locate the exact replacement parts you need, optimized for your specific machinery and operation.</p>
            </div>

            <div class="equipment-grid" data-reveal>
                @foreach ($equipmentTypes as $i => $type)
                    <a href="{{ route('equipment-types.show', $type->slug) }}" class="equipment-card" data-reveal
                        data-reveal-delay="{{ $i * 50 }}">
                        @if ($type->image_media)
                            <div class="equipment-card-img">
                                <img src="{{ $type->image_media->public_url }}" alt="{{ $type->name }}"
                                    loading="lazy">
                            </div>
                        @else
                            <div class="equipment-card-icon">
                                <i class="fa-solid fa-screwdriver-wrench"></i>
                            </div>
                        @endif
                        <div class="equipment-card-body">
                            <h4 class="equipment-card-name">{{ $type->name }}</h4>
                            <span class="equipment-card-count">{{ number_format($type->parts_count) }} parts</span>
                        </div>
                    </a>
                @endforeach
            </div>

            <div style="text-align:center;margin-top:28px;" data-reveal>
                <a href="{{ route('equipment-types.index') }}" class="btn btn-ghost">
                    View All Equipment Types <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- ══════════ FEATURED PARTS ══════════ --}}
    @if ($featured->count())
        <section class="section section--warm">
            <div class="container">
                <div class="section-header" data-reveal>
                    <span class="section-label">Top Sellers</span>
                    <h2 class="section-title">Featured Parts</h2>
                    <p class="section-subtitle">Popular new, used and rebuilt parts ready to ship.</p>
                </div>

                <div class="parts-grid parts-grid--4" id="featuredPartsGrid">
                    @foreach ($featured as $i => $part)
                        @include('partials.part-card', ['part' => $part, 'delay' => $i * 60])
                    @endforeach
                </div>

                <div style="text-align:center;margin-top:36px;" data-reveal>
                    <a href="{{ route('parts.index') }}" class="btn btn-primary">
                        <i class="fa-solid fa-magnifying-glass"></i> Browse All Parts
                    </a>
                </div>
            </div>
        </section>
    @endif


    {{-- ══════════ HEAVY DUTY TOOLS ══════════ --}}
    @if (isset($featuredTools) && $featuredTools->count())
        <section class="section section--gray home-tools-section">
            <div class="container">
                <div class="section-header" data-reveal>
                    <span class="section-label">
                        <i class="fa-solid fa-hammer"></i> Professional Grade
                    </span>
                    <h2 class="section-title">Heavy Duty Tools</h2>
                    <p class="section-subtitle">
                        Purpose-built tools for heavy equipment maintenance. Designed to handle the toughest jobs.
                    </p>
                </div>

                <div class="tools-grid home-tools-grid" data-reveal>
                    @foreach ($featuredTools as $i => $tool)
                        @include('partials.tool-card', ['tool' => $tool, 'delay' => $i * 70])
                    @endforeach
                </div>

                <div style="text-align:center;margin-top:36px;" data-reveal>
                    <a href="{{ route('tools.index') }}" class="btn btn-primary">
                        <i class="fa-solid fa-hammer"></i> Browse All Tools
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- ══════════ WHY CHOOSE US / ABOUT ══════════ --}}
    <section class="section section--dark">
        <div class="container">
            <div class="about-grid">
                <div data-reveal>
                    <span class="section-label">WHY INNOVATION INVESTMENTS & RENTAL SOLUTIONS</span>
                    <h2 class="section-title" style="color:var(--white);margin-top:8px;">
                        We Provide High-Quality <br>Part Options—Fast.
                    </h2>
                    <p style="color:var(--gray-400);margin:16px 0 24px;line-height:1.8;">
                        Innovation Investments & Rental Solutions is committed to being your premier parts partner. More than just a supplier—we leverage a global network of manufacturers and specialized sourcing to deliver a complete selection that keeps your operations running at peak performance.
                    </p>
                    <div class="why-list">
                        @php
    $whyItems = [
        ['fa-boxes-stacked', 'Wide Product Range', 'Thousands of heavy-duty parts, filters, and seal kits ready for prompt delivery.'],
        ['fa-shield-halved', 'Quality & Reliability', 'Trusted industry-standard components designed to meet demanding job site needs.'],
        ['fa-headset', 'Expert Sourcing', 'Our team specializes in locating hard-to-find parts that others simply can\'t find.'],
        ['fa-truck-fast', 'Global Export Services', 'Serving local and international markets, including specialized shipping to the Caribbean.'],
    ];
@endphp

@foreach ($whyItems as $item)
                            <div class="why-item" data-reveal>
                                <div class="why-icon"><i class="fa-solid fa-{{ $item[0] }}"></i></div>
                                <div>
                                    <div class="why-title">{{ $item[1] }}</div>
                                    <div class="why-text">{{ $item[2] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div style="margin-top:28px;display:flex;gap:12px;flex-wrap:wrap;">
                        <a href="{{ route('about') }}" class="btn btn-outline-white">Learn About Us</a>
                        <a href="{{ route('quote.create') }}" class="btn btn-primary">Get a Quote</a>
                    </div>
                </div>

                <div class="about-video-wrap" data-reveal data-reveal-delay="150">
                    <div class="about-video-card">
                        @if (config('amsparts.homepage_video'))
                            <video poster="{{ config('amsparts.homepage_video_thumb') }}" class="about-video"
                                id="aboutVideo" muted loop playsinline>
                                <source src="{{ config('amsparts.homepage_video') }}" type="video/mp4">
                            </video>
                            <button class="video-play-btn" id="videoPlayBtn" aria-label="Play video">
                                <i class="fa-solid fa-play"></i>
                            </button>
                        @else
                            {{-- Placeholder when no video --}}
                            <div class="about-placeholder-img">
                                <i class="fa-solid fa-gear fa-spin-pulse"
                                    style="font-size:48px;color:var(--orange);opacity:.5;"></i>
                                <p style="color:var(--gray-600);margin-top:12px;font-size:13px;">Your One Point of
                                    Contact<br>for Heavy Equipment Parts</p>
                            </div>
                        @endif
                    </div>
                    {{-- Floating badge --}}
                    <div class="about-float-badge">
                        <div class="about-float-num">{{ $stats['years_experience'] }}+</div>
                        <div class="about-float-text">Years in Business</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════ PART CATEGORIES ══════════ --}}
    @if ($categories->count())
        <section class="section section--warm">
            <div class="container">
                <div class="section-header" data-reveal>
                    <span class="section-label">Browse by Category</span>
                    <h2 class="section-title">Shop by Part Category</h2>
                    <p class="section-subtitle">From hydraulic pumps to final drives — we carry it all.</p>
                </div>

                <div class="categories-grid" data-reveal>
                    @foreach ($categories as $i => $cat)
                        <a href="{{ route('categories.show', $cat->slug) }}" class="category-card" data-reveal
                            data-reveal-delay="{{ $i * 40 }}">
                            @if ($cat->image_media)
                                <div class="category-card-img">
                                    <img src="{{ $cat->image_media->public_url }}" alt="{{ $cat->name }}"
                                        loading="lazy">
                                </div>
                            @else
                                <div class="category-card-icon">
                                    <i class="fa-solid fa-gears"></i>
                                </div>
                            @endif
                            <div class="category-card-body">
                                <h5 class="category-card-name">{{ $cat->name }}</h5>
                                <span class="category-card-count">{{ number_format($cat->parts_count) }} parts</span>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div style="text-align:center;margin-top:28px;" data-reveal>
                    <a href="{{ route('categories.index') }}" class="btn btn-ghost">
                        All Part Categories <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- ══════════ TESTIMONIALS ══════════ --}}
    @if ($testimonials->count())
        <section class="section section--gray">
            <div class="container">
                <div class="section-header" data-reveal>
                    <span class="section-label">Customer Reviews</span>
                    <h2 class="section-title">What Our Customers Say</h2>
                </div>

                <div class="testimonials-grid" data-reveal>
                    @foreach ($testimonials as $i => $t)
                        <div class="testimonial-card" data-reveal data-reveal-delay="{{ $i * 70 }}">
                            <div class="testimonial-stars">
                                @for ($s = 0; $s < ($t->rating ?? 5); $s++)
                                    <i class="fa-solid fa-star"></i>
                                @endfor
                            </div>
                            <span class="testimonial-quote">"</span>
                            <p class="testimonial-text">{{ $t->content }}</p>
                            <div class="testimonial-author">{{ $t->reviewer_name }}</div>
                            @if ($t->reviewer_title || $t->company)
                                <div class="testimonial-title">
                                    {{ collect([$t->reviewer_title, $t->company])->filter()->join(' — ') }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ══════════ RECENT BLOG POSTS ══════════ --}}
    @if ($recentPosts->count())
        <section class="section section--warm">
            <div class="container">
                <div class="section-header" data-reveal>
                    <span class="section-label">Knowledge Base</span>
                    <h2 class="section-title">From the Blog</h2>
                    <p class="section-subtitle">Parts tips, maintenance guides, and industry news.</p>
                </div>

                <div class="blog-grid" data-reveal>
                    @foreach ($recentPosts as $i => $post)
                        <article class="blog-card card" data-reveal data-reveal-delay="{{ $i * 80 }}">
                            @if ($post->featured_image)
                                <div class="blog-card-img">
                                    <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" loading="lazy">
                                </div>
                            @endif
                            <div class="blog-card-body">
                                @if ($post->category)
                                    <span class="blog-card-cat">{{ $post->category->name }}</span>
                                @endif
                                <h3 class="blog-card-title">
                                    <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                                </h3>
                                @if ($post->excerpt)
                                    <p class="blog-card-excerpt">{{ Str::limit($post->excerpt, 100) }}</p>
                                @endif
                            </div>
                            <div class="blog-card-footer">
                                <span>{{ $post->published_at?->format('M j, Y') }}</span>
                                <a href="{{ route('blog.show', $post->slug) }}" class="text-orange"
                                    style="font-weight:600;font-size:12px;">
                                    Read More <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div style="text-align:center;margin-top:28px;" data-reveal>
                    <a href="{{ route('blog.index') }}" class="btn btn-ghost">
                        View All Articles <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- ══════════ TRUST BAR ══════════ --}}
    <div class="section--gray" style="padding:28px 0;border-top:1px solid var(--gray-200);">
        <div class="container">
            <div class="trust-bar">
                <div class="trust-item"><i class="fa-solid fa-certificate"></i> OEM &amp; Aftermarket Parts</div>
                <div class="trust-item"><i class="fa-solid fa-recycle"></i> New, Used &amp; Rebuilt Options</div>
                <div class="trust-item"><i class="fa-solid fa-truck-fast"></i> Same-Day Shipping Available</div>
                <div class="trust-item"><i class="fa-solid fa-shield-halved"></i> Up to 3-Year Warranty</div>
                <div class="trust-item"><i class="fa-solid fa-globe"></i> Ships Worldwide</div>
                <div class="trust-item"><i class="fa-solid fa-headset"></i> Expert Parts Support</div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        /* ── Heavy Duty Tools (homepage) ───────────────────── */
        .home-tools-section {
            background: var(--gray-50, #f9fafb);
        }

        .home-tools-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
        }

        @media (max-width: 600px) {
            .home-tools-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }
        }

        /* ── Home-specific component styles ───────────────────── */
        .makes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 12px;
        }

        .make-card {
            position: relative;
        }

        .make-card-count {
            position: absolute;
            bottom: 6px;
            right: 8px;
            font-size: 10px;
            color: var(--gray-400);
            font-weight: 600;
        }

        .equipment-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 16px;
        }

        .equipment-card {
            display: flex;
            flex-direction: column;
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            overflow: hidden;
            cursor: pointer;
            transition: box-shadow var(--transition-md), transform var(--transition-md), border-color var(--transition-md);
            text-decoration: none;
        }

        .equipment-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-3px);
            border-color: var(--orange);
        }

        .equipment-card-img {
            aspect-ratio: 16/9;
            overflow: hidden;
            background: var(--gray-100);
        }

        .equipment-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .4s;
        }

        .equipment-card:hover .equipment-card-img img {
            transform: scale(1.05);
        }

        .equipment-card-icon {
            aspect-ratio: 16/9;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gray-100);
            font-size: 36px;
            color: var(--gray-300);
        }

        .equipment-card-body {
            padding: 14px 16px;
        }

        .equipment-card-name {
            font-family: var(--font-display);
            font-size: 16px;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 4px;
        }

        .equipment-card-count {
            font-size: 11px;
            color: var(--gray-400);
            font-weight: 600;
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 14px;
        }

        .category-card {
            display: flex;
            flex-direction: column;
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            overflow: hidden;
            transition: box-shadow var(--transition-md), transform var(--transition-md), border-color var(--transition-md);
            text-decoration: none;
        }

        .category-card:hover {
            box-shadow: var(--shadow);
            transform: translateY(-2px);
            border-color: var(--orange);
        }

        .category-card-img {
            aspect-ratio: 16/10;
            overflow: hidden;
            background: var(--gray-100);
        }

        .category-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .4s;
        }

        .category-card:hover .category-card-img img {
            transform: scale(1.05);
        }

        .category-card-icon {
            aspect-ratio: 16/10;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gray-50);
            color: var(--gray-300);
            font-size: 28px;
        }

        .category-card-body {
            padding: 12px 14px;
        }

        .category-card-name {
            font-family: var(--font-display);
            font-size: 15px;
            font-weight: 700;
            color: var(--ink);
            line-height: 1.2;
        }

        .category-card-count {
            font-size: 11px;
            color: var(--gray-400);
            margin-top: 3px;
            display: block;
        }

        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .blog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        /* About section */
        .about-grid {
            display: grid;
            grid-template-columns: 1fr 480px;
            gap: 60px;
            align-items: center;
        }

        .why-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .why-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        .why-icon {
            width: 40px;
            height: 40px;
            flex-shrink: 0;
            background: rgba(224, 92, 26, .15);
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--orange);
            font-size: 16px;
        }

        .why-title {
            font-weight: 700;
            color: var(--white);
            font-size: 14px;
            margin-bottom: 2px;
        }

        .why-text {
            font-size: 13px;
            color: var(--gray-500);
            line-height: 1.5;
        }

        .about-video-wrap {
            position: relative;
        }

        .about-video-card {
            border-radius: var(--radius-xl);
            overflow: hidden;
            background: var(--gray-900);
            aspect-ratio: 4/3;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .about-video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .about-placeholder-img {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }

        .video-play-btn {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, .3);
            cursor: pointer;
            border: none;
            transition: background var(--transition);
        }

        .video-play-btn:hover {
            background: rgba(0, 0, 0, .1);
        }

        .video-play-btn i {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: var(--orange);
            color: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            padding-left: 4px;
            box-shadow: 0 8px 32px rgba(224, 92, 26, .5);
            transition: transform var(--transition);
        }

        .video-play-btn:hover i {
            transform: scale(1.08);
        }

        .about-float-badge {
            position: absolute;
            bottom: -16px;
            left: -16px;
            background: var(--orange);
            border-radius: var(--radius-lg);
            padding: 14px 20px;
            text-align: center;
            box-shadow: var(--shadow-orange);
        }

        .about-float-num {
            font-family: var(--font-display);
            font-size: 28px;
            font-weight: 800;
            color: var(--white);
            line-height: 1;
        }

        .about-float-text {
            font-size: 11px;
            color: rgba(255, 255, 255, .8);
            text-transform: uppercase;
            letter-spacing: .07em;
            margin-top: 3px;
        }

        @media (max-width: 960px) {
            .hero-inner {
                grid-template-columns: 1fr;
            }

            .hero-quote-card {
                display: none;
            }

            .about-grid {
                grid-template-columns: 1fr;
            }

            .about-video-wrap {
                display: none;
            }
        }

        @media (max-width: 640px) {
            .makes-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .equipment-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .categories-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .testimonials-grid,
            .blog-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Condition tab navigation
        document.querySelectorAll('.condition-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.condition-tab').forEach(t => {
                    t.classList.remove('active');
                    t.setAttribute('aria-selected', 'false');
                });
                this.classList.add('active');
                this.setAttribute('aria-selected', 'true');
                if (this.dataset.href) window.location.href = this.dataset.href;
            });
        });

        // Hero model cascade
        const heroMake = document.getElementById('heroMakeSelect');
        const heroModel = document.getElementById('heroModelSelect');
        if (heroMake && heroModel) {
            heroMake.addEventListener('change', async function() {
                const makeId = this.value;
                if (!makeId) {
                    heroModel.innerHTML = '<option value="">Select Model (optional)</option>';
                    heroModel.disabled = true;
                    return;
                }
                heroModel.disabled = true;
                heroModel.innerHTML = '<option>Loading…</option>';
                try {
                    const res = await fetch(`/api/makes/${makeId}/models`, {
                        headers: {
                            Accept: 'application/json'
                        }
                    });
                    const data = await res.json();
                    heroModel.innerHTML = '<option value="">Select Model (optional)</option>' +
                        (data.models || []).map(m =>
                            `<option value="${m.id}">${m.name}${m.year_range ? ' (' + m.year_range + ')' : ''}</option>`
                        ).join('');
                    heroModel.disabled = false;
                } catch {
                    heroModel.innerHTML = '<option value="">Select Model (optional)</option>';
                    heroModel.disabled = false;
                }
            });
        }

        // Hero quote form — loading state on submit
        document.getElementById('heroQuoteForm')?.addEventListener('submit', function(e) {
            const btn = this.querySelector('[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Submitting…';
            }
        });

        // Video play button
        document.getElementById('videoPlayBtn')?.addEventListener('click', function() {
            const video = document.getElementById('aboutVideo');
            if (video.paused) {
                video.play();
                this.style.display = 'none';
            }
        });
    </script>
@endpush
