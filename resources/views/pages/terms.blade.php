{{-- resources/views/pages/terms.blade.php --}}
@extends('layouts.app')

@section('meta_title', 'Terms of Service | ' . config('amsparts.company_name', 'Parts Plus Innovation Solutions'))
@section('meta_description',
    'Terms of Service for ' .
    config('amsparts.company_name', 'Parts Plus Innovation Solutions') .
    '. Your agreement
    when using our website and purchasing parts.')
@section('meta_robots', 'noindex, follow')
@section('body_class', 'page-terms')

@section('content')
    <div class="page-hero">
        <div class="container">
            @include('partials.breadcrumb', ['crumbs' => [['label' => 'Terms of Service', 'url' => null]]])
            <div class="page-hero-label">Legal</div>
            <h1 class="page-hero-title">Terms of Service</h1>
            <p class="page-hero-sub">Last updated: {{ now()->format('F j, Y') }}</p>
        </div>
    </div>
    <div class="section section--warm">
        <div class="container policy-container">

            {{-- TOC --}}
            <div class="terms-toc" data-reveal>
                <div class="terms-toc-title">Contents</div>
                <div class="terms-toc-links">
                    @foreach (['Acceptance', 'Use of Website', 'Orders & Payment', 'Pricing', 'Shipping', 'Returns & Refunds', 'Warranty', 'Limitation of Liability', 'Intellectual Property', 'Governing Law', 'Changes', 'Contact'] as $i => $section)
                        <a href="#terms-{{ $i + 1 }}" class="terms-toc-link">{{ $i + 1 }}.
                            {{ $section }}</a>
                    @endforeach
                </div>
            </div>

            <div class="policy-prose" data-reveal>

                <p>Please read these Terms of Service ("Terms") carefully before using the
                    {{ config('amsparts.company_name', 'Parts Plus Innovation Solutions') }} website. By accessing or using
                    our site, you agree to
                    be bound by these Terms.</p>

                <h2 id="terms-1">1. Acceptance of Terms</h2>
                <p>By accessing this website and/or placing an order, you confirm that you are at least 18 years of age,
                    have read and understood these Terms, and agree to be bound by them. If you do not agree, please do not
                    use our website or services.</p>

                <h2 id="terms-2">2. Use of Website</h2>
                <p>You agree to use this website only for lawful purposes and in a manner that does not infringe the rights
                    of others. You may not:</p>
                <ul>
                    <li>Use the site to transmit spam or unsolicited communications</li>
                    <li>Attempt to gain unauthorized access to any part of the site or its systems</li>
                    <li>Scrape, crawl, or systematically extract data without written permission</li>
                    <li>Reproduce, distribute, or display site content without authorization</li>
                </ul>

                <h2 id="terms-3">3. Orders & Payment</h2>
                <p>All orders are subject to availability and acceptance by
                    {{ config('amsparts.company_name', 'Parts Plus Innovation Solutions') }}. We reserve the right to refuse
                    or cancel any order
                    for any reason, including errors in pricing or product descriptions.</p>
                <p>Payment is due at the time of order unless a credit account has been established. We accept major credit
                    cards, wire transfers, and other payment methods as indicated at checkout. All prices are in USD unless
                    otherwise stated.</p>

                <h2 id="terms-4">4. Pricing</h2>
                <p>Prices displayed on our website are subject to change without notice. Prices quoted in response to a
                    quote request are valid for the period specified in the quote (typically 30 days) unless otherwise
                    stated.</p>
                <p>We make every effort to display accurate pricing, but if an error occurs, we will notify you before
                    processing your order and give you the option to proceed at the correct price or cancel.</p>

                <h2 id="terms-5">5. Shipping</h2>
                <p>Shipping terms are outlined in our <a href="{{ route('shipping') }}"
                        style="color:var(--orange);">Shipping Policy</a>. Risk of loss and title for parts purchased pass to
                    you upon delivery to the carrier. We are not responsible for delays caused by the carrier or customs.
                </p>

                <h2 id="terms-6">6. Returns & Refunds</h2>
                <p>Parts may be returned within <strong>30 days</strong> of receipt under the following conditions:</p>
                <ul>
                    <li>The part is in its original, uninstalled condition</li>
                    <li>The original packaging is intact</li>
                    <li>A return authorization (RA) number has been obtained from our team</li>
                </ul>
                <p>Electrical components, custom-sourced parts, and parts returned after 30 days are not eligible for
                    return. Shipping costs for non-defective returns are the buyer's responsibility. Approved returns are
                    credited to the original payment method, less a restocking fee if applicable.</p>

                <h2 id="terms-7">7. Warranty</h2>
                <p>Warranty terms are outlined in our <a href="{{ route('warranty') }}"
                        style="color:var(--orange);">Warranty Policy</a>, which is incorporated into these Terms by
                    reference.</p>

                <h2 id="terms-8">8. Limitation of Liability</h2>
                <p>To the fullest extent permitted by law,
                    {{ config('amsparts.company_name', 'Parts Plus Innovation Solutions') }} shall not be
                    liable for any indirect, incidental, special, consequential, or punitive damages, including but not
                    limited to lost profits, lost data, or equipment downtime, arising out of your use of our products or
                    services.</p>
                <p>Our total liability for any claim arising from or related to your purchase shall not exceed the amount
                    you paid for the specific part giving rise to the claim.</p>

                <h2 id="terms-9">9. Intellectual Property</h2>
                <p>All content on this website — including text, images, logos, and code — is the property of
                    {{ config('amsparts.company_name', 'Parts Plus Innovation Solutions') }} or its licensors and is
                    protected by applicable
                    intellectual property laws. You may not use, reproduce, or distribute our content without express
                    written permission.</p>

                <h2 id="terms-10">10. Governing Law</h2>
                <p>These Terms shall be governed by and construed in accordance with the laws of the state of
                    {{ config('amsparts.address_state', 'the United States') }}, without regard to conflict of law
                    provisions. Any disputes shall be resolved in the courts of that jurisdiction.</p>

                <h2 id="terms-11">11. Changes to Terms</h2>
                <p>We reserve the right to modify these Terms at any time. Changes will be posted on this page with an
                    updated date. Your continued use of our services after changes constitutes acceptance of the revised
                    Terms.</p>

                <h2 id="terms-12">12. Contact</h2>
                <p>Questions about these Terms? Contact us:</p>
                <ul>
                    @if (config('amsparts.email_general'))
                        <li>Email: <a href="mailto:{{ config('amsparts.email_general') }}"
                                style="color:var(--orange);">{{ config('amsparts.email_general') }}</a></li>
                    @endif
                    @if (config('amsparts.phone_main'))
                        <li>Phone: <a href="tel:{{ config('amsparts.phone_main') }}"
                                style="color:var(--orange);">{{ config('amsparts.phone_main') }}</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .terms-toc {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            padding: 20px 24px;
            margin-bottom: 28px;
        }

        .terms-toc-title {
            font-family: var(--font-display);
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--gray-500);
            margin-bottom: 12px;
        }

        .terms-toc-links {
            display: flex;
            flex-wrap: wrap;
            gap: 6px 16px;
        }

        .terms-toc-link {
            font-size: 13px;
            color: var(--orange);
            font-weight: 500;
        }

        .terms-toc-link:hover {
            text-decoration: underline;
        }
    </style>
@endpush
