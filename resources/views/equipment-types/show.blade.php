{{-- resources/views/equipment-types/show.blade.php --}}
@extends('layouts.app')

@section('meta_title', ($equipmentType->seo_title ?: $equipmentType->name . ' Parts — New, Used & Rebuilt') . ' | ' .
    config('amsparts.company_name', 'Parts Plus Innovation Solutions'))
@section('meta_description',
    $equipmentType->seo_description ?:
    'Shop new, used and rebuilt ' .
    $equipmentType->name .
    '
    parts for all major makes. Hydraulic, undercarriage, engine and more. Fast shipping.')
@section('body_class', 'page-equipment-type-detail')

@section('content')

    {{-- Hero --}}
    <div class="page-hero" style="position:relative;overflow:hidden;">
        @if ($equipmentType->image_media)
            <div style="position:absolute;inset:0;z-index:0;">
                <img src="{{ $equipmentType->image_media->public_url }}" alt="{{ $equipmentType->name }}"
                    style="width:100%;height:100%;object-fit:cover;opacity:.18;">
            </div>
        @endif
        <div class="container" style="position:relative;z-index:1;">
            @include('partials.breadcrumb', [
                'crumbs' => [
                    ['label' => 'Parts by Equipment', 'url' => route('equipment-types.index')],
                    ['label' => $equipmentType->name, 'url' => null],
                ],
            ])
            <div class="page-hero-label">{{ number_format($equipmentType->parts_count) }} Parts Available</div>
            <h1 class="page-hero-title">{{ $equipmentType->name }} Parts</h1>
            @if ($equipmentType->description)
                <p class="page-hero-sub">{{ Str::limit($equipmentType->description, 200) }}</p>
            @else
                <p class="page-hero-sub">
                    New, used and rebuilt replacement parts for {{ $equipmentType->name }}s.
                    All major makes covered. Fast shipping.
                </p>
            @endif
        </div>
    </div>

    <div class="section section--warm">
        <div class="container">

            {{-- Dual filter: Makes + Categories --}}
            <div class="et-filters" data-reveal>
                {{-- Make filter --}}
                @if ($makes->count())
                    <div class="et-filter-group">
                        <div class="et-filter-label">Filter by Make:</div>
                        <div class="make-category-pills">
                            <a href="{{ route('equipment-types.show', $equipmentType->slug) }}"
                                class="mcp {{ !request('make') ? 'mcp--active' : '' }}">All Makes</a>
                            @foreach ($makes as $make)
                                <a href="{{ route('equipment-types.show', $equipmentType->slug) }}?make={{ $make->id }}{{ request('category') ? '&category=' . request('category') : '' }}"
                                    class="mcp {{ request('make') == $make->id ? 'mcp--active' : '' }}">
                                    {{ $make->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Category filter --}}
                @if ($categories->count())
                    <div class="et-filter-group" style="margin-top:12px;">
                        <div class="et-filter-label">Filter by Part Category:</div>
                        <div class="make-category-pills">
                            <a href="{{ route('equipment-types.show', $equipmentType->slug) }}{{ request('make') ? '?make=' . request('make') : '' }}"
                                class="mcp {{ !request('category') ? 'mcp--active' : '' }}">All Categories</a>
                            @foreach ($categories as $cat)
                                <a href="{{ route('equipment-types.show', $equipmentType->slug) }}?{{ http_build_query(array_merge(request()->except('category', 'page'), ['category' => $cat->id])) }}"
                                    class="mcp {{ request('category') == $cat->id ? 'mcp--active' : '' }}">
                                    {{ $cat->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Toolbar --}}
            <div class="catalog-toolbar" style="margin-top:20px;" data-reveal>
                <div class="catalog-count">
                    <strong>{{ number_format($parts->total()) }}</strong> {{ $equipmentType->name }} parts
                    @if (request('make'))
                        for <strong>{{ $makes->firstWhere('id', request('make'))?->name }}</strong>
                    @endif
                    @if (request('category'))
                        — {{ $categories->firstWhere('id', request('category'))?->name }}
                    @endif
                </div>
                <div class="catalog-toolbar-right">
                    <select class="form-control" style="height:36px;font-size:12px;width:160px;"
                        onchange="applyCatalogSort(this.value)">
                        <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Newest First
                        </option>
                        <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Popular
                        </option>
                        High</option>
                        Low</option>
                    </select>
                </div>
            </div>

            {{-- Parts Grid --}}
            @if ($parts->count())
                <div class="parts-grid parts-grid--3" id="partsGrid">
                    @foreach ($parts as $i => $part)
                        @include('partials.part-card', ['part' => $part, 'delay' => min($i, 8) * 50])
                    @endforeach
                </div>

                @if ($parts->hasPages())
                    <div class="pagination-wrap">
                        {{ $parts->onEachSide(2)->links('vendor.pagination.simple-admin') }}
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <div class="empty-state-icon"><i class="fa-solid fa-box-open"></i></div>
                    <h3 class="empty-state-title">No Parts Found</h3>
                    <p class="empty-state-text">
                        No {{ $equipmentType->name }} parts match your filters.
                        <a href="{{ route('equipment-types.show', $equipmentType->slug) }}" style="color:var(--orange);">
                            Clear filters
                        </a>
                        or submit a quote request.
                    </p>
                    <a href="{{ route('quote.create') }}" class="btn btn-primary" style="margin-top:12px;">
                        <i class="fa-solid fa-file-lines"></i> Request a Quote
                    </a>
                </div>
            @endif

            {{-- Related Equipment Types --}}
            <div style="margin-top:52px;" data-reveal>
                <h2 style="font-family:var(--font-display);font-size:1.4rem;font-weight:700;margin-bottom:16px;">
                    Browse Other Equipment Types
                </h2>
                <div style="display:flex;flex-wrap:wrap;gap:10px;">
                    @foreach ($navEquipmentTypes ?? [] as $et)
                        @if ($et->id !== $equipmentType->id)
                            <a href="{{ route('equipment-types.show', $et->slug) }}" class="cql-btn"
                                style="text-decoration:none;">
                                {{ $et->name }}
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

        </div>
    </div>

@endsection

@push('styles')
    <style>
        .et-filters {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            padding: 16px 20px;
            margin-bottom: 8px;
        }

        .et-filter-group {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            flex-wrap: wrap;
        }

        .et-filter-label {
            font-size: 12px;
            font-weight: 700;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: .07em;
            padding-top: 6px;
            flex-shrink: 0;
            white-space: nowrap;
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
    </script>
@endpush
