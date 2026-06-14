{{-- resources/views/checkout/index.blade.php --}}
@extends('layouts.app')

@section('meta_title', 'Checkout | ' . config('amsparts.company_name', 'Parts Plus Innovation Solutions'))
@section('meta_description', 'Secure checkout for heavy duty tools. Powered by Stripe.')
@section('body_class', 'page-checkout')

@push('styles')
    <style>
        /* ── Checkout layout ────────────────────────────────────── */
        .checkout-hero {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
            padding: 32px 0 24px;
        }

        .checkout-hero-title {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 1.9rem;
            font-weight: 800;
            color: #fff;
            margin: 0 0 4px;
        }

        .checkout-hero-sub {
            color: rgba(255, 255, 255, .6);
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkout-hero-sub i {
            color: #ff6b00;
        }

        .checkout-wrap {
            padding: 36px 0 80px;
        }

        .checkout-layout {
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 28px;
            align-items: start;
        }

        @media (max-width: 920px) {
            .checkout-layout {
                grid-template-columns: 1fr;
            }
        }

        /* ── Steps indicator ────────────────────────────────────── */
        .checkout-steps {
            display: flex;
            align-items: center;
            gap: 0;
            margin-bottom: 28px;
        }

        .checkout-step {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-400);
        }

        .checkout-step.active {
            color: var(--orange);
        }

        .checkout-step.done {
            color: #16a34a;
        }

        .checkout-step-num {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: var(--gray-200);
            color: var(--gray-500);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 800;
            flex-shrink: 0;
        }

        .checkout-step.active .checkout-step-num {
            background: var(--orange);
            color: #fff;
        }

        .checkout-step.done .checkout-step-num {
            background: #16a34a;
            color: #fff;
        }

        .checkout-step-sep {
            flex: 1;
            height: 2px;
            background: var(--gray-200);
            margin: 0 8px;
            max-width: 40px;
        }

        /* ── Section cards ──────────────────────────────────────── */
        .checkout-section {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .checkout-section-header {
            padding: 16px 22px;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .checkout-section-icon {
            color: var(--orange);
            font-size: 15px;
            width: 20px;
            text-align: center;
        }

        .checkout-section-title {
            font-size: 15px;
            font-weight: 700;
        }

        .checkout-section-body {
            padding: 22px;
        }

        /* ── Form grid ──────────────────────────────────────────── */
        .co-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .co-grid-full {
            grid-column: 1 / -1;
        }

        @media (max-width: 560px) {
            .co-grid {
                grid-template-columns: 1fr;
            }
        }

        .co-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: var(--gray-600);
            margin-bottom: 6px;
        }

        .co-label .req {
            color: #ef4444;
        }

        .co-input {
            width: 100%;
            padding: 11px 14px;
            font-size: 14px;
            border: 1.5px solid var(--gray-300);
            border-radius: 8px;
            background: var(--white);
            color: var(--gray-900);
            transition: border-color .15s, box-shadow .15s;
            outline: none;
        }

        .co-input:focus {
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(255, 107, 0, .12);
        }

        .co-input.error {
            border-color: #ef4444;
        }

        .co-input-row {
            display: flex;
            gap: 10px;
        }

        .co-select {
            width: 100%;
            padding: 11px 14px;
            font-size: 14px;
            border: 1.5px solid var(--gray-300);
            border-radius: 8px;
            background: var(--white);
            color: var(--gray-900);
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23888' stroke-width='1.5' fill='none'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            transition: border-color .15s;
            outline: none;
            cursor: pointer;
        }

        .co-select:focus {
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(255, 107, 0, .12);
        }

        .co-error-msg {
            font-size: 11px;
            color: #ef4444;
            margin-top: 4px;
            display: none;
        }

        /* ── Stripe element wrapper ──────────────────────────────── */
        .stripe-element-wrap {
            padding: 12px 14px;
            border: 1.5px solid var(--gray-300);
            border-radius: 8px;
            background: var(--white);
            min-height: 46px;
            transition: border-color .15s, box-shadow .15s;
        }

        .stripe-element-wrap.StripeElement--focus {
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(255, 107, 0, .12);
        }

        .stripe-element-wrap.StripeElement--invalid {
            border-color: #ef4444;
        }

        #stripe-errors {
            color: #ef4444;
            font-size: 13px;
            margin-top: 8px;
            min-height: 18px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* ── Place order button ──────────────────────────────────── */
        .place-order-btn {
            width: 100%;
            padding: 15px;
            font-size: 17px;
            font-weight: 800;
            background: var(--orange);
            color: #fff;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background .2s, transform .1s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            letter-spacing: .02em;
        }

        .place-order-btn:hover {
            background: #d95f00;
        }

        .place-order-btn:active {
            transform: scale(.98);
        }

        .place-order-btn:disabled {
            background: var(--gray-300);
            cursor: not-allowed;
            transform: none;
        }

        /* ── Checkout summary card ───────────────────────────────── */
        .checkout-summary {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            overflow: hidden;
            position: sticky;
            top: 80px;
        }

        .checkout-summary-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--gray-200);
            font-size: 15px;
            font-weight: 700;
        }

        .checkout-summary-items {
            max-height: 320px;
            overflow-y: auto;
        }

        .checkout-summary-item {
            display: flex;
            gap: 12px;
            padding: 14px 20px;
            border-bottom: 1px solid var(--gray-100);
            align-items: center;
        }

        .checkout-summary-item-img {
            width: 52px;
            height: 52px;
            border-radius: 6px;
            overflow: hidden;
            background: var(--gray-100);
            flex-shrink: 0;
            border: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .checkout-summary-item-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .checkout-summary-item-img i {
            color: var(--gray-300);
            font-size: 1.2rem;
        }

        .checkout-summary-item-info {
            flex: 1;
            min-width: 0;
        }

        .checkout-summary-item-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-900);
            line-height: 1.3;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .checkout-summary-item-meta {
            font-size: 11px;
            color: var(--gray-400);
            margin-top: 3px;
        }

        .checkout-summary-item-price {
            font-size: 14px;
            font-weight: 700;
            color: var(--gray-900);
            white-space: nowrap;
        }

        .checkout-summary-totals {
            padding: 16px 20px;
        }

        .checkout-totals-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 0;
            font-size: 14px;
            color: var(--gray-600);
        }

        .checkout-totals-row.grand {
            border-top: 2px solid var(--gray-200);
            margin-top: 8px;
            padding-top: 12px;
            font-size: 18px;
            font-weight: 800;
            color: var(--gray-900);
        }

        .checkout-totals-row.grand .val {
            color: var(--orange);
        }

        .free-ship {
            color: #16a34a;
            font-weight: 700;
        }

        /* ── Trust badges ────────────────────────────────────────── */
        .co-trust {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            padding: 16px 20px;
            border-top: 1px solid var(--gray-100);
        }

        .co-trust-item {
            font-size: 11px;
            color: var(--gray-400);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .co-trust-item i {
            color: var(--orange);
        }

        /* ── Overlay spinner ─────────────────────────────────────── */
        .co-processing-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 999;
            background: rgba(0, 0, 0, .6);
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 16px;
        }

        .co-processing-overlay.active {
            display: flex;
        }

        .co-processing-overlay p {
            color: #fff;
            font-size: 16px;
            font-weight: 600;
        }

        .co-spinner {
            width: 48px;
            height: 48px;
            border: 4px solid rgba(255, 255, 255, .3);
            border-top-color: #ff6b00;
            border-radius: 50%;
            animation: spin .8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush

@section('content')

    {{-- Hero --}}
    <div class="checkout-hero">
        <div class="container">
            @include('partials.breadcrumb', [
                'crumbs' => [
                    ['label' => 'Tools', 'url' => route('tools.index')],
                    ['label' => 'Cart', 'url' => route('cart.index')],
                    ['label' => 'Checkout', 'url' => null],
                ],
            ])
            <h1 class="checkout-hero-title">Secure Checkout</h1>
            <p class="checkout-hero-sub">
                <i class="fa-solid fa-lock"></i> SSL encrypted &nbsp;·&nbsp;
                <i class="fa-brands fa-stripe"></i> Powered by Stripe
            </p>
        </div>
    </div>

    <div class="checkout-wrap">
        <div class="container">

            {{-- Steps --}}
            <div class="checkout-steps">
                <div class="checkout-step done">
                    <div class="checkout-step-num"><i class="fa-solid fa-check" style="font-size:10px;"></i></div>
                    <span>Cart</span>
                </div>
                <div class="checkout-step-sep"></div>
                <div class="checkout-step active">
                    <div class="checkout-step-num">2</div>
                    <span>Checkout</span>
                </div>
                <div class="checkout-step-sep"></div>
                <div class="checkout-step">
                    <div class="checkout-step-num">3</div>
                    <span>Confirmation</span>
                </div>
            </div>

            <div class="checkout-layout">

                {{-- ── LEFT: Form ───────────────────────────────── --}}
                <div>

                    {{-- Contact --}}
                    <div class="checkout-section">
                        <div class="checkout-section-header">
                            <i class="fa-solid fa-user checkout-section-icon"></i>
                            <span class="checkout-section-title">Contact Information</span>
                        </div>
                        <div class="checkout-section-body">
                            <div class="co-grid">
                                <div>
                                    <label class="co-label" for="first_name">First Name <span
                                            class="req">*</span></label>
                                    <input type="text" id="first_name" class="co-input" placeholder="John"
                                        autocomplete="given-name" required>
                                    <div class="co-error-msg" id="err-first_name"></div>
                                </div>
                                <div>
                                    <label class="co-label" for="last_name">Last Name <span class="req">*</span></label>
                                    <input type="text" id="last_name" class="co-input" placeholder="Smith"
                                        autocomplete="family-name" required>
                                    <div class="co-error-msg" id="err-last_name"></div>
                                </div>
                                <div class="co-grid-full">
                                    <label class="co-label" for="email">Email Address <span
                                            class="req">*</span></label>
                                    <input type="email" id="email" class="co-input" placeholder="john@example.com"
                                        autocomplete="email" required>
                                    <div class="co-error-msg" id="err-email"></div>
                                </div>
                                <div>
                                    <label class="co-label" for="phone">Phone</label>
                                    <input type="tel" id="phone" class="co-input" placeholder="(555) 123-4567"
                                        autocomplete="tel">
                                </div>
                                <div>
                                    <label class="co-label" for="company">Company</label>
                                    <input type="text" id="company" class="co-input" placeholder="Optional"
                                        autocomplete="organization">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Shipping --}}
                    <div class="checkout-section">
                        <div class="checkout-section-header">
                            <i class="fa-solid fa-truck-fast checkout-section-icon"></i>
                            <span class="checkout-section-title">Shipping Address</span>
                        </div>
                        <div class="checkout-section-body">
                            <div class="co-grid">
                                <div class="co-grid-full">
                                    <label class="co-label" for="shipping_address">Street Address <span
                                            class="req">*</span></label>
                                    <input type="text" id="shipping_address" class="co-input" placeholder="123 Main St"
                                        autocomplete="street-address" required>
                                    <div class="co-error-msg" id="err-shipping_address"></div>
                                </div>
                                <div>
                                    <label class="co-label" for="shipping_city">City <span class="req">*</span></label>
                                    <input type="text" id="shipping_city" class="co-input" placeholder="Cincinnati"
                                        autocomplete="address-level2" required>
                                    <div class="co-error-msg" id="err-shipping_city"></div>
                                </div>
                                <div>
                                    <label class="co-label" for="shipping_state">State / Province <span
                                            class="req">*</span></label>
                                    <input type="text" id="shipping_state" class="co-input" placeholder="OH"
                                        autocomplete="address-level1" required>
                                    <div class="co-error-msg" id="err-shipping_state"></div>
                                </div>
                                <div>
                                    <label class="co-label" for="shipping_zip">ZIP / Postal Code <span
                                            class="req">*</span></label>
                                    <input type="text" id="shipping_zip" class="co-input" placeholder="45044"
                                        autocomplete="postal-code" required>
                                    <div class="co-error-msg" id="err-shipping_zip"></div>
                                </div>
                                <div>
                                    <label class="co-label" for="shipping_country">Country <span
                                            class="req">*</span></label>
                                    <select id="shipping_country" class="co-select" autocomplete="country" required>
                                        <option value="US" selected>United States</option>
                                        <option value="CA">Canada</option>
                                        <option value="MX">Mexico</option>
                                        <option value="GB">United Kingdom</option>
                                        <option value="AU">Australia</option>
                                        <option value="DE">Germany</option>
                                        <option value="FR">France</option>
                                        <option value="JP">Japan</option>
                                        <option value="BR">Brazil</option>
                                        <option value="ZA">South Africa</option>
                                        <option value="OTHER">Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Payment --}}
                    <div class="checkout-section">
                        <div class="checkout-section-header">
                            <i class="fa-solid fa-credit-card checkout-section-icon"></i>
                            <span class="checkout-section-title">Payment</span>
                            <div style="margin-left:auto;display:flex;gap:6px;align-items:center;">
                                <i class="fa-brands fa-cc-visa" style="font-size:22px;color:#1a1f71;"></i>
                                <i class="fa-brands fa-cc-mastercard" style="font-size:22px;color:#eb001b;"></i>
                                <i class="fa-brands fa-cc-amex" style="font-size:22px;color:#007bc1;"></i>
                                <i class="fa-brands fa-cc-discover" style="font-size:22px;color:#f76f20;"></i>
                            </div>
                        </div>
                        <div class="checkout-section-body">
                            <div style="margin-bottom:16px;">
                                <label class="co-label">Card Details <span class="req">*</span></label>
                                <div id="stripe-payment-element" class="stripe-element-wrap"></div>
                                <div id="stripe-errors"></div>
                            </div>

                            <button type="button" id="placeOrderBtn" class="place-order-btn" onclick="submitOrder()">
                                <i class="fa-solid fa-lock"></i>
                                Pay ${{ number_format($total, 2) }} — Place Order
                            </button>

                            <p
                                style="font-size:12px;color:var(--gray-400);text-align:center;margin-top:12px;line-height:1.6;">
                                By placing your order you agree to our
                                <a href="{{ route('terms') }}" style="color:var(--orange);">Terms of Service</a>
                                and
                                <a href="{{ route('privacy') }}" style="color:var(--orange);">Privacy Policy</a>.
                                Your payment is secured by Stripe — we never store card details.
                            </p>
                        </div>
                    </div>

                </div>

                {{-- ── RIGHT: Order summary ──────────────────────── --}}
                <div>
                    <div class="checkout-summary">
                        <div class="checkout-summary-header">
                            <i class="fa-solid fa-receipt" style="color:var(--orange);margin-right:8px;"></i>
                            Order Summary
                            <a href="{{ route('cart.index') }}"
                                style="float:right;font-size:12px;color:var(--orange);font-weight:600;text-decoration:none;">Edit</a>
                        </div>

                        <div class="checkout-summary-items">
                            @foreach ($items as $item)
                                <div class="checkout-summary-item">
                                    <div class="checkout-summary-item-img">
                                        @if ($item['image_url'])
                                            <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" loading="lazy">
                                        @else
                                            <i class="fa-solid fa-hammer"></i>
                                        @endif
                                    </div>
                                    <div class="checkout-summary-item-info">
                                        <div class="checkout-summary-item-name" title="{{ $item['name'] }}">
                                            {{ $item['name'] }}
                                        </div>
                                        <div class="checkout-summary-item-meta">
                                            @if ($item['sku'])
                                                SKU: {{ $item['sku'] }} &nbsp;·&nbsp;
                                            @endif
                                            Qty: {{ $item['quantity'] }}
                                        </div>
                                    </div>
                                    <div class="checkout-summary-item-price">
                                        ${{ number_format($item['line_total'], 2) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="checkout-summary-totals">
                            <div class="checkout-totals-row">
                                <span>Subtotal ({{ $items->sum('quantity') }} items)</span>
                                <span class="val">${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="checkout-totals-row">
                                <span>Shipping</span>
                                <span class="val {{ $shipping == 0 ? 'free-ship' : '' }}">
                                    {{ $shipping == 0 ? 'Free' : '$' . number_format($shipping, 2) }}
                                </span>
                            </div>
                            <div class="checkout-totals-row">
                                <span>Tax (8%)</span>
                                <span class="val">${{ number_format($tax, 2) }}</span>
                            </div>
                            <div class="checkout-totals-row grand">
                                <span>Total</span>
                                <span class="val">${{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <div class="co-trust">
                            <div class="co-trust-item"><i class="fa-solid fa-lock"></i> SSL Encrypted</div>
                            <div class="co-trust-item"><i class="fa-brands fa-stripe"></i> Stripe Secure</div>
                            <div class="co-trust-item"><i class="fa-solid fa-truck-fast"></i> Fast Shipping</div>
                            <div class="co-trust-item"><i class="fa-solid fa-rotate-left"></i> 30-Day Returns</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Processing overlay --}}
    <div class="co-processing-overlay" id="processingOverlay">
        <div class="co-spinner"></div>
        <p id="processingMsg">Processing your order…</p>
    </div>

@endsection

@push('scripts')
    {{-- Stripe.js --}}
    <script src="https://js.stripe.com/v3/"></script>

    <script>
        const STRIPE_KEY = '{{ $stripeKey }}';
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;
        const stripe = Stripe(STRIPE_KEY);
        let elements, paymentElement, clientSecret;

        // ── Init Stripe Elements on load ────────────────────────────
        async function initStripe() {
            try {
                const r = await fetch('{{ route('checkout.payment-intent') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({}),
                });
                const data = await r.json();

                if (data.error) {
                    showStripeError(data.error);
                    return;
                }

                clientSecret = data.client_secret;

                elements = stripe.elements({
                    clientSecret,
                    appearance: {
                        theme: 'stripe',
                        variables: {
                            colorPrimary: '#ff6b00',
                            colorBackground: '#ffffff',
                            colorText: '#111827',
                            colorDanger: '#ef4444',
                            fontFamily: '"DM Sans", system-ui, sans-serif',
                            spacingUnit: '4px',
                            borderRadius: '8px',
                            fontSizeBase: '14px',
                        },
                    },
                });

                paymentElement = elements.create('payment', {
                    layout: {
                        type: 'tabs',
                        defaultCollapsed: false
                    },
                });
                paymentElement.mount('#stripe-payment-element');

                paymentElement.on('change', e => {
                    if (e.error) {
                        showStripeError(e.error.message);
                    } else {
                        clearStripeError();
                    }
                });

            } catch (err) {
                showStripeError('Payment service unavailable. Please refresh and try again.');
            }
        }

        // ── Field validation ─────────────────────────────────────────
        const required = ['first_name', 'last_name', 'email', 'shipping_address', 'shipping_city', 'shipping_state',
            'shipping_zip', 'shipping_country'
        ];

        function validateFields() {
            let valid = true;
            required.forEach(id => {
                const el = document.getElementById(id);
                const err = document.getElementById('err-' + id);
                if (!el) return;
                const val = el.value.trim();
                if (!val) {
                    el.classList.add('error');
                    if (err) {
                        err.textContent = 'This field is required.';
                        err.style.display = 'block';
                    }
                    valid = false;
                } else if (id === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
                    el.classList.add('error');
                    if (err) {
                        err.textContent = 'Please enter a valid email address.';
                        err.style.display = 'block';
                    }
                    valid = false;
                } else {
                    el.classList.remove('error');
                    if (err) {
                        err.style.display = 'none';
                    }
                }
            });
            return valid;
        }

        // Clear error on input
        document.querySelectorAll('.co-input').forEach(el => {
            el.addEventListener('input', () => {
                el.classList.remove('error');
                const err = document.getElementById('err-' + el.id);
                if (err) err.style.display = 'none';
            });
        });

        // ── Place order ──────────────────────────────────────────────
        async function submitOrder() {
            clearStripeError();

            if (!validateFields()) {
                // Scroll to first error
                document.querySelector('.co-input.error')?.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                return;
            }

            const btn = document.getElementById('placeOrderBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing…';
            showOverlay('Confirming payment…');

            try {
                // Step 1: Confirm payment with Stripe
                const {
                    error: stripeError,
                    paymentIntent
                } = await stripe.confirmPayment({
                    elements,
                    redirect: 'if_required',
                });

                if (stripeError) {
                    hideOverlay();
                    showStripeError(stripeError.message);
                    btn.disabled = false;
                    btn.innerHTML =
                        '<i class="fa-solid fa-lock"></i> Pay ${{ number_format($total, 2) }} — Place Order';
                    return;
                }

                if (paymentIntent.status !== 'succeeded') {
                    hideOverlay();
                    showStripeError('Payment incomplete. Please try again.');
                    btn.disabled = false;
                    btn.innerHTML =
                        '<i class="fa-solid fa-lock"></i> Pay ${{ number_format($total, 2) }} — Place Order';
                    return;
                }

                // Step 2: Send order data to our server
                showOverlay('Placing your order…');

                const payload = {
                    payment_intent_id: paymentIntent.id,
                    first_name: document.getElementById('first_name').value.trim(),
                    last_name: document.getElementById('last_name').value.trim(),
                    email: document.getElementById('email').value.trim(),
                    phone: document.getElementById('phone').value.trim(),
                    company: document.getElementById('company').value.trim(),
                    shipping_address: document.getElementById('shipping_address').value.trim(),
                    shipping_city: document.getElementById('shipping_city').value.trim(),
                    shipping_state: document.getElementById('shipping_state').value.trim(),
                    shipping_zip: document.getElementById('shipping_zip').value.trim(),
                    shipping_country: document.getElementById('shipping_country').value,
                };

                const r = await fetch('{{ route('checkout.place-order') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload),
                });
                const data = await r.json();

                if (data.success) {
                    showOverlay('Order confirmed! Redirecting…');
                    window.location.href = data.redirect;
                } else {
                    hideOverlay();
                    showStripeError(data.error || 'Order placement failed. Please contact support.');
                    btn.disabled = false;
                    btn.innerHTML =
                        '<i class="fa-solid fa-lock"></i> Pay ${{ number_format($total, 2) }} — Place Order';
                }

            } catch (err) {
                hideOverlay();
                showStripeError('An unexpected error occurred. Please try again.');
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-lock"></i> Pay ${{ number_format($total, 2) }} — Place Order';
            }
        }

        // ── Helpers ──────────────────────────────────────────────────
        function showStripeError(msg) {
            const el = document.getElementById('stripe-errors');
            el.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> ' + msg;
            el.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }

        function clearStripeError() {
            document.getElementById('stripe-errors').innerHTML = '';
        }

        function showOverlay(msg) {
            document.getElementById('processingMsg').textContent = msg;
            document.getElementById('processingOverlay').classList.add('active');
        }

        function hideOverlay() {
            document.getElementById('processingOverlay').classList.remove('active');
        }

        // ── Boot ─────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', initStripe);
    </script>
@endpush
