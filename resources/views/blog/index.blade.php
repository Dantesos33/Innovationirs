{{-- resources/views/blog/index.blade.php --}}
@extends('layouts.app')

@php
    $pageTitle = isset($blogCategory)
        ? $blogCategory->name . ' — Blog'
        : (isset($tag)
            ? '#' . $tag->name . ' — Blog'
            : 'Blog & Industry News');
@endphp

@section('meta_title', $pageTitle . ' | ' . config('amsparts.company_name', 'Parts Plus Innovation Solutions'))
@section('meta_description',
    'Heavy equipment parts tips, maintenance guides, industry news and how-to articles from
    the ' .
    config('amsparts.company_name', 'Parts Plus Innovation Solutions') .
    ' team.')
@section('body_class', 'page-blog-index')

@section('content')

    {{-- Hero --}}
    <div class="page-hero">
        <div class="container">
            @include('partials.breadcrumb', [
                'crumbs' => [
                    [
                        'label' => 'Blog',
                        'url' => isset($blogCategory) || isset($tag) ? route('blog.index') : null,
                    ],
                    ...isset($blogCategory) ? [['label' => $blogCategory->name, 'url' => null]] : [],
                    ...isset($tag) ? [['label' => '#' . $tag->name, 'url' => null]] : [],
                ],
            ])
            <div class="page-hero-label">Knowledge Base</div>
            <h1 class="page-hero-title">{{ $pageTitle }}</h1>
            <p class="page-hero-sub">
                @if (isset($blogCategory))
                    Articles in the <strong style="color:var(--white)">{{ $blogCategory->name }}</strong> category.
                @elseif(isset($tag))
                    Articles tagged <strong style="color:var(--white)">#{{ $tag->name }}</strong>.
                @else
                    Parts tips, maintenance guides, and industry news from our specialists.
                @endif
            </p>
        </div>
    </div>

    <div class="section section--warm">
        <div class="container">
            <div class="blog-layout">

                {{-- ══ Main Feed ══ --}}
                <main class="blog-main">

                    {{-- Search + active filter --}}
                    @if (request('search') || isset($blogCategory) || isset($tag))
                        <div class="blog-active-filter">
                            @if (request('search'))
                                <span>Search: <strong>"{{ request('search') }}"</strong></span>
                                <a href="{{ route('blog.index') }}" class="filter-pill" style="margin-left:8px;">
                                    Clear <i class="fa-solid fa-xmark"></i>
                                </a>
                            @elseif(isset($blogCategory))
                                <span>Category: <strong>{{ $blogCategory->name }}</strong></span>
                                <a href="{{ route('blog.index') }}" class="filter-pill" style="margin-left:8px;">
                                    Clear <i class="fa-solid fa-xmark"></i>
                                </a>
                            @elseif(isset($tag))
                                <span>Tag: <strong>#{{ $tag->name }}</strong></span>
                                <a href="{{ route('blog.index') }}" class="filter-pill" style="margin-left:8px;">
                                    Clear <i class="fa-solid fa-xmark"></i>
                                </a>
                            @endif
                        </div>
                    @endif

                    {{-- Featured post (first post, full-width, only on page 1 with no filter) --}}
                    @if ($posts->currentPage() === 1 && !request('search') && !isset($blogCategory) && !isset($tag) && $posts->count())
                        @php $featured = $posts->first(); @endphp
                        <article class="blog-featured-post card" data-reveal>
                            @if ($featured->featured_image_url)
                                <a href="{{ route('blog.show', $featured->slug) }}" class="blog-featured-img">
                                    <img src="{{ $featured->featured_image_url }}" alt="{{ $featured->title }}"
                                        loading="eager">
                                    <div class="blog-featured-overlay"></div>
                                    <div class="blog-featured-badge">
                                        <i class="fa-solid fa-bookmark"></i> Featured
                                    </div>
                                </a>
                            @endif
                            <div class="blog-featured-body">
                                <div class="blog-featured-meta">
                                    @if ($featured->category)
                                        <a href="{{ route('blog.category', $featured->category->slug) }}"
                                            class="blog-cat-link">{{ $featured->category->name }}</a>
                                    @endif
                                    <span class="blog-meta-dot">·</span>
                                    <time datetime="{{ $featured->published_at?->toIso8601String() }}">
                                        {{ $featured->published_at?->format('M j, Y') }}
                                    </time>
                                    @if ($featured->author)
                                        <span class="blog-meta-dot">·</span>
                                        <span>{{ $featured->author->name }}</span>
                                    @endif
                                </div>
                                <h2 class="blog-featured-title">
                                    <a href="{{ route('blog.show', $featured->slug) }}">{{ $featured->title }}</a>
                                </h2>
                                @if ($featured->excerpt)
                                    <p class="blog-featured-excerpt">{{ Str::limit($featured->excerpt, 200) }}</p>
                                @endif
                                <a href="{{ route('blog.show', $featured->slug) }}" class="btn btn-primary btn-sm">
                                    Read Article <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </article>
                        @php $remainingPosts = $posts->skip(1); @endphp
                    @else
                        @php $remainingPosts = $posts; @endphp
                    @endif

                    {{-- Post Grid --}}
                    @if ($remainingPosts->count())
                        <div class="blog-grid-main">
                            @foreach ($remainingPosts as $i => $post)
                                <article class="blog-card card" data-reveal data-reveal-delay="{{ min($i, 6) * 60 }}">
                                    @if ($post->featured_image_url)
                                        <a href="{{ route('blog.show', $post->slug) }}" class="blog-card-img">
                                            <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}"
                                                loading="lazy">
                                        </a>
                                    @endif
                                    <div class="blog-card-body">
                                        <div class="blog-card-meta">
                                            @if ($post->category)
                                                <a href="{{ route('blog.category', $post->category->slug) }}"
                                                    class="blog-cat-link">{{ $post->category->name }}</a>
                                                <span class="blog-meta-dot">·</span>
                                            @endif
                                            <time datetime="{{ $post->published_at?->toIso8601String() }}">
                                                {{ $post->published_at?->format('M j, Y') }}
                                            </time>
                                        </div>
                                        <h2 class="blog-card-title">
                                            <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                                        </h2>
                                        @if ($post->excerpt)
                                            <p class="blog-card-excerpt">{{ Str::limit($post->excerpt, 110) }}</p>
                                        @endif
                                    </div>
                                    <div class="blog-card-footer">
                                        @if ($post->author)
                                            <span style="font-size:12px;color:var(--gray-500);">
                                                <i class="fa-solid fa-user" style="font-size:10px;"></i>
                                                {{ $post->author->name }}
                                            </span>
                                        @endif
                                        <a href="{{ route('blog.show', $post->slug) }}" class="text-orange"
                                            style="font-weight:600;font-size:12px;">
                                            Read More <i class="fa-solid fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif

                    {{-- Empty state --}}
                    @if ($posts->isEmpty())
                        <div class="empty-state">
                            <div class="empty-state-icon"><i class="fa-solid fa-newspaper"></i></div>
                            <h3 class="empty-state-title">No Articles Found</h3>
                            <p class="empty-state-text">No posts match your current filter. Try browsing all articles.
                            </p>
                            <a href="{{ route('blog.index') }}" class="btn btn-ghost" style="margin-top:8px;">
                                View All Articles
                            </a>
                        </div>
                    @endif

                    {{-- Pagination --}}
                    @if ($posts->hasPages())
                        <div class="pagination-wrap">
                            {{ $posts->onEachSide(2)->links('vendor.pagination.simple-admin') }}
                        </div>
                    @endif

                </main>{{-- /.blog-main --}}

                {{-- ══ Sidebar ══ --}}
                <aside class="blog-sidebar">

                    {{-- Search --}}
                    <div class="blog-sidebar-widget">
                        <div class="blog-widget-title">Search Articles</div>
                        <form action="{{ route('blog.index') }}" method="GET" class="blog-search-form">
                            <div class="blog-search-inner">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search…"
                                    class="blog-search-input" aria-label="Search blog">
                                <button type="submit" class="blog-search-btn">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Categories --}}
                    @if (isset($blogCategories) && $blogCategories->count())
                        <div class="blog-sidebar-widget">
                            <div class="blog-widget-title">Categories</div>
                            <ul class="blog-widget-list">
                                <li>
                                    <a href="{{ route('blog.index') }}"
                                        class="blog-widget-link {{ !isset($blogCategory) && !isset($tag) ? 'blog-widget-link--active' : '' }}">
                                        All Articles
                                        <span class="blog-widget-count">{{ $posts->total() }}</span>
                                    </a>
                                </li>
                                @foreach ($blogCategories as $cat)
                                    <li>
                                        <a href="{{ route('blog.category', $cat->slug) }}"
                                            class="blog-widget-link {{ isset($blogCategory) && $blogCategory->id === $cat->id ? 'blog-widget-link--active' : '' }}">
                                            {{ $cat->name }}
                                            <span class="blog-widget-count">{{ $cat->posts_count }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Recent Posts --}}
                    @if (isset($recentPosts) && $recentPosts->count())
                        <div class="blog-sidebar-widget">
                            <div class="blog-widget-title">Recent Posts</div>
                            <ul class="blog-recent-list">
                                @foreach ($recentPosts as $recent)
                                    <li class="blog-recent-item">
                                        @if ($recent->featured_image_url)
                                            <a href="{{ route('blog.show', $recent->slug) }}" class="blog-recent-img">
                                                <img src="{{ $recent->featured_image_url }}" alt="{{ $recent->title }}"
                                                    loading="lazy">
                                            </a>
                                        @endif
                                        <div class="blog-recent-body">
                                            <a href="{{ route('blog.show', $recent->slug) }}" class="blog-recent-title">
                                                {{ Str::limit($recent->title, 60) }}
                                            </a>
                                            <div class="blog-recent-date">
                                                {{ $recent->published_at?->format('M j, Y') }}
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Popular Tags --}}
                    @if (isset($popularTags) && $popularTags->count())
                        <div class="blog-sidebar-widget">
                            <div class="blog-widget-title">Popular Tags</div>
                            <div class="blog-tags-cloud">
                                @foreach ($popularTags as $blogTag)
                                    <a href="{{ route('blog.tag', $blogTag->slug) }}"
                                        class="blog-tag {{ isset($tag) && $tag->id === $blogTag->id ? 'blog-tag--active' : '' }}">
                                        #{{ $blogTag->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- CTA Widget --}}
                    <div class="blog-sidebar-cta">
                        <div class="blog-cta-icon"><i class="fa-solid fa-bolt"></i></div>
                        <div class="blog-cta-title">Need a Part?</div>
                        <p class="blog-cta-text">
                            Get a free quote from our parts specialists — fast response guaranteed.
                        </p>
                        <a href="{{ route('quote.create') }}" class="btn btn-primary"
                            style="width:100%;justify-content:center;">
                            Get Free Quote
                        </a>
                        @if (config('amsparts.phone_main'))
                            <a href="tel:{{ config('amsparts.phone_main') }}" class="blog-cta-phone">
                                <i class="fa-solid fa-phone"></i>
                                {{ config('amsparts.phone_main') }}
                            </a>
                        @endif
                    </div>

                </aside>{{-- /.blog-sidebar --}}
            </div>{{-- /.blog-layout --}}
        </div>
    </div>

@endsection

@push('styles')
    <style>
        /* ── Blog Layout ──────────────────────────────────────── */
        .blog-layout {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 36px;
            align-items: start;
        }

        /* ── Featured Post ────────────────────────────────────── */
        .blog-featured-post {
            margin-bottom: 28px;
            overflow: hidden;
        }

        .blog-featured-img {
            display: block;
            position: relative;
            aspect-ratio: 16/7;
            overflow: hidden;
            background: var(--gray-100);
        }

        .blog-featured-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .5s var(--ease);
        }

        .blog-featured-post:hover .blog-featured-img img {
            transform: scale(1.03);
        }

        .blog-featured-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(14, 14, 16, .4) 0%, transparent 60%);
        }

        .blog-featured-badge {
            position: absolute;
            top: 14px;
            left: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
            background: var(--orange);
            color: var(--white);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            padding: 5px 12px;
            border-radius: var(--radius-full);
        }

        .blog-featured-body {
            padding: 24px 28px 26px;
        }

        .blog-featured-meta {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 6px;
            font-size: 12px;
            color: var(--gray-500);
            margin-bottom: 10px;
        }

        .blog-featured-title {
            font-family: var(--font-display);
            font-size: clamp(1.4rem, 3vw, 2rem);
            font-weight: 800;
            line-height: 1.15;
            color: var(--ink);
            margin-bottom: 12px;
        }

        .blog-featured-title a {
            color: inherit;
            transition: color var(--transition);
        }

        .blog-featured-title a:hover {
            color: var(--orange);
        }

        .blog-featured-excerpt {
            font-size: 14px;
            color: var(--gray-600);
            line-height: 1.7;
            margin-bottom: 18px;
        }

        /* ── Post Grid ────────────────────────────────────────── */
        .blog-grid-main {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        /* ── Shared Meta ──────────────────────────────────────── */
        .blog-cat-link {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: var(--orange);
            transition: color var(--transition);
        }

        .blog-cat-link:hover {
            color: var(--orange-dark);
        }

        .blog-meta-dot {
            color: var(--gray-300);
        }

        .blog-card-meta {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 5px;
            font-size: 12px;
            color: var(--gray-500);
            margin-bottom: 8px;
        }

        .blog-active-filter {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 20px;
            font-size: 13px;
            color: var(--gray-600);
        }

        /* ── Sidebar ──────────────────────────────────────────── */
        .blog-sidebar {
            position: sticky;
            top: calc(var(--total-header) + 20px);
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .blog-sidebar-widget {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            padding: 20px;
        }

        .blog-widget-title {
            font-family: var(--font-display);
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--ink);
            margin-bottom: 14px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--orange);
            display: inline-block;
        }

        .blog-search-form {}

        .blog-search-inner {
            display: flex;
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-full);
            overflow: hidden;
            transition: border-color var(--transition);
        }

        .blog-search-inner:focus-within {
            border-color: var(--orange);
        }

        .blog-search-input {
            flex: 1;
            height: 38px;
            padding: 0 14px;
            border: none;
            outline: none;
            font-size: 13px;
            font-family: var(--font-body);
            color: var(--gray-900);
            background: transparent;
        }

        .blog-search-btn {
            width: 40px;
            height: 38px;
            flex-shrink: 0;
            background: var(--orange);
            color: var(--white);
            border: none;
            cursor: pointer;
            font-size: 13px;
            transition: background var(--transition);
        }

        .blog-search-btn:hover {
            background: var(--orange-dark);
        }

        .blog-widget-list {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .blog-widget-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 10px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            color: var(--gray-700);
            transition: background var(--transition), color var(--transition);
        }

        .blog-widget-link:hover {
            background: var(--orange-pale);
            color: var(--orange);
        }

        .blog-widget-link--active {
            background: var(--orange-pale);
            color: var(--orange);
            font-weight: 600;
        }

        .blog-widget-count {
            background: var(--gray-100);
            color: var(--gray-500);
            font-size: 11px;
            font-weight: 700;
            padding: 1px 7px;
            border-radius: var(--radius-full);
        }

        .blog-widget-link--active .blog-widget-count {
            background: rgba(224, 92, 26, .15);
            color: var(--orange-dark);
        }

        .blog-recent-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .blog-recent-item {
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .blog-recent-img {
            width: 60px;
            height: 46px;
            flex-shrink: 0;
            border-radius: var(--radius-sm);
            overflow: hidden;
        }

        .blog-recent-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .blog-recent-title {
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-800);
            line-height: 1.35;
            display: block;
            transition: color var(--transition);
        }

        .blog-recent-title:hover {
            color: var(--orange);
        }

        .blog-recent-date {
            font-size: 11px;
            color: var(--gray-400);
            margin-top: 3px;
        }

        .blog-tags-cloud {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .blog-tag {
            display: inline-block;
            padding: 4px 10px;
            background: var(--gray-100);
            color: var(--gray-600);
            border-radius: var(--radius-full);
            font-size: 12px;
            font-weight: 500;
            transition: background var(--transition), color var(--transition);
        }

        .blog-tag:hover {
            background: var(--orange-pale);
            color: var(--orange-dark);
        }

        .blog-tag--active {
            background: var(--orange);
            color: var(--white);
        }

        .blog-sidebar-cta {
            background: var(--ink);
            border-radius: var(--radius-lg);
            padding: 22px 20px;
            text-align: center;
        }

        .blog-cta-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: rgba(224, 92, 26, .2);
            color: var(--orange);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin: 0 auto 12px;
        }

        .blog-cta-title {
            font-family: var(--font-display);
            font-size: 18px;
            font-weight: 800;
            color: var(--white);
            margin-bottom: 8px;
        }

        .blog-cta-text {
            font-size: 13px;
            color: var(--gray-500);
            line-height: 1.5;
            margin-bottom: 16px;
        }

        .blog-cta-phone {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            color: var(--gray-500);
            font-size: 13px;
            margin-top: 12px;
            transition: color var(--transition);
        }

        .blog-cta-phone:hover {
            color: var(--white);
        }

        .blog-cta-phone i {
            color: var(--orange);
        }

        /* ── Responsive ───────────────────────────────────────── */
        @media (max-width: 960px) {
            .blog-layout {
                grid-template-columns: 1fr;
            }

            .blog-sidebar {
                position: static;
            }

            .blog-sidebar-cta {
                display: none;
            }
        }

        @media (max-width: 640px) {
            .blog-grid-main {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush
