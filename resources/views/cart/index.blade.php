{{-- resources/views/cart/index.blade.php --}}
@extends('layouts.app')

@section('meta_title', 'Your Cart | ' . config('amsparts.company_name', 'AMS Parts'))
@section('meta_description', 'Review your heavy duty tools cart and proceed to secure checkout.')
@section('body_class', 'page-cart')

@include('partials.tool-card-styles')

@push('styles')
    <style>
        /* ── Cart page ──────────────────────────────────────────── */
        .cart-wrap {
            padding: 40px 0 80px;
            min-height: 60vh;
        }

        .cart-hero {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
            padding: 36px 0 28px;
        }

        .cart-hero-title {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            color: #fff;
            margin: 0 0 4px;
        }

        .cart-hero-sub {
            color: rgba(255, 255, 255, .6);
            font-size: 14px;
        }

        /* ── Layout ──────────────────────────────────────────────── */
        .cart-layout {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 28px;
            align-items: start;
            margin-top: 32px;
        }

        @media (max-width: 900px) {
            .cart-layout {
                grid-template-columns: 1fr;
            }
        }

        /* ── Cart item rows ──────────────────────────────────────── */
        .cart-items-card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            overflow: hidden;
        }

        .cart-items-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .cart-items-title {
            font-size: 15px;
            font-weight: 700;
        }

        .cart-items-count {
            font-size: 13px;
            color: var(--gray-500);
        }

        .cart-item {
            display: grid;
            grid-template-columns: 80px 1fr auto;
            gap: 16px;
            padding: 18px 20px;
            border-bottom: 1px solid var(--gray-100);
            align-items: center;
            transition: background .15s;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-item:hover {
            background: var(--gray-50);
        }

        /* Image */
        .cart-item-img {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid var(--gray-200);
            background: var(--gray-100);
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cart-item-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .cart-item-img .no-img {
            font-size: 1.6rem;
            color: var(--gray-300);
        }

        /* Info */
        .cart-item-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
            min-width: 0;
        }

        .cart-item-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-900);
            text-decoration: none;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .cart-item-name:hover {
            color: var(--orange);
        }

        .cart-item-meta {
            font-size: 11px;
            color: var(--gray-400);
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .cart-item-price {
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-700);
        }

        /* Controls */
        .cart-item-controls {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 10px;
            flex-shrink: 0;
        }

        .cart-item-line-total {
            font-size: 16px;
            font-weight: 800;
            color: var(--gray-900);
            white-space: nowrap;
        }

        .cart-qty-row {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .cart-qty-ctrl {
            display: flex;
            align-items: center;
            border: 1px solid var(--gray-300);
            border-radius: 7px;
            overflow: hidden;
        }

        .cart-qty-btn {
            width: 30px;
            height: 30px;
            background: var(--gray-50);
            border: none;
            font-size: 15px;
            cursor: pointer;
            color: var(--gray-600);
            transition: background .15s;
        }

        .cart-qty-btn:hover {
            background: var(--gray-200);
        }

        .cart-qty-val {
            width: 40px;
            height: 30px;
            border: none;
            border-left: 1px solid var(--gray-300);
            border-right: 1px solid var(--gray-300);
            text-align: center;
            font-size: 13px;
            font-weight: 700;
            color: var(--gray-900);
            -moz-appearance: textfield;
            background: var(--white);
        }

        .cart-qty-val::-webkit-outer-spin-button,
        .cart-qty-val::-webkit-inner-spin-button {
            appearance: none;
        }

        .cart-remove-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--gray-400);
            font-size: 13px;
            padding: 4px;
            transition: color .15s;
        }

        .cart-remove-btn:hover {
            color: #ef4444;
        }

        /* ── Clear cart link ─────────────────────────────────────── */
        .cart-clear-link {
            font-size: 12px;
            color: var(--gray-400);
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
            text-decoration: underline;
        }

        .cart-clear-link:hover {
            color: #ef4444;
        }

        /* ── Continue shopping ───────────────────────────────────── */
        .cart-continue {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: var(--orange);
            text-decoration: none;
            padding: 10px 0;
            font-weight: 600;
        }

        .cart-continue:hover {
            text-decoration: underline;
        }

        /* ── Order summary card ──────────────────────────────────── */
        .cart-summary-card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            overflow: hidden;
            position: sticky;
            top: 80px;
        }

        .cart-summary-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--gray-200);
        }

        .cart-summary-title {
            font-size: 15px;
            font-weight: 700;
        }

        .cart-summary-body {
            padding: 20px;
        }

        .cart-summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 7px 0;
            font-size: 14px;
            color: var(--gray-600);
        }

        .cart-summary-row.total {
            border-top: 2px solid var(--gray-200);
            margin-top: 8px;
            padding-top: 14px;
            font-size: 18px;
            font-weight: 800;
            color: var(--gray-900);
        }

        .cart-summary-row.shipping-free .val {
            color: #16a34a;
            font-weight: 700;
        }

        .cart-checkout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 15px;
            margin-top: 16px;
            background: var(--orange);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: background .2s, transform .1s;
        }

        .cart-checkout-btn:hover {
            background: #d95f00;
        }

        .cart-checkout-btn:active {
            transform: scale(.98);
        }

        .cart-security-note {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 12px;
            font-size: 12px;
            color: var(--gray-500);
        }

        .cart-security-note i {
            color: var(--orange);
        }

        .cart-shipping-note {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 10px 14px;
            margin-top: 14px;
            font-size: 12px;
            color: #15803d;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ── Upsell / related ────────────────────────────────────── */
        .cart-upsell {
            margin-top: 32px;
        }

        .cart-upsell-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 16px;
        }

        /* ── Empty cart ──────────────────────────────────────────── */
        .cart-empty {
            text-align: center;
            padding: 80px 24px;
            background: var(--white);
            border-radius: 12px;
            border: 1px solid var(--gray-200);
        }

        .cart-empty i {
            font-size: 4rem;
            color: var(--gray-300);
            margin-bottom: 20px;
            display: block;
        }

        .cart-empty h2 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .cart-empty p {
            color: var(--gray-500);
            margin-bottom: 24px;
        }

        /* ── Updating overlay ────────────────────────────────────── */
        .cart-item.updating {
            opacity: .5;
            pointer-events: none;
        }

        @media (max-width: 640px) {
            .cart-item {
                grid-template-columns: 64px 1fr;
            }

            .cart-item-controls {
                grid-column: 1 / -1;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }
        }
    </style>
@endpush

@section('content')

    {{-- Hero --}}
    <div class="cart-hero">
        <div class="container cart-hero-inner">
            @include('partials.breadcrumb', [
                'crumbs' => [
                    ['label' => 'Tools', 'url' => route('tools.index')],
                    ['label' => 'Cart', 'url' => null],
                ],
            ])
            <h1 class="cart-hero-title">Your Cart</h1>
            <p class="cart-hero-sub">
                {{ $items->count() }} {{ Str::plural('item', $items->count()) }}
                &middot; Secure checkout with Stripe
            </p>
        </div>
    </div>

    <div class="cart-wrap">
        <div class="container">

            @if (session('success'))
                <div class="alert alert-success"
                    style="margin-bottom:20px;padding:12px 16px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;color:#15803d;font-size:14px;">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif

            @if ($items->isEmpty())

                {{-- Empty cart --}}
                <div class="cart-empty">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <h2>Your cart is empty</h2>
                    <p>Add some heavy duty tools to get started.</p>
                    <a href="{{ route('tools.index') }}" class="btn btn-primary" style="min-width:200px;">
                        <i class="fa-solid fa-hammer"></i> Browse Tools
                    </a>
                </div>
            @else
                <div class="cart-layout" id="cartLayout">

                    {{-- LEFT: cart items --}}
                    <div>
                        <div class="cart-items-card" id="cartItemsCard">
                            <div class="cart-items-header">
                                <span class="cart-items-title">
                                    <i class="fa-solid fa-cart-shopping" style="color:var(--orange);"></i>
                                    Cart Items
                                </span>
                                <span class="cart-items-count" id="cartItemsCount">
                                    {{ $items->count() }} {{ Str::plural('item', $items->count()) }}
                                </span>
                            </div>

                            <div id="cartItemsList">
                                @foreach ($items as $item)
                                    <div class="cart-item" id="cart-item-{{ $item['tool_id'] }}"
                                        data-tool-id="{{ $item['tool_id'] }}">

                                        {{-- Image --}}
                                        <div class="cart-item-img">
                                            @if ($item['image_url'])
                                                <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}"
                                                    loading="lazy">
                                            @else
                                                <i class="fa-solid fa-hammer no-img"></i>
                                            @endif
                                        </div>

                                        {{-- Info --}}
                                        <div class="cart-item-info">
                                            <a href="{{ $item['url'] }}" class="cart-item-name">
                                                {{ $item['name'] }}
                                            </a>
                                            <div class="cart-item-meta">
                                                @if ($item['sku'])
                                                    <span>SKU: {{ $item['sku'] }}</span>
                                                @endif
                                                @if ($item['part_number'])
                                                    <span>Part #: {{ $item['part_number'] }}</span>
                                                @endif
                                            </div>
                                            <div class="cart-item-price">
                                                ${{ number_format($item['price'], 2) }} each
                                            </div>
                                        </div>

                                        {{-- Controls --}}
                                        <div class="cart-item-controls">
                                            <span class="cart-item-line-total" id="line-total-{{ $item['tool_id'] }}">
                                                ${{ number_format($item['line_total'], 2) }}
                                            </span>
                                            <div class="cart-qty-row">
                                                <div class="cart-qty-ctrl">
                                                    <button type="button" class="cart-qty-btn"
                                                        onclick="updateQty({{ $item['tool_id'] }}, {{ $item['price'] }}, -1)">−</button>
                                                    <input type="number" class="cart-qty-val"
                                                        id="qty-{{ $item['tool_id'] }}" value="{{ $item['quantity'] }}"
                                                        min="1" max="{{ $item['max_qty'] }}"
                                                        onchange="setQty({{ $item['tool_id'] }}, {{ $item['price'] }}, this.value)">
                                                    <button type="button" class="cart-qty-btn"
                                                        onclick="updateQty({{ $item['tool_id'] }}, {{ $item['price'] }}, 1)">+</button>
                                                </div>
                                                <button type="button" class="cart-remove-btn" title="Remove"
                                                    onclick="removeItem({{ $item['tool_id'] }})">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div
                            style="display:flex;align-items:center;justify-content:space-between;margin-top:14px;flex-wrap:wrap;gap:10px;">
                            <a href="{{ route('tools.index') }}" class="cart-continue">
                                <i class="fa-solid fa-arrow-left" style="font-size:11px;"></i> Continue Shopping
                            </a>
                            <form method="POST" action="{{ route('cart.clear') }}"
                                onsubmit="return confirm('Clear your entire cart?')">
                                @csrf
                                <button type="submit" class="cart-clear-link">Clear cart</button>
                            </form>
                        </div>

                        {{-- Upsell: related tools --}}
                        @if (isset($relatedTools) && $relatedTools->isNotEmpty())
                            <div class="cart-upsell">
                                <h3 class="cart-upsell-title">You might also need</h3>
                                <div class="tools-grid" style="grid-template-columns:repeat(auto-fill,minmax(190px,1fr));">
                                    @foreach ($relatedTools as $i => $tool)
                                        @include('partials.tool-card', [
                                            'tool' => $tool,
                                            'delay' => $i * 60,
                                        ])
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- RIGHT: order summary --}}
                    <div>
                        <div class="cart-summary-card">
                            <div class="cart-summary-header">
                                <div class="cart-summary-title">Order Summary</div>
                            </div>
                            <div class="cart-summary-body">

                                <div class="cart-summary-row">
                                    <span>Subtotal (<span id="sumItemCount">{{ $items->sum('quantity') }}</span>
                                        items)</span>
                                    <span class="val" id="sumSubtotal">${{ number_format($subtotal, 2) }}</span>
                                </div>

                                <div class="cart-summary-row {{ $shipping == 0 ? 'shipping-free' : '' }}"
                                    id="sumShippingRow">
                                    <span>Shipping</span>
                                    <span class="val" id="sumShipping">
                                        {{ $shipping == 0 ? 'Free' : '$' . number_format($shipping, 2) }}
                                    </span>
                                </div>

                                <div class="cart-summary-row">
                                    <span>Estimated Tax (8%)</span>
                                    <span class="val" id="sumTax">${{ number_format($tax, 2) }}</span>
                                </div>

                                <div class="cart-summary-row total">
                                    <span>Total</span>
                                    <span id="sumTotal">${{ number_format($total, 2) }}</span>
                                </div>

                                <a href="{{ route('checkout.index') }}" class="cart-checkout-btn" id="checkoutBtn">
                                    <i class="fa-solid fa-lock"></i>
                                    Proceed to Checkout
                                </a>

                                <div class="cart-security-note">
                                    <i class="fa-brands fa-stripe"></i>
                                    Secured by Stripe
                                </div>

                                @if ($shipping > 0)
                                    @php $remaining = 200 - $subtotal; @endphp
                                    @if ($remaining > 0)
                                        <div class="cart-shipping-note">
                                            <i class="fa-solid fa-truck-fast"></i>
                                            Add ${{ number_format($remaining, 2) }} more for <strong>free
                                                shipping</strong>!
                                        </div>
                                    @endif
                                @else
                                    <div class="cart-shipping-note">
                                        <i class="fa-solid fa-circle-check"></i>
                                        You qualify for <strong>free shipping</strong>!
                                    </div>
                                @endif

                                <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:10px;margin-top:16px;">
                                    <div
                                        style="font-size:11px;color:var(--gray-400);display:flex;align-items:center;gap:5px;">
                                        <i class="fa-brands fa-cc-visa" style="font-size:18px;"></i>
                                        <i class="fa-brands fa-cc-mastercard" style="font-size:18px;"></i>
                                        <i class="fa-brands fa-cc-amex" style="font-size:18px;"></i>
                                        <i class="fa-brands fa-cc-discover" style="font-size:18px;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            @endif
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;

        // ── Post helper ──────────────────────────────────────────────
        async function cartPost(url, body) {
            const r = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(body),
            });
            return r.json();
        }

        // ── Refresh totals in the summary panel ─────────────────────
        function refreshSummary(data) {
            if (!data.success) return;

            const fmt = n => '$' + parseFloat(n).toFixed(2);

            if (document.getElementById('sumSubtotal'))
                document.getElementById('sumSubtotal').textContent = fmt(data.subtotal);
            if (document.getElementById('sumTax'))
                document.getElementById('sumTax').textContent = fmt(data.tax);
            if (document.getElementById('sumTotal'))
                document.getElementById('sumTotal').textContent = fmt(data.total);

            const shipEl = document.getElementById('sumShipping');
            const shipRow = document.getElementById('sumShippingRow');
            if (shipEl) {
                shipEl.textContent = data.shipping == 0 ? 'Free' : fmt(data.shipping);
                shipRow?.classList.toggle('shipping-free', data.shipping == 0);
            }

            // cart count in header
            document.querySelectorAll('[data-cart-count]').forEach(el => {
                el.textContent = data.cart_count;
                el.style.display = data.cart_count > 0 ? 'flex' : 'none';
            });
        }

        // ── Update quantity via +/- buttons ─────────────────────────
        async function updateQty(toolId, price, delta) {
            const inp = document.getElementById('qty-' + toolId);
            if (!inp) return;
            const newQty = Math.max(1, parseInt(inp.value) + delta);
            inp.value = newQty;
            await setQty(toolId, price, newQty);
        }

        // ── Set quantity on input change ─────────────────────────────
        async function setQty(toolId, price, qty) {
            qty = parseInt(qty);
            const row = document.getElementById('cart-item-' + toolId);
            row?.classList.add('updating');

            const data = await cartPost('{{ route('cart.update') }}', {
                tool_id: toolId,
                quantity: qty
            });

            row?.classList.remove('updating');

            if (data.success) {
                // Update line total inline
                const lineEl = document.getElementById('line-total-' + toolId);
                if (lineEl) lineEl.textContent = '$' + (price * qty).toFixed(2);
                refreshSummary(data);
            }
        }

        // ── Remove item ──────────────────────────────────────────────
        async function removeItem(toolId) {
            const row = document.getElementById('cart-item-' + toolId);
            row?.classList.add('updating');

            const data = await cartPost('{{ route('cart.remove') }}', {
                tool_id: toolId
            });

            if (data.success) {
                row?.remove();
                refreshSummary(data);

                // If cart is now empty, reload to show empty state
                if (data.cart_count === 0) {
                    window.location.reload();
                }
            } else {
                row?.classList.remove('updating');
            }
        }
    </script>
@endpush
