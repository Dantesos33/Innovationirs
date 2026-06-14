@extends('layouts.admin')

@section('title', 'Tool Orders')

@section('breadcrumb')
    <span class="breadcrumb-current">Tool Orders</span>
@endsection

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Tool Orders</h1>
            <p class="page-subtitle">{{ number_format($orders->total()) }} total orders</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.tool-orders.export') }}" class="btn btn--secondary">
                <i class="fa-solid fa-download"></i> Export CSV
            </a>
        </div>
    </div>

    <div class="card">

        {{-- Filters --}}
        <form method="GET" action="{{ route('admin.tool-orders.index') }}">
            <div class="filters-bar">
                <div class="filter-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Order #, customer name, email…">
                </div>
                <select name="payment_status" class="filter-select">
                    <option value="">All Payments</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending
                    </option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Refunded
                    </option>
                </select>
                <select name="fulfillment_status" class="filter-select">
                    <option value="">All Fulfillment</option>
                    <option value="pending" {{ request('fulfillment_status') === 'pending' ? 'selected' : '' }}>Pending
                    </option>
                    <option value="processing" {{ request('fulfillment_status') === 'processing' ? 'selected' : '' }}>
                        Processing</option>
                    <option value="shipped" {{ request('fulfillment_status') === 'shipped' ? 'selected' : '' }}>Shipped
                    </option>
                    <option value="delivered" {{ request('fulfillment_status') === 'delivered' ? 'selected' : '' }}>
                        Delivered</option>
                    <option value="cancelled" {{ request('fulfillment_status') === 'cancelled' ? 'selected' : '' }}>
                        Cancelled</option>
                </select>
                <div class="filter-actions">
                    @if (request()->hasAny(['search', 'payment_status', 'fulfillment_status']))
                        <a href="{{ route('admin.tool-orders.index') }}" class="btn btn--ghost btn--sm">
                            <i class="fa-solid fa-xmark"></i> Clear
                        </a>
                    @endif
                    <button type="submit" class="btn btn--secondary btn--sm">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>
                </div>
            </div>
        </form>

        {{-- Table --}}
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Fulfillment</th>
                        <th class="col-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('admin.tool-orders.show', $order) }}"
                                    style="font-weight:500;font-family:monospace;">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td>
                                <span>{{ $order->created_at->format('M j, Y') }}</span>
                                <br><span class="table-meta">{{ $order->created_at->format('g:i A') }}</span>
                            </td>
                            <td>
                                <strong>{{ $order->full_name }}</strong>
                                <br><span class="table-meta">{{ $order->email }}</span>
                                @if ($order->company)
                                    <br><span class="table-meta">{{ $order->company }}</span>
                                @endif
                            </td>
                            <td>
                                <span>{{ $order->items->count() }} item(s)</span>
                            </td>
                            <td>
                                <span style="font-weight:600;">${{ number_format($order->total, 2) }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $order->payment_status_badge }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $order->fulfillment_status_badge }}">
                                    {{ ucfirst($order->fulfillment_status) }}
                                </span>
                            </td>
                            <td class="col-actions">
                                <div class="action-btns">
                                    <a href="{{ route('admin.tool-orders.show', $order) }}" class="btn btn--ghost btn--sm"
                                        title="View Order">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-state">
                                <i class="fa-solid fa-box-open" style="font-size:2rem;opacity:.3;"></i>
                                <p>No orders yet. They will appear here once customers complete checkout.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($orders->hasPages())
            <div class="table-footer">
                {{ $orders->links('vendor.pagination.simple-admin') }}
            </div>
        @endif

    </div>

@endsection
