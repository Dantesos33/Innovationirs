{{-- resources/views/pages/faqs.blade.php --}}
@extends('layouts.app')

@section('meta_title', 'Frequently Asked Questions | ' . config('amsparts.company_name', 'Parts Plus Innovation
    Solutions'))
@section('meta_description',
    'Answers to common questions about ordering heavy equipment parts, shipping, warranties,
    returns, and working with ' .
    config('amsparts.company_name', 'Parts Plus Innovation Solutions') .
    '.')
@section('body_class', 'page-faqs')

@push('head')
    @if ($faqs->flatten()->count())
        <script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        @foreach($faqs->flatten() as $i => $faq)
        {
            "@type": "Question",
            "name": "{{ addslashes($faq->question) }}",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "{{ addslashes(strip_tags($faq->answer)) }}"
            }
        }{{ !$loop->last ? ',' : '' }}
        @endforeach
    ]
}
</script>
    @endif
@endpush

@section('content')

    <div class="page-hero">
        <div class="container">
            @include('partials.breadcrumb', ['crumbs' => [['label' => 'FAQs', 'url' => null]]])
            <div class="page-hero-label">Help Center</div>
            <h1 class="page-hero-title">Frequently Asked Questions</h1>
            <p class="page-hero-sub">
                Can't find your answer here?
                <a href="{{ route('contact') }}" style="color:var(--orange);font-weight:600;">Contact our team</a>
                and we'll get back to you quickly.
            </p>
        </div>
    </div>

    <div class="section section--warm">
        <div class="container">
            <div class="faqs-layout">

                {{-- Sidebar TOC --}}
                @if ($faqs->keys()->count() > 1)
                    <aside class="faqs-toc" id="faqsToc">
                        <div class="faqs-toc-title">Jump to Section</div>
                        <ul class="faqs-toc-list">
                            @foreach ($faqs->keys() as $category)
                                <li>
                                    <a href="#faq-cat-{{ Str::slug($category) }}" class="faqs-toc-link">
                                        {{ $category ?: 'General' }}
                                        <span class="faqs-toc-count">{{ $faqs[$category]->count() }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        {{-- Search within FAQs --}}
                        <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--gray-200);">
                            <input type="text" id="faqSearch" class="form-control" style="height:36px;font-size:12px;"
                                placeholder="Search FAQs…" aria-label="Search FAQs">
                        </div>
                    </aside>
                @endif

                {{-- FAQ Sections --}}
                <div class="faqs-main" id="faqsMain">

                    @forelse($faqs as $category => $items)
                        <div class="faq-section" id="faq-cat-{{ Str::slug($category) }}">
                            @if ($category)
                                <h2 class="faq-section-title">{{ $category }}</h2>
                            @endif

                            <div class="faq-accordion">
                                @foreach ($items as $i => $faq)
                                    <div class="faq-item" data-question="{{ strtolower($faq->question) }}"
                                        data-answer="{{ strtolower(strip_tags($faq->answer)) }}">
                                        <button class="faq-question" aria-expanded="false" id="faq-q-{{ $faq->id }}"
                                            aria-controls="faq-a-{{ $faq->id }}">
                                            <span>{{ $faq->question }}</span>
                                            <i class="fa-solid fa-chevron-down faq-caret"></i>
                                        </button>
                                        <div class="faq-answer" id="faq-a-{{ $faq->id }}" role="region"
                                            aria-labelledby="faq-q-{{ $faq->id }}" hidden>
                                            <div class="faq-answer-inner prose">
                                                {!! $faq->answer !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-state-icon"><i class="fa-solid fa-circle-question"></i></div>
                            <h3 class="empty-state-title">No FAQs Yet</h3>
                            <p class="empty-state-text">Check back soon, or contact us with your question.</p>
                            <a href="{{ route('contact') }}" class="btn btn-primary" style="margin-top:8px;">
                                <i class="fa-solid fa-message"></i> Ask a Question
                            </a>
                        </div>
                    @endforelse

                    {{-- No search results msg --}}
                    <div id="faqNoResults" style="display:none;" class="empty-state">
                        <div class="empty-state-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
                        <h3 class="empty-state-title">No results found</h3>
                        <p class="empty-state-text">
                            Can't find your answer?
                            <a href="{{ route('contact') }}" style="color:var(--orange);">Contact our team</a>.
                        </p>
                    </div>

                </div>{{-- /.faqs-main --}}
            </div>{{-- /.faqs-layout --}}

            {{-- Still need help --}}
            <div class="makes-cta-box" style="margin-top:48px;">
                <div class="makes-cta-icon"><i class="fa-solid fa-headset"></i></div>
                <div>
                    <div class="makes-cta-title">Still Have Questions?</div>
                    <p class="makes-cta-text">Our team is ready to help. Reach out by phone, email, or contact form.</p>
                </div>
                <div style="display:flex;gap:10px;flex-wrap:wrap;margin-left:auto;">
                    <a href="{{ route('contact') }}" class="btn btn-ghost">Contact Us</a>
                    <a href="{{ route('quote.create') }}" class="btn btn-primary">
                        <i class="fa-solid fa-file-lines"></i> Get a Quote
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .faqs-layout {
            display: grid;
            grid-template-columns: 240px 1fr;
            gap: 40px;
            align-items: start;
        }

        .faqs-toc {
            position: sticky;
            top: calc(var(--total-header) + 20px);
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            padding: 20px;
        }

        .faqs-toc-title {
            font-family: var(--font-display);
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--gray-500);
            margin-bottom: 12px;
        }

        .faqs-toc-list {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .faqs-toc-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 7px 10px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            color: var(--gray-700);
            transition: background var(--transition), color var(--transition);
        }

        .faqs-toc-link:hover {
            background: var(--orange-pale);
            color: var(--orange);
        }

        .faqs-toc-link.active {
            background: var(--orange-pale);
            color: var(--orange);
            font-weight: 600;
        }

        .faqs-toc-count {
            font-size: 11px;
            font-weight: 700;
            color: var(--gray-400);
            background: var(--gray-100);
            padding: 0 6px;
            border-radius: 10px;
        }

        .faq-section {
            margin-bottom: 40px;
        }

        .faq-section-title {
            font-family: var(--font-display);
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--ink);
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--orange);
            display: inline-block;
        }

        .faq-accordion {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .faq-item {
            background: var(--white);
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-lg);
            overflow: hidden;
            transition: border-color var(--transition);
        }

        .faq-item:has(.faq-question[aria-expanded="true"]),
        .faq-item.is-open {
            border-color: var(--orange);
        }

        .faq-question {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 16px 20px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            color: var(--ink);
            text-align: left;
            background: none;
            border: none;
            transition: color var(--transition);
        }

        .faq-question:hover {
            color: var(--orange);
        }

        .faq-question[aria-expanded="true"] {
            color: var(--orange);
        }

        .faq-caret {
            flex-shrink: 0;
            font-size: 11px;
            color: var(--gray-400);
            transition: transform var(--transition);
        }

        .faq-question[aria-expanded="true"] .faq-caret {
            transform: rotate(180deg);
            color: var(--orange);
        }

        .faq-answer {
            background: var(--gray-50);
            border-top: 1px solid var(--gray-200);
        }

        .faq-answer[hidden] {
            display: none;
        }

        .faq-answer-inner {
            padding: 16px 20px 18px;
            font-size: 14px;
            color: var(--gray-600);
            line-height: 1.8;
        }

        .faq-answer-inner .prose {
            font-size: 14px;
        }

        .faq-item--hidden {
            display: none;
        }

        @media (max-width: 800px) {
            .faqs-layout {
                grid-template-columns: 1fr;
            }

            .faqs-toc {
                display: none;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Accordion
        document.querySelectorAll('.faq-question').forEach(btn => {
            btn.addEventListener('click', function() {
                const expanded = this.getAttribute('aria-expanded') === 'true';
                const answer = document.getElementById(this.getAttribute('aria-controls'));
                // Close all in same accordion
                const siblings = this.closest('.faq-accordion').querySelectorAll('.faq-question');
                siblings.forEach(s => {
                    s.setAttribute('aria-expanded', 'false');
                    const a = document.getElementById(s.getAttribute('aria-controls'));
                    if (a) a.hidden = true;
                    s.closest('.faq-item')?.classList.remove('is-open');
                });
                if (!expanded) {
                    this.setAttribute('aria-expanded', 'true');
                    if (answer) answer.hidden = false;
                    this.closest('.faq-item')?.classList.add('is-open');
                }
            });
        });

        // Open from hash
        if (window.location.hash) {
            const target = document.querySelector(window.location.hash);
            if (target && target.classList.contains('faq-item')) {
                const btn = target.querySelector('.faq-question');
                if (btn) btn.click();
            }
        }

        // Search FAQs
        const faqSearch = document.getElementById('faqSearch');
        const faqNoRes = document.getElementById('faqNoResults');
        faqSearch?.addEventListener('input', function() {
            const term = this.value.toLowerCase().trim();
            let visible = 0;
            document.querySelectorAll('.faq-item').forEach(item => {
                const q = item.dataset.question || '';
                const a = item.dataset.answer || '';
                const show = !term || q.includes(term) || a.includes(term);
                item.classList.toggle('faq-item--hidden', !show);
                if (show) visible++;
            });
            // Hide empty sections
            document.querySelectorAll('.faq-section').forEach(sec => {
                const hasVisible = sec.querySelectorAll('.faq-item:not(.faq-item--hidden)').length > 0;
                sec.style.display = hasVisible ? '' : 'none';
            });
            if (faqNoRes) faqNoRes.style.display = (visible === 0 && term) ? '' : 'none';
        });

        // TOC active on scroll
        const tocLinks = document.querySelectorAll('.faqs-toc-link');
        if (tocLinks.length) {
            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        tocLinks.forEach(l => l.classList.remove('active'));
                        const link = document.querySelector(`.faqs-toc-link[href="#${entry.target.id}"]`);
                        if (link) link.classList.add('active');
                    }
                });
            }, {
                rootMargin: '-100px 0px -60% 0px'
            });
            document.querySelectorAll('.faq-section[id]').forEach(sec => observer.observe(sec));
        }
    </script>
@endpush
