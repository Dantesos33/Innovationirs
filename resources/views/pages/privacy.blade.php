{{-- ═══════════════════════════════════════════════════════════
     resources/views/pages/privacy.blade.php
═══════════════════════════════════════════════════════════ --}}
@extends('layouts.app')

@section('meta_title', 'Privacy Policy | ' . config('amsparts.company_name', 'Parts Plus Innovation Solutions'))
@section('meta_description',
    'Privacy policy for ' .
    config('amsparts.company_name', 'Parts Plus Innovation Solutions') .
    '. How we collect,
    use, and protect your personal information.')
@section('meta_robots', 'noindex, follow')
@section('body_class', 'page-privacy')

@section('content')
    <div class="page-hero">
        <div class="container">
            @include('partials.breadcrumb', ['crumbs' => [['label' => 'Privacy Policy', 'url' => null]]])
            <div class="page-hero-label">Legal</div>
            <h1 class="page-hero-title">Privacy Policy</h1>
            <p class="page-hero-sub">Last updated: {{ now()->format('F j, Y') }}</p>
        </div>
    </div>
    <div class="section section--warm">
        <div class="container policy-container">
            <div class="policy-prose">

                <p>{{ config('amsparts.company_name', 'Parts Plus Innovation Solutions') }} ("we", "us", or "our") is
                    committed to protecting your
                    personal information. This Privacy Policy explains how we collect, use, disclose, and safeguard your
                    information when you visit our website or use our services.</p>

                <h2>1. Information We Collect</h2>
                <p>We may collect the following types of information:</p>
                <ul>
                    <li><strong>Contact information:</strong> Name, email address, phone number, company name, and mailing
                        address when you submit a quote request or contact form.</li>
                    <li><strong>Transaction information:</strong> Details about orders and quotes you request through our
                        platform.</li>
                    <li><strong>Usage data:</strong> Information about how you interact with our website, including pages
                        visited, time spent, and referring URLs.</li>
                    <li><strong>Device and technical data:</strong> IP address, browser type, operating system, and other
                        technical identifiers.</li>
                    <li><strong>Communications:</strong> Records of emails, calls, and messages you send to us.</li>
                </ul>

                <h2>2. How We Use Your Information</h2>
                <p>We use your information to:</p>
                <ul>
                    <li>Process and respond to quote requests and inquiries</li>
                    <li>Send order confirmations, shipping updates, and customer service communications</li>
                    <li>Send newsletters and promotional emails (with your consent, and you may unsubscribe at any time)
                    </li>
                    <li>Improve our website and services through analytics</li>
                    <li>Comply with legal obligations</li>
                    <li>Prevent fraud and protect the security of our platform</li>
                </ul>

                <h2>3. Sharing Your Information</h2>
                <p>We do not sell, trade, or rent your personal information to third parties. We may share your information
                    with:</p>
                <ul>
                    <li><strong>Service providers:</strong> Trusted third parties who assist in operating our website or
                        serving you (e.g., email providers, shipping carriers, analytics platforms). These providers are
                        contractually bound to protect your data.</li>
                    <li><strong>Legal requirements:</strong> If required by law, court order, or governmental authority.
                    </li>
                    <li><strong>Business transfers:</strong> In the event of a merger, acquisition, or sale of all or a
                        portion of our assets.</li>
                </ul>

                <h2>4. Cookies & Tracking Technologies</h2>
                <p>We use cookies and similar tracking technologies to enhance your experience on our website. You can
                    control cookie settings through your browser preferences. Note that disabling cookies may affect the
                    functionality of some parts of our site.</p>

                <h2>5. Data Retention</h2>
                <p>We retain personal data for as long as necessary to fulfill the purposes outlined in this policy, unless
                    a longer retention period is required by law.</p>

                <h2>6. Your Rights</h2>
                <p>Depending on your location, you may have the right to:</p>
                <ul>
                    <li>Access the personal data we hold about you</li>
                    <li>Request correction of inaccurate data</li>
                    <li>Request deletion of your data</li>
                    <li>Opt out of marketing communications at any time</li>
                    <li>Lodge a complaint with your local data protection authority</li>
                </ul>

                <h2>7. Security</h2>
                <p>We implement appropriate technical and organizational measures to protect your personal information
                    against unauthorized access, alteration, disclosure, or destruction. However, no internet transmission
                    is 100% secure, and we cannot guarantee absolute security.</p>

                <h2>8. Third-Party Links</h2>
                <p>Our website may contain links to third-party websites. We are not responsible for the privacy practices
                    or content of those sites.</p>

                <h2>9. Changes to This Policy</h2>
                <p>We may update this Privacy Policy from time to time. We will notify you of significant changes by posting
                    a notice on our website or sending you an email. Continued use of our services after changes constitutes
                    acceptance of the updated policy.</p>

                <h2>10. Contact Us</h2>
                <p>If you have questions about this Privacy Policy or your personal data, please contact us:</p>
                <ul>
                    @if (config('amsparts.email_general'))
                        <li>Email: <a href="mailto:{{ config('amsparts.email_general') }}"
                                style="color:var(--orange);">{{ config('amsparts.email_general') }}</a></li>
                    @endif
                    @if (config('amsparts.phone_main'))
                        <li>Phone: <a href="tel:{{ config('amsparts.phone_main') }}"
                                style="color:var(--orange);">{{ config('amsparts.phone_main') }}</a></li>
                    @endif
                    @if (config('amsparts.address_street'))
                        <li>Mail: {{ config('amsparts.address_street') }}, {{ config('amsparts.address_city') }},
                            {{ config('amsparts.address_state') }} {{ config('amsparts.address_zip') }}</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
@endsection


{{-- ═══════════════════════════════════════════════════════════
     resources/views/pages/terms.blade.php
     (Separate file — split at deployment)
═══════════════════════════════════════════════════════════ --}}
{{-- @extends('layouts.app') --}}
{{-- See terms.blade.php below --}}


{{-- TERMS FILE CONTENT BELOW - DEPLOY AS: resources/views/pages/terms.blade.php --}}
