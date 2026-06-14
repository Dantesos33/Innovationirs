{{-- resources/views/pages/about.blade.php --}}
@extends('layouts.app')

@section('meta_title', 'About Us | ' . config('amsparts.company_name', 'Parts Plus Innovation Solutions'))
@section('meta_description', 'Learn about ' . config('amsparts.company_name', 'Parts Plus Innovation Solutions') . ' — your trusted heavy
    equipment parts supplier. ' . config('amsparts.years_experience', 20) . '+ years of experience serving fleets
    worldwide.')
@section('body_class', 'page-about')

@section('content')

    {{-- Hero --}}
    <div class="page-hero" style="padding-bottom:56px;">
        <div class="container">
            @include('partials.breadcrumb', ['crumbs' => [['label' => 'About Us', 'url' => null]]])
            <div class="page-hero-label">Who We Are</div>
            <h1 class="page-hero-title">
                Your One-Stop Solution for<br>Industrial Parts & Rentals
            </h1>
            <p class="page-hero-sub" style="max-width:680px;">
                {{ config('amsparts.company_name', 'Innovation Investments & Rental Solutions') }} is dedicated to being your premier partner for heavy-duty components. We bridge the gap between quality and availability, sourcing premium parts from a global network to ensure your machinery never stops.
            </p>
            <div style="display:flex;flex-wrap:wrap;gap:12px;margin-top:24px;">
                <a href="{{ route('quote.create') }}" class="btn btn-primary btn-lg">
                    <i class="fa-solid fa-file-lines"></i> Get a Free Quote
                </a>
                <a href="{{ route('contact') }}" class="btn btn-outline-white btn-lg">
                    <i class="fa-solid fa-headset"></i> Talk to a Specialist
                </a>
            </div>
        </div>
    </div>

    {{-- Stats strip --}}
    <div class="stats-strip">
        <div class="container">
            <div class="stats-grid">
                @foreach ([[config('amsparts.years_experience', '20') . '+', 'Years in Business'], [config('amsparts.fleets_served', '50,000+'), 'Fleets Served'], ['3yr', 'Max Warranty'], ['50+', 'Countries Shipped'], ['24hr', 'Avg Quote Response']] as [$val, $label])
                    <div class="stat-item" data-reveal>
                        <div class="stat-value">{{ $val }}</div>
                        <div class="stat-label">{{ $label }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Mission --}}
    <section class="section section--warm">
        <div class="container">
            <div class="about-mission-grid">
                <div data-reveal>
                    <span class="section-label">OUR MISSION</span>
                    <h2 class="section-title" style="margin-top:8px;">
                        We Source the Parts<br>Others Simply Can't.
                    </h2>
                    <div class="about-body-text">
                        <p>
                            Innovation Investments & Rental Solutions was founded on a simple reality: equipment downtime is expensive. Finding the right part quickly is often the difference between a productive day and a weeks-long delay.
                        </p>
                        <p>
                            We have built a robust network of manufacturers, trusted suppliers, and international partners. When you submit a quote request, our specialists aren't just checking a local shelf—they are searching a global supply chain to find the most efficient solution for you.
                        </p>
                        <p>
                            Whether you need high-quality engine components, specialized filters, or heavy machinery rentals, we find the options you need and deliver them with speed.
                        </p>
                    </div>
                </div>
                <div class="about-mission-values" data-reveal data-reveal-delay="120">
                    @foreach ([['fa-handshake', 'Honest &amp; Transparent', 'We provide clear options for every budget—ensuring you get the best value without hidden costs or pressure.'], ['fa-magnifying-glass', 'Unlimited Sourcing', 'If it’s not in our immediate inventory, we don’t stop. Our network allows us to locate parts most suppliers overlook.'], ['fa-shield-halved', 'Quality Guaranteed', 'We only supply industry-standard products that meet rigorous performance checks. We stand behind every part we sell.'], ['fa-bolt', 'Response Time Matters', 'In this industry, every hour counts. We aim to respond to all inquiries within a few hours, not days.']] as [$icon, $title, $text])
                        <div class="about-value-card">
                            <div class="about-value-icon">
                                <i class="fa-solid fa-{{ $icon }}"></i>
                            </div>
                            <div>
                                <div class="about-value-title">{!! $title !!}</div>
                                <div class="about-value-text">{{ $text }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- How it works --}}
    <section class="section section--gray">
        <div class="container">
            <div class="section-header" data-reveal>
                <span class="section-label">OUR PROCESS</span>
                <h2 class="section-title">How We Source Your Solutions</h2>
                <p class="section-subtitle">From your initial inquiry to the parts arriving at your site—here is how we keep you moving.</p>
            </div>
            <div class="how-grid" data-reveal>
                @foreach ([['fa-paper-plane', '01', 'Submit an Inquiry', 'Tell us what you need. Provide the part name, number, or equipment details the more info, the faster we work.'], ['fa-network-wired', '02', 'Sourcing & Verification', 'Our specialists search our global network of manufacturers and suppliers to find the exact match and verify quality.'], ['fa-list-check', '03', 'Receive Your Quote', 'We provide competitive pricing with options for single or bulk purchases, tailored to your budget and timeline.'], ['fa-truck-fast', '04', 'Fast Fulfillment', 'Once confirmed, we handle the logistics—ensuring fast order processing and reliable delivery to your doorstep.']] as [$icon, $num, $title, $text])
                    <div class="how-step" data-reveal>
                        <div class="how-step-num">{{ $num }}</div>
                        <div class="how-step-icon"><i class="fa-solid fa-{{ $icon }}"></i></div>
                        <h3 class="how-step-title">{{ $title }}</h3>
                        <p class="how-step-text">{{ $text }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Testimonials --}}
    @if ($testimonials->count())
        <section class="section section--warm">
            <div class="container">
                <div class="section-header" data-reveal>
                    <span class="section-label">CUSTOMER REVIEWS</span>
                    <h2 class="section-title">Don't Take Our Word for It</h2>
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

    {{-- CTA --}}
    <section class="section section--dark">
        <div class="container" style="text-align:center;max-width:680px;">
            <span class="section-label">READY TO GET MOVING?</span>
            <h2 class="section-title" style="color:var(--white);margin-top:8px;">
                Get a Professional Quote Today
            </h2>
            <p style="color:var(--gray-400);margin:12px 0 28px;font-size:16px;line-height:1.7;">
                Tell us what you need—whether it's heavy-duty parts or equipment rentals. Our specialists will source your options and get back to you, usually within a few hours.
            </p>
            <div style="display:flex;justify-content:center;gap:14px;flex-wrap:wrap;">
                <a href="{{ route('quote.create') }}" class="btn btn-primary btn-lg">
                    <i class="fa-solid fa-file-lines"></i> Request a Quote
                </a>
                <a href="{{ route('parts.index') }}" class="btn btn-outline-white btn-lg">
                    <i class="fa-solid fa-magnifying-glass"></i> Browse Parts
                </a>
            </div>
        </div>
    </section>

@endsection

@push('styles')
    <style>
        .about-mission-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 56px;
            align-items: start;
        }

        .about-body-text {
            color: var(--gray-600);
            font-size: 15px;
            line-height: 1.85;
        }

        .about-body-text p {
            margin-bottom: 14px;
        }

        .about-mission-values {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .about-value-card {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 16px 18px;
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            transition: border-color var(--transition), box-shadow var(--transition);
        }

        .about-value-card:hover {
            border-color: var(--orange);
            box-shadow: var(--shadow);
        }

        .about-value-icon {
            width: 42px;
            height: 42px;
            flex-shrink: 0;
            background: var(--orange-pale);
            color: var(--orange);
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
        }

        .about-value-title {
            font-weight: 700;
            font-size: 14px;
            color: var(--ink);
            margin-bottom: 3px;
        }

        .about-value-text {
            font-size: 13px;
            color: var(--gray-500);
            line-height: 1.5;
        }

        .how-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .how-step {
            text-align: center;
            padding: 28px 20px;
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-xl);
            position: relative;
            transition: box-shadow var(--transition-md), transform var(--transition-md), border-color var(--transition-md);
        }

        .how-step:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-4px);
            border-color: var(--orange);
        }

        .how-step-num {
            position: absolute;
            top: -14px;
            left: 50%;
            transform: translateX(-50%);
            font-family: var(--font-display);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .1em;
            background: var(--orange);
            color: var(--white);
            padding: 3px 12px;
            border-radius: var(--radius-full);
        }

        .how-step-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            margin: 10px auto 14px;
            background: var(--orange-pale);
            color: var(--orange);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .how-step-title {
            font-family: var(--font-display);
            font-size: 18px;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 8px;
        }

        .how-step-text {
            font-size: 13px;
            color: var(--gray-500);
            line-height: 1.6;
        }

        @media (max-width: 900px) {
            .about-mission-grid {
                grid-template-columns: 1fr;
            }

            .how-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 500px) {
            .how-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush
