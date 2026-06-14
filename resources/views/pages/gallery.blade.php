{{-- resources/views/pages/gallery.blade.php --}}
@extends('layouts.app')

@section('meta_title', 'Media Gallery | ' . config('amsparts.company_name', 'Parts Plus Innovation Solutions'))
@section('meta_description', 'Browse our media gallery — warehouse photos, equipment parts, and more from ' .
    config('amsparts.company_name', 'Parts Plus Innovation Solutions') . '.')
@section('body_class', 'page-gallery')

@push('styles')
    <style>
        .gallery-hero {
            padding: 48px 0 36px;
            background: var(--ink);
            color: var(--white);
        }

        .gallery-hero-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--orange);
            margin-bottom: 10px;
        }

        .gallery-hero h1 {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            margin: 0 0 12px;
            line-height: 1.1;
        }

        .gallery-hero p {
            font-size: 15px;
            color: rgba(255, 255, 255, .65);
            margin: 0;
            max-width: 560px;
        }

        .gallery-section {
            padding: 48px 0 72px;
            background: var(--gray-50);
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 16px;
        }

        .gallery-item {
            position: relative;
            aspect-ratio: 4/3;
            overflow: hidden;
            border-radius: var(--radius-lg);
            background: var(--gray-200);
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .07);
            transition: box-shadow .2s, transform .2s;
        }

        .gallery-item:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, .15);
            transform: translateY(-2px);
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .35s ease;
            display: block;
        }

        .gallery-item:hover img {
            transform: scale(1.05);
        }

        .gallery-item-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, .55) 0%, transparent 50%);
            opacity: 0;
            transition: opacity .25s;
            display: flex;
            align-items: flex-end;
            padding: 14px;
        }

        .gallery-item:hover .gallery-item-overlay {
            opacity: 1;
        }

        .gallery-item-caption {
            color: #fff;
            font-size: 12px;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 100%;
        }

        /* Lightbox */
        .lightbox-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .94);
            z-index: 9000;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 16px;
            padding: 20px;
        }

        .lightbox-backdrop.is-open {
            display: flex;
        }

        .lightbox-img {
            max-width: min(900px, 92vw);
            max-height: 78vh;
            object-fit: contain;
            border-radius: 4px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .6);
        }

        .lightbox-caption {
            color: rgba(255, 255, 255, .65);
            font-size: 13px;
            text-align: center;
            max-width: 600px;
        }

        .lightbox-close {
            position: fixed;
            top: 18px;
            right: 22px;
            background: none;
            border: none;
            color: rgba(255, 255, 255, .7);
            font-size: 32px;
            cursor: pointer;
            line-height: 1;
            transition: color .15s;
            z-index: 9001;
        }

        .lightbox-close:hover {
            color: #fff;
        }

        .lightbox-nav {
            position: fixed;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, .12);
            border: none;
            color: #fff;
            font-size: 20px;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            cursor: pointer;
            transition: background .15s;
            z-index: 9001;
        }

        .lightbox-nav:hover {
            background: rgba(255, 255, 255, .25);
        }

        #lightboxPrev {
            left: 16px;
        }

        #lightboxNext {
            right: 16px;
        }

        /* Empty state */
        .gallery-empty {
            text-align: center;
            padding: 80px 20px;
        }

        .gallery-empty i {
            font-size: 48px;
            color: var(--gray-300);
            display: block;
            margin-bottom: 16px;
        }

        .gallery-empty h3 {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--gray-500);
            margin-bottom: 8px;
        }

        .gallery-empty p {
            font-size: 14px;
            color: var(--gray-400);
        }
    </style>
@endpush

@section('content')

    {{-- Hero --}}
    <div class="gallery-hero">
        <div class="container">
            @include('partials.breadcrumb', [
                'crumbs' => [['label' => 'Gallery', 'url' => null]],
            ])
            <div class="gallery-hero-label">Media Galleries</div>
            <h1>Our Photo Gallery</h1>
            <p>Take a look inside our warehouses, equipment, and the parts that keep your fleet running.</p>
        </div>
    </div>

    {{-- Gallery --}}
    <div class="gallery-section">
        <div class="container">

            @if ($images->count())

                <div class="gallery-grid" id="galleryGrid">
                    @foreach ($images as $i => $image)
                        <div class="gallery-item" data-index="{{ $i }}" data-src="{{ $image->public_url }}"
                            data-caption="{{ $image->alt_text ?: $image->original_name }}"
                            onclick="openLightbox({{ $i }})" data-reveal
                            data-reveal-delay="{{ min($i, 9) * 60 }}">
                            <img src="{{ $image->public_url }}" alt="{{ $image->alt_text ?: $image->original_name }}"
                                loading="{{ $i < 12 ? 'eager' : 'lazy' }}">
                            <div class="gallery-item-overlay">
                                <span class="gallery-item-caption">
                                    {{ $image->alt_text ?: $image->original_name }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if ($images->hasPages())
                    <div class="pagination-wrap" style="margin-top:40px;">
                        {{ $images->onEachSide(2)->links('vendor.pagination.simple-admin') }}
                    </div>
                @endif
            @else
                <div class="gallery-empty">
                    <i class="fa-solid fa-images"></i>
                    <h3>Gallery Coming Soon</h3>
                    <p>We are in the process of uploading our galleries. Check back soon!</p>
                </div>

            @endif

        </div>
    </div>

    {{-- Lightbox --}}
    <div class="lightbox-backdrop" id="lightbox" onclick="handleLightboxClick(event)">
        <button class="lightbox-close" onclick="closeLightbox()" aria-label="Close">×</button>
        <button class="lightbox-nav" id="lightboxPrev" onclick="event.stopPropagation();moveLightbox(-1)"
            aria-label="Previous">
            <i class="fa-solid fa-chevron-left"></i>
        </button>
        <img class="lightbox-img" id="lightboxImg" src="" alt="">
        <button class="lightbox-nav" id="lightboxNext" onclick="event.stopPropagation();moveLightbox(1)" aria-label="Next">
            <i class="fa-solid fa-chevron-right"></i>
        </button>
        <div class="lightbox-caption" id="lightboxCaption"></div>
    </div>

@endsection

@push('scripts')
    <script>
        const galleryItems = Array.from(document.querySelectorAll('.gallery-item'));
        let currentIndex = 0;

        function openLightbox(index) {
            currentIndex = index;
            updateLightbox();
            document.getElementById('lightbox').classList.add('is-open');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            document.getElementById('lightbox').classList.remove('is-open');
            document.body.style.overflow = '';
        }

        function handleLightboxClick(e) {
            if (e.target === document.getElementById('lightbox')) closeLightbox();
        }

        function moveLightbox(dir) {
            currentIndex = (currentIndex + dir + galleryItems.length) % galleryItems.length;
            updateLightbox();
        }

        function updateLightbox() {
            const item = galleryItems[currentIndex];
            document.getElementById('lightboxImg').src = item.dataset.src;
            document.getElementById('lightboxImg').alt = item.dataset.caption;
            document.getElementById('lightboxCaption').textContent = item.dataset.caption;
            // Hide nav arrows if only 1 image
            const showNav = galleryItems.length > 1;
            document.getElementById('lightboxPrev').style.display = showNav ? '' : 'none';
            document.getElementById('lightboxNext').style.display = showNav ? '' : 'none';
        }

        document.addEventListener('keydown', function(e) {
            if (!document.getElementById('lightbox').classList.contains('is-open')) return;
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') moveLightbox(-1);
            if (e.key === 'ArrowRight') moveLightbox(1);
        });
    </script>
@endpush
