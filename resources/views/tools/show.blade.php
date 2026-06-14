{{-- resources/views/tools/show.blade.php --}}
@extends('layouts.app')

@section('meta_title', ($tool->meta_title ?: $tool->name) . ' | ' . config('amsparts.company_name', 'AMS Parts'))
@section('meta_description', $tool->meta_description ?: $tool->short_description ?: 'Buy ' . $tool->name . ' from ' .
    config('amsparts.company_name', 'AMS Parts') . '. Fast shipping, secure Stripe checkout.')
@section('og_image', $tool->image_url)
@section('body_class', 'page-tool-detail')

@include('partials.tool-card-styles')

@push('styles')
    <style>
        .tool-detail-wrap {
            padding: 40px 0 80px;
        }

        /* ── Gallery ──────────────────────────────────── */
        .tool-gallery {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .tool-gallery-main {
            aspect-ratio: 1;
            border-radius: 12px;
            overflow: hidden;
            background: var(--gray-100);
            border: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: zoom-in;
        }

        .tool-gallery-main img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .3s;
        }

        .tool-gallery-main:hover img {
            transform: scale(1.04);
        }

        .tool-gallery-main .no-img {
            font-size: 5rem;
            color: var(--gray-300);
        }

        .tool-gallery-thumbs {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .tool-gallery-thumb {
            width: 72px;
            height: 72px;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid transparent;
            cursor: pointer;
            flex-shrink: 0;
            background: var(--gray-100);
            transition: border-color .15s;
        }

        .tool-gallery-thumb.active {
            border-color: var(--orange);
        }

        .tool-gallery-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ── Product info ────────────────────────────── */
        .tool-info {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .tool-brand-line {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: var(--orange);
        }

        .tool-detail-name {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: clamp(1.6rem, 3vw, 2.2rem);
            font-weight: 800;
            line-height: 1.1;
            margin: 0;
            color: var(--gray-900);
        }

        .tool-meta-row {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
        }

        .tool-meta-item {
            font-size: 13px;
            color: var(--gray-600);
        }

        .tool-meta-item strong {
            color: var(--gray-900);
            font-weight: 600;
        }

        /* ── Availability badge ──────────────────────── */
        .tool-avail {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
            padding: 5px 14px;
            border-radius: 20px;
        }

        .tool-avail--in {
            background: #f0fdf4;
            color: #15803d;
        }

        .tool-avail--out {
            background: #fef2f2;
            color: #991b1b;
        }

        .tool-avail--order {
            background: #fffbeb;
            color: #92400e;
        }

        .tool-avail i {
            font-size: 9px;
        }

        /* ── Price block ─────────────────────────────── */
        .tool-price-block {
            display: flex;
            align-items: baseline;
            gap: 10px;
        }

        .tool-detail-price {
            font-size: 2rem;
            font-weight: 800;
            color: var(--gray-900);
        }

        .tool-detail-price--sale {
            color: #ef4444;
        }

        .tool-detail-price-original {
            font-size: 1.1rem;
            color: var(--gray-400);
            text-decoration: line-through;
        }

        .tool-savings {
            font-size: 13px;
            font-weight: 600;
            color: #15803d;
            background: #f0fdf4;
            padding: 3px 10px;
            border-radius: 20px;
        }

        /* ── Add to cart ─────────────────────────────── */
        .tool-atc {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            padding: 24px;
        }

        .tool-qty-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .tool-qty-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-700);
            min-width: 60px;
        }

        .tool-qty-ctrl {
            display: flex;
            align-items: center;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            overflow: hidden;
        }

        .tool-qty-btn {
            width: 36px;
            height: 40px;
            background: var(--gray-50);
            border: none;
            font-size: 16px;
            cursor: pointer;
            color: var(--gray-700);
            transition: background .15s;
        }

        .tool-qty-btn:hover {
            background: var(--gray-200);
        }

        .tool-qty-input {
            width: 52px;
            height: 40px;
            border: none;
            border-left: 1px solid var(--gray-300);
            border-right: 1px solid var(--gray-300);
            text-align: center;
            font-size: 15px;
            font-weight: 600;
            color: var(--gray-900);
            -moz-appearance: textfield;
        }

        .tool-qty-input::-webkit-outer-spin-button,
        .tool-qty-input::-webkit-inner-spin-button {
            appearance: none;
            margin: 0;
        }

        .tool-atc-btn {
            width: 100%;
            padding: 14px;
            font-size: 16px;
            font-weight: 700;
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
        }

        .tool-atc-btn:hover {
            background: #d95f00;
        }

        .tool-atc-btn:active {
            transform: scale(.98);
        }

        .tool-atc-btn:disabled {
            background: var(--gray-300);
            cursor: not-allowed;
        }

        .tool-atc-sub {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            margin-top: 14px;
            font-size: 12px;
            color: var(--gray-500);
        }

        .tool-atc-sub i {
            color: var(--orange);
        }

        /* ── Tabs ────────────────────────────────────── */
        .tool-tabs-wrap {
            margin-top: 48px;
        }

        .tool-tabs {
            display: flex;
            gap: 0;
            border-bottom: 2px solid var(--gray-200);
            margin-bottom: 0;
        }

        .tool-tab-btn {
            padding: 11px 22px;
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-500);
            border: none;
            background: none;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            transition: color .15s, border-color .15s;
        }

        .tool-tab-btn.active {
            color: var(--orange);
            border-bottom-color: var(--orange);
        }

        .tool-tab-panel {
            display: none;
            padding: 28px 0;
        }

        .tool-tab-panel.active {
            display: block;
        }

        .tool-tab-content {
            font-size: 15px;
            line-height: 1.8;
            color: var(--gray-700);
        }

        .tool-tab-content p {
            margin-bottom: 14px;
        }

        .tool-tab-content ul,
        .tool-tab-content ol {
            padding-left: 20px;
            margin-bottom: 14px;
        }

        .tool-tab-content li {
            margin-bottom: 6px;
        }

        .tool-specs-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .tool-specs-table tr {
            border-bottom: 1px solid var(--gray-100);
        }

        .tool-specs-table tr:last-child {
            border-bottom: none;
        }

        .tool-specs-table td {
            padding: 10px 0;
        }

        .tool-specs-table td:first-child {
            font-weight: 600;
            color: var(--gray-700);
            width: 40%;
        }

        .tool-specs-table td:last-child {
            color: var(--gray-600);
        }

        /* ── Related ────────────────────────────────── */
        .related-tools {
            padding: 52px 0;
            background: var(--gray-50);
            border-top: 1px solid var(--gray-200);
        }

        /* ── Sticky mobile bar ──────────────────────── */
        .tool-sticky-bar {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 80;
            background: var(--white);
            border-top: 1px solid var(--gray-200);
            padding: 12px 16px;
            gap: 10px;
            align-items: center;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, .08);
        }

        @media (max-width: 768px) {
            .tool-sticky-bar {
                display: flex;
            }

            .tool-detail-wrap {
                padding-bottom: 80px;
            }
        }

        .tool-sticky-price {
            font-size: 18px;
            font-weight: 800;
            color: var(--gray-900);
        }

        .tool-sticky-btn {
            flex: 1;
            padding: 12px;
            background: var(--orange);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }

        /* ── Layout ─────────────────────────────────── */
        .tool-detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 48px;
            align-items: start;
        }

        @media (max-width: 860px) {
            .tool-detail-grid {
                grid-template-columns: 1fr;
                gap: 32px;
            }
        }

        /* ── Lightbox ───────────────────────────────── */
        .tool-lightbox {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 500;
            background: rgba(0, 0, 0, .92);
            align-items: center;
            justify-content: center;
        }

        .tool-lightbox.open {
            display: flex;
        }

        .tool-lightbox img {
            max-width: 90vw;
            max-height: 90vh;
            border-radius: 8px;
            object-fit: contain;
        }

        .tool-lightbox-close {
            position: absolute;
            top: 16px;
            right: 20px;
            background: none;
            border: none;
            color: #fff;
            font-size: 28px;
            cursor: pointer;
        }
    </style>
@endpush

@section('content')

    <div class="tool-detail-wrap">
        <div class="container">

            {{-- Breadcrumb --}}
            @include('partials.breadcrumb', [
                'crumbs' => [
                    ['label' => 'Tools', 'url' => route('tools.index')],
                    ['label' => $tool->name, 'url' => null],
                ],
            ])

            {{-- Main detail grid --}}
            <div class="tool-detail-grid" style="margin-top:24px;">

                {{-- Gallery --}}
                <div class="tool-gallery">
                    <div class="tool-gallery-main" id="mainImage" onclick="openLightbox(this.dataset.src)"
                        data-src="{{ $mainImage }}">
                        @if ($mainImage)
                            <img src="{{ $mainImage }}" alt="{{ $tool->name }}" id="mainImg">
                        @else
                            <i class="fa-solid fa-hammer no-img"></i>
                        @endif
                    </div>

                    @if ($allImages->count() > 1)
                        <div class="tool-gallery-thumbs">
                            @foreach ($allImages as $i => $img)
                                <div class="tool-gallery-thumb {{ $i === 0 ? 'active' : '' }}"
                                    onclick="switchImage('{{ $img['url'] }}', this)">
                                    <img src="{{ $img['url'] }}" alt="{{ $tool->name }} image {{ $i + 1 }}"
                                        loading="lazy">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Product info + ATC --}}
                <div class="tool-info">

                    @if ($tool->brand)
                        <div class="tool-brand-line">{{ $tool->brand }}</div>
                    @endif

                    <h1 class="tool-detail-name">{{ $tool->name }}</h1>

                    {{-- Meta --}}
                    <div class="tool-meta-row">
                        @if ($tool->sku)
                            <div class="tool-meta-item"><strong>SKU:</strong> {{ $tool->sku }}</div>
                        @endif
                        @if ($tool->part_number)
                            <div class="tool-meta-item"><strong>Part #:</strong> {{ $tool->part_number }}</div>
                        @endif
                        @if ($tool->model_number)
                            <div class="tool-meta-item"><strong>Model:</strong> {{ $tool->model_number }}</div>
                        @endif
                    </div>

                    {{-- Availability --}}
                    <div>
                        @if ($tool->stock_status === 'in_stock')
                            <span class="tool-avail tool-avail--in">
                                <i class="fa-solid fa-circle-check"></i> In Stock
                                @if ($tool->stock_quantity > 0 && $tool->stock_quantity <= 10)
                                    — Only {{ $tool->stock_quantity }} left
                                @endif
                            </span>
                        @elseif ($tool->stock_status === 'on_order')
                            <span class="tool-avail tool-avail--order">
                                <i class="fa-solid fa-clock"></i> On Order
                            </span>
                        @else
                            <span class="tool-avail tool-avail--out">
                                <i class="fa-solid fa-circle-xmark"></i> Out of Stock
                            </span>
                        @endif
                    </div>

                    {{-- Price --}}
                    <div class="tool-price-block">
                        @if ($tool->is_on_sale)
                            <span
                                class="tool-detail-price tool-detail-price--sale">${{ number_format($tool->sale_price, 2) }}</span>
                            <span class="tool-detail-price-original">${{ number_format($tool->price, 2) }}</span>
                            <span class="tool-savings">Save
                                ${{ number_format($tool->price - $tool->sale_price, 2) }}</span>
                        @else
                            <span class="tool-detail-price">${{ number_format($tool->price, 2) }}</span>
                        @endif
                    </div>

                    @if ($tool->short_description)
                        <p style="font-size:15px;color:var(--gray-600);line-height:1.7;margin:0;">
                            {{ $tool->short_description }}
                        </p>
                    @endif

                    {{-- Add to Cart --}}
                    <div class="tool-atc">
                        @if ($tool->stock_status !== 'out_of_stock')
                            <div class="tool-qty-row">
                                <span class="tool-qty-label">Quantity</span>
                                <div class="tool-qty-ctrl">
                                    <button type="button" class="tool-qty-btn" onclick="adjustQty(-1)">−</button>
                                    <input type="number" id="qtyInput" class="tool-qty-input" value="1"
                                        min="1" max="{{ $tool->stock_quantity ?: 99 }}">
                                    <button type="button" class="tool-qty-btn" onclick="adjustQty(1)">+</button>
                                </div>
                            </div>

                            <button type="button" class="tool-atc-btn" id="addToCartBtn"
                                onclick="addToCart({{ $tool->id }}, '{{ addslashes($tool->name) }}', {{ $tool->effective_price }})">
                                <i class="fa-solid fa-cart-plus"></i>
                                Add to Cart
                            </button>
                        @else
                            <button type="button" class="tool-atc-btn" disabled>
                                <i class="fa-solid fa-ban"></i> Out of Stock
                            </button>
                        @endif

                        <div class="tool-atc-sub">
                            <span><i class="fa-solid fa-lock"></i> Secure Stripe checkout</span>
                            <span><i class="fa-solid fa-truck-fast"></i> Fast shipping</span>
                            @if ($tool->ships_worldwide)
                                <span><i class="fa-solid fa-globe"></i> Ships worldwide</span>
                            @endif
                        </div>
                    </div>

                    {{-- Shipping/specs callout --}}
                    <div style="display:flex;flex-wrap:wrap;gap:12px;">
                        @if ($tool->weight_lbs)
                            <div style="font-size:12px;color:var(--gray-500);">
                                <i class="fa-solid fa-weight-hanging" style="color:var(--orange);"></i>
                                {{ $tool->weight_lbs }} lbs
                            </div>
                        @endif
                        @if ($tool->dimensions)
                            <div style="font-size:12px;color:var(--gray-500);">
                                <i class="fa-solid fa-ruler-combined" style="color:var(--orange);"></i>
                                {{ $tool->dimensions }}
                            </div>
                        @endif
                    </div>

                </div>
            </div>

            {{-- Tabs: Description / Specifications --}}
            <div class="tool-tabs-wrap">
                <div class="tool-tabs">
                    <button class="tool-tab-btn active" onclick="switchTab('desc', this)">Description</button>
                    @if ($tool->specifications)
                        <button class="tool-tab-btn" onclick="switchTab('specs', this)">Specifications</button>
                    @endif
                    <button class="tool-tab-btn" onclick="switchTab('shipping', this)">Shipping & Returns</button>
                </div>

                <div class="tool-tab-panel active" id="tab-desc">
                    <div class="tool-tab-content">
                        @if ($tool->description)
                            {!! $tool->description !!}
                        @else
                            <p>{{ $tool->short_description ?: 'No description available.' }}</p>
                        @endif
                    </div>
                </div>

                @if ($tool->specifications)
                    <div class="tool-tab-panel" id="tab-specs">
                        <div class="tool-tab-content">
                            {!! $tool->specifications !!}
                        </div>
                    </div>
                @endif

                <div class="tool-tab-panel" id="tab-shipping">
                    <div class="tool-tab-content">
                        <p><strong>Shipping:</strong> Most in-stock items ship within 1–2 business days.
                            Expedited shipping options available at checkout. We ship to all 50 US states
                            @if ($tool->ships_worldwide)
                                and internationally
                            @endif.
                        </p>
                        <p><strong>Returns:</strong> Unused items in original packaging may be returned within 30 days
                            for a full refund. Please contact us at <a href="mailto:{{ config('amsparts.company.email') }}"
                                style="color:var(--orange);">{{ config('amsparts.company.email') }}</a>
                            to initiate a return.</p>
                        <p><strong>Questions?</strong> Call us at
                            <a href="tel:{{ config('amsparts.company.phone') }}"
                                style="color:var(--orange);">{{ config('amsparts.company.phone') }}</a>.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Related tools --}}
    @if ($relatedTools->isNotEmpty())
        <div class="related-tools">
            <div class="container">
                <div class="section-header" style="margin-bottom:32px;" data-reveal>
                    <span class="section-label">More Tools</span>
                    <h2 class="section-title">Related Products</h2>
                </div>
                <div class="tools-grid" style="grid-template-columns:repeat(auto-fill,minmax(220px,1fr));">
                    @foreach ($relatedTools as $i => $related)
                        @include('partials.tool-card', ['tool' => $related, 'delay' => $i * 70])
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- Sticky mobile add-to-cart --}}
    @if ($tool->stock_status !== 'out_of_stock')
        <div class="tool-sticky-bar" id="stickyBar">
            <div>
                <div style="font-size:11px;color:var(--gray-500);">{{ Str::limit($tool->name, 30) }}</div>
                <div class="tool-sticky-price">${{ number_format($tool->effective_price, 2) }}</div>
            </div>
            <button class="tool-sticky-btn"
                onclick="addToCart({{ $tool->id }}, '{{ addslashes($tool->name) }}', {{ $tool->effective_price }})">
                <i class="fa-solid fa-cart-plus"></i> Add to Cart
            </button>
        </div>
    @endif

    {{-- Lightbox --}}
    <div class="tool-lightbox" id="lightbox" onclick="closeLightbox()">
        <button class="tool-lightbox-close" onclick="closeLightbox()"><i class="fa-solid fa-xmark"></i></button>
        <img src="" alt="{{ $tool->name }}" id="lightboxImg">
    </div>

@endsection

@push('scripts')
    <script>
        // ── Gallery switcher ─────────────────────────────────────────
        function switchImage(url, thumb) {
            document.getElementById('mainImg')?.setAttribute('src', url);
            document.getElementById('mainImage').dataset.src = url;
            document.querySelectorAll('.tool-gallery-thumb').forEach(t => t.classList.remove('active'));
            thumb.classList.add('active');
        }

        // ── Lightbox ─────────────────────────────────────────────────
        function openLightbox(src) {
            if (!src) return;
            document.getElementById('lightboxImg').src = src;
            document.getElementById('lightbox').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            document.getElementById('lightbox').classList.remove('open');
            document.body.style.overflow = '';
        }
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeLightbox();
        });

        // ── Quantity adjuster ─────────────────────────────────────────
        function adjustQty(delta) {
            const inp = document.getElementById('qtyInput');
            if (!inp) return;
            const max = parseInt(inp.max) || 99;
            inp.value = Math.max(1, Math.min(max, parseInt(inp.value || 1) + delta));
        }

        // ── Tabs ──────────────────────────────────────────────────────
        function switchTab(id, btn) {
            document.querySelectorAll('.tool-tab-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.tool-tab-btn').forEach(b => b.classList.remove('active'));
            document.getElementById('tab-' + id)?.classList.add('active');
            btn.classList.add('active');
        }

        // ── Add to Cart ───────────────────────────────────────────────
        function addToCart(toolId, toolName, price) {
            const qty = parseInt(document.getElementById('qtyInput')?.value || 1);
            const btn = document.getElementById('addToCartBtn');

            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Adding…';
            }

            fetch('{{ route('cart.add') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        tool_id: toolId,
                        quantity: qty
                    }),
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        // Update cart badge count
                        document.querySelectorAll('[data-cart-count]').forEach(el => {
                            el.textContent = data.cart_count;
                            el.style.display = data.cart_count > 0 ? '' : 'none';
                        });
                        // Flash success on button
                        if (btn) {
                            btn.innerHTML = '<i class="fa-solid fa-check"></i> Added to Cart!';
                            btn.style.background = '#16a34a';
                            setTimeout(() => {
                                btn.innerHTML = '<i class="fa-solid fa-cart-plus"></i> Add to Cart';
                                btn.style.background = '';
                                btn.disabled = false;
                            }, 1800);
                        }
                    } else {
                        alert(data.message || 'Could not add to cart.');
                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = '<i class="fa-solid fa-cart-plus"></i> Add to Cart';
                        }
                    }
                })
                .catch(() => {
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fa-solid fa-cart-plus"></i> Add to Cart';
                    }
                });
        }

        // ── Sticky bar show/hide on scroll ────────────────────────────
        const atcSection = document.querySelector('.tool-atc');
        const stickyBar = document.getElementById('stickyBar');
        if (atcSection && stickyBar) {
            const obs = new IntersectionObserver(entries => {
                stickyBar.style.display = entries[0].isIntersecting ? 'none' : '';
            }, {
                threshold: 0.1
            });
            obs.observe(atcSection);
        }
    </script>
@endpush
