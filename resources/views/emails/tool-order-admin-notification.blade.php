@extends('emails.layouts.master')

@section('email_title', 'New Tool Order — ' . $order->order_number)
@section('hero_icon') 🛒 @endsection
@section('hero_title', 'New Tool Order!')
@section('hero_sub', 'A customer has completed a Heavy Duty Tools purchase. Payment confirmed via Stripe.')

@section('email_body')

    <p class="email-greeting">New order received at {{ $order->created_at->format('g:i A') }} on
        {{ $order->created_at->format('F j, Y') }}.</p>

    {{-- Quick stats --}}
    <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin-bottom:24px;">
        <tr>
            <td style="padding:12px;background:#FFF7ED;border-radius:8px;text-align:center;width:33%;">
                <div style="font-size:20px;font-weight:800;color:#EA580C;">${{ number_format($order->total, 2) }}</div>
                <div style="font-size:11px;color:#9CA3AF;text-transform:uppercase;letter-spacing:.06em;margin-top:3px;">Order
                    Total</div>
            </td>
            <td width="8"></td>
            <td style="padding:12px;background:#F0FDF4;border-radius:8px;text-align:center;width:33%;">
                <div style="font-size:20px;font-weight:800;color:#16A34A;">✓ Paid</div>
                <div style="font-size:11px;color:#9CA3AF;text-transform:uppercase;letter-spacing:.06em;margin-top:3px;">
                    Payment</div>
            </td>
            <td width="8"></td>
            <td style="padding:12px;background:#F9FAFB;border-radius:8px;text-align:center;width:33%;">
                <div style="font-size:20px;font-weight:800;color:#111113;">{{ $order->items->count() }}</div>
                <div style="font-size:11px;color:#9CA3AF;text-transform:uppercase;letter-spacing:.06em;margin-top:3px;">
                    Items</div>
            </td>
        </tr>
    </table>

    {{-- Customer info --}}
    <div class="info-box info-box--orange">
        <div class="info-box-title">Customer Information</div>
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td style="padding:7px 0;font-size:13px;color:#6B7280;border-bottom:1px solid #FED7AA;width:35%;">Name</td>
                <td
                    style="padding:7px 0;font-size:13px;color:#111113;font-weight:600;border-bottom:1px solid #FED7AA;text-align:right;">
                    {{ $order->full_name }}</td>
            </tr>
            <tr>
                <td style="padding:7px 0;font-size:13px;color:#6B7280;border-bottom:1px solid #FED7AA;">Email</td>
                <td style="padding:7px 0;font-size:13px;border-bottom:1px solid #FED7AA;text-align:right;"><a
                        href="mailto:{{ $order->email }}" style="color:#EA580C;">{{ $order->email }}</a></td>
            </tr>
            @if ($order->phone)
                <tr>
                    <td style="padding:7px 0;font-size:13px;color:#6B7280;border-bottom:1px solid #FED7AA;">Phone</td>
                    <td
                        style="padding:7px 0;font-size:13px;color:#111113;border-bottom:1px solid #FED7AA;text-align:right;">
                        {{ $order->phone }}</td>
                </tr>
            @endif
            @if ($order->company)
                <tr>
                    <td style="padding:7px 0;font-size:13px;color:#6B7280;border-bottom:1px solid #FED7AA;">Company</td>
                    <td
                        style="padding:7px 0;font-size:13px;color:#111113;border-bottom:1px solid #FED7AA;text-align:right;">
                        {{ $order->company }}</td>
                </tr>
            @endif
            <tr>
                <td style="padding:7px 0;font-size:13px;color:#6B7280;">Ship To</td>
                <td style="padding:7px 0;font-size:13px;color:#111113;text-align:right;">
                    {{ $order->shipping_address }}, {{ $order->shipping_city }}, {{ $order->shipping_state }}
                    {{ $order->shipping_zip }}
                </td>
            </tr>
        </table>
    </div>

    {{-- Items ordered --}}
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
                    Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr style="border-bottom:1px solid #F3F4F6;">
                    <td style="padding:12px;color:#111113;font-weight:600;">
                        {{ $item->tool_name }}
                        @if ($item->tool_sku)
                            <span
                                style="font-size:11px;color:#9CA3AF;font-weight:400;margin-left:6px;">{{ $item->tool_sku }}</span>
                        @endif
                    </td>
                    <td style="padding:12px;text-align:center;color:#374151;">× {{ $item->quantity }}</td>
                    <td style="padding:12px;text-align:right;font-weight:700;">${{ number_format($item->line_total, 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background:#FFF7ED;">
                <td colspan="2"
                    style="padding:12px;text-align:right;font-weight:800;font-size:15px;border-top:2px solid #FED7AA;">Order
                    Total</td>
                <td
                    style="padding:12px;text-align:right;font-weight:800;font-size:18px;color:#EA580C;border-top:2px solid #FED7AA;">
                    ${{ number_format($order->total, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- CTA to admin --}}
    <div style="text-align:center;margin:32px 0 16px;">
        <a href="{{ route('admin.tool-orders.show', $order) }}"
            style="display:inline-block;background:#EA580C;color:#fff;padding:14px 28px;border-radius:8px;font-size:15px;font-weight:700;text-decoration:none;">
            View Order in Admin →
        </a>
    </div>

    <p style="font-size:13px;color:#9CA3AF;text-align:center;">
        Stripe Payment Intent: <code style="font-size:12px;">{{ $order->stripe_payment_intent_id }}</code>
    </p>

@endsection
