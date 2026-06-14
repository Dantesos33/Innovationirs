{{-- resources/views/categories/index.blade.php --}}
@extends('layouts.app')

@section('meta_title', 'Heavy Equipment Part Categories | ' . config('amsparts.company_name', 'Parts Plus Innovation Solutions'))
@section('meta_description', 'Browse heavy equipment parts by category. Hydraulic pumps, final drives, engines,
    undercarriages, transmissions and more. New, used & rebuilt.')
@section('body_class', 'page-categories-index')

@section('content')

    {{-- Hero --}}
    <div class="page-hero">
        <div class="container">
            @include('partials.breadcrumb', ['crumbs' => [['label' => 'Part Categories', 'url' => null]]])
            <div class="page-hero-label">Browse by Category</div>
            <h1 class="page-hero-title">Heavy Equipment Part Categories</h1>
            <p class="page-hero-sub">
                We carry {{ $categories->count() }} part categories for all major makes and equipment types.
            </p>
        </div>
    </div>

    <div class="section section--warm">
        <div class="container">

            {{-- Search within categories --}}
            <div class="cat-search-bar" data-reveal>
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="catFilterInput" placeholder="Filter categories…" class="cat-search-input"
                    aria-label="Filter categories">
            </div>

            {{-- Categories Grid --}}
            <div class="cat-index-grid" id="catIndexGrid" data-reveal>
                @foreach ($categories as $i => $cat)
                    <a href="{{ route('categories.show', $cat->slug) }}" class="cat-index-card"
                        data-name="{{ strtolower($cat->name) }}" data-reveal data-reveal-delay="{{ min($i, 12) * 40 }}">

                        <div class="cat-index-img-wrap">
                            @if ($cat->image_media)
                                <img src="{{ $cat->image_media->public_url }}" alt="{{ $cat->name }}" loading="lazy">
                            @else
                                <div class="cat-index-icon">
                                    <i class="fa-solid fa-gears"></i>
                                </div>
                            @endif
                        </div>

                        <div class="cat-index-body">
                            <h2 class="cat-index-name">{{ $cat->name }}</h2>
                            @if ($cat->description)
                                <p class="cat-index-desc">{{ Str::limit($cat->description, 80) }}</p>
                            @endif
                            <div class="cat-index-footer">
                                <span class="cat-index-count">
                                    <i class="fa-solid fa-gear"></i>
                                    {{ number_format($cat->parts_count) }} parts
                                </span>
                                <span class="cat-index-cta">
                                    Shop Now <i class="fa-solid fa-arrow-right"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- No results message (hidden by default) --}}
            <div class="cat-no-results" id="catNoResults" style="display:none;">
                <i class="fa-solid fa-circle-info"></i>
                No categories match "<span id="catNoResultsTerm"></span>".
                <a href="{{ route('quote.create') }}" style="color:var(--orange);">Request a quote</a> and we'll source it.
            </div>

            {{-- Can't find CTA --}}
            <div class="makes-cta-box" style="margin-top:48px;" data-reveal>
                <div class="makes-cta-icon"><i class="fa-solid fa-circle-question"></i></div>
                <div>
                    <div class="makes-cta-title">Looking for something specific?</div>
                    <p class="makes-cta-text">
                        Our parts specialists can source virtually any component.
                        Submit a quote request with your part details.
                    </p>
                </div>
                <a href="{{ route('quote.create') }}" class="btn btn-primary">
                    <i class="fa-solid fa-file-lines"></i> Request a Quote
                </a>
            </div>

        </div>
    </div>

@endsection

@push('styles')
    <style>
        .cat-search-bar {
            display: flex;
            align-items: center;
            gap: 12px;
            background: var(--white);
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-full);
            padding: 0 18px;
            margin-bottom: 28px;
            transition: border-color var(--transition);
            max-width: 480px;
        }

        .cat-search-bar:focus-within {
            border-color: var(--orange);
        }

        .cat-search-bar i {
            color: var(--gray-400);
        }

        .cat-search-input {
            flex: 1;
            height: 46px;
            border: none;
            outline: none;
            font-size: 14px;
            font-family: var(--font-body);
            color: var(--gray-900);
            background: transparent;
        }

        .cat-search-input::placeholder {
            color: var(--gray-400);
        }

        .cat-index-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 18px;
        }

        .cat-index-card {
            display: flex;
            flex-direction: column;
            background: var(--white);
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-xl);
            overflow: hidden;
            text-decoration: none;
            transition: box-shadow var(--transition-md), transform var(--transition-md), border-color var(--transition-md);
        }

        .cat-index-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-4px);
            border-color: var(--orange);
        }

        .cat-index-img-wrap {
            aspect-ratio: 16/9;
            overflow: hidden;
            background: var(--gray-100);
        }

        .cat-index-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .4s var(--ease);
        }

        .cat-index-card:hover .cat-index-img-wrap img {
            transform: scale(1.05);
        }

        .cat-index-icon {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: var(--gray-300);
        }

        .cat-index-body {
            padding: 18px 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .cat-index-name {
            font-family: var(--font-display);
            font-size: 19px;
            font-weight: 700;
            color: var(--ink);
            line-height: 1.15;
        }

        .cat-index-desc {
            font-size: 13px;
            color: var(--gray-500);
            line-height: 1.5;
            flex: 1;
        }

        .cat-index-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 10px;
            padding-top: 12px;
            border-top: 1px solid var(--gray-100);
        }

        .cat-index-count {
            font-size: 12px;
            color: var(--gray-500);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .cat-index-count i {
            color: var(--orange);
            font-size: 11px;
        }

        .cat-index-cta {
            font-size: 12px;
            font-weight: 700;
            color: var(--orange);
            display: flex;
            align-items: center;
            gap: 5px;
            transition: gap var(--transition);
        }

        .cat-index-card:hover .cat-index-cta {
            gap: 8px;
        }

        .cat-no-results {
            text-align: center;
            padding: 32px;
            color: var(--gray-500);
            font-size: 14px;
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
        }

        .cat-no-results i {
            color: var(--orange);
            margin-right: 6px;
        }

        @media (max-width: 640px) {
            .cat-index-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .cat-index-name {
                font-size: 15px;
            }
        }

        @media (max-width: 400px) {
            .cat-index-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Live category filter
        const catInput = document.getElementById('catFilterInput');
        const catGrid = document.getElementById('catIndexGrid');
        const catNoRes = document.getElementById('catNoResults');
        const catNoTerm = document.getElementById('catNoResultsTerm');

        catInput?.addEventListener('input', function() {
            const term = this.value.toLowerCase().trim();
            const cards = catGrid.querySelectorAll('.cat-index-card');
            let visible = 0;

            cards.forEach(card => {
                const name = card.dataset.name || '';
                const show = !term || name.includes(term);
                card.style.display = show ? '' : 'none';
                if (show) visible++;
            });

            if (catNoRes) {
                catNoRes.style.display = (visible === 0 && term) ? '' : 'none';
                if (catNoTerm) catNoTerm.textContent = term;
            }
        });
    </script>
@endpush
