{{-- resources/views/parts/show.blade.php --}}
@extends('layouts.app')

@section('meta_title', $part->seo_title ?: $part->name . ' | ' . config('amsparts.company_name', 'Parts Plus Innovation
    Solutions'))
@section('meta_description', $part->seo_description ?: $part->short_description ?: 'Buy ' . $part->name . ' from ' .
    config('amsparts.company_name', 'Parts Plus Innovation Solutions') . '. Fast shipping, expert support.')
@section('og_image', $part->images->first()?->public_url ?? asset('images/og-default.jpg'))
@section('body_class', 'page-part-detail')

@push('styles')
    <style>
        .part-detail-wrap {
            padding: 32px 0 64px;
        }

        .part-availability {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: var(--radius-full);
        }

        .part-availability--in {
            background: #F0FDF4;
            color: #15803D;
        }

        .part-availability--out {
            background: #FEF2F2;
            color: #991B1B;
        }

        .part-availability--call {
            background: var(--amber-pale);
            color: #92400E;
        }

        .part-availability i {
            font-size: 10px;
        }

        .part-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px;
        }

        /* Tabs */
        .part-tabs {
            border-bottom: 2px solid var(--gray-200);
            margin: 32px 0 0;
            display: flex;
            gap: 0;
        }

        .part-tab-btn {
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-500);
            border: none;
            background: none;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            transition: color var(--transition), border-color var(--transition);
        }

        .part-tab-btn.active,
        .part-tab-btn:hover {
            color: var(--orange);
            border-bottom-color: var(--orange);
        }

        .part-tab-panel {
            display: none;
            padding: 24px 0;
        }

        .part-tab-panel.active {
            display: block;
        }

        .part-desc {
            font-size: 15px;
            line-height: 1.8;
            color: var(--gray-700);
        }

        .part-desc p {
            margin-bottom: 14px;
        }

        /* Related parts */
        .related-section {
            padding: 52px 0;
            background: var(--gray-100);
        }

        .related-section .section-header {
            margin-bottom: 32px;
        }

        /* Sticky add-to-quote bar (mobile) */
        .sticky-quote-bar {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 80;
            background: var(--white);
            border-top: 1px solid var(--gray-200);
            padding: 12px 16px;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, .08);
        }

        @media (max-width: 768px) {
            .sticky-quote-bar {
                display: flex;
                gap: 10px;
                align-items: center;
            }

            .part-detail-wrap {
                padding-bottom: 80px;
            }
        }
    </style>
@endpush

@section('content')

    <div class="part-detail-wrap">
        <div class="container">

            {{-- Breadcrumb --}}
            @include('partials.breadcrumb', [
                'crumbs' => [
                    ['label' => 'Parts', 'url' => route('parts.index')],
                    [
                        'label' => $part->category?->name ?? 'Parts',
                        'url' => $part->category ? route('categories.show', $part->category->slug) : null,
                    ],
                    ['label' => $part->name, 'url' => null],
                ],
            ])

            {{-- Detail Layout --}}
            <div class="part-detail-layout">

                {{-- ── Gallery ── --}}
                <div class="part-gallery">
                    <div class="part-gallery-main" id="galleryMain">
                        @if ($part->images->count())
                            <img id="mainGalleryImg" src="{{ $part->images->first()->public_url }}" alt="{{ $part->name }}"
                                loading="eager">
                        @else
                            <div
                                style="display:flex;align-items:center;justify-content:center;height:100%;background:var(--gray-100);">
                                <i class="fa-solid fa-gear" style="font-size:64px;color:var(--gray-300);"></i>
                            </div>
                        @endif
                    </div>

                    @if ($part->images->count() > 1)
                        <div class="part-gallery-thumbs">
                            @foreach ($part->images as $i => $img)
                                <div class="part-gallery-thumb {{ $i === 0 ? 'part-gallery-thumb--active' : '' }}"
                                    data-full="{{ $img->public_url }}">
                                    <img src="{{ $img->public_url }}" alt="{{ $part->name }} image {{ $i + 1 }}"
                                        loading="lazy">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- ── Info Panel ── --}}
                <div class="part-info">

                    {{-- Make + Category --}}
                    <div class="part-info-meta">
                        @if ($part->make)
                            <a href="{{ route('makes.show', $part->make->slug) }}"
                                class="badge badge-orange">{{ $part->make->name }}</a>
                        @endif
                        @if ($part->category)
                            <a href="{{ route('categories.show', $part->category->slug) }}"
                                class="badge badge-gray">{{ $part->category->name }}</a>
                        @endif
                        <span
                            class="badge badge-{{ $part->condition_type === 'new' ? 'new' : ($part->condition_type === 'rebuilt' ? 'rebuilt' : 'used') }}">
                            {{ ucfirst($part->condition_type ?? 'new') }}
                        </span>
                    </div>

                    <h1 class="part-info-title">{{ $part->name }}</h1>

                    {{-- Part Numbers --}}
                    <div
                        style="display:flex;flex-wrap:wrap;gap:12px;margin:10px 0 4px;font-size:13px;color:var(--gray-600);">
                        @if ($part->part_number)
                            <span><strong style="color:var(--gray-800);">Part #:</strong> {{ $part->part_number }}</span>
                        @endif
                        @if ($part->oem_part_number)
                            <span><strong style="color:var(--gray-800);">OEM #:</strong>
                                {{ $part->oem_part_number }}</span>
                        @endif
                        @if ($part->sku)
                            <span><strong style="color:var(--gray-800);">SKU:</strong> {{ $part->sku }}</span>
                        @endif
                    </div>

                    {{-- Short description --}}
                    @if ($part->short_description)
                        <p style="font-size:14px;color:var(--gray-600);margin:12px 0;line-height:1.7;">
                            {{ $part->short_description }}
                        </p>
                    @endif

                    {{-- No price displayed — quote-only business model like amsparts.com --}}

                    {{-- Availability — show status label, never show stock quantity --}}
                    @php
                        $stockStatus = $part->stock_status ?? 'call_for_availability';
                    @endphp
                    @if ($stockStatus === 'in_stock')
                        <div class="part-availability part-availability--in">
                            <i class="fa-solid fa-circle-check"></i> In Stock
                        </div>
                    @elseif ($stockStatus === 'out_of_stock')
                        <div class="part-availability part-availability--out">
                            <i class="fa-solid fa-circle-xmark"></i> Out of Stock
                        </div>
                    @elseif ($stockStatus === 'on_order')
                        <div class="part-availability part-availability--call">
                            <i class="fa-solid fa-clock"></i> On Order — Contact Us
                        </div>
                    @else
                        <div class="part-availability part-availability--call">
                            <i class="fa-solid fa-phone"></i> Call for Availability
                        </div>
                    @endif

                    {{-- Actions --}}
                    <div class="part-actions">
                        @php
                            $query = http_build_query([
                                'part_number' => $part->part_number ?? $part->name,
                                'make_slug' => $part->make?->slug,
                                'model_id' => $part->compatibleModels->first()?->id,
                                'year' => $part->compatibleModels->first()?->year_range,
                                'serial_number' => '',
                                'part_name' => $part->name,
                                'part_desc' => $part->short_description,
                                'condition' => $part->condition_type,
                                'oem' => $part->oem_part_number,
                            ]);
                        @endphp
                        <a href="{{ route('quote.create') . '?' . $query }}" class="btn btn-primary btn-lg">
                            <i class="fa-solid fa-file-lines"></i> Request a Quote
                        </a>
                        @if (config('amsparts.phone_main'))
                            <a href="tel:{{ config('amsparts.phone_main') }}" class="btn btn-secondary btn-lg">
                                <i class="fa-solid fa-phone"></i> {{ config('amsparts.phone_main') }}
                            </a>
                        @endif
                    </div>

                    {{-- Quick Specs Table --}}
                    <div class="part-info-specs">
                        <table class="specs-table">
                            @if ($part->condition_type)
                                <tr>
                                    <td>Condition</td>
                                    <td>{{ ucfirst($part->condition_type) }}</td>
                                </tr>
                            @endif
                            @if ($part->make)
                                <tr>
                                    <td>Make</td>
                                    <td><a href="{{ route('makes.show', $part->make->slug) }}"
                                            style="color:var(--orange);">{{ $part->make->name }}</a></td>
                                </tr>
                            @endif
                            @if ($part->equipmentType)
                                <tr>
                                    <td>Equipment Type</td>
                                    <td>{{ $part->equipmentType->name }}</td>
                                </tr>
                            @endif
                            @if ($part->warranty_type && $part->warranty_type !== 'none')
                                <tr>
                                    <td>Warranty</td>
                                    <td>{{ $part->warranty_label }}</td>
                                </tr>
                            @endif
                            @if ($part->weight_lbs)
                                <tr>
                                    <td>Weight</td>
                                    <td>{{ $part->weight_lbs }} lbs</td>
                                </tr>
                            @endif
                            <tr>
                                <td>Ships Worldwide</td>
                                <td>
                                    @if ($part->ships_worldwide)
                                        <i class="fa-solid fa-check" style="color:var(--success);"></i> Yes
                                    @else
                                        Contact for details
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>

                    {{-- Compatible Models --}}
                    @if ($part->compatibleModels->count())
                        <div style="margin-top:16px;">
                            <div
                                style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--gray-500);margin-bottom:8px;">
                                Compatible With
                            </div>
                            <div class="compatibility-tags">
                                @foreach ($part->compatibleModels as $model)
                                    <a href="{{ route('makes.show', $model->make?->slug ?? '#') }}" class="compat-tag">
                                        <i class="fa-solid fa-check" style="color:var(--success);font-size:9px;"></i>
                                        {{ $model->make?->name }} {{ $model->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Trust snippets --}}
                    <div
                        style="display:flex;flex-wrap:wrap;gap:12px;margin-top:20px;padding-top:16px;border-top:1px solid var(--gray-100);">
                        <div class="trust-item" style="font-size:12px;">
                            <i class="fa-solid fa-truck-fast"></i> Fast Shipping
                        </div>
                        <div class="trust-item" style="font-size:12px;">
                            <i class="fa-solid fa-shield-halved"></i> Warranty Available
                        </div>
                        <div class="trust-item" style="font-size:12px;">
                            <i class="fa-solid fa-headset"></i> Expert Support
                        </div>
                    </div>

                </div>{{-- /.part-info --}}
            </div>{{-- /.part-detail-layout --}}

            {{-- ── Tabs: Description / Compatibility / Shipping ── --}}
            <div class="part-tabs" role="tablist">
                <button class="part-tab-btn active" role="tab" data-tab="description" aria-selected="true">
                    Description
                </button>
                @if ($part->compatibleModels->count())
                    <button class="part-tab-btn" role="tab" data-tab="compatibility" aria-selected="false">
                        Compatibility ({{ $part->compatibleModels->count() }})
                    </button>
                @endif
                <button class="part-tab-btn" role="tab" data-tab="shipping" aria-selected="false">
                    Shipping &amp; Returns
                </button>
            </div>

            {{-- Description Tab --}}
            <div class="part-tab-panel active" id="tab-description" role="tabpanel">
                <div class="part-desc container--md" style="max-width:800px;">
                    @if ($part->description)
                        {!! $part->description !!}
                    @else
                        <p>Contact us for detailed specifications and compatibility information for this part.</p>
                    @endif
                </div>
            </div>

            {{-- Compatibility Tab --}}
            @if ($part->compatibleModels->count())
                <div class="part-tab-panel" id="tab-compatibility" role="tabpanel">
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:8px;">
                        @foreach ($part->compatibleModels as $model)
                            <div
                                style="display:flex;align-items:center;gap:8px;padding:10px 14px;background:var(--gray-50);border:1px solid var(--gray-200);border-radius:var(--radius);">
                                <i class="fa-solid fa-circle-check" style="color:var(--success);flex-shrink:0;"></i>
                                <span style="font-size:13px;font-weight:500;">
                                    {{ $model->make?->name }} {{ $model->name }}
                                    @if ($model->year_range)
                                        <span
                                            style="color:var(--gray-400);font-weight:400;">({{ $model->year_range }})</span>
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Shipping Tab --}}
            <div class="part-tab-panel" id="tab-shipping" role="tabpanel">
                <div class="part-desc" style="max-width:680px;">
                    <p>We ship from multiple warehouses across North America. Most in-stock orders ship within 1–2 business
                        days.</p>
                    <p>We ship worldwide to 50+ countries. International shipping rates are calculated at checkout or by
                        contacting our team directly.</p>
                    @if (config('amsparts.shipping_info'))
                        <p>{{ config('amsparts.shipping_info') }}</p>
                    @endif
                    <p>For warranty and return information, please see our <a href="{{ route('warranty') }}"
                            style="color:var(--orange);">Warranty Policy</a>.</p>
                </div>
            </div>

        </div>{{-- /.container --}}
    </div>{{-- /.part-detail-wrap --}}

    {{-- ── Related Parts ── --}}
    @if ($related->count() || $sameMake->count())
        <div class="related-section">
            <div class="container">

                @if ($related->count())
                    <div class="section-header" style="text-align:left;max-width:none;margin-bottom:24px;">
                        <span class="section-label">Same Category</span>
                        <h2 style="font-size:1.6rem;">Related Parts</h2>
                    </div>
                    <div class="parts-grid parts-grid--4">
                        @foreach ($related as $i => $rp)
                            @include('partials.part-card', ['part' => $rp, 'delay' => $i * 50])
                        @endforeach
                    </div>
                @endif

                @if ($sameMake->count())
                    <div class="section-header" style="text-align:left;max-width:none;margin: 40px 0 24px;">
                        <span class="section-label">More from {{ $part->make?->name }}</span>
                        <h2 style="font-size:1.6rem;">{{ $part->make?->name }} Parts</h2>
                    </div>
                    <div class="parts-grid parts-grid--4">
                        @foreach ($sameMake as $i => $sp)
                            @include('partials.part-card', ['part' => $sp, 'delay' => $i * 50])
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    @endif

    {{-- Mobile Sticky Bar --}}
    <div class="sticky-quote-bar">
        <div style="flex:1;min-width:0;">
            <div
                style="font-size:12px;font-weight:700;color:var(--ink);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                {{ Str::limit($part->name, 30) }}
            </div>
            <div style="font-size:11px;color:var(--gray-500);">
                {{ $part->part_number ? 'Part #: ' . $part->part_number : '' }}
            </div>
        </div>
        <a href="{{ route('quote.create') }}?part_number={{ urlencode($part->part_number ?? $part->name) }}&make_slug={{ $part->make?->slug }}&part_name={{ urlencode($part->name) }}&part_desc={{ urlencode($part->short_description ?? '') }}&condition={{ $part->condition_type ?? '' }}&oem={{ urlencode($part->oem_part_number ?? '') }}"
            class="btn btn-primary">
            <i class="fa-solid fa-file-lines"></i> Get Quote
        </a>
    </div>

@endsection

@push('scripts')
    <script>
        // Tab switching
        document.querySelectorAll('.part-tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.part-tab-btn').forEach(b => {
                    b.classList.remove('active');
                    b.setAttribute('aria-selected', 'false');
                });
                document.querySelectorAll('.part-tab-panel').forEach(p => p.classList.remove('active'));

                this.classList.add('active');
                this.setAttribute('aria-selected', 'true');
                const panel = document.getElementById('tab-' + this.dataset.tab);
                if (panel) panel.classList.add('active');
            });
        });
    </script>
@endpush
