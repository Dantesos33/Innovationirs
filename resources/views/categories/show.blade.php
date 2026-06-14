{{-- resources/views/categories/show.blade.php --}}
@extends('layouts.app')

@section('meta_title', ($category->seo_title ?: $category->name . ' — Heavy Equipment Parts') . ' | ' .
    config('amsparts.company_name', 'Parts Plus Innovation Solutions'))
@section('meta_description',
    $category->seo_description ?:
    'Shop new, used and rebuilt ' .
    $category->name .
    ' for all
    major heavy equipment makes. Fast shipping. Expert support.')
@section('body_class', 'page-category-detail')

@section('content')

    {{-- Hero --}}
    <div class="page-hero">
        <div class="container">
            @include('partials.breadcrumb', [
                'crumbs' => [
                    ['label' => 'Part Categories', 'url' => route('categories.index')],
                    ['label' => $category->name, 'url' => null],
                ],
            ])
            <div class="page-hero-label">{{ number_format($category->parts_count) }} Parts Available</div>
            <h1 class="page-hero-title">{{ $category->name }}</h1>
            @if ($category->description)
                <p class="page-hero-sub">{{ Str::limit($category->description, 200) }}</p>
            @else
                <p class="page-hero-sub">
                    New, used and rebuilt {{ $category->name }} for all major heavy equipment makes.
                    Fast shipping. Expert support.
                </p>
            @endif
        </div>
    </div>

    <div class="section section--warm">
        <div class="container">

            {{-- Make filter pills --}}
            @if ($makes->count())
                <div class="make-category-strip" style="margin-bottom:20px;" data-reveal>
                    <div class="make-category-strip-label">Filter by Make:</div>
                    <div class="make-category-pills">
                        <a href="{{ route('categories.show', $category->slug) }}"
                            class="mcp {{ !request('make') ? 'mcp--active' : '' }}">
                            All Makes
                        </a>
                        @foreach ($makes as $make)
                            <a href="{{ route('categories.show', $category->slug) }}?make={{ $make->id }}"
                                class="mcp {{ request('make') == $make->id ? 'mcp--active' : '' }}">
                                {{ $make->name }}
                                <span class="mcp-count">{{ number_format($make->parts_count) }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Condition filter + sort toolbar --}}
            <div class="catalog-toolbar" data-reveal>
                <div class="catalog-count">
                    <strong>{{ number_format($parts->total()) }}</strong> {{ $category->name }}
                    @if (request('make'))
                        for <strong>{{ $makes->firstWhere('id', request('make'))?->name }}</strong>
                    @endif
                </div>
                <div class="catalog-toolbar-right">
                    {{-- Condition quick filter --}}
                    <div class="condition-quick-links" style="margin:0;">
                        <a href="{{ route('categories.show', $category->slug) }}{{ count(request()->except('type')) ? '?' . http_build_query(request()->except('type')) : '' }}"
                            class="cql-btn {{ !request('type') ? 'cql-btn--active' : '' }}"
                            style="height:36px;font-size:11px;padding:0 12px;">All</a>
                        @foreach (['new' => 'New', 'used' => 'Used', 'rebuilt' => 'Rebuilt'] as $val => $label)
                            <a href="{{ route('categories.show', $category->slug) }}?{{ http_build_query(array_merge(request()->except('type', 'page'), ['type' => $val])) }}"
                                class="cql-btn {{ request('type') === $val ? 'cql-btn--active' : '' }}"
                                style="height:36px;font-size:11px;padding:0 12px;">{{ $label }}</a>
                        @endforeach
                    </div>
                    <select class="form-control" style="height:36px;font-size:12px;width:150px;"
                        onchange="applyCatalogSort(this.value)">
                        <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Newest
                        </option>
                        <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Popular
                        </option>
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
                        No {{ $category->name }} matching your filters.
                        @if (request('make') || request('type'))
                            <a href="{{ route('categories.show', $category->slug) }}" style="color:var(--orange);">Clear
                                filters</a>
                            or
                        @endif
                        submit a quote and we'll source it.
                    </p>
                    <a href="{{ route('quote.create') }}" class="btn btn-primary" style="margin-top:12px;">
                        <i class="fa-solid fa-file-lines"></i> Request a Quote
                    </a>
                </div>
            @endif

            {{-- Category description --}}
            @if ($category->description && strlen($category->description) > 200)
                <div class="make-description-block" style="margin-top:48px;" data-reveal>
                    <h2 class="make-desc-title">About {{ $category->name }}</h2>
                    <div class="make-desc-body">{!! nl2br(e($category->description)) !!}</div>
                </div>
            @endif

        </div>
    </div>

@endsection

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
