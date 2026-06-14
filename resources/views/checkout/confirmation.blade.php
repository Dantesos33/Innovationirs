{{-- resources/views/checkout/confirmation.blade.php --}}
@extends('layouts.app')

@section('meta_title', 'Order Confirmed — ' . $order->order_number . ' | ' . config('amsparts.company_name', 'Parts Plus Innovation Solutions'))
@section('meta_robots', 'noindex, nofollow')
@section('body_class', 'page-order-confirmation')

@push('styles')
    <style>
        .conf-hero {
            background: linear-gradient(135deg, #064e3b 0%, #065f46 60%, #047857 100%);
            padding: 52px 0 44px;
            text-align: center;
        }

        .conf-success-ring {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .15);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 32px;
            animation: pop .5s cubic-bezier(.34, 1.56, .64, 1);
        }

        @keyframes pop {
            from {
                transform: scale(.5);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .conf-hero-title {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 2.4rem;
            font-weight: 800;
            color: #fff;
            margin: 0 0 10px;
        }

        .conf-hero-sub {
            color: rgba(255, 255, 255, .8);
            font-size: 16px;
            margin-bottom: 20px;
        }

        .conf-order-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, .15);
            border: 1px solid rgba(255, 255, 255, .25);
            color: #fff;
            padding: 8px 20px;
            border-radius: 30px;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: .02em;
        }

        .conf-order-badge span {
            opacity: .7;
            font-weight: 400;
            font-size: 13px;
        }

        .conf-wrap {
            padding: 48px 0 80px;
        }

        .conf-layout {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 28px;
            align-items: start;
        }

        @media (max-width: 860px) {
            .conf-layout {
                grid-template-columns: 1fr;
            }
        }

        /* ── Cards ───────────────────────────────────────────────── */
        .conf-card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .conf-card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 15px;
            font-weight: 700;
        }

        .conf-card-header i {
            color: var(--orange);
            font-size: 14px;
        }

        .conf-card-body {
            padding: 20px;
        }

        /* ── Items table ─────────────────────────────────────────── */
        .conf-items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .conf-items-table th {
            padding: 10px 12px;
            text-align: left;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: var(--gray-500);
            border-bottom: 2px solid var(--gray-200);
            background: var(--gray-50);
        }

        .conf-items-table th:last-child {
            text-align: right;
        }

        .conf-items-table td {
            padding: 14px 12px;
            border-bottom: 1px solid var(--gray-100);
            vertical-align: middle;
        }

        .conf-items-table tr:last-child td {
            border-bottom: none;
        }

        .conf-item-name {
            font-weight: 600;
            color: var(--gray-900);
        }

        .conf-item-meta {
            font-size: 11px;
            color: var(--gray-400);
            margin-top: 3px;
        }

        .conf-item-qty {
            color: var(--gray-600);
            text-align: center;
        }

        .conf-item-total {
            font-weight: 700;
            text-align: right;
        }

        /* ── Totals ──────────────────────────────────────────────── */
        .conf-totals {
            padding: 16px 20px;
        }

        .conf-totals-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-size: 14px;
            color: var(--gray-600);
        }

        .conf-totals-row.grand {
            border-top: 2px solid var(--gray-200);
            margin-top: 8px;
            padding-top: 12px;
            font-size: 20px;
            font-weight: 800;
            color: var(--gray-900);
        }

        .conf-totals-row.grand .val {
            color: var(--orange);
        }

        .conf-free {
            color: #16a34a;
            font-weight: 700;
        }

        /* ── Next steps ──────────────────────────────────────────── */
        .conf-steps {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .conf-step-item {
            display: flex;
            gap: 14px;
            align-items: flex-start;
        }

        .conf-step-num {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--orange);
            color: #fff;
            font-size: 13px;
            font-weight: 800;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .conf-step-text strong {
            display: block;
            font-size: 14px;
            font-weight: 700;
            color: var(--gray-900);
        }

        .conf-step-text p {
            font-size: 13px;
            color: var(--gray-500);
            margin: 2px 0 0;
        }

        /* ── CTA buttons ─────────────────────────────────────────── */
        .conf-cta-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 20px;
        }

        .conf-cta-primary {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 13px;
            background: var(--orange);
            color: #fff;
            border-radius: 8px;
            text-decoration: none;
            font-size: 15px;
            font-weight: 700;
            transition: background .2s;
        }

        .conf-cta-primary:hover {
            background: #d95f00;
        }

        .conf-cta-secondary {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 13px;
            background: var(--white);
            color: var(--gray-700);
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: border-color .15s;
        }

        .conf-cta-secondary:hover {
            border-color: var(--orange);
            color: var(--orange);
        }
    </style>
@endpush

@section('content')

    {{-- Success hero --}}
    <div class="conf-hero">
        <div class="container">
            <div class="conf-success-ring">✓</div>
            <h1 class="conf-hero-title">Order Confirmed!</h1>
            <p class="conf-hero-sub">
                Thank you, {{ $order->first_name }}! Your payment was processed and your order is confirmed.
            </p>
            <div class="conf-order-badge">
                <span>Order</span> {{ $order->order_number }}
            </div>
        </div>
    </div>

    <div class="conf-wrap">
        <div class="container">
            <div class="conf-layout">

                {{-- LEFT --}}
                <div>

                    {{-- Items --}}
                    <div class="conf-card">
                        <div class="conf-card-header">
                            <i class="fa-solid fa-hammer"></i>
                            Items Ordered
                        </div>
                        <table class="conf-items-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th style="text-align:center;">Qty</th>
                                    <th style="text-align:right;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="conf-item-name">{{ $item->tool_name }}</div>
                                            <div class="conf-item-meta">
                                                @if ($item->tool_sku)
                                                    SKU: {{ $item->tool_sku }}
                                                @endif
                                                @if ($item->tool_part_number)
                                                    &nbsp;· Part #: {{ $item->tool_part_number }}
                                                @endif
                                                &nbsp;· ${{ number_format($item->unit_price, 2) }} each
                                            </div>
                                        </td>
                                        <td class="conf-item-qty">× {{ $item->quantity }}</td>
                                        <td class="conf-item-total">${{ number_format($item->line_total, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="conf-totals">
                            <div class="conf-totals-row">
                                <span>Subtotal</span>
                                <span>${{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div class="conf-totals-row">
                                <span>Shipping</span>
                                <span class="{{ $order->shipping_cost == 0 ? 'conf-free' : '' }}">
                                    {{ $order->shipping_cost > 0 ? '$' . number_format($order->shipping_cost, 2) : 'Free' }}
                                </span>
                            </div>
                            @if ($order->tax > 0)
                                <div class="conf-totals-row">
                                    <span>Tax</span>
                                    <span>${{ number_format($order->tax, 2) }}</span>
                                </div>
                            @endif
                            <div class="conf-totals-row grand">
                                <span>Total Paid</span>
                                <span class="val">${{ number_format($order->total, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Shipping address --}}
                    <div class="conf-card">
                        <div class="conf-card-header">
                            <i class="fa-solid fa-location-dot"></i> Shipping Address
                        </div>
                        <div class="conf-card-body">
                            <p style="margin:0;font-size:14px;line-height:1.8;color:var(--gray-700);">
                                <strong>{{ $order->full_name }}</strong><br>
                                @if ($order->company)
                                    {{ $order->company }}<br>
                                @endif
                                {{ $order->shipping_address }}<br>
                                {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}<br>
                                {{ $order->shipping_country }}
                            </p>
                        </div>
                    </div>

                    {{-- What's next --}}
                    <div class="conf-card">
                        <div class="conf-card-header">
                            <i class="fa-solid fa-list-check"></i> What Happens Next
                        </div>
                        <div class="conf-card-body">
                            <div class="conf-steps">
                                <div class="conf-step-item">
                                    <div class="conf-step-num">1</div>
                                    <div class="conf-step-text">
                                        <strong>Confirmation Email Sent</strong>
                                        <p>A copy of this order has been sent to <strong>{{ $order->email }}</strong>.</p>
                                    </div>
                                </div>
                                <div class="conf-step-item">
                                    <div class="conf-step-num">2</div>
                                    <div class="conf-step-text">
                                        <strong>Order Processing</strong>
                                        <p>Our team will pick, pack, and prepare your order within 1–2 business days.</p>
                                    </div>
                                </div>
                                <div class="conf-step-item">
                                    <div class="conf-step-num">3</div>
                                    <div class="conf-step-text">
                                        <strong>Tracking Email</strong>
                                        <p>We'll email you a tracking number as soon as your order ships.</p>
                                    </div>
                                </div>
                                <div class="conf-step-item">
                                    <div class="conf-step-num">4</div>
                                    <div class="conf-step-text">
                                        <strong>Delivery</strong>
                                        <p>Most orders arrive within 3–7 business days depending on your location.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- RIGHT --}}
                <div>

                    {{-- Order details card --}}
                    <div class="conf-card" style="margin-bottom:20px;">
                        <div class="conf-card-header">
                            <i class="fa-solid fa-receipt"></i> Order Details
                        </div>
                        <div class="conf-card-body" style="display:flex;flex-direction:column;gap:10px;">
                            <div style="display:flex;justify-content:space-between;font-size:13px;">
                                <span style="color:var(--gray-500);">Order Number</span>
                                <strong style="font-family:monospace;">{{ $order->order_number }}</strong>
                            </div>
                            <div style="display:flex;justify-content:space-between;font-size:13px;">
                                <span style="color:var(--gray-500);">Date</span>
                                <span>{{ $order->created_at->format('M j, Y') }}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;font-size:13px;">
                                <span style="color:var(--gray-500);">Payment</span>
                                <span style="color:#16a34a;font-weight:700;"><i class="fa-solid fa-check-circle"></i>
                                    Paid</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;font-size:13px;">
                                <span style="color:var(--gray-500);">Email</span>
                                <span style="font-size:12px;">{{ $order->email }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Support card --}}
                    <div class="conf-card" style="margin-bottom:20px;">
                        <div class="conf-card-header">
                            <i class="fa-solid fa-headset"></i> Need Help?
                        </div>
                        <div class="conf-card-body" style="font-size:14px;color:var(--gray-600);line-height:1.7;">
                            <p style="margin:0 0 10px;">Questions about your order? We're here to help.</p>
                            <p style="margin:0;">
                                <i class="fa-solid fa-phone" style="color:var(--orange);"></i>
                                <a href="tel:{{ config('amsparts.company.phone') }}"
                                    style="color:var(--orange);font-weight:700;">
                                    {{ config('amsparts.company.phone') }}
                                </a>
                            </p>
                            <p style="margin:4px 0 0;">
                                <i class="fa-solid fa-envelope" style="color:var(--orange);"></i>
                                <a href="mailto:{{ config('amsparts.company.email') }}" style="color:var(--orange);">
                                    {{ config('amsparts.company.email') }}
                                </a>
                            </p>
                        </div>
                    </div>

                    {{-- CTAs --}}
                    <div class="conf-cta-group">
                        <a href="{{ route('tools.index') }}" class="conf-cta-primary">
                            <i class="fa-solid fa-hammer"></i> Continue Shopping
                        </a>
                        <a href="{{ route('home') }}" class="conf-cta-secondary">
                            <i class="fa-solid fa-house"></i> Back to Homepage
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
