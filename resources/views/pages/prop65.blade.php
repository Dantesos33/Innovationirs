{{-- resources/views/pages/prop65.blade.php --}}
@extends('layouts.app')

@section('meta_title', 'California Proposition 65 Warning | ' . config('amsparts.company_name', 'Parts Plus Innovation
    Solutions'))
@section('meta_description', 'California Proposition 65 (Prop 65) warning notice from ' .
    config('amsparts.company_name', 'Parts Plus Innovation Solutions') . '. Important information about chemical exposure
    warnings.')
@section('meta_robots', 'noindex, follow')
@section('canonical', route('prop65'))
@section('body_class', 'page-prop65')

@section('content')

    {{-- Page Hero --}}
    <div class="page-hero">
        <div class="container">
            @include('partials.breadcrumb', [
                'crumbs' => [['label' => 'California Prop 65', 'url' => null]],
            ])
            <div class="page-hero-label">Legal Notice</div>
            <h1 class="page-hero-title">California Proposition 65</h1>
            <p class="page-hero-sub">Safe Drinking Water and Toxic Enforcement Act of 1986</p>
        </div>
    </div>

    <div class="section section--warm">
        <div class="container policy-container">
            <div class="policy-prose" data-reveal>

                {{-- Official Warning Box --}}
                <div class="prop65-warning-box">
                    <div class="prop65-warning-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="48" height="48"
                            aria-hidden="true">
                            <path fill="#f59e0b" d="M50 5L5 90h90L50 5z" />
                            <path fill="#fff" d="M46 38h8v24h-8zM46 68h8v8h-8z" />
                        </svg>
                    </div>
                    <div class="prop65-warning-content">
                        <p class="prop65-warning-headline">
                            <strong>WARNING:</strong> Cancer and Reproductive Harm
                        </p>
                        <p class="prop65-warning-body">
                            Some products sold on this website can expose you to chemicals including lead, which is known
                            to the State of California to cause cancer and birth defects or other reproductive harm.
                            For more information go to
                            <a href="https://www.p65warnings.ca.gov" target="_blank" rel="noopener noreferrer"
                                style="color:inherit;font-weight:600;text-decoration:underline;">
                                www.P65Warnings.ca.gov
                            </a>.
                        </p>
                    </div>
                </div>

                <h2>What is Proposition 65?</h2>
                <p>
                    California's Proposition 65, officially known as the Safe Drinking Water and Toxic Enforcement
                    Act of 1986, requires businesses to provide warnings to Californians about significant exposures
                    to chemicals that cause cancer, birth defects, or other reproductive harm. These chemicals can
                    be in the products you purchase, in your home or workplace, or that are released into the
                    environment.
                </p>
                <p>
                    The State of California maintains a list of chemicals known to cause cancer, birth defects,
                    or other reproductive harm. This list, which must be updated at least once a year, has grown
                    to include over 900 chemicals since it was first published in 1987.
                </p>

                <h2>Why Are We Providing This Warning?</h2>
                <p>
                    {{ config('amsparts.company_name', 'Parts Plus Innovation Solutions') }} sells heavy equipment parts
                    that may contain
                    materials subject to Proposition 65 requirements, including:
                </p>
                <ul>
                    <li><strong>Lead</strong> — found in certain metal components, solder, and coatings</li>
                    <li><strong>Diesel exhaust particulates</strong> — associated with engine components</li>
                    <li><strong>Phthalates</strong> — found in some rubber hoses, seals, and gaskets</li>
                    <li><strong>Nickel</strong> — present in some stainless steel and alloy components</li>
                    <li><strong>Carbon black</strong> — found in rubber belts, tires, and hoses</li>
                    <li><strong>Crystalline silica</strong> — present in some casting and brake components</li>
                </ul>
                <p>
                    Because we cannot always determine with certainty which individual products may trigger
                    Proposition 65 requirements, we provide this general warning for products that may be
                    purchased by California residents or shipped to California.
                </p>

                <h2>Does This Mean the Product Is Unsafe?</h2>
                <p>
                    A Proposition 65 warning does not necessarily mean a product is unsafe. California's
                    Proposition 65 warning threshold levels are set significantly below levels that scientific
                    studies have shown to cause harm. Receiving a warning simply means the product contains a
                    listed chemical at a level that California has determined warrants notification.
                </p>
                <p>
                    Heavy equipment parts are designed for industrial and commercial use. When handling any
                    parts, we recommend:
                </p>
                <ul>
                    <li>Wearing appropriate personal protective equipment (gloves, eye protection)</li>
                    <li>Washing hands thoroughly after handling parts</li>
                    <li>Working in well-ventilated areas when possible</li>
                    <li>Following all manufacturer safety guidelines</li>
                    <li>Disposing of used parts in accordance with local regulations</li>
                </ul>

                <h2>Which Products Carry a Prop 65 Warning?</h2>
                <p>
                    Products listed on our website that are subject to a Proposition 65 warning are identified
                    with a warning label on their product detail page. If you have a specific question about
                    whether a product triggers a Prop 65 warning, please
                    <a href="{{ route('contact') }}" style="color:var(--orange);">contact us</a> before purchasing.
                </p>

                <h2>More Information</h2>
                <p>For more information about Proposition 65 and the list of chemicals covered, visit:</p>
                <ul>
                    <li>
                        <a href="https://www.p65warnings.ca.gov" target="_blank" rel="noopener noreferrer"
                            style="color:var(--orange);">
                            California Office of Environmental Health Hazard Assessment (OEHHA) — P65Warnings.ca.gov
                        </a>
                    </li>
                    <li>
                        <a href="https://oehha.ca.gov/proposition-65/about-proposition-65" target="_blank"
                            rel="noopener noreferrer" style="color:var(--orange);">
                            About Proposition 65 — OEHHA
                        </a>
                    </li>
                </ul>

                <h2>Contact Us</h2>
                <p>
                    If you have questions about Proposition 65 warnings on specific products or our compliance
                    practices, please reach out to our team:
                </p>
                <ul>
                    @if (config('amsparts.email_general'))
                        <li>
                            Email:
                            <a href="mailto:{{ config('amsparts.email_general') }}" style="color:var(--orange);">
                                {{ config('amsparts.email_general') }}
                            </a>
                        </li>
                    @endif
                    @if (config('amsparts.phone_main'))
                        <li>
                            Phone:
                            <a href="tel:{{ config('amsparts.phone_main') }}" style="color:var(--orange);">
                                {{ config('amsparts.phone_main') }}
                            </a>
                        </li>
                    @endif
                    <li>
                        <a href="{{ route('contact') }}" style="color:var(--orange);">
                            Use our contact form
                        </a>
                    </li>
                </ul>

                <p style="margin-top:32px;font-size:13px;color:var(--gray-500);">
                    Last reviewed: {{ now()->format('F Y') }}
                </p>

            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .prop65-warning-box {
            display: flex;
            gap: 20px;
            align-items: flex-start;
            background: #fffbeb;
            border: 2px solid #f59e0b;
            border-radius: var(--radius-lg);
            padding: 24px 28px;
            margin-bottom: 32px;
        }

        .prop65-warning-icon {
            flex-shrink: 0;
            margin-top: 2px;
        }

        .prop65-warning-headline {
            font-size: 16px;
            font-weight: 700;
            color: #92400e;
            margin: 0 0 8px;
        }

        .prop65-warning-body {
            font-size: 14px;
            color: #78350f;
            line-height: 1.6;
            margin: 0;
        }

        @media (max-width: 600px) {
            .prop65-warning-box {
                flex-direction: column;
                gap: 14px;
            }
        }
    </style>
@endpush
