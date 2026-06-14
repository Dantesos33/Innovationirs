@extends('emails.layouts.master')

@section('email_title', 'Order Confirmed — ' . $order->order_number)
@section('hero_icon') ✅ @endsection
@section('hero_title', 'Order Confirmed!')
@section('hero_sub', 'Thank you for your purchase. Your order has been received and payment confirmed.')

@section('email_body')

    <p class="email-greeting">Hi {{ $order->first_name }},</p>
    <p>Great news — your order <strong>{{ $order->order_number }}</strong> has been confirmed and payment processed
        successfully. We'll get it packed and shipped to you shortly.</p>

    {{-- Order summary box --}}
    <div class="info-box info-box--orange">
        <div class="info-box-title">Order Summary</div>
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td
                    style="padding:7px 0;font-size:13px;color:#6B7280;font-weight:500;border-bottom:1px solid #FED7AA;width:40%;">
                    Order Number</td>
                <td
                    style="padding:7px 0;font-size:13px;color:#111113;font-weight:700;border-bottom:1px solid #FED7AA;text-align:right;">
                    {{ $order->order_number }}</td>
            </tr>
            <tr>
                <td style="padding:7px 0;font-size:13px;color:#6B7280;font-weight:500;border-bottom:1px solid #FED7AA;">Date
                </td>
                <td
                    style="padding:7px 0;font-size:13px;color:#111113;font-weight:600;border-bottom:1px solid #FED7AA;text-align:right;">
                    {{ $order->created_at->format('F j, Y') }}</td>
            </tr>
            <tr>
                <td style="padding:7px 0;font-size:13px;color:#6B7280;font-weight:500;border-bottom:1px solid #FED7AA;">
                    Payment</td>
                <td
                    style="padding:7px 0;font-size:13px;color:#16A34A;font-weight:700;border-bottom:1px solid #FED7AA;text-align:right;">
                    ✓ Paid</td>
            </tr>
            <tr>
                <td style="padding:7px 0;font-size:13px;color:#6B7280;font-weight:500;">Total Charged</td>
                <td style="padding:7px 0;font-size:18px;color:#111113;font-weight:800;text-align:right;">
                    ${{ number_format($order->total, 2) }}</td>
            </tr>
        </table>
    </div>

    {{-- Items --}}
    <h3 style="font-size:15px;font-weight:700;color:#111113;margin:24px 0 12px;">Items Ordered</h3>
    <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font-size:13px;">
        <thead>
            <tr style="background:#F9FAFB;">
                <th
                    style="padding:10px 12px;text-align:left;color:#6B7280;font-weight:600;border-bottom:2px solid #E5E7EB;">
                    Product</th>
                <th
                    style="padding:10px 12px;text-align:center;color:#6B7280;font-weight:600;border-bottom:2px solid #E5E7EB;">
                    Qty</th>
                <th
                    style="padding:10px 12px;text-align:right;color:#6B7280;font-weight:600;border-bottom:2px solid #E5E7EB;">
                    Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr style="border-bottom:1px solid #F3F4F6;">
                    <td style="padding:12px;color:#111113;font-weight:600;">
                        {{ $item->tool_name }}
                        @if ($item->tool_sku)
                            <br><span style="font-size:11px;color:#9CA3AF;font-weight:400;">SKU:
                                {{ $item->tool_sku }}</span>
                        @endif
                    </td>
                    <td style="padding:12px;text-align:center;color:#374151;">{{ $item->quantity }}</td>
                    <td style="padding:12px;text-align:right;color:#111113;font-weight:700;">
                        ${{ number_format($item->line_total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2"
                    style="padding:10px 12px;text-align:right;color:#6B7280;font-size:13px;border-top:1px solid #E5E7EB;">
                    Subtotal</td>
                <td style="padding:10px 12px;text-align:right;font-weight:600;border-top:1px solid #E5E7EB;">
                    ${{ number_format($order->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td colspan="2" style="padding:6px 12px;text-align:right;color:#6B7280;font-size:13px;">Shipping</td>
                <td
                    style="padding:6px 12px;text-align:right;font-weight:600;{{ $order->shipping_cost == 0 ? 'color:#16A34A;' : '' }}">
                    {{ $order->shipping_cost > 0 ? '$' . number_format($order->shipping_cost, 2) : 'Free' }}
                </td>
            </tr>
            @if ($order->tax > 0)
                <tr>
                    <td colspan="2" style="padding:6px 12px;text-align:right;color:#6B7280;font-size:13px;">Tax</td>
                    <td style="padding:6px 12px;text-align:right;font-weight:600;">${{ number_format($order->tax, 2) }}
                    </td>
                </tr>
            @endif
            <tr style="background:#FFF7ED;">
                <td colspan="2"
                    style="padding:12px;text-align:right;font-size:15px;font-weight:800;color:#111113;border-top:2px solid #FED7AA;">
                    Total</td>
                <td
                    style="padding:12px;text-align:right;font-size:18px;font-weight:800;color:#EA580C;border-top:2px solid #FED7AA;">
                    ${{ number_format($order->total, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- Shipping address --}}
    <div class="info-box"
        style="margin-top:24px;background:#F9FAFB;border-left:4px solid #E5E7EB;padding:16px 20px;border-radius:6px;">
        <div class="info-box-title"
            style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6B7280;margin-bottom:10px;">
            Shipping To</div>
        <p style="margin:0;font-size:14px;color:#111113;font-weight:600;">{{ $order->full_name }}</p>
        @if ($order->company)
            <p style="margin:2px 0 0;font-size:13px;color:#6B7280;">{{ $order->company }}</p>
        @endif
        <p style="margin:6px 0 0;font-size:13px;color:#374151;">
            {{ $order->shipping_address }}<br>
            {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}<br>
            {{ $order->shipping_country }}
        </p>
    </div>

    <p style="margin-top:24px;font-size:14px;color:#374151;line-height:1.7;">
        We'll send you a tracking number as soon as your order ships.
        If you have any questions, reply to this email or call us at
        <a href="tel:{{ config('amsparts.company.phone') }}"
            style="color:#EA580C;text-decoration:none;">{{ config('amsparts.company.phone') }}</a>.
    </p>

    <p style="font-size:14px;color:#374151;">
        Thanks for choosing <strong>{{ config('amsparts.company.name', 'AMS Parts') }}</strong>!
    </p>

@endsection
