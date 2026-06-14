{{-- resources/views/pages/warranty.blade.php --}}
@extends('layouts.app')

@section('meta_title', 'Warranty Policy | ' . config('amsparts.company_name', 'Parts Plus Innovation Solutions'))
@section('meta_description',
    'Learn about our warranty on new, used, and rebuilt heavy equipment parts. Up to 3 years on
    selected rebuilt parts.')
@section('body_class', 'page-warranty')

@section('content')

    <div class="page-hero">
        <div class="container">
            @include('partials.breadcrumb', ['crumbs' => [['label' => 'Warranty Policy', 'url' => null]]])
            <div class="page-hero-label">Customer Protection</div>
            <h1 class="page-hero-title">Warranty Policy</h1>
            <p class="page-hero-sub">We stand behind every part we sell.</p>
        </div>
    </div>

    <div class="section section--warm">
        <div class="container policy-container">

            <div class="policy-alert">
                <i class="fa-solid fa-shield-halved"></i>
                <div>
                    <strong>We Stand Behind Our Parts.</strong>
                    Every rebuilt and remanufactured part we sell is backed by a warranty.
                    New OEM parts carry the manufacturer's warranty.
                </div>
            </div>

            {{-- Warranty grid --}}
            <div class="warranty-grid" data-reveal>
                @foreach ([['fa-star', 'New OEM Parts', '1 Year', 'new', 'New original equipment manufacturer parts carry the standard manufacturer warranty — typically 12 months or 1,000 operating hours.'], ['fa-star-half-stroke', 'New Aftermarket Parts', '1 Year', 'new', 'Quality aftermarket parts are covered by our standard 12-month warranty against manufacturing defects.'], ['fa-screwdriver-wrench', 'Rebuilt / Reman Parts', 'Up to 3yr', 'rebuilt', 'Our premium rebuilt and remanufactured parts come with our industry-leading warranty — up to 36 months depending on the part type.'], ['fa-recycle', 'Used Parts', '90 Days', 'used', 'Used parts are covered by a 90-day warranty against defects found upon installation. Wear-related failures are not covered.']] as [$icon, $type, $duration, $badge, $desc])
                    <div class="warranty-card">
                        <div class="warranty-card-header">
                            <div class="warranty-icon"><i class="fa-solid fa-{{ $icon }}"></i></div>
                            <div>
                                <div class="warranty-type">{{ $type }}</div>
                                <div class="warranty-duration">{{ $duration }} Warranty</div>
                            </div>
                            <span class="badge badge-{{ $badge }}"
                                style="margin-left:auto;">{{ ucfirst($badge) }}</span>
                        </div>
                        <p class="warranty-desc">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>

            <div class="policy-prose" data-reveal>

                <h2>What Our Warranty Covers</h2>
                <p>Our warranty covers defects in material and workmanship present at the time of sale. If a warranted part
                    fails under normal operating conditions within the warranty period, we will, at our discretion:</p>
                <ul>
                    <li>Replace the part at no charge</li>
                    <li>Issue a credit toward a replacement</li>
                    <li>Provide a refund of the original purchase price</li>
                </ul>

                <h2>Warranty Exclusions</h2>
                <p>The warranty does not cover failures resulting from:</p>
                <ul>
                    <li>Improper installation, modification, or misuse</li>
                    <li>Normal wear and tear</li>
                    <li>Damage caused by contamination, improper fluids, or lack of maintenance</li>
                    <li>Operation outside the part's rated specifications</li>
                    <li>Physical damage after delivery</li>
                    <li>Cosmetic issues that do not affect function</li>
                </ul>

                <h2>How to Make a Warranty Claim</h2>
                <p>To submit a warranty claim, contact us within the warranty period with the following information:</p>
                <ul>
                    <li>Your original order number or invoice</li>
                    <li>The part number and description</li>
                    <li>A description of the failure and when it occurred</li>
                    <li>Photos or videos of the failed part (if possible)</li>
                </ul>
                <p>Our team will review your claim and respond within 2 business days with next steps.</p>

                <h2>Return Shipping</h2>
                <p>For approved warranty claims, we will provide a prepaid return shipping label. Parts must be returned in
                    their original condition (as failed) — do not disassemble beyond what is needed to identify the issue.
                </p>

                <div class="policy-cta-inline">
                    <div>
                        <strong>Have a warranty question?</strong><br>
                        <span style="font-size:13px;color:var(--gray-500);">Contact our parts team — we'll help you through
                            the process.</span>
                    </div>
                    <a href="{{ route('contact') }}" class="btn btn-primary btn-sm">Contact Us</a>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .policy-container {
            max-width: 860px;
        }

        .policy-alert {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            background: var(--orange-pale);
            border: 1.5px solid rgba(224, 92, 26, .25);
            border-radius: var(--radius-lg);
            padding: 16px 20px;
            margin-bottom: 32px;
            font-size: 14px;
            color: var(--gray-700);
        }

        .policy-alert i {
            color: var(--orange);
            font-size: 20px;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .policy-alert strong {
            color: var(--ink);
        }

        .warranty-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 14px;
            margin-bottom: 40px;
        }

        .warranty-card {
            background: var(--white);
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-lg);
            padding: 18px 20px;
        }

        .warranty-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
        }

        .warranty-icon {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-sm);
            flex-shrink: 0;
            background: var(--orange-pale);
            color: var(--orange);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .warranty-type {
            font-size: 12px;
            color: var(--gray-500);
            font-weight: 600;
        }

        .warranty-duration {
            font-family: var(--font-display);
            font-size: 17px;
            font-weight: 800;
            color: var(--ink);
        }

        .warranty-desc {
            font-size: 13px;
            color: var(--gray-600);
            line-height: 1.6;
        }

        .policy-prose {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-xl);
            padding: 32px;
        }

        .policy-prose h2 {
            font-family: var(--font-display);
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--ink);
            margin: 1.5em 0 .5em;
        }

        .policy-prose h2:first-child {
            margin-top: 0;
        }

        .policy-prose p,
        .policy-prose ul,
        .policy-prose ol {
            font-size: 14px;
            color: var(--gray-600);
            line-height: 1.8;
            margin-bottom: 1em;
        }

        .policy-prose ul,
        .policy-prose ol {
            padding-left: 1.4em;
        }

        .policy-prose li {
            margin-bottom: .4em;
        }

        .policy-cta-inline {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 28px;
            padding: 20px;
            background: var(--gray-50);
            border-radius: var(--radius-lg);
            border: 1px solid var(--gray-200);
        }
    </style>
@endpush
