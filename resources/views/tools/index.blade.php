{{-- resources/views/tools/index.blade.php --}}
@extends('layouts.app')

@section('meta_title', 'Heavy Duty Tools | ' . config('amsparts.company_name', 'AMS Parts'))
@section('meta_description', 'Shop our full range of heavy duty tools and equipment. Fast shipping, secure checkout,
    worldwide delivery.')
@section('body_class', 'page-tools-index')

@include('partials.tool-card-styles')

@push('styles')
    <style>
        /* ── Page Header ─────────────────────────────────────────── */
        .tools-page-header {
            background: linear-gradient(135deg, var(--ink) 0%, #1a2535 100%);
            padding: 56px 0 40px;
            position: relative;
            overflow: hidden;
        }

        .tools-page-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .tools-page-header .container {
            position: relative;
        }

        .tools-page-title {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            color: var(--white);
            margin: 12px 0 8px;
            line-height: 1.1;
            text-transform: uppercase;
        }

        .tools-page-subtitle {
            color: rgba(255, 255, 255, 0.65);
            font-size: 16px;
            margin: 0;
            max-width: 560px;
        }

        /* ── Layout ──────────────────────────────────────────────── */
        .tools-layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            gap: 32px;
            padding: 40px 0 80px;
            align-items: start;
        }

        @media (max-width: 900px) {
            .tools-layout {
                grid-template-columns: 1fr;
            }

            .tools-sidebar {
                order: -1;
            }
        }

        /* ── Sidebar ─────────────────────────────────────────────── */
        .tools-sidebar {
            position: sticky;
            top: 80px;
        }

        .tools-filter-card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
        }

        .tools-filter-title {
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--gray-700);
            margin: 0 0 16px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--orange);
            display: inline-block;
        }

        .tools-filter-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .tools-filter-label {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            font-size: 14px;
            color: var(--gray-700);
            transition: color .15s;
        }

        .tools-filter-label:hover {
            color: var(--orange);
        }

        .tools-filter-label input[type="checkbox"] {
            accent-color: var(--orange);
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }

        .tools-filter-range {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .tools-filter-range input {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid var(--gray-300);
            border-radius: 6px;
            font-size: 13px;
            outline: none;
            transition: border-color .15s;
        }

        .tools-filter-range input:focus {
            border-color: var(--orange);
        }

        .btn-filter-apply {
            width: 100%;
            padding: 10px;
            background: var(--orange);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 14px;
            transition: background .15s;
        }

        .btn-filter-apply:hover {
            background: var(--orange-dark, #c4540a);
        }

        .btn-filter-reset {
            width: 100%;
            padding: 8px;
            background: transparent;
            color: var(--gray-500);
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            font-size: 13px;
            cursor: pointer;
            margin-top: 8px;
            transition: all .15s;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .btn-filter-reset:hover {
            border-color: var(--gray-500);
            color: var(--gray-700);
        }

        /* ── Main content ────────────────────────────────────────── */
        .tools-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 24px;
        }

        .tools-count {
            font-size: 14px;
            color: var(--gray-500);
        }

        .tools-count strong {
            color: var(--gray-900);
        }

        .tools-sort {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: var(--gray-600);
        }

        .tools-sort select {
            padding: 8px 12px;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            font-size: 14px;
            background: var(--white);
            cursor: pointer;
            outline: none;
            transition: border-color .15s;
        }

        .tools-sort select:focus {
            border-color: var(--orange);
        }

        /* ── Tools grid ──────────────────────────────────────────── */
        .tools-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
        }

        /* ── Search bar ──────────────────────────────────────────── */
        .tools-search-wrap {
            position: relative;
            margin-bottom: 20px;
        }

        .tools-search-wrap input {
            width: 100%;
            padding: 11px 40px 11px 16px;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: border-color .15s;
            box-sizing: border-box;
        }

        .tools-search-wrap input:focus {
            border-color: var(--orange);
        }

        .tools-search-wrap .search-icon {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            font-size: 14px;
        }

        /* ── Empty state ─────────────────────────────────────────── */
        .tools-empty {
            text-align: center;
            padding: 80px 20px;
            color: var(--gray-500);
        }

        .tools-empty i {
            font-size: 48px;
            color: var(--gray-300);
            margin-bottom: 16px;
            display: block;
        }

        .tools-empty h3 {
            font-size: 20px;
            font-weight: 700;
            color: var(--gray-700);
            margin-bottom: 8px;
        }

        /* ── Active filters chips ────────────────────────────────── */
        .active-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 16px;
        }

        .active-filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fff3e8;
            border: 1px solid #fed7aa;
            color: var(--orange);
            font-size: 12px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
        }

        .active-filter-chip a {
            color: inherit;
            text-decoration: none;
            font-size: 14px;
            line-height: 1;
        }
    </style>
@endpush

@section('content')

    {{-- Page Header --}}
    <div class="tools-page-header">
        <div class="container">
            @include('partials.breadcrumb', [
                'crumbs' => [['label' => 'Heavy Duty Tools', 'url' => null]],
            ])
            <div style="margin-top:12px;">
                <span class="section-label"
                    style="color:var(--orange);font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;">
                    <i class="fa-solid fa-hammer"></i> Professional Grade
                </span>
                <h1 class="tools-page-title">Heavy Duty Tools</h1>
                <p class="tools-page-subtitle">
                    Professional-grade tools built for heavy equipment maintenance and repair. Fast shipping, secure
                    checkout.
                </p>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="tools-layout">

            {{-- ── Sidebar Filters ── --}}
            <aside class="tools-sidebar">

                <form method="GET" action="{{ route('tools.index') }}" id="filterForm">
                    {{-- Preserve sort --}}
                    @if (request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif

                    {{-- Search --}}
                    <div class="tools-filter-card">
                        <div class="tools-filter-title">Search Tools</div>
                        <div class="tools-search-wrap">
                            <input type="text" name="search" placeholder="Name, SKU, brand…"
                                value="{{ request('search') }}">
                            <i class="fa-solid fa-magnifying-glass search-icon"></i>
                        </div>
                    </div>

                    {{-- Brand filter --}}
                    @if ($brands->count())
                        <div class="tools-filter-card">
                            <div class="tools-filter-title">Brand</div>
                            <div class="tools-filter-group">
                                @foreach ($brands as $brand)
                                    <label class="tools-filter-label">
                                        <input type="checkbox" name="brand[]" value="{{ $brand }}"
                                            {{ in_array($brand, (array) request('brand')) ? 'checked' : '' }}
                                            onchange="document.getElementById('filterForm').submit()">
                                        {{ $brand }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Stock filter --}}
                    <div class="tools-filter-card">
                        <div class="tools-filter-title">Availability</div>
                        <div class="tools-filter-group">
                            <label class="tools-filter-label">
                                <input type="checkbox" name="stock" value="in_stock"
                                    {{ request('stock') === 'in_stock' ? 'checked' : '' }}
                                    onchange="document.getElementById('filterForm').submit()">
                                In Stock Only
                            </label>
                        </div>
                    </div>

                    {{-- Price range --}}
                    <div class="tools-filter-card">
                        <div class="tools-filter-title">Price Range</div>
                        <div class="tools-filter-range">
                            <input type="number" name="min_price" placeholder="Min $" value="{{ request('min_price') }}"
                                min="0" step="1">
                            <input type="number" name="max_price" placeholder="Max $" value="{{ request('max_price') }}"
                                min="0" step="1">
                        </div>
                        <button type="submit" class="btn-filter-apply">
                            <i class="fa-solid fa-sliders"></i> Apply Filters
                        </button>
                        @if (request()->hasAny(['search', 'brand', 'stock', 'min_price', 'max_price']))
                            <a href="{{ route('tools.index') }}" class="btn-filter-reset">
                                <i class="fa-solid fa-xmark"></i> Clear All Filters
                            </a>
                        @endif
                    </div>

                </form>

            </aside>

            {{-- ── Main Content ── --}}
            <div>

                {{-- Active filter chips --}}
                @if (request()->hasAny(['search', 'brand', 'stock', 'min_price', 'max_price']))
                    <div class="active-filters">
                        @if (request('search'))
                            <span class="active-filter-chip">
                                Search: "{{ request('search') }}"
                                <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}">×</a>
                            </span>
                        @endif
                        @foreach ((array) request('brand', []) as $b)
                            <span class="active-filter-chip">
                                Brand: {{ $b }}
                                <a
                                    href="{{ request()->fullUrlWithQuery(['brand' => array_diff((array) request('brand', []), [$b])]) }}">×</a>
                            </span>
                        @endforeach
                        @if (request('stock') === 'in_stock')
                            <span class="active-filter-chip">
                                In Stock Only
                                <a href="{{ request()->fullUrlWithQuery(['stock' => null]) }}">×</a>
                            </span>
                        @endif
                        @if (request('min_price') || request('max_price'))
                            <span class="active-filter-chip">
                                Price: ${{ request('min_price', '0') }} – ${{ request('max_price', '∞') }}
                                <a
                                    href="{{ request()->fullUrlWithQuery(['min_price' => null, 'max_price' => null]) }}">×</a>
                            </span>
                        @endif
                    </div>
                @endif

                {{-- Topbar: count + sort --}}
                <div class="tools-topbar">
                    <div class="tools-count">
                        <strong>{{ number_format($tools->total()) }}</strong> tool{{ $tools->total() !== 1 ? 's' : '' }}
                        found
                    </div>
                    <div class="tools-sort">
                        <label for="sortSelect">Sort:</label>
                        <select id="sortSelect" onchange="applySort(this.value)">
                            <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>
                                Newest</option>
                            <option value="featured" {{ request('sort') === 'featured' ? 'selected' : '' }}>Featured
                                First</option>
                            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low →
                                High</option>
                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High
                                → Low</option>
                            <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Name A–Z
                            </option>
                            <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Popular
                            </option>
                        </select>
                    </div>
                </div>

                {{-- Grid --}}
                @if ($tools->count())
                    <div class="tools-grid">
                        @foreach ($tools as $i => $tool)
                            @include('partials.tool-card', ['tool' => $tool, 'delay' => ($i % 12) * 50])
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if ($tools->hasPages())
                        <div style="margin-top:48px;">
                            {{ $tools->links() }}
                        </div>
                    @endif
                @else
                    <div class="tools-empty">
                        <i class="fa-solid fa-hammer"></i>
                        <h3>No tools found</h3>
                        <p>Try adjusting your filters or search term.</p>
                        <a href="{{ route('tools.index') }}" class="btn btn-primary" style="margin-top:16px;">
                            <i class="fa-solid fa-rotate-left"></i> View All Tools
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function applySort(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', value);
            url.searchParams.delete('page');
            window.location.href = url.toString();
        }
    </script>
@endpush
