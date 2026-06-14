{{-- resources/views/blog/show.blade.php --}}
@extends('layouts.app')

@section('meta_title', ($post->meta_title ?: $post->title) . ' | ' . config('amsparts.company_name', 'Parts Plus
    Innovation Solutions'))
@section('meta_description', $post->meta_description ?: $post->excerpt ?: Str::limit(strip_tags($post->content), 160))
@section('og_image', $post->featured_image_url ?? asset('images/og-default.jpg'))
@section('body_class', 'page-blog-detail')

@push('head')
    <script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BlogPosting",
    "headline": "{{ addslashes($post->title) }}",
    "datePublished": "{{ $post->published_at?->toIso8601String() }}",
    "dateModified": "{{ $post->updated_at?->toIso8601String() }}",
    "author": {
        "@type": "Person",
        "name": "{{ addslashes($post->author?->name ?? config('amsparts.company_name')) }}"
    },
    "publisher": {
        "@type": "Organization",
        "name": "{{ config('amsparts.company_name') }}",
        "url": "{{ url('/') }}"
    },
    "description": "{{ addslashes($post->excerpt ?? '') }}",
    "image": "{{ $post->featured_image_url ?? asset('images/og-default.jpg') }}"
}
</script>
@endpush

@section('content')

    {{-- Hero / Featured image --}}
    @if ($post->featured_image_url)
        <div class="blog-post-hero">
            <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" loading="eager" class="blog-post-hero-img">
            <div class="blog-post-hero-overlay"></div>
            <div class="container blog-post-hero-inner">
                @include('partials.breadcrumb', [
                    'crumbs' => [
                        ['label' => 'Blog', 'url' => route('blog.index')],
                        [
                            'label' => $post->category?->name ?? 'Article',
                            'url' => $post->category ? route('blog.category', $post->category->slug) : null,
                        ],
                        ['label' => Str::limit($post->title, 40), 'url' => null],
                    ],
                ])
                <div class="blog-post-meta-row">
                    @if ($post->category)
                        <a href="{{ route('blog.category', $post->category->slug) }}" class="blog-cat-link"
                            style="color:var(--orange);">
                            {{ $post->category->name }}
                        </a>
                        <span class="blog-meta-dot" style="color:rgba(255,255,255,.4);">·</span>
                    @endif
                    <time datetime="{{ $post->published_at?->toIso8601String() }}"
                        style="color:rgba(255,255,255,.7);font-size:13px;">
                        {{ $post->published_at?->format('F j, Y') }}
                    </time>
                    @if ($post->author)
                        <span class="blog-meta-dot" style="color:rgba(255,255,255,.4);">·</span>
                        <span style="color:rgba(255,255,255,.7);font-size:13px;">{{ $post->author->name }}</span>
                    @endif
                </div>
                <h1 class="blog-post-hero-title">{{ $post->title }}</h1>
            </div>
        </div>
    @else
        <div class="page-hero">
            <div class="container">
                @include('partials.breadcrumb', [
                    'crumbs' => [
                        ['label' => 'Blog', 'url' => route('blog.index')],
                        [
                            'label' => $post->category?->name ?? 'Article',
                            'url' => $post->category ? route('blog.category', $post->category->slug) : null,
                        ],
                        ['label' => Str::limit($post->title, 40), 'url' => null],
                    ],
                ])
                <div class="blog-post-meta-row" style="margin-bottom:12px;">
                    @if ($post->category)
                        <a href="{{ route('blog.category', $post->category->slug) }}"
                            class="blog-cat-link">{{ $post->category->name }}</a>
                        <span class="blog-meta-dot">·</span>
                    @endif
                    <time datetime="{{ $post->published_at?->toIso8601String() }}"
                        style="font-size:13px;color:var(--gray-400);">
                        {{ $post->published_at?->format('F j, Y') }}
                    </time>
                    @if ($post->author)
                        <span class="blog-meta-dot">·</span>
                        <span style="font-size:13px;color:var(--gray-400);">{{ $post->author->name }}</span>
                    @endif
                </div>
                <h1 class="page-hero-title" style="max-width:720px;">{{ $post->title }}</h1>
            </div>
        </div>
    @endif

    {{-- Main content area --}}
    <div class="section section--warm">
        <div class="container">
            <div class="blog-post-layout">

                {{-- ══ Article ══ --}}
                <article class="blog-post-article" itemscope itemtype="https://schema.org/BlogPosting">

                    {{-- Excerpt / intro --}}
                    @if ($post->excerpt)
                        <div class="blog-post-excerpt">{{ $post->excerpt }}</div>
                    @endif

                    {{-- Estimated read time --}}
                    @php
                        $wordCount = str_word_count(strip_tags($post->content ?? ''));
                        $readTime = max(1, round($wordCount / 200));
                    @endphp
                    <div class="blog-post-read-time">
                        <i class="fa-regular fa-clock"></i>
                        {{ $readTime }} min read &nbsp;·&nbsp; {{ number_format($wordCount) }} words
                        @if ($post->views)
                            &nbsp;·&nbsp; <i class="fa-regular fa-eye"></i> {{ number_format($post->views) }} views
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="blog-post-content prose" itemprop="articleBody">
                        {!! $post->content !!}
                    </div>

                    {{-- Tags --}}
                    @if ($post->tags->count())
                        <div class="blog-post-tags">
                            <span
                                style="font-size:12px;font-weight:700;color:var(--gray-500);text-transform:uppercase;letter-spacing:.07em;">Tags:</span>
                            @foreach ($post->tags as $t)
                                <a href="{{ route('blog.tag', $t->slug) }}" class="blog-tag">
                                    #{{ $t->name }}
                                </a>
                            @endforeach
                        </div>
                    @endif

                    {{-- Share strip --}}
                    <div class="blog-post-share">
                        <div class="blog-share-label">Share this article:</div>
                        <div class="blog-share-btns">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                target="_blank" rel="noopener" class="blog-share-btn blog-share-btn--fb"
                                aria-label="Share on Facebook">
                                <i class="fa-brands fa-facebook-f"></i> Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}"
                                target="_blank" rel="noopener" class="blog-share-btn blog-share-btn--tw"
                                aria-label="Share on X/Twitter">
                                <i class="fa-brands fa-x-twitter"></i> X / Twitter
                            </a>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode($post->title) }}"
                                target="_blank" rel="noopener" class="blog-share-btn blog-share-btn--li"
                                aria-label="Share on LinkedIn">
                                <i class="fa-brands fa-linkedin-in"></i> LinkedIn
                            </a>
                            <button class="blog-share-btn blog-share-btn--copy" onclick="copyPostUrl(this)"
                                aria-label="Copy link">
                                <i class="fa-solid fa-link"></i> Copy Link
                            </button>
                        </div>
                    </div>

                    {{-- Author bio --}}
                    @if ($post->author)
                        <div class="blog-author-card">
                            <div class="blog-author-avatar">
                                @if ($post->author->avatar)
                                    <img src="{{ $post->author->avatar }}" alt="{{ $post->author->name }}">
                                @else
                                    <span>{{ strtoupper(substr($post->author->name, 0, 1)) }}</span>
                                @endif
                            </div>
                            <div>
                                <div class="blog-author-name">{{ $post->author->name }}</div>
                                @if ($post->author->bio)
                                    <div class="blog-author-bio">{{ $post->author->bio }}</div>
                                @else
                                    <div class="blog-author-bio">Parts specialist at
                                        {{ config('amsparts.company_name', 'Parts Plus Innovation Solutions') }}.</div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Prev / Next nav --}}
                    @if ($prev || $next)
                        <nav class="blog-post-nav" aria-label="Post navigation">
                            <div class="blog-post-nav-prev">
                                @if ($prev)
                                    <a href="{{ route('blog.show', $prev->slug) }}" class="blog-post-nav-link">
                                        <i class="fa-solid fa-arrow-left"></i>
                                        <div>
                                            <div class="blog-post-nav-dir">Previous</div>
                                            <div class="blog-post-nav-title">{{ Str::limit($prev->title, 55) }}</div>
                                        </div>
                                    </a>
                                @endif
                            </div>
                            <div class="blog-post-nav-next">
                                @if ($next)
                                    <a href="{{ route('blog.show', $next->slug) }}"
                                        class="blog-post-nav-link blog-post-nav-link--right">
                                        <div>
                                            <div class="blog-post-nav-dir">Next</div>
                                            <div class="blog-post-nav-title">{{ Str::limit($next->title, 55) }}</div>
                                        </div>
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                @endif
                            </div>
                        </nav>
                    @endif

                </article>{{-- /.blog-post-article --}}

                {{-- ══ Sidebar ══ --}}
                <aside class="blog-sidebar">

                    {{-- Inline CTA --}}
                    <div class="blog-sidebar-cta">
                        <div class="blog-cta-icon"><i class="fa-solid fa-bolt"></i></div>
                        <div class="blog-cta-title">Need a Part?</div>
                        <p class="blog-cta-text">
                            Get a free quote from our specialists — fast response guaranteed.
                        </p>
                        <a href="{{ route('quote.create') }}" class="btn btn-primary"
                            style="width:100%;justify-content:center;">
                            Get Free Quote
                        </a>
                        @if (config('amsparts.phone_main'))
                            <a href="tel:{{ config('amsparts.phone_main') }}" class="blog-cta-phone">
                                <i class="fa-solid fa-phone"></i> {{ config('amsparts.phone_main') }}
                            </a>
                        @endif
                    </div>

                    {{-- Recent posts --}}
                    @if ($recentPosts->count())
                        <div class="blog-sidebar-widget">
                            <div class="blog-widget-title">Recent Articles</div>
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
                                            <div class="blog-recent-date">{{ $recent->published_at?->format('M j, Y') }}
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Tags --}}
                    @if ($popularTags->count())
                        <div class="blog-sidebar-widget">
                            <div class="blog-widget-title">Topics</div>
                            <div class="blog-tags-cloud">
                                @foreach ($popularTags as $t)
                                    <a href="{{ route('blog.tag', $t->slug) }}" class="blog-tag">
                                        #{{ $t->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </aside>

            </div>{{-- /.blog-post-layout --}}

            {{-- Related posts --}}
            @if ($related->count())
                <div style="margin-top:52px;" data-reveal>
                    <div class="section-header" style="text-align:left;max-width:none;margin-bottom:24px;">
                        <span class="section-label">Keep Reading</span>
                        <h2 style="font-size:1.6rem;">Related Articles</h2>
                    </div>
                    <div class="blog-grid"
                        style="grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px;display:grid;">
                        @foreach ($related as $i => $rp)
                            <article class="blog-card card" data-reveal data-reveal-delay="{{ $i * 80 }}">
                                @if ($rp->featured_image_url)
                                    <a href="{{ route('blog.show', $rp->slug) }}" class="blog-card-img">
                                        <img src="{{ $rp->featured_image_url }}" alt="{{ $rp->title }}"
                                            loading="lazy">
                                    </a>
                                @endif
                                <div class="blog-card-body">
                                    <div class="blog-card-meta">
                                        @if ($rp->category)
                                            <a href="{{ route('blog.category', $rp->category->slug) }}"
                                                class="blog-cat-link">{{ $rp->category->name }}</a>
                                            <span class="blog-meta-dot">·</span>
                                        @endif
                                        <time>{{ $rp->published_at?->format('M j, Y') }}</time>
                                    </div>
                                    <h2 class="blog-card-title">
                                        <a href="{{ route('blog.show', $rp->slug) }}">{{ $rp->title }}</a>
                                    </h2>
                                    @if ($rp->excerpt)
                                        <p class="blog-card-excerpt">{{ Str::limit($rp->excerpt, 100) }}</p>
                                    @endif
                                </div>
                                <div class="blog-card-footer">
                                    <span></span>
                                    <a href="{{ route('blog.show', $rp->slug) }}" class="text-orange"
                                        style="font-weight:600;font-size:12px;">
                                        Read More <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

@endsection

@push('styles')
    <style>
        /* ── Hero ─────────────────────────────────────────────── */
        .blog-post-hero {
            position: relative;
            min-height: 420px;
            display: flex;
            align-items: flex-end;
            overflow: hidden;
        }

        .blog-post-hero-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .blog-post-hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(14, 14, 16, .92) 0%, rgba(14, 14, 16, .55) 50%, rgba(14, 14, 16, .2) 100%);
        }

        .blog-post-hero-inner {
            position: relative;
            z-index: 2;
            padding-bottom: 40px;
        }

        .blog-post-hero-title {
            font-family: var(--font-display);
            font-size: clamp(1.8rem, 4vw, 3rem);
            font-weight: 800;
            color: var(--white);
            line-height: 1.1;
            max-width: 780px;
            margin-top: 10px;
        }

        .blog-post-meta-row {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 8px;
        }

        /* ── Article ──────────────────────────────────────────── */
        .blog-post-layout {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 40px;
            align-items: start;
        }

        .blog-post-excerpt {
            font-size: 17px;
            color: var(--gray-600);
            line-height: 1.75;
            font-style: italic;
            border-left: 4px solid var(--orange);
            padding: 14px 20px;
            background: var(--orange-pale);
            border-radius: 0 var(--radius) var(--radius) 0;
            margin-bottom: 20px;
        }

        .blog-post-read-time {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--gray-400);
            margin-bottom: 28px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--gray-200);
        }

        .blog-post-read-time i {
            color: var(--orange);
        }

        /* ── Prose / article body ─────────────────────────────── */
        .prose {
            font-size: 16px;
            line-height: 1.85;
            color: var(--gray-700);
        }

        .prose h2 {
            font-family: var(--font-display);
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--ink);
            margin: 2em 0 .6em;
            line-height: 1.15;
        }

        .prose h3 {
            font-family: var(--font-display);
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--ink);
            margin: 1.6em 0 .5em;
        }

        .prose h4 {
            font-weight: 700;
            color: var(--ink);
            margin: 1.2em 0 .4em;
        }

        .prose p {
            margin-bottom: 1.2em;
        }

        .prose a {
            color: var(--orange);
            font-weight: 500;
            text-decoration: underline;
            text-decoration-thickness: 1px;
            text-underline-offset: 2px;
        }

        .prose a:hover {
            color: var(--orange-dark);
        }

        .prose strong {
            color: var(--gray-900);
            font-weight: 700;
        }

        .prose ul,
        .prose ol {
            padding-left: 1.5em;
            margin-bottom: 1.2em;
        }

        .prose li {
            margin-bottom: .5em;
        }

        .prose blockquote {
            border-left: 4px solid var(--orange);
            padding: 14px 20px;
            background: var(--orange-pale);
            border-radius: 0 var(--radius) var(--radius) 0;
            margin: 1.5em 0;
            font-style: italic;
            color: var(--gray-600);
        }

        .prose img {
            max-width: 100%;
            height: auto;
            border-radius: var(--radius-lg);
            margin: 1.5em 0;
            box-shadow: var(--shadow);
        }

        .prose table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.5em 0;
            font-size: 14px;
        }

        .prose th,
        .prose td {
            padding: 10px 14px;
            border: 1px solid var(--gray-200);
            text-align: left;
        }

        .prose th {
            background: var(--gray-50);
            font-weight: 700;
            color: var(--ink);
        }

        .prose tr:nth-child(even) td {
            background: var(--gray-50);
        }

        .prose pre {
            background: var(--ink);
            color: #e2e8f0;
            padding: 20px;
            border-radius: var(--radius-lg);
            overflow-x: auto;
            font-size: 13px;
            margin: 1.5em 0;
        }

        .prose code {
            font-size: .9em;
        }

        .prose hr {
            border: none;
            border-top: 2px solid var(--gray-200);
            margin: 2em 0;
        }

        /* ── Tags ─────────────────────────────────────────────── */
        .blog-post-tags {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid var(--gray-200);
        }

        /* ── Share ────────────────────────────────────────────── */
        .blog-post-share {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 24px;
            padding: 20px;
            background: var(--gray-50);
            border-radius: var(--radius-lg);
            border: 1px solid var(--gray-200);
        }

        .blog-share-label {
            font-size: 13px;
            font-weight: 700;
            color: var(--gray-600);
        }

        .blog-share-btns {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .blog-share-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: var(--radius-sm);
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: opacity var(--transition), transform var(--transition);
        }

        .blog-share-btn:hover {
            opacity: .85;
            transform: translateY(-1px);
        }

        .blog-share-btn--fb {
            background: #1877F2;
            color: #fff;
        }

        .blog-share-btn--tw {
            background: #000;
            color: #fff;
        }

        .blog-share-btn--li {
            background: #0A66C2;
            color: #fff;
        }

        .blog-share-btn--copy {
            background: var(--gray-200);
            color: var(--gray-700);
        }

        /* ── Author ───────────────────────────────────────────── */
        .blog-author-card {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            margin-top: 32px;
            padding: 20px 24px;
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
        }

        .blog-author-avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            flex-shrink: 0;
            overflow: hidden;
            background: var(--orange);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-display);
            font-size: 22px;
            font-weight: 800;
            color: var(--white);
        }

        .blog-author-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .blog-author-name {
            font-weight: 700;
            font-size: 15px;
            color: var(--ink);
            margin-bottom: 4px;
        }

        .blog-author-bio {
            font-size: 13px;
            color: var(--gray-500);
            line-height: 1.6;
        }

        /* ── Prev / Next ──────────────────────────────────────── */
        .blog-post-nav {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-top: 36px;
            padding-top: 24px;
            border-top: 1px solid var(--gray-200);
        }

        .blog-post-nav-prev {}

        .blog-post-nav-next {
            text-align: right;
        }

        .blog-post-nav-link {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--gray-700);
            padding: 14px 16px;
            background: var(--white);
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-lg);
            transition: border-color var(--transition), box-shadow var(--transition);
            width: 100%;
        }

        .blog-post-nav-link:hover {
            border-color: var(--orange);
            box-shadow: var(--shadow);
        }

        .blog-post-nav-link--right {
            justify-content: flex-end;
        }

        .blog-post-nav-link i {
            color: var(--orange);
            flex-shrink: 0;
        }

        .blog-post-nav-dir {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--gray-400);
            margin-bottom: 3px;
        }

        .blog-post-nav-title {
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-800);
            line-height: 1.3;
        }

        /* ── Responsive ───────────────────────────────────────── */
        @media (max-width: 960px) {
            .blog-post-layout {
                grid-template-columns: 1fr;
            }

            .blog-sidebar {
                display: none;
            }

            .blog-post-hero {
                min-height: 300px;
            }
        }

        @media (max-width: 640px) {
            .blog-post-nav {
                grid-template-columns: 1fr;
            }

            .blog-share-label {
                display: none;
            }

            .prose h2 {
                font-size: 1.3rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function copyPostUrl(btn) {
            navigator.clipboard.writeText(window.location.href).then(() => {
                const orig = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-check"></i> Copied!';
                btn.style.background = '#22C55E';
                btn.style.color = '#fff';
                setTimeout(() => {
                    btn.innerHTML = orig;
                    btn.style.background = '';
                    btn.style.color = '';
                }, 2000);
            });
        }
    </script>
@endpush
