{{-- resources/views/pages/shipping.blade.php --}}
@extends('layouts.app')

@section('meta_title', 'Shipping & Delivery | ' . config('amsparts.company_name', 'Parts Plus Innovation Solutions'))
@section('meta_description', 'Shipping and delivery information for heavy equipment parts from ' .
    config('amsparts.company_name', 'Parts Plus Innovation Solutions') . '. Domestic and international shipping available.')
@section('body_class', 'page-shipping')

@section('content')

    <div class="page-hero">
        <div class="container">
            @include('partials.breadcrumb', [
                'crumbs' => [['label' => 'Shipping & Delivery', 'url' => null]],
            ])
            <div class="page-hero-label">Delivery Information</div>
            <h1 class="page-hero-title">Shipping & Delivery</h1>
            <p class="page-hero-sub">Fast, reliable delivery across North America and to 50+ countries worldwide.</p>
        </div>
    </div>

    <div class="section section--warm">
        <div class="container policy-container">

            {{-- Shipping highlights --}}
            <div class="shipping-highlights" data-reveal>
                @foreach ([['fa-truck-fast', 'Same-Day Shipping', 'In-stock orders placed before 2pm EST ship the same business day.'], ['fa-building', 'Multiple Warehouses', 'We ship from warehouses across North America to get parts to you faster.'], ['fa-globe', 'Ships Worldwide', 'International shipping available to 50+ countries. Contact us for rates.'], ['fa-box-open', 'Secure Packaging', 'Parts are carefully packaged to arrive in perfect condition.']] as [$icon, $title, $text])
                    <div class="shipping-highlight-card">
                        <div class="sh-icon"><i class="fa-solid fa-{{ $icon }}"></i></div>
                        <div class="sh-title">{{ $title }}</div>
                        <div class="sh-text">{{ $text }}</div>
                    </div>
                @endforeach
            </div>

            <div class="policy-prose" data-reveal>

                <h2>Domestic Shipping (USA & Canada)</h2>
                <p>We offer multiple shipping options for orders within North America:</p>

                <div class="shipping-table-wrap">
                    <table class="shipping-table">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Estimated Transit</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Ground Shipping</td>
                                <td>3–7 business days</td>
                                <td>Standard option for most parts</td>
                            </tr>
                            <tr>
                                <td>2-Day Shipping</td>
                                <td>2 business days</td>
                                <td>Available for in-stock parts</td>
                            </tr>
                            <tr>
                                <td>Next Day Air</td>
                                <td>1 business day</td>
                                <td>Order before 12pm EST for same-day dispatch</td>
                            </tr>
                            <tr>
                                <td>Freight (LTL)</td>
                                <td>3–10 business days</td>
                                <td>Required for heavy or oversized parts</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <p>Shipping rates are calculated based on weight, dimensions, and destination. Exact rates are provided with
                    your quote.</p>

                <h2>International Shipping</h2>
                <p>
                    We ship to over 50 countries worldwide. International orders are shipped via DHL, FedEx International,
                    or sea freight depending on size and urgency. All applicable duties, taxes, and customs fees are the
                    responsibility of the buyer.
                </p>
                <p>For international shipping quotes, please include your country and postal code when submitting a parts
                    quote, or contact us directly.</p>

                <h2>Order Processing Times</h2>
                <p>Orders placed before <strong>2:00pm Eastern Time</strong> on business days (Monday–Friday, excluding
                    holidays) are typically processed same day. Orders placed after 2pm or on weekends are processed the
                    next business day.</p>
                <p>For urgent or emergency orders, please call us directly so we can prioritize your shipment.</p>

                <h2>Tracking Your Order</h2>
                <p>Once your order ships, you will receive an email confirmation with your tracking number. You can use this
                    to track your shipment on the carrier's website.</p>
                <p>If you have not received a tracking number within 2 business days of your order confirmation, please
                    contact us.</p>

                <h2>Lost or Damaged Shipments</h2>
                <p>If your shipment arrives damaged, please:</p>
                <ul>
                    <li>Take photos of the outer packaging and the damaged part before unpacking further</li>
                    <li>Note the damage on the carrier's delivery receipt if signing for the package</li>
                    <li>Contact us within 5 business days of receipt</li>
                </ul>
                <p>We will work with you to file a claim and arrange a replacement.</p>

                <div class="policy-cta-inline">
                    <div>
                        <strong>Shipping questions?</strong><br>
                        <span style="font-size:13px;color:var(--gray-500);">Contact us for custom freight quotes or urgent
                            shipment requests.</span>
                    </div>
                    <div style="display:flex;gap:8px;">
                        <a href="{{ route('contact') }}" class="btn btn-ghost btn-sm">Contact Us</a>
                        <a href="{{ route('quote.create') }}" class="btn btn-primary btn-sm">Get a Quote</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .shipping-highlights {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 14px;
            margin-bottom: 32px;
        }

        .shipping-highlight-card {
            background: var(--white);
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-lg);
            padding: 20px;
            text-align: center;
            transition: border-color var(--transition), box-shadow var(--transition);
        }

        .shipping-highlight-card:hover {
            border-color: var(--orange);
            box-shadow: var(--shadow);
        }

        .sh-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            margin: 0 auto 12px;
            background: var(--orange-pale);
            color: var(--orange);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .sh-title {
            font-family: var(--font-display);
            font-size: 16px;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 5px;
        }

        .sh-text {
            font-size: 12px;
            color: var(--gray-500);
            line-height: 1.5;
        }

        .shipping-table-wrap {
            overflow-x: auto;
            margin: 14px 0 20px;
            border-radius: var(--radius-lg);
            border: 1px solid var(--gray-200);
        }

        .shipping-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .shipping-table th {
            background: var(--gray-50);
            padding: 10px 14px;
            text-align: left;
            font-weight: 700;
            color: var(--ink);
            border-bottom: 1px solid var(--gray-200);
        }

        .shipping-table td {
            padding: 10px 14px;
            border-bottom: 1px solid var(--gray-100);
            color: var(--gray-700);
            vertical-align: top;
        }

        .shipping-table tr:last-child td {
            border-bottom: none;
        }

        .shipping-table tr:nth-child(even) td {
            background: var(--gray-50);
        }
    </style>
@endpush
