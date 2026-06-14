{{-- resources/views/equipment-types/index.blade.php --}}
@extends('layouts.app')

@section('meta_title', 'Heavy Equipment Parts by Equipment Type | ' . config('amsparts.company_name', 'Parts Plus
    Innovation Solutions'))
@section('meta_description',
    'Find parts for excavators, backhoes, bulldozers, wheel loaders, skid steers and more.
    Browse by equipment type to find exactly what your machine needs.')
@section('body_class', 'page-equipment-types-index')

@section('content')

    {{-- Hero --}}
    <div class="page-hero">
        <div class="container">
            @include('partials.breadcrumb', [
                'crumbs' => [['label' => 'Parts by Equipment', 'url' => null]],
            ])
            <div class="page-hero-label">Browse by Machine</div>
            <h1 class="page-hero-title">Parts by Equipment Type</h1>
            <p class="page-hero-sub">
                Find replacement parts matched to your specific type of heavy equipment.
                {{ $equipmentTypes->count() }} equipment categories covered.
            </p>
        </div>
    </div>

    <div class="section section--warm">
        <div class="container">

            {{-- Equipment Types Grid --}}
            <div class="et-grid" data-reveal>
                @foreach ($equipmentTypes as $i => $type)
                    <a href="{{ route('equipment-types.show', $type->slug) }}" class="et-card" data-reveal
                        data-reveal-delay="{{ min($i, 8) * 60 }}">

                        <div class="et-card-img-wrap">
                            @if ($type->image_media)
                                <img src="{{ $type->image_media->public_url }}" alt="{{ $type->name }}" loading="lazy">
                            @else
                                <div class="et-card-icon-placeholder">
                                    <i class="fa-solid fa-screwdriver-wrench"></i>
                                </div>
                            @endif
                            {{-- Overlay --}}
                            <div class="et-card-overlay"></div>
                        </div>

                        <div class="et-card-body">
                            <h2 class="et-card-name">{{ $type->name }}</h2>
                            @if ($type->description)
                                <p class="et-card-desc">{{ Str::limit($type->description, 90) }}</p>
                            @endif
                            <div class="et-card-footer">
                                <span class="et-card-count">
                                    {{ number_format($type->parts_count) }} parts
                                </span>
                                <span class="et-card-arrow">
                                    Shop Parts <i class="fa-solid fa-arrow-right"></i>
                                </span>
                            </div>
                        </div>

                    </a>
                @endforeach
            </div>

            {{-- CTA --}}
            <div class="makes-cta-box" style="margin-top:48px;" data-reveal>
                <div class="makes-cta-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
                <div>
                    <div class="makes-cta-title">Don't see your equipment type?</div>
                    <p class="makes-cta-text">
                        We source parts for all types of heavy equipment.
                        Submit a quote request with your machine details.
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
        .et-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .et-card {
            position: relative;
            display: flex;
            flex-direction: column;
            border-radius: var(--radius-xl);
            overflow: hidden;
            text-decoration: none;
            background: var(--ink);
            min-height: 260px;
            transition: box-shadow var(--transition-md), transform var(--transition-md);
        }

        .et-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-4px);
        }

        .et-card-img-wrap {
            position: absolute;
            inset: 0;
            z-index: 0;
        }

        .et-card-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .5s var(--ease);
        }

        .et-card:hover .et-card-img-wrap img {
            transform: scale(1.06);
        }

        .et-card-icon-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 56px;
            color: rgba(255, 255, 255, .15);
            background: var(--gray-900);
        }

        .et-card-overlay {
            position: absolute;
            inset: 0;
            z-index: 1;
            background: linear-gradient(to top,
                    rgba(14, 14, 16, .95) 0%,
                    rgba(14, 14, 16, .6) 50%,
                    rgba(14, 14, 16, .15) 100%);
            transition: background var(--transition-md);
        }

        .et-card:hover .et-card-overlay {
            background: linear-gradient(to top,
                    rgba(14, 14, 16, .98) 0%,
                    rgba(14, 14, 16, .7) 50%,
                    rgba(14, 14, 16, .25) 100%);
        }

        .et-card-body {
            position: relative;
            z-index: 2;
            padding: 22px 22px 20px;
            margin-top: auto;
        }

        .et-card-name {
            font-family: var(--font-display);
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--white);
            line-height: 1.1;
            margin-bottom: 6px;
        }

        .et-card-desc {
            font-size: 13px;
            color: rgba(255, 255, 255, .6);
            line-height: 1.5;
            margin-bottom: 12px;
        }

        .et-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 12px;
            border-top: 1px solid rgba(255, 255, 255, .12);
        }

        .et-card-count {
            font-size: 12px;
            color: rgba(255, 255, 255, .5);
            font-weight: 600;
        }

        .et-card-arrow {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 700;
            color: var(--orange);
            transition: gap var(--transition);
        }

        .et-card:hover .et-card-arrow {
            gap: 10px;
        }

        @media (max-width: 640px) {
            .et-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .et-card {
                min-height: 200px;
            }

            .et-card-name {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 400px) {
            .et-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush
