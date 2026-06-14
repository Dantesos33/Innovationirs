{{-- resources/views/makes/show.blade.php --}}
@extends('layouts.app')

@section('meta_title', $make->seo_title ?: $make->name . ' Parts — New, Used & Rebuilt | ' .
    config('amsparts.company_name', 'Parts Plus Innovation Solutions'))
@section('meta_description',
    $make->seo_description ?:
    'Shop new, used and rebuilt ' .
    $make->name .
    ' heavy equipment
    parts. Hydraulic pumps, final drives, engines and more. Fast shipping.')
@section('og_image', $make->logo_media?->public_url ?? asset('images/og-default.jpg'))
@section('body_class', 'page-make-detail')

@section('content')

    {{-- Hero --}}
    <div class="page-hero">
        <div class="container">
            @include('partials.breadcrumb', [
                'crumbs' => [
                    ['label' => 'Parts by Make', 'url' => route('makes.index')],
                    ['label' => $make->name, 'url' => null],
                ],
            ])
            <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
                @if ($make->logo_media)
                    <div class="make-hero-logo">
                        <img src="{{ $make->logo_media->public_url }}" alt="{{ $make->name }}">
                    </div>
                @endif
                <div>
                    <div class="page-hero-label">{{ number_format($make->parts_count) }} Parts Available</div>
                    <h1 class="page-hero-title">{{ $make->name }} Parts</h1>
                    @if ($make->description)
                        <p class="page-hero-sub">{{ Str::limit($make->description, 160) }}</p>
                    @else
                        <p class="page-hero-sub">
                            New, used and rebuilt {{ $make->name }} heavy equipment parts.
                            Fast shipping. Expert support.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="section section--warm">
        <div class="container">

            {{-- Categories for this make --}}
            @if ($categories->count())
                <div class="make-category-strip" data-reveal>
                    <div class="make-category-strip-label">Filter by Category:</div>
                    <div class="make-category-pills">
                        <a href="{{ route('makes.show', $make->slug) }}"
                            class="mcp {{ !request('category') ? 'mcp--active' : '' }}">
                            All ({{ number_format($make->parts_count) }})
                        </a>
                        @foreach ($categories as $cat)
                            <a href="{{ route('makes.show', $make->slug) }}?category={{ $cat->id }}"
                                class="mcp {{ request('category') == $cat->id ? 'mcp--active' : '' }}">
                                {{ $cat->name }}
                                <span class="mcp-count">{{ number_format($cat->parts_count) }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Models for this make --}}
            @if ($models->count())
                <details class="make-models-accordion" data-reveal>
                    <summary class="make-models-summary">
                        <i class="fa-solid fa-list-ul"></i>
                        Browse {{ $make->name }} Models ({{ $models->count() }})
                        <i class="fa-solid fa-chevron-down make-models-caret"></i>
                    </summary>
                    <div class="make-models-grid">
                        @foreach ($models as $model)
                            <a href="{{ route('parts.index') }}?make={{ $make->id }}&model={{ $model->id }}"
                                class="make-model-chip">
                                {{ $model->name }}
                                @if ($model->year_range)
                                    <span class="make-model-year">{{ $model->year_range }}</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </details>
            @endif

            {{-- Toolbar --}}
            <div class="catalog-toolbar" style="margin-top:20px;" data-reveal>
                <div class="catalog-count">
                    <strong>{{ number_format($parts->total()) }}</strong> parts found
                    for <strong>{{ $make->name }}</strong>
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
                        → High</option>
                        High → Low</option>
                        <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Name A–Z
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
                        We don't have {{ $make->name }} parts listed in this category right now.
                    </p>
                    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
                        <a href="{{ route('makes.show', $make->slug) }}" class="btn btn-ghost">
                            View All {{ $make->name }} Parts
                        </a>
                        <a href="{{ route('quote.create') }}?make={{ $make->name }}" class="btn btn-primary">
                            <i class="fa-solid fa-file-lines"></i> Request a Quote
                        </a>
                    </div>
                </div>
            @endif

            {{-- Make description (full) --}}
            @if ($make->description && strlen($make->description) > 160)
                <div class="make-description-block" data-reveal>
                    <h2 class="make-desc-title">About {{ $make->name }} Parts</h2>
                    <div class="make-desc-body">
                        {!! nl2br(e($make->description)) !!}
                    </div>
                </div>
            @endif

        </div>
    </div>

@endsection

@push('styles')
    <style>
        .make-hero-logo {
            width: 100px;
            height: 72px;
            flex-shrink: 0;
            background: rgba(255, 255, 255, .08);
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px;
        }

        .make-hero-logo img {
            max-width: 80px;
            max-height: 52px;
            object-fit: contain;
            filter: brightness(0) invert(1);
        }

        .make-category-strip {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            padding: 14px 16px;
            display: flex;
            align-items: flex-start;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .make-category-strip-label {
            font-size: 12px;
            font-weight: 700;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: .07em;
            padding-top: 6px;
            flex-shrink: 0;
            white-space: nowrap;
        }

        .make-category-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .mcp {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: var(--radius-full);
            font-size: 12px;
            font-weight: 600;
            background: var(--gray-100);
            color: var(--gray-700);
            border: 1.5px solid transparent;
            transition: all var(--transition);
        }

        .mcp:hover {
            border-color: var(--orange);
            color: var(--orange);
            background: var(--orange-pale);
        }

        .mcp--active {
            background: var(--orange);
            color: var(--white) !important;
            border-color: var(--orange);
        }

        .mcp-count {
            background: rgba(0, 0, 0, .1);
            border-radius: 10px;
            padding: 0 5px;
            font-size: 10px;
        }

        .mcp--active .mcp-count {
            background: rgba(255, 255, 255, .2);
        }

        .make-models-accordion {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            overflow: hidden;
            margin-bottom: 16px;
        }

        .make-models-summary {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 13px 16px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-700);
            list-style: none;
            transition: background var(--transition);
        }

        .make-models-summary:hover {
            background: var(--gray-50);
        }

        .make-models-summary i:first-child {
            color: var(--orange);
        }

        .make-models-caret {
            margin-left: auto;
            font-size: 10px;
            transition: transform var(--transition);
        }

        details[open] .make-models-caret {
            transform: rotate(180deg);
        }

        .make-models-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            padding: 12px 16px 16px;
            border-top: 1px solid var(--gray-100);
        }

        .make-model-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: var(--radius-sm);
            background: var(--gray-100);
            color: var(--gray-700);
            font-size: 12px;
            font-weight: 500;
            transition: background var(--transition), color var(--transition);
        }

        .make-model-chip:hover {
            background: var(--orange-pale);
            color: var(--orange-dark);
        }

        .make-model-year {
            color: var(--gray-400);
            font-size: 11px;
        }

        .make-description-block {
            margin-top: 48px;
            padding: 28px 32px;
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-xl);
            border-left: 4px solid var(--orange);
        }

        .make-desc-title {
            font-family: var(--font-display);
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 14px;
        }

        .make-desc-body {
            font-size: 14px;
            line-height: 1.8;
            color: var(--gray-700);
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
