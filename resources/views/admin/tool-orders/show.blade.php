@extends('layouts.admin')

@section('title', 'Order ' . $order->order_number)

@section('breadcrumb')
    <a href="{{ route('admin.tool-orders.index') }}">Tool Orders</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">{{ $order->order_number }}</span>
@endsection

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Order {{ $order->order_number }}</h1>
            <p class="page-subtitle">
                Placed {{ $order->created_at->format('F j, Y \a\t g:i A') }}
                &middot; IP: {{ $order->ip_address ?? 'N/A' }}
            </p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.tool-orders.index') }}" class="btn btn--ghost">
                <i class="fa-solid fa-arrow-left"></i> Back to Orders
            </a>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start;">

        {{-- LEFT: order details --}}
        <div style="display:flex;flex-direction:column;gap:20px;">

            {{-- Order Items --}}
            <div class="card">
                <div class="card-header"><span class="card-title">Order Items</span></div>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Unit Price</th>
                                <th>Qty</th>
                                <th style="text-align:right;">Line Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->tool_name }}</strong>
                                        @if ($item->tool_part_number)
                                            <br><span class="table-meta">Part #: {{ $item->tool_part_number }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="table-code">{{ $item->tool_sku ?? '—' }}</span>
                                    </td>
                                    <td>${{ number_format($item->unit_price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td style="text-align:right;font-weight:600;">
                                        ${{ number_format($item->line_total, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" style="text-align:right;padding-right:12px;">Subtotal</td>
                                <td style="text-align:right;">${{ number_format($order->subtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" style="text-align:right;padding-right:12px;">Shipping</td>
                                <td style="text-align:right;">
                                    {{ $order->shipping_cost > 0 ? '$' . number_format($order->shipping_cost, 2) : 'Free' }}
                                </td>
                            </tr>
                            @if ($order->tax > 0)
                                <tr>
                                    <td colspan="4" style="text-align:right;padding-right:12px;">Tax</td>
                                    <td style="text-align:right;">${{ number_format($order->tax, 2) }}</td>
                                </tr>
                            @endif
                            <tr style="font-weight:700;font-size:15px;">
                                <td colspan="4" style="text-align:right;padding-right:12px;">Total</td>
                                <td style="text-align:right;">${{ number_format($order->total, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Customer & Shipping --}}
            <div class="card">
                <div class="card-header"><span class="card-title">Customer &amp; Shipping</span></div>
                <div class="card-body">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
                        <div>
                            <p class="form-label" style="margin-bottom:6px;">Customer</p>
                            <p><strong>{{ $order->full_name }}</strong></p>
                            <p>{{ $order->email }}</p>
                            @if ($order->phone)
                                <p>{{ $order->phone }}</p>
                            @endif
                            @if ($order->company)
                                <p>{{ $order->company }}</p>
                            @endif
                        </div>
                        <div>
                            <p class="form-label" style="margin-bottom:6px;">Ship To</p>
                            <p>{{ $order->shipping_address }}</p>
                            <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</p>
                            <p>{{ $order->shipping_country }}</p>
                        </div>
                    </div>
                    @if ($order->notes)
                        <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--color-border-tertiary);">
                            <p class="form-label" style="margin-bottom:6px;">Customer Notes</p>
                            <p>{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Payment Info --}}
            <div class="card">
                <div class="card-header"><span class="card-title">Payment Information</span></div>
                <div class="card-body">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                        <div>
                            <p class="form-label">Payment Status</p>
                            <span class="badge {{ $order->payment_status_badge }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                        @if ($order->stripe_payment_intent_id)
                            <div>
                                <p class="form-label">Stripe Payment Intent</p>
                                <span class="table-code"
                                    style="font-size:12px;">{{ $order->stripe_payment_intent_id }}</span>
                            </div>
                        @endif
                        @if ($order->stripe_charge_id)
                            <div>
                                <p class="form-label">Stripe Charge ID</p>
                                <span class="table-code" style="font-size:12px;">{{ $order->stripe_charge_id }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        {{-- RIGHT: status management --}}
        <div style="display:flex;flex-direction:column;gap:20px;">

            {{-- Status Card --}}
            <div class="card">
                <div class="card-header"><span class="card-title">Order Status</span></div>
                <div class="card-body">
                    <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:16px;">
                        <div>
                            <p class="form-label" style="margin-bottom:4px;">Payment</p>
                            <span class="badge {{ $order->payment_status_badge }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                        <div>
                            <p class="form-label" style="margin-bottom:4px;">Fulfillment</p>
                            <span class="badge {{ $order->fulfillment_status_badge }}">
                                {{ ucfirst($order->fulfillment_status) }}
                            </span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.tool-orders.status', $order) }}">
                        @csrf
                        @method('PATCH')
                        <div style="display:flex;flex-direction:column;gap:14px;">

                            <div class="form-group">
                                <label class="form-label">Update Fulfillment Status</label>
                                <select name="fulfillment_status" class="form-control">
                                    @foreach (['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
                                        <option value="{{ $status }}"
                                            {{ $order->fulfillment_status === $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Tracking Number</label>
                                <input type="text" name="tracking_number" class="form-control"
                                    value="{{ $order->tracking_number }}" placeholder="e.g. 1Z999AA10123456784">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Admin Notes</label>
                                <textarea name="admin_notes" class="form-control" rows="3" placeholder="Internal notes (not shown to customer)">{{ $order->admin_notes }}</textarea>
                            </div>

                            <button type="submit" class="btn btn--primary btn--sm">
                                <i class="fa-solid fa-floppy-disk"></i> Update Status
                            </button>

                        </div>
                    </form>
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="card">
                <div class="card-header"><span class="card-title">Order Summary</span></div>
                <div class="card-body">
                    <table style="width:100%;font-size:14px;">
                        <tr>
                            <td style="padding:4px 0;color:var(--color-text-secondary);">Subtotal</td>
                            <td style="text-align:right;">${{ number_format($order->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td style="padding:4px 0;color:var(--color-text-secondary);">Shipping</td>
                            <td style="text-align:right;">
                                {{ $order->shipping_cost > 0 ? '$' . number_format($order->shipping_cost, 2) : 'Free' }}
                            </td>
                        </tr>
                        @if ($order->tax > 0)
                            <tr>
                                <td style="padding:4px 0;color:var(--color-text-secondary);">Tax</td>
                                <td style="text-align:right;">${{ number_format($order->tax, 2) }}</td>
                            </tr>
                        @endif
                        <tr style="border-top:1px solid var(--color-border-tertiary);">
                            <td style="padding-top:10px;font-weight:700;">Total</td>
                            <td style="padding-top:10px;text-align:right;font-weight:700;font-size:16px;">
                                ${{ number_format($order->total, 2) }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>

    </div>

@endsection

<style>
    @media (max-width: 1024px) {
        div[style*="grid-template-columns:1fr 340px"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
