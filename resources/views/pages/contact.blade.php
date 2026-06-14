{{-- resources/views/pages/contact.blade.php --}}
@extends('layouts.app')

@section('meta_title', 'Contact Us | ' . config('amsparts.company_name', 'Parts Plus Innovation Solutions'))
@section('meta_description', 'Contact ' . config('amsparts.company_name', 'Parts Plus Innovation Solutions') . ' for heavy equipment parts
    inquiries. Call, email, or use our contact form — fast response guaranteed.')
@section('body_class', 'page-contact')

@section('content')

    {{-- Hero --}}
    <div class="page-hero">
        <div class="container">
            @include('partials.breadcrumb', ['crumbs' => [['label' => 'Contact Us', 'url' => null]]])
            <div class="page-hero-label">Get In Touch</div>
            <h1 class="page-hero-title">Contact Our Team</h1>
            <p class="page-hero-sub">
                Have a question, need a part, or just want to talk to a real person?
                We're here to help.
            </p>
        </div>
    </div>

    <div class="section section--warm">
        <div class="container">

            {{-- Contact methods strip --}}
            <div class="contact-methods-strip" data-reveal>
                @if (config('amsparts.phone_main'))
                    <a href="tel:{{ config('amsparts.phone_main') }}" class="contact-method-card">
                        <div class="cmc-icon"><i class="fa-solid fa-phone"></i></div>
                        <div class="cmc-label">Call Us</div>
                        <div class="cmc-value">{{ config('amsparts.phone_main') }}</div>
                        <div class="cmc-note">{{ config('amsparts.business_hours', 'Mon–Fri 8am–5pm') }}</div>
                    </a>
                @endif
                @if (config('amsparts.email_general'))
                    <a href="mailto:{{ config('amsparts.email_general') }}" class="contact-method-card">
                        <div class="cmc-icon"><i class="fa-solid fa-envelope"></i></div>
                        <div class="cmc-label">Email Us</div>
                        <div class="cmc-value">{{ config('amsparts.email_general') }}</div>
                        <div class="cmc-note">Reply within 1 business day</div>
                    </a>
                @endif
                <a href="{{ route('quote.create') }}" class="contact-method-card contact-method-card--accent">
                    <div class="cmc-icon"><i class="fa-solid fa-file-lines"></i></div>
                    <div class="cmc-label">Parts Quote</div>
                    <div class="cmc-value">Request a Free Quote</div>
                    <div class="cmc-note">Fastest way to get parts pricing</div>
                </a>
                @if (config('amsparts.address_street'))
                    <div class="contact-method-card">
                        <div class="cmc-icon"><i class="fa-solid fa-location-dot"></i></div>
                        <div class="cmc-label">Our Location</div>
                        <div class="cmc-value" style="font-size:13px;">
                            {{ config('amsparts.address_street') }}<br>
                            {{ config('amsparts.address_city') }}, {{ config('amsparts.address_state') }}
                            {{ config('amsparts.address_zip') }}
                        </div>
                    </div>
                @endif
            </div>

            {{-- Main layout --}}
            <div class="contact-layout">

                {{-- ══ Contact Form ══ --}}
                <div class="contact-form-wrap" data-reveal>
                    <div class="contact-form-header">
                        <h2 class="contact-form-title">Send Us a Message</h2>
                        <p class="contact-form-sub">
                            For parts inquiries, use our
                            <a href="{{ route('quote.create') }}" style="color:var(--orange);">quote form</a>
                            for faster processing. This form is for general questions.
                        </p>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            Please correct the errors below.
                        </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST" id="contactForm" novalidate>
                        @csrf

                        <div class="qform-grid qform-grid--2" style="gap:16px;margin-bottom:16px;">
                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                <label class="form-label" for="c_name">Full Name <span class="required">*</span></label>
                                <input type="text" id="c_name" name="name" class="form-control" required
                                    value="{{ old('name') }}" placeholder="John Smith" autocomplete="name">
                                @error('name')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                <label class="form-label" for="c_email">Email Address <span
                                        class="required">*</span></label>
                                <input type="email" id="c_email" name="email" class="form-control" required
                                    value="{{ old('email') }}" placeholder="john@company.com" autocomplete="email">
                                @error('email')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="c_phone">Phone Number</label>
                                <input type="tel" id="c_phone" name="phone" class="form-control"
                                    value="{{ old('phone') }}" placeholder="+1 (555) 000-0000" autocomplete="tel">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="c_company">Company</label>
                                <input type="text" id="c_company" name="company" class="form-control"
                                    value="{{ old('company') }}" placeholder="Optional" autocomplete="organization">
                            </div>
                            <div class="form-group qform-grid--full {{ $errors->has('subject') ? 'has-error' : '' }}">
                                <label class="form-label" for="c_subject">Subject <span class="required">*</span></label>
                                <input type="text" id="c_subject" name="subject" class="form-control" required
                                    value="{{ old('subject') }}"
                                    placeholder="e.g. General inquiry about parts availability">
                                @error('subject')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group qform-grid--full {{ $errors->has('message') ? 'has-error' : '' }}">
                                <label class="form-label" for="c_message">
                                    Message <span class="required">*</span>
                                    <span class="form-label-hint" id="charCountLabel">(min 10 characters)</span>
                                </label>
                                <textarea id="c_message" name="message" class="form-control" rows="6" required minlength="10"
                                    placeholder="How can we help you?">{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                                <div class="form-hint" id="charCount" aria-live="polite">0 / 5000 characters</div>
                            </div>
                        </div>

                        <div
                            style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;">
                            <div class="qform-submit-info">
                                <i class="fa-solid fa-shield-halved" style="color:var(--orange);"></i>
                                We respect your privacy and never share your information.
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg" id="contactSubmitBtn">
                                <i class="fa-solid fa-paper-plane"></i> Send Message
                            </button>
                        </div>
                    </form>
                </div>

                {{-- ══ Sidebar / Map area ══ --}}
                <aside class="contact-sidebar">

                    {{-- Business info --}}
                    <div class="quote-sidebar-card" data-reveal>
                        <div class="qsc-title">Business Hours</div>
                        <div class="contact-hours">
                            @php
                                $hours = [
                                    'Monday' => config('amsparts.hours_monday', '8:00am – 5:00pm'),
                                    'Tuesday' => config('amsparts.hours_tuesday', '8:00am – 5:00pm'),
                                    'Wednesday' => config('amsparts.hours_wednesday', '8:00am – 5:00pm'),
                                    'Thursday' => config('amsparts.hours_thursday', '8:00am – 5:00pm'),
                                    'Friday' => config('amsparts.hours_friday', '8:00am – 5:00pm'),
                                    'Saturday' => config('amsparts.hours_saturday', 'Closed'),
                                    'Sunday' => config('amsparts.hours_sunday', 'Closed'),
                                ];
                                $today = now()->format('l');
                            @endphp
                            @foreach ($hours as $day => $time)
                                <div class="contact-hour-row {{ $day === $today ? 'contact-hour-row--today' : '' }}">
                                    <span class="contact-hour-day">
                                        {{ $day }}
                                        @if ($day === $today)
                                            <span class="contact-today-badge">Today</span>
                                        @endif
                                    </span>
                                    <span class="contact-hour-time {{ $time === 'Closed' ? 'closed' : '' }}">
                                        {{ $time }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                        @if (config('amsparts.timezone'))
                            <div style="font-size:11px;color:var(--gray-400);margin-top:10px;">
                                All times in {{ config('amsparts.timezone') }}
                            </div>
                        @endif
                    </div>

                    {{-- Parts quote CTA --}}
                    <div class="quote-sidebar-card quote-sidebar-card--dark" data-reveal>
                        <div
                            style="font-family:var(--font-display);font-size:18px;font-weight:800;color:var(--white);margin-bottom:8px;">
                            Looking for a Part?
                        </div>
                        <p style="font-size:13px;color:var(--gray-500);margin-bottom:16px;line-height:1.6;">
                            Use our dedicated quote form for part requests — our specialists check it first thing.
                        </p>
                        <a href="{{ route('quote.create') }}" class="btn btn-primary"
                            style="width:100%;justify-content:center;">
                            <i class="fa-solid fa-file-lines"></i> Request Parts Quote
                        </a>
                    </div>

                    {{-- Social links --}}
                    @if (config('amsparts.social_facebook') || config('amsparts.social_linkedin') || config('amsparts.social_youtube'))
                        <div class="quote-sidebar-card" data-reveal>
                            <div class="qsc-title">Follow Us</div>
                            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                                @if (config('amsparts.social_facebook'))
                                    <a href="{{ config('amsparts.social_facebook') }}" target="_blank" rel="noopener"
                                        class="social-btn social-btn--fb" aria-label="Facebook">
                                        <i class="fa-brands fa-facebook-f"></i> Facebook
                                    </a>
                                @endif
                                @if (config('amsparts.social_linkedin'))
                                    <a href="{{ config('amsparts.social_linkedin') }}" target="_blank" rel="noopener"
                                        class="social-btn social-btn--li" aria-label="LinkedIn">
                                        <i class="fa-brands fa-linkedin-in"></i> LinkedIn
                                    </a>
                                @endif
                                @if (config('amsparts.social_youtube'))
                                    <a href="{{ config('amsparts.social_youtube') }}" target="_blank" rel="noopener"
                                        class="social-btn social-btn--yt" aria-label="YouTube">
                                        <i class="fa-brands fa-youtube"></i> YouTube
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                </aside>
            </div>{{-- /.contact-layout --}}
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .contact-methods-strip {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 14px;
            margin-bottom: 36px;
        }

        .contact-method-card {
            display: flex;
            flex-direction: column;
            gap: 4px;
            background: var(--white);
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-lg);
            padding: 20px;
            text-decoration: none;
            transition: border-color var(--transition), box-shadow var(--transition), transform var(--transition);
        }

        a.contact-method-card:hover {
            border-color: var(--orange);
            box-shadow: var(--shadow);
            transform: translateY(-2px);
        }

        .contact-method-card--accent {
            border-color: var(--orange);
            background: var(--orange-pale);
        }

        .cmc-icon {
            width: 40px;
            height: 40px;
            border-radius: var(--radius);
            background: rgba(224, 92, 26, .1);
            color: var(--orange);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-bottom: 8px;
        }

        .contact-method-card--accent .cmc-icon {
            background: rgba(224, 92, 26, .2);
        }

        .cmc-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--gray-400);
        }

        .cmc-value {
            font-family: var(--font-display);
            font-size: 16px;
            font-weight: 700;
            color: var(--ink);
        }

        .cmc-note {
            font-size: 12px;
            color: var(--gray-500);
        }

        .contact-layout {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 32px;
            align-items: start;
        }

        .contact-form-wrap {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-xl);
            padding: 32px;
        }

        .contact-form-header {
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--gray-100);
        }

        .contact-form-title {
            font-family: var(--font-display);
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--ink);
            margin-bottom: 6px;
        }

        .contact-form-sub {
            font-size: 13px;
            color: var(--gray-500);
        }

        .form-label-hint {
            font-size: 11px;
            color: var(--gray-400);
            font-weight: 400;
            margin-left: 6px;
        }

        .contact-sidebar {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .contact-hours {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .contact-hour-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 6px 8px;
            border-radius: var(--radius-sm);
            font-size: 13px;
        }

        .contact-hour-row--today {
            background: var(--orange-pale);
        }

        .contact-hour-day {
            color: var(--gray-700);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .contact-hour-time {
            color: var(--gray-600);
            font-weight: 600;
        }

        .contact-hour-time.closed {
            color: var(--gray-400);
        }

        .contact-today-badge {
            background: var(--orange);
            color: var(--white);
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .06em;
            padding: 2px 6px;
            border-radius: var(--radius-full);
        }

        .social-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 14px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: opacity var(--transition);
        }

        .social-btn:hover {
            opacity: .85;
        }

        .social-btn--fb {
            background: #1877F2;
            color: #fff;
        }

        .social-btn--li {
            background: #0A66C2;
            color: #fff;
        }

        .social-btn--yt {
            background: #FF0000;
            color: #fff;
        }

        @media (max-width: 860px) {
            .contact-layout {
                grid-template-columns: 1fr;
            }

            .contact-sidebar {
                display: none;
            }

            .contact-form-wrap {
                padding: 20px;
            }

            .qform-grid--2 {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 580px) {
            .contact-methods-strip {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Character counter for message
        const msgArea = document.getElementById('c_message');
        const charCount = document.getElementById('charCount');
        msgArea?.addEventListener('input', function() {
            const len = this.value.length;
            if (charCount) {
                charCount.textContent = `${len.toLocaleString()} / 5,000 characters`;
                charCount.style.color = len > 4800 ? 'var(--error)' : 'var(--gray-400)';
            }
        });

        // Submit state
        document.getElementById('contactForm')?.addEventListener('submit', function() {
            const btn = document.getElementById('contactSubmitBtn');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Sending…';
            }
        });
    </script>
@endpush
