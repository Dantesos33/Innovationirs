{{-- resources/views/pages/search.blade.php --}}
@extends('layouts.app')

@section('meta_title', ($q ? 'Search: ' . $q : 'Search Parts') . ' | ' . config('amsparts.company_name', 'Parts Plus
    Innovation Solutions'))
@section('meta_description', 'Search results for "' . $q . '" — heavy equipment parts from ' .
    config('amsparts.company_name', 'Parts Plus Innovation Solutions') . '.')
@section('meta_robots', 'noindex, follow')
@section('body_class', 'page-search')

@section('content')

    {{-- Hero --}}
    <div class="page-hero">
        <div class="container">
            <div class="page-hero-label">Search Results</div>
            @if ($q)
                <h1 class="page-hero-title">
                    Results for <em style="color:var(--orange);">"{{ $q }}"</em>
                </h1>
                <p class="page-hero-sub">
                    @if ($total > 0)
                        Found <strong style="color:var(--white);">{{ number_format($total) }}</strong>
                        result{{ $total !== 1 ? 's' : '' }} matching your search.
                    @else
                        No results found. Try a different search term or browse our inventory.
                    @endif
                </p>
            @else
                <h1 class="page-hero-title">Search Parts</h1>
                <p class="page-hero-sub">Enter a part number, name, make, or description below.</p>
            @endif
        </div>
    </div>

    <div class="section section--warm">
        <div class="container">

            {{-- Search Bar --}}
            <form action="{{ route('search') }}" method="GET" class="search-bar-form">
                <div class="search-bar-inner">
                    <i class="fa-solid fa-magnifying-glass search-bar-icon"></i>
                    <input type="text" name="q" class="search-bar-input" value="{{ $q }}"
                        placeholder="Search by part number, name, make, or description…" autofocus
                        aria-label="Search parts">
                    <button type="submit" class="search-bar-btn">Search</button>
                </div>
            </form>

            @if ($q)

                {{-- ── Parts Results ── --}}
                @if ($parts->count())
                    <div class="search-section">
                        <div class="search-section-header">
                            <h2 class="search-section-title">
                                <i class="fa-solid fa-gear"></i>
                                Parts
                                <span class="search-section-count">{{ number_format($parts->total()) }}</span>
                            </h2>
                            <a href="{{ route('parts.index') }}?search={{ urlencode($q) }}" class="search-see-all">
                                View all in catalog <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>

                        <div class="parts-grid parts-grid--3" id="partsGrid">
                            @foreach ($parts as $i => $part)
                                @include('partials.part-card', [
                                    'part' => $part,
                                    'delay' => min($i, 5) * 60,
                                ])
                            @endforeach
                        </div>

                        @if ($parts->hasPages())
                            <div style="padding:24px 0 8px;">
                                {{ $parts->appends(['q' => $q])->onEachSide(2)->links('vendor.pagination.simple-admin') }}
                            </div>
                        @endif
                    </div>
                @endif

                {{-- ── Blog Results ── --}}
                @if ($posts->count())
                    <div class="search-section" style="margin-top:48px;">
                        <div class="search-section-header">
                            <h2 class="search-section-title">
                                <i class="fa-solid fa-newspaper"></i>
                                Articles &amp; Guides
                                <span class="search-section-count">{{ $posts->count() }}</span>
                            </h2>
                            <a href="{{ route('blog.index') }}?search={{ urlencode($q) }}" class="search-see-all">
                                More articles <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>

                        <div class="search-blog-list">
                            @foreach ($posts as $post)
                                <article class="search-blog-item">
                                    @if ($post->featured_image)
                                        <div class="search-blog-img">
                                            <img src="{{ $post->featured_image }}" alt="{{ $post->title }}"
                                                loading="lazy">
                                        </div>
                                    @endif
                                    <div class="search-blog-body">
                                        @if ($post->category)
                                            <span class="blog-card-cat">{{ $post->category->name }}</span>
                                        @endif
                                        <h3 class="search-blog-title">
                                            <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                                        </h3>
                                        @if ($post->excerpt)
                                            <p class="search-blog-excerpt">{{ Str::limit($post->excerpt, 160) }}</p>
                                        @endif
                                        <div class="search-blog-meta">
                                            <span>{{ $post->published_at?->format('M j, Y') }}</span>
                                            <a href="{{ route('blog.show', $post->slug) }}"
                                                style="color:var(--orange);font-weight:600;">
                                                Read More <i class="fa-solid fa-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- ── Empty State ── --}}
                @if (!$parts->count() && !$posts->count())
                    <div class="empty-state" style="padding: 60px 0;">
                        <div class="empty-state-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
                        <h3 class="empty-state-title">No Results for "{{ $q }}"</h3>
                        <p class="empty-state-text" style="max-width:480px;margin:0 auto 20px;">
                            We couldn't find any parts or articles matching your search.
                            Try using different keywords or a part number.
                        </p>

                        <div style="display:flex;flex-wrap:wrap;gap:12px;justify-content:center;margin-bottom:32px;">
                            <a href="{{ route('parts.index') }}" class="btn btn-primary">
                                <i class="fa-solid fa-magnifying-glass"></i> Browse All Parts
                            </a>
                            <a href="{{ route('quote.create') }}" class="btn btn-outline">
                                <i class="fa-solid fa-file-lines"></i> Request a Quote
                            </a>
                        </div>

                        {{-- Search Tips --}}
                        <div class="search-tips">
                            <div class="search-tips-title">Search Tips</div>
                            <ul class="search-tips-list">
                                <li>Try searching by part number (e.g. <code>7J1234</code>)</li>
                                <li>Use the make name (e.g. <code>Caterpillar</code> or <code>CAT</code>)</li>
                                <li>Search by part type (e.g. <code>hydraulic pump</code> or <code>final drive</code>)</li>
                                <li>Try broader keywords if your specific search returned no results</li>
                            </ul>
                        </div>
                    </div>

                    {{-- Popular Categories --}}
                    <div style="margin-top:48px;">
                        <h3 style="font-family:var(--font-display);font-size:1.4rem;font-weight:700;margin-bottom:16px;">
                            Browse Popular Categories
                        </h3>
                        <div style="display:flex;flex-wrap:wrap;gap:8px;">
                            @foreach ($navCategories ?? [] as $cat)
                                <a href="{{ route('categories.show', $cat->slug) }}" class="badge badge-gray"
                                    style="font-size:13px;padding:7px 14px;">
                                    {{ $cat->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @else
                {{-- No query yet — show suggestions --}}
                <div style="padding:40px 0;">
                    <h3 style="font-family:var(--font-display);font-size:1.6rem;font-weight:700;margin-bottom:8px;">
                        Popular Searches
                    </h3>
                    <p style="color:var(--gray-500);margin-bottom:20px;font-size:14px;">
                        Start typing or try one of these popular searches:
                    </p>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:40px;">
                        @foreach (['Hydraulic Pump', 'Final Drive', 'Engine Parts', 'Undercarriage', 'Radiator', 'Transmission', 'Cylinder Seal Kit', 'Track Chain'] as $term)
                            <a href="{{ route('search') }}?q={{ urlencode($term) }}" class="cql-btn"
                                style="text-decoration:none;">
                                <i class="fa-solid fa-magnifying-glass" style="font-size:11px;"></i>
                                {{ $term }}
                            </a>
                        @endforeach
                    </div>

                    <h3 style="font-family:var(--font-display);font-size:1.4rem;font-weight:700;margin-bottom:16px;">
                        Browse by Make
                    </h3>
                    <div class="makes-grid" style="grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:10px;">
                        @foreach ($navMakes ?? [] as $make)
                            <a href="{{ route('makes.show', $make->slug) }}" class="make-card">
                                @if ($make->logo_media)
                                    <img src="{{ $make->logo_media->public_url }}" alt="{{ $make->name }}"
                                        loading="lazy">
                                @else
                                    <span
                                        style="font-family:var(--font-display);font-size:14px;font-weight:700;color:var(--gray-700);">{{ $make->name }}</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

@endsection

@push('styles')
    <style>
        .search-bar-form {
            margin-bottom: 32px;
        }

        .search-bar-inner {
            display: flex;
            align-items: center;
            background: var(--white);
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-full);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: border-color var(--transition);
        }

        .search-bar-inner:focus-within {
            border-color: var(--orange);
        }

        .search-bar-icon {
            color: var(--gray-400);
            font-size: 15px;
            padding: 0 0 0 20px;
            flex-shrink: 0;
        }

        .search-bar-input {
            flex: 1;
            height: 52px;
            padding: 0 14px;
            background: transparent;
            border: none;
            outline: none;
            font-size: 15px;
            color: var(--gray-900);
            font-family: var(--font-body);
        }

        .search-bar-input::placeholder {
            color: var(--gray-400);
        }

        .search-bar-btn {
            height: 52px;
            padding: 0 24px;
            background: var(--orange);
            color: var(--white);
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            border: none;
            cursor: pointer;
            flex-shrink: 0;
            transition: background var(--transition);
        }

        .search-bar-btn:hover {
            background: var(--orange-dark);
        }

        .search-section {}

        .search-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 2px solid var(--gray-200);
        }

        .search-section-title {
            font-family: var(--font-display);
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--ink);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-section-title i {
            color: var(--orange);
            font-size: 1rem;
        }

        .search-section-count {
            background: var(--orange-pale);
            color: var(--orange-dark);
            font-size: 13px;
            font-weight: 700;
            padding: 2px 10px;
            border-radius: var(--radius-full);
        }

        .search-see-all {
            font-size: 13px;
            color: var(--orange);
            font-weight: 600;
        }

        .search-see-all:hover {
            text-decoration: underline;
        }

        .search-blog-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .search-blog-item {
            display: flex;
            gap: 16px;
            align-items: flex-start;
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            padding: 16px;
            transition: box-shadow var(--transition), border-color var(--transition);
        }

        .search-blog-item:hover {
            box-shadow: var(--shadow);
            border-color: var(--gray-300);
        }

        .search-blog-img {
            width: 120px;
            flex-shrink: 0;
            border-radius: var(--radius);
            overflow: hidden;
        }

        .search-blog-img img {
            width: 100%;
            aspect-ratio: 4/3;
            object-fit: cover;
        }

        .search-blog-body {
            flex: 1;
            min-width: 0;
        }

        .search-blog-title {
            font-family: var(--font-display);
            font-size: 18px;
            font-weight: 700;
            line-height: 1.25;
            margin: 6px 0 8px;
        }

        .search-blog-title a {
            color: var(--ink);
        }

        .search-blog-title a:hover {
            color: var(--orange);
        }

        .search-blog-excerpt {
            font-size: 13px;
            color: var(--gray-600);
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .search-blog-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12px;
            color: var(--gray-500);
        }

        .search-tips {
            background: var(--amber-pale);
            border: 1px solid #FDE68A;
            border-radius: var(--radius-lg);
            padding: 20px 24px;
            max-width: 520px;
            margin: 0 auto;
            text-align: left;
        }

        .search-tips-title {
            font-weight: 700;
            color: #92400E;
            margin-bottom: 10px;
        }

        .search-tips-list {
            padding-left: 18px;
            color: #92400E;
            font-size: 13px;
        }

        .search-tips-list li {
            margin-bottom: 6px;
        }

        .search-tips-list code {
            background: rgba(0, 0, 0, .06);
            padding: 1px 5px;
            border-radius: 3px;
            font-family: monospace;
            font-size: 12px;
        }

        @media (max-width: 600px) {
            .search-blog-img {
                display: none;
            }

            .parts-grid.parts-grid--3 {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
@endpush


{{-- ══════════════════════════════════════════════════════════════
     BREADCRUMB PARTIAL
     resources/views/partials/breadcrumb.blade.php
══════════════════════════════════════════════════════════════ --}}
{{--
Usage:
@include('partials.breadcrumb', ['crumbs' => [
    ['label' => 'Parts',          'url' => route('parts.index')],
    ['label' => 'Hydraulic Pumps','url' => route('categories.show','hydraulic-pumps')],
    ['label' => 'CAT 320D Pump',  'url' => null],   ← null = current page
]])
--}}
