{{-- resources/views/parts/index.blade.php --}}
@extends('layouts.app')

@php
    $title = $pageTitle ?? 'Heavy Equipment Parts';
    $subtitle = 'Browse our inventory of new, used, and rebuilt parts for all major makes.';
@endphp

@section('meta_title', $title . ' | ' . config('amsparts.company_name', 'Parts Plus Innovation Solutions'))
@section('meta_description',
    'Shop ' .
    strtolower($title) .
    '. Filter by make, category and condition. Fast
    shipping nationwide.')
@section('body_class', 'page-parts-index')

@section('content')

    {{-- Page Hero --}}
    <div class="page-hero">
        <div class="container">
            @include('partials.breadcrumb', ['crumbs' => [['label' => 'Parts', 'url' => null]]])
            <div class="page-hero-label">Inventory</div>
            <h1 class="page-hero-title">{{ $title }}</h1>
            <p class="page-hero-sub">{{ $subtitle }}</p>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="section section--warm">
        <div class="container">

            {{-- Condition Quick-links --}}
            <div class="condition-quick-links">
                <a href="{{ route('parts.index') }}"
                    class="cql-btn {{ !request('condition') && !isset($activeCondition) ? 'cql-btn--active' : '' }}">
                    All Parts
                    <span
                        class="cql-count">{{ number_format($filterMakes->sum('parts_count') ?: \App\Models\Part::active()->count()) }}</span>
                </a>
                <a href="{{ route('parts.new') }}"
                    class="cql-btn {{ ($activeCondition ?? '') === 'new' ? 'cql-btn--active' : '' }}">
                    <i class="fa-solid fa-star"></i> New
                </a>
                <a href="{{ route('parts.used') }}"
                    class="cql-btn {{ ($activeCondition ?? '') === 'used' ? 'cql-btn--active' : '' }}">
                    <i class="fa-solid fa-recycle"></i> Used
                </a>
                <a href="{{ route('parts.rebuilt') }}"
                    class="cql-btn {{ ($activeCondition ?? '') === 'rebuilt' ? 'cql-btn--active' : '' }}">
                    <i class="fa-solid fa-screwdriver-wrench"></i> Rebuilt
                </a>
            </div>

            <div class="catalog-layout">

                {{-- ── Sidebar Filters ── --}}
                <aside class="filter-sidebar" id="filterSidebar" aria-label="Filter parts">
                    <div class="filter-sidebar-header">
                        <div class="filter-sidebar-title"><i class="fa-solid fa-sliders"></i> Filters</div>
                        @if (request()->hasAny(['search', 'category', 'make', 'type', 'equipment_type', 'in_stock']))
                            <a href="{{ route('parts.index') }}" class="filter-clear">Clear All</a>
                        @endif
                    </div>

                    <form method="GET" action="{{ route('parts.index') }}" id="filterForm">
                        {{-- Preserve search --}}
                        @if (request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        {{-- Search within --}}
                        <div class="filter-group">
                            <div class="filter-group-body" style="padding-top:14px;">
                                <div style="display:flex;gap:6px;">
                                    <input type="text" name="search" class="form-control"
                                        style="height:36px;font-size:12px;" value="{{ request('search') }}"
                                        placeholder="Search part name, number…">
                                    <button type="submit" class="btn btn-primary btn-sm" style="flex-shrink:0;">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Make --}}
                        <div class="filter-group">
                            <button type="button" class="filter-group-toggle" aria-expanded="true">
                                Make / Brand <i class="fa-solid fa-chevron-down"></i>
                            </button>
                            <div class="filter-group-body">
                                @foreach ($filterMakes->take(10) as $make)
                                    <label class="filter-option">
                                        <input type="checkbox" name="make[]" value="{{ $make->id }}"
                                            {{ in_array($make->id, (array) request('make', [])) ? 'checked' : '' }}>
                                        <span class="filter-option-label">{{ $make->name }}</span>
                                        <span class="filter-option-count">{{ number_format($make->parts_count) }}</span>
                                    </label>
                                @endforeach
                                @if ($filterMakes->count() > 10)
                                    <button type="button" class="filter-show-more" data-target="makesExtra">
                                        +{{ $filterMakes->count() - 10 }} more
                                    </button>
                                    <div id="makesExtra" style="display:none;">
                                        @foreach ($filterMakes->skip(10) as $make)
                                            <label class="filter-option">
                                                <input type="checkbox" name="make[]" value="{{ $make->id }}"
                                                    {{ in_array($make->id, (array) request('make', [])) ? 'checked' : '' }}>
                                                <span class="filter-option-label">{{ $make->name }}</span>
                                                <span
                                                    class="filter-option-count">{{ number_format($make->parts_count) }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Category --}}
                        <div class="filter-group">
                            <button type="button" class="filter-group-toggle" aria-expanded="true">
                                Part Category <i class="fa-solid fa-chevron-down"></i>
                            </button>
                            <div class="filter-group-body">
                                @foreach ($filterCategories->take(10) as $cat)
                                    <label class="filter-option">
                                        <input type="checkbox" name="category[]" value="{{ $cat->id }}"
                                            {{ in_array($cat->id, (array) request('category', [])) ? 'checked' : '' }}>
                                        <span class="filter-option-label">{{ $cat->name }}</span>
                                        <span class="filter-option-count">{{ number_format($cat->parts_count) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Equipment Type --}}
                        @if ($filterEquipmentTypes->count())
                            <div class="filter-group">
                                <button type="button" class="filter-group-toggle" aria-expanded="false">
                                    Equipment Type <i class="fa-solid fa-chevron-down"></i>
                                </button>
                                <div class="filter-group-body" style="display:none;">
                                    @foreach ($filterEquipmentTypes as $et)
                                        <label class="filter-option">
                                            <input type="checkbox" name="equipment_type[]" value="{{ $et->id }}"
                                                {{ in_array($et->id, (array) request('equipment_type', [])) ? 'checked' : '' }}>
                                            <span class="filter-option-label">{{ $et->name }}</span>
                                            <span class="filter-option-count">{{ number_format($et->parts_count) }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Price Range --}}
                        <div class="filter-group">
                            <button type="button" class="filter-group-toggle" aria-expanded="false">
                                Price Range <i class="fa-solid fa-chevron-down"></i>
                            </button>
                            <div class="filter-group-body" style="display:none;">
                                <button type="submit" class="btn btn-ghost btn-sm"
                                    style="margin-top:10px;width:100%;justify-content:center;">Apply Price</button>
                            </div>
                        </div>

                        {{-- In Stock --}}
                        <div class="filter-group" style="border-bottom:none;">
                            <div class="filter-group-body" style="padding-top:14px;">
                                <label class="filter-option" style="padding:0;">
                                    <input type="checkbox" name="in_stock" value="1"
                                        {{ request('in_stock') ? 'checked' : '' }}>
                                    <span class="filter-option-label" style="font-weight:600;">In Stock Only</span>
                                </label>
                            </div>
                        </div>

                    </form>
                </aside>

                {{-- ── Main Results ── --}}
                <div class="catalog-main">

                    {{-- Toolbar --}}
                    <div class="catalog-toolbar">
                        <div class="catalog-count">
                            @if ($parts->total() > 0)
                                <strong>{{ number_format($parts->total()) }}</strong> parts found
                                @if (request('search'))
                                    for "<em>{{ request('search') }}</em>"
                                @endif
                            @else
                                No parts found
                            @endif
                        </div>
                        <div class="catalog-toolbar-right">
                            {{-- Mobile filter toggle --}}
                            <button class="btn btn-ghost btn-sm filter-mobile-toggle d-md-none" id="filterMobileToggle">
                                <i class="fa-solid fa-sliders"></i> Filters
                                @if (request()->hasAny(['category', 'make', 'type', 'equipment_type', 'in_stock']))
                                    <span class="filter-active-dot"></span>
                                @endif
                            </button>
                            {{-- Sort --}}
                            <div class="catalog-sort">
                                <label for="sortSelect" class="sr-only">Sort by</label>
                                <select id="sortSelect" name="sort" class="form-control"
                                    style="height:36px;font-size:12px;width:160px;"
                                    onchange="applyCatalogSort(this.value)">
                                    <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>
                                        Newest First
                                    </option>
                                    <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most
                                        Popular
                                    </option>
                                    </option>
                                    <option <option value="name_asc"
                                        {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Name A–Z</option>
                                </select>
                            </div>
                            {{-- Grid / List toggle --}}
                            <div class="view-toggle" role="group" aria-label="View mode">
                                <button class="view-toggle-btn active" data-view="grid" aria-label="Grid view">
                                    <i class="fa-solid fa-grid-2"></i>
                                </button>
                                <button class="view-toggle-btn" data-view="list" aria-label="List view">
                                    <i class="fa-solid fa-list"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Active Filter Pills --}}
                    @php
                        $activeFilters = [];
                        if (request('search')) {
                            $activeFilters[] = ['label' => 'Search: ' . request('search'), 'remove' => 'search'];
                        }
                        if (request('in_stock')) {
                            $activeFilters[] = ['label' => 'In Stock', 'remove' => 'in_stock'];
                        }
                    @endphp
                    {{-- Parts Grid --}}
                    @if ($parts->count())
                        <div class="parts-grid parts-grid--3" id="partsGrid">
                            @foreach ($parts as $i => $part)
                                @include('partials.part-card', [
                                    'part' => $part,
                                    'delay' => min($i, 8) * 50,
                                ])
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        @if ($parts->hasPages())
                            <div class="pagination-wrap">
                                {{ $parts->onEachSide(2)->links('vendor.pagination.simple-admin') }}
                            </div>
                        @endif
                    @else
                        {{-- Empty State --}}
                        <div class="empty-state">
                            <div class="empty-state-icon"><i class="fa-solid fa-box-open"></i></div>
                            <h3 class="empty-state-title">No Parts Found</h3>
                            <p class="empty-state-text">
                                We could not find parts matching your filters.
                                Try adjusting your search or
                                <a href="{{ route('parts.index') }}" style="color:var(--orange);">clear all filters</a>.
                            </p>
                            <a href="{{ route('quote.create') }}" class="btn btn-primary" style="margin-top:8px;">
                                <i class="fa-solid fa-file-lines"></i> Request a Quote — We'll Source It
                            </a>
                        </div>
                    @endif

                </div>{{-- /.catalog-main --}}
            </div>{{-- /.catalog-layout --}}
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .condition-quick-links {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 24px;
            padding-top: 4px;
        }

        .cql-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: var(--radius-full);
            font-size: 13px;
            font-weight: 600;
            background: var(--white);
            border: 1.5px solid var(--gray-200);
            color: var(--gray-700);
            text-decoration: none;
            transition: all var(--transition);
        }

        .cql-btn:hover {
            border-color: var(--orange);
            color: var(--orange);
        }

        .cql-btn--active {
            background: var(--orange);
            border-color: var(--orange);
            color: var(--white);
        }

        .cql-count {
            background: rgba(0, 0, 0, .1);
            border-radius: var(--radius-full);
            padding: 1px 7px;
            font-size: 11px;
        }

        .cql-btn--active .cql-count {
            background: rgba(255, 255, 255, .2);
        }

        .catalog-main {
            min-width: 0;
        }

        .catalog-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--gray-200);
        }

        .catalog-count {
            font-size: 14px;
            color: var(--gray-600);
        }

        .catalog-count strong {
            color: var(--ink);
        }

        .catalog-toolbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .catalog-sort {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .view-toggle {
            display: flex;
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius);
            overflow: hidden;
        }

        .view-toggle-btn {
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            color: var(--gray-500);
            border: none;
            background: var(--white);
            border-right: 1px solid var(--gray-200);
            cursor: pointer;
            transition: background var(--transition), color var(--transition);
        }

        .view-toggle-btn:last-child {
            border-right: none;
        }

        .view-toggle-btn.active,
        .view-toggle-btn:hover {
            background: var(--orange-pale);
            color: var(--orange);
        }

        .active-filter-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 14px;
        }

        .filter-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: var(--radius-full);
            background: var(--orange-pale);
            color: var(--orange-dark);
            font-size: 12px;
            font-weight: 600;
            border: 1px solid rgba(224, 92, 26, .2);
            transition: background var(--transition);
        }

        .filter-pill:hover {
            background: rgba(224, 92, 26, .2);
        }

        .filter-show-more {
            font-size: 12px;
            color: var(--orange);
            font-weight: 600;
            padding: 4px 0;
            cursor: pointer;
            border: none;
            background: none;
            display: block;
            margin-top: 4px;
        }

        .filter-active-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--orange);
            display: inline-block;
            margin-left: 2px;
        }

        .pagination-wrap {
            padding: 24px 0 8px;
        }

        /* List view */
        .parts-grid.list-view {
            grid-template-columns: 1fr !important;
        }

        .parts-grid.list-view .part-card {
            flex-direction: row;
        }

        .parts-grid.list-view .part-card-img {
            width: 160px;
            flex-shrink: 0;
            aspect-ratio: unset;
            height: 140px;
        }

        .parts-grid.list-view .part-card-body {
            flex: 1;
        }

        .parts-grid.list-view .part-card-footer {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
            padding-top: 12px;
        }

        @media (max-width: 768px) {
            .catalog-layout {
                grid-template-columns: 1fr;
            }

            .filter-sidebar {
                position: fixed;
                left: 0;
                right: 0;
                bottom: 0;
                top: auto;
                z-index: 150;
                transform: translateY(100%);
                transition: transform .3s ease;
                border-radius: var(--radius-xl) var(--radius-xl) 0 0;
                max-height: 80vh;
                overflow-y: auto;
            }

            .filter-sidebar.open {
                transform: translateY(0);
            }

            .parts-grid.parts-grid--3 {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .parts-grid.parts-grid--3 {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function applyCatalogSort(val) {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', val);
            url.searchParams.delete('page');
            window.location.href = url.toString();
        }

        // View toggle (grid / list)
        document.querySelectorAll('.view-toggle-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.view-toggle-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const grid = document.getElementById('partsGrid');
                if (grid) grid.classList.toggle('list-view', this.dataset.view === 'list');
            });
        });

        // Show more makes
        document.querySelectorAll('.filter-show-more').forEach(btn => {
            btn.addEventListener('click', function() {
                const target = document.getElementById(this.dataset.target);
                if (target) {
                    target.style.display = target.style.display === 'none' ? '' : 'none';
                }
                this.textContent = target.style.display === 'none' ? this.textContent : 'Show less';
            });
        });

        // Mobile filter drawer
        const filterToggle = document.getElementById('filterMobileToggle');
        const filterSidebar = document.getElementById('filterSidebar');
        if (filterToggle && filterSidebar) {
            filterToggle.addEventListener('click', () => filterSidebar.classList.toggle('open'));
            // Close on outside tap
            document.addEventListener('click', e => {
                if (!filterSidebar.contains(e.target) && !filterToggle.contains(e.target)) {
                    filterSidebar.classList.remove('open');
                }
            });
        }
    </script>
@endpush
