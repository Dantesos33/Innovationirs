{{-- resources/views/makes/index.blade.php --}}
@extends('layouts.app')

@section('meta_title', 'Heavy Equipment Parts by Make & Brand | ' . config('amsparts.company_name', 'Parts Plus Innovation Solutions'))
@section('meta_description', 'Browse heavy equipment parts by manufacturer. Shop Caterpillar, Komatsu, John Deere,
    Bobcat, Volvo and 20+ more brands. New, used & rebuilt options.')
@section('body_class', 'page-makes-index')

@section('content')

    {{-- Hero --}}
    <div class="page-hero">
        <div class="container">
            @include('partials.breadcrumb', ['crumbs' => [['label' => 'Parts by Make', 'url' => null]]])
            <div class="page-hero-label">Browse by Brand</div>
            <h1 class="page-hero-title">Heavy Equipment Parts by Make</h1>
            <p class="page-hero-sub">
                Find parts for {{ $makes->count() }}+ major equipment manufacturers.
                New, used and rebuilt options available.
            </p>
        </div>
    </div>

    <div class="section section--warm">
        <div class="container">

            {{-- Alpha Jump --}}
            @php
                $letters = $makes->groupBy(fn($m) => strtoupper(substr($m->name, 0, 1)))->keys()->sort();
            @endphp
            @if ($letters->count() > 4)
                <div class="alpha-jump" aria-label="Jump to letter">
                    @foreach ($letters as $letter)
                        <a href="#letter-{{ $letter }}" class="alpha-jump-btn">{{ $letter }}</a>
                    @endforeach
                </div>
            @endif

            {{-- Makes Grid grouped by first letter --}}
            @php
                $grouped = $makes->groupBy(fn($m) => strtoupper(substr($m->name, 0, 1)))->sortKeys();
            @endphp

            @foreach ($grouped as $letter => $group)
                <div class="makes-letter-group" id="letter-{{ $letter }}">
                    <div class="makes-letter-heading">{{ $letter }}</div>
                    <div class="makes-index-grid">
                        @foreach ($group as $make)
                            <a href="{{ route('makes.show', $make->slug) }}" class="make-index-card"
                                title="Shop {{ $make->name }} Parts">

                                <div class="make-index-logo">
                                    @if ($make->logo_media)
                                        <img src="{{ $make->logo_media->public_url }}" alt="{{ $make->name }}"
                                            loading="lazy">
                                    @else
                                        <span class="make-index-initials">
                                            {{ strtoupper(substr($make->name, 0, 2)) }}
                                        </span>
                                    @endif
                                </div>

                                <div class="make-index-info">
                                    <div class="make-index-name">{{ $make->name }}</div>
                                    <div class="make-index-count">
                                        {{ number_format($make->parts_count) }} parts available
                                    </div>
                                </div>

                                <i class="fa-solid fa-chevron-right make-index-arrow"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach

            {{-- CTA --}}
            <div class="makes-cta-box">
                <div class="makes-cta-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
                <div>
                    <div class="makes-cta-title">Don't see your make?</div>
                    <p class="makes-cta-text">
                        We source parts for virtually every heavy equipment manufacturer.
                        Submit a quote and our specialists will find what you need.
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
        .alpha-jump {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            margin-bottom: 32px;
        }

        .alpha-jump-btn {
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            background: var(--white);
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius);
            color: var(--gray-600);
            transition: all var(--transition);
        }

        .alpha-jump-btn:hover {
            background: var(--orange);
            border-color: var(--orange);
            color: var(--white);
        }

        .makes-letter-group {
            margin-bottom: 40px;
        }

        .makes-letter-heading {
            font-family: var(--font-display);
            font-size: 2rem;
            font-weight: 800;
            color: var(--orange);
            margin-bottom: 14px;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--gray-200);
            line-height: 1;
        }

        .makes-index-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 10px;
        }

        .make-index-card {
            display: flex;
            align-items: center;
            gap: 14px;
            background: var(--white);
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-lg);
            padding: 14px 16px;
            text-decoration: none;
            transition: border-color var(--transition), box-shadow var(--transition), transform var(--transition);
        }

        .make-index-card:hover {
            border-color: var(--orange);
            box-shadow: var(--shadow);
            transform: translateX(3px);
        }

        .make-index-logo {
            width: 52px;
            height: 40px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gray-50);
            border-radius: var(--radius-sm);
            overflow: hidden;
        }

        .make-index-logo img {
            max-width: 44px;
            max-height: 32px;
            object-fit: contain;
        }

        .make-index-initials {
            font-family: var(--font-display);
            font-size: 18px;
            font-weight: 800;
            color: var(--gray-400);
        }

        .make-index-info {
            flex: 1;
            min-width: 0;
        }

        .make-index-name {
            font-family: var(--font-display);
            font-size: 16px;
            font-weight: 700;
            color: var(--ink);
            line-height: 1.2;
        }

        .make-index-count {
            font-size: 11px;
            color: var(--gray-500);
            margin-top: 2px;
        }

        .make-index-arrow {
            color: var(--gray-300);
            font-size: 11px;
            flex-shrink: 0;
            transition: color var(--transition);
        }

        .make-index-card:hover .make-index-arrow {
            color: var(--orange);
        }

        .makes-cta-box {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            background: var(--ink);
            border-radius: var(--radius-xl);
            padding: 28px 32px;
            margin-top: 48px;
        }

        .makes-cta-icon {
            width: 52px;
            height: 52px;
            flex-shrink: 0;
            background: rgba(224, 92, 26, .15);
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--orange);
            font-size: 22px;
        }

        .makes-cta-title {
            font-family: var(--font-display);
            font-size: 20px;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 4px;
        }

        .makes-cta-text {
            font-size: 13px;
            color: var(--gray-500);
            margin: 0;
        }

        .makes-cta-box>a {
            margin-left: auto;
            flex-shrink: 0;
        }

        @media (max-width: 640px) {
            .makes-index-grid {
                grid-template-columns: 1fr;
            }

            .makes-cta-box {
                flex-direction: column;
                text-align: center;
            }

            .makes-cta-box>a {
                margin-left: 0;
            }
        }
    </style>
@endpush
