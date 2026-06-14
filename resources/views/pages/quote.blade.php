{{-- resources/views/pages/quote.blade.php --}}
@extends('layouts.app')

@section('meta_title', 'Request a Free Parts Quote | ' . config('amsparts.company_name', 'Parts Plus Innovation
    Solutions'))
@section('meta_description',
    'Get a free quote for new, used or rebuilt heavy equipment parts. Fill out our quick form
    and our specialists will respond within 1 business day.')
@section('body_class', 'page-quote')

@section('content')

    {{-- Hero --}}
    <div class="page-hero">
        <div class="container">
            @include('partials.breadcrumb', ['crumbs' => [['label' => 'Request a Quote', 'url' => null]]])
            <div class="page-hero-label">Free Quote</div>
            <h1 class="page-hero-title">Request a Parts Quote</h1>
            <p class="page-hero-sub">
                Fill out the form below and our parts specialists will get back to you
                within 1 business day — often the same day.
            </p>
        </div>
    </div>

    <div class="section section--warm">
        <div class="container">
            <div class="quote-layout">

                {{-- ══ Quote Form ══ --}}
                <div class="quote-form-wrap" data-reveal>

                    {{-- Success message (shown after redirect) --}}
                    @if (session('success'))
                        <div class="alert alert-success" style="margin-bottom:24px;" role="alert">
                            <i class="fa-solid fa-circle-check"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger" style="margin-bottom:24px;" role="alert">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            Please fix the errors below before submitting.
                        </div>
                    @endif

                    <form action="{{ route('quote.store') }}" method="POST" id="quoteForm" novalidate>
                        @csrf

                        {{-- ── Section 1: Contact ── --}}
                        <div class="qform-section">
                            <div class="qform-section-title">
                                <span class="qform-step">1</span>
                                Your Contact Information
                            </div>
                            <div class="qform-grid qform-grid--2">
                                <div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
                                    <label class="form-label" for="first_name">First Name <span
                                            class="required">*</span></label>
                                    <input type="text" id="first_name" name="first_name" class="form-control" required
                                        value="{{ old('first_name') }}" placeholder="John" autocomplete="given-name">
                                    @error('first_name')
                                        <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
                                    <label class="form-label" for="last_name">Last Name <span
                                            class="required">*</span></label>
                                    <input type="text" id="last_name" name="last_name" class="form-control" required
                                        value="{{ old('last_name') }}" placeholder="Smith" autocomplete="family-name">
                                    @error('last_name')
                                        <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                    <label class="form-label" for="email">Email Address <span
                                            class="required">*</span></label>
                                    <input type="email" id="email" name="email" class="form-control" required
                                        value="{{ old('email') }}" placeholder="john@company.com" autocomplete="email">
                                    @error('email')
                                        <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
                                    <label class="form-label" for="phone">Phone Number <span
                                            class="required">*</span></label>
                                    <input type="tel" id="phone" name="phone" class="form-control" required
                                        value="{{ old('phone') }}" placeholder="+1 (555) 000-0000" autocomplete="tel">
                                    @error('phone')
                                        <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group qform-grid--full">
                                    <label class="form-label" for="company">Company / Fleet Name</label>
                                    <input type="text" id="company" name="company" class="form-control"
                                        value="{{ old('company') }}" placeholder="ABC Construction Co."
                                        autocomplete="organization">
                                </div>
                            </div>
                        </div>

                        {{-- ── Section 2: Machine Info ── --}}
                        <div class="qform-section">
                            <div class="qform-section-title">
                                <span class="qform-step">2</span>
                                Machine Information
                            </div>
                            <div class="qform-grid qform-grid--3">
                                <div class="form-group">
                                    <label class="form-label" for="make_id">Equipment Make</label>
                                    <select id="make_id" name="make_id" class="form-control">
                                        <option value="">Select Make…</option>
                                        @foreach ($makes as $make)
                                            {{-- pre-select from URL ?make_slug= (slug is reliable across DB reseeds)
                                                 or from ?make_id= or from old() after validation failure --}}
                                            @php
                                                $isSelected = old('make_id')
                                                    ? old('make_id') == $make->id
                                                    : (request('make_slug')
                                                        ? request('make_slug') === $make->slug
                                                        : request('make_id') == $make->id);
                                            @endphp
                                            <option value="{{ $make->id }}" {{ $isSelected ? 'selected' : '' }}>
                                                {{ $make->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="model_id">Model</label>
                                    <select id="model_id" name="model_id" class="form-control" disabled>
                                        <option value="">Select Model…</option>
                                    </select>
                                    <div class="form-hint">Select a make first</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="year">Year</label>
                                    <input type="text" id="year" name="year" class="form-control"
                                        value="{{ old('year', request('year')) }}" placeholder="e.g. 2018">
                                </div>
                                <div class="form-group qform-grid--full">
                                    <label class="form-label" for="serial_number">Machine Serial Number</label>
                                    <input type="text" id="serial_number" name="serial_number" class="form-control"
                                        value="{{ old('serial_number', request('serial_number')) }}"
                                        placeholder="Helps us find the exact part">
                                    <div class="form-hint">Optional but helps ensure we find the right part</div>
                                </div>
                            </div>
                        </div>

                        {{-- ── Section 3: Part Info ── --}}
                        <div class="qform-section">
                            <div class="qform-section-title">
                                <span class="qform-step">3</span>
                                Part Details
                            </div>

                            {{-- Part number lookup --}}
                            <div class="form-group" style="margin-bottom:16px;">
                                <label class="form-label" for="part_number">Part Number / OEM Number</label>
                                <div style="display:flex;gap:8px;">
                                    <input type="text" id="part_number" name="part_number" class="form-control"
                                        value="{{ old('part_number', request('part_number')) }}"
                                        placeholder="e.g. 7J1234 or 20Y-70-11551" autocomplete="off">
                                    <button type="button" class="btn btn-ghost btn-sm" id="partLookupBtn"
                                        style="flex-shrink:0;white-space:nowrap;" aria-label="Look up part">
                                        <i class="fa-solid fa-magnifying-glass"></i> Look Up
                                    </button>
                                </div>
                                <div id="partLookupResult" class="part-lookup-result" style="display:none;"
                                    aria-live="polite"></div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="oem_part_number">OEM / Cross-reference Number</label>
                                <input type="text" id="oem_part_number" name="oem_part_number" class="form-control"
                                    value="{{ old('oem_part_number', request('oem')) }}"
                                    placeholder="If different from above">
                            </div>

                            <div class="form-group {{ $errors->has('part_description') ? 'has-error' : '' }}">
                                <label class="form-label" for="part_description">Part Description <span
                                        class="required">*</span></label>
                                <textarea id="part_description" name="part_description" class="form-control" rows="4" required
                                    placeholder="Describe the part you need — type, function, location on machine, symptoms if replacing a failed part…">{{ old('part_description', request('part_desc')) }}</textarea>
                                @error('part_description')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="qform-grid qform-grid--3">
                                <div class="form-group">
                                    <label class="form-label" for="quantity">Quantity Needed</label>
                                    <input type="number" id="quantity" name="quantity" class="form-control"
                                        min="1" value="{{ old('quantity', 1) }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="condition">Preferred Condition</label>
                                    <select id="condition" name="condition" class="form-control">
                                        @php $selCondition = old('condition', request('condition', 'any')); @endphp
                                        <option value="any" {{ $selCondition === 'any' ? 'selected' : '' }}>
                                            Any (Best Price)</option>
                                        <option value="new" {{ $selCondition === 'new' ? 'selected' : '' }}>
                                            New Only</option>
                                        <option value="rebuilt" {{ $selCondition === 'rebuilt' ? 'selected' : '' }}>
                                            Rebuilt / Reman</option>
                                        <option value="used" {{ $selCondition === 'used' ? 'selected' : '' }}>
                                            Used Only</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="urgency">Urgency Level</label>
                                    <select id="urgency" name="urgency" class="form-control">
                                        <option value="standard"
                                            {{ old('urgency', 'standard') === 'standard' ? 'selected' : '' }}>Standard
                                        </option>
                                        <option value="urgent" {{ old('urgency') === 'urgent' ? 'selected' : '' }}>Urgent
                                            (48hr)
                                        </option>
                                        <option value="emergency" {{ old('urgency') === 'emergency' ? 'selected' : '' }}>
                                            Emergency (ASAP)
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="notes">Additional Notes</label>
                                <textarea id="notes" name="notes" class="form-control" rows="3"
                                    placeholder="Shipping location, budget, any other details…">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        {{-- Hidden source tracking --}}
                        <input type="hidden" name="source" value="{{ request('source', 'quote_page') }}">

                        {{-- Submit --}}
                        <div class="qform-submit-row">
                            <div class="qform-submit-info">
                                <i class="fa-solid fa-shield-halved" style="color:var(--orange);"></i>
                                Your information is secure and will never be shared.
                            </div>
                            <button type="submit" class="btn btn-primary btn-xl" id="quoteSubmitBtn">
                                <i class="fa-solid fa-paper-plane"></i>
                                Submit Quote Request
                            </button>
                        </div>
                    </form>
                </div>{{-- /.quote-form-wrap --}}

                {{-- ══ Sidebar ══ --}}
                <aside class="quote-sidebar">

                    {{-- What to expect --}}
                    <div class="quote-sidebar-card" data-reveal>
                        <div class="qsc-title">What Happens Next?</div>
                        <div class="qsc-steps">
                            @foreach ([['fa-paper-plane', 'Submit Your Request', 'Fill in the form with as much detail as possible.'], ['fa-headset', 'We Review & Source', 'Our specialists review your request and source options — new, used & rebuilt.'], ['fa-envelope', 'You Get a Quote', 'We\'ll reply by email (and call if urgent) — usually within a few hours.'], ['fa-truck-fast', 'Part Ships Fast', 'Confirm your order and we\'ll ship promptly from our North American warehouses.']] as $i => [$icon, $title, $text])
                                <div class="qsc-step">
                                    <div class="qsc-step-num">{{ $i + 1 }}</div>
                                    <div class="qsc-step-icon"><i class="fa-solid fa-{{ $icon }}"></i></div>
                                    <div>
                                        <div class="qsc-step-title">{{ $title }}</div>
                                        <div class="qsc-step-text">{{ $text }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Contact alternatives --}}
                    <div class="quote-sidebar-card" data-reveal>
                        <div class="qsc-title">Other Ways to Reach Us</div>
                        <div class="qsc-contacts">
                            @if (config('amsparts.phone_main'))
                                <a href="tel:{{ config('amsparts.phone_main') }}" class="qsc-contact-item">
                                    <div class="qsc-contact-icon"><i class="fa-solid fa-phone"></i></div>
                                    <div>
                                        <div class="qsc-contact-label">Call Us</div>
                                        <div class="qsc-contact-value">{{ config('amsparts.phone_main') }}</div>
                                    </div>
                                </a>
                            @endif
                            @if (config('amsparts.email_general'))
                                <a href="mailto:{{ config('amsparts.email_general') }}" class="qsc-contact-item">
                                    <div class="qsc-contact-icon"><i class="fa-solid fa-envelope"></i></div>
                                    <div>
                                        <div class="qsc-contact-label">Email Us</div>
                                        <div class="qsc-contact-value">{{ config('amsparts.email_general') }}</div>
                                    </div>
                                </a>
                            @endif
                            <a href="{{ route('contact') }}" class="qsc-contact-item">
                                <div class="qsc-contact-icon"><i class="fa-solid fa-message"></i></div>
                                <div>
                                    <div class="qsc-contact-label">Contact Form</div>
                                    <div class="qsc-contact-value">General enquiries</div>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- Trust badges --}}
                    <div class="quote-sidebar-card quote-sidebar-card--dark" data-reveal>
                        <div class="qsc-trust-list">
                            @foreach ([['fa-shield-halved', 'Up to 3-Year Warranty'], ['fa-truck-fast', 'Same-Day Shipping Available'], ['fa-globe', 'Ships to 50+ Countries'], ['fa-headset', 'Expert Parts Specialists'], ['fa-certificate', 'OEM & Aftermarket Options'], ['fa-recycle', 'New, Used & Rebuilt Parts']] as [$icon, $label])
                                <div class="qsc-trust-item">
                                    <i class="fa-solid fa-{{ $icon }}"></i>
                                    {{ $label }}
                                </div>
                            @endforeach
                        </div>
                    </div>

                </aside>
            </div>{{-- /.quote-layout --}}
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .quote-layout {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 36px;
            align-items: start;
        }

        /* ── Form ─────────────────────────────────────────────── */
        .quote-form-wrap {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-xl);
            overflow: hidden;
        }

        .qform-section {
            padding: 28px 32px;
            border-bottom: 1px solid var(--gray-100);
        }

        .qform-section:last-of-type {
            border-bottom: none;
        }

        .qform-section-title {
            display: flex;
            align-items: center;
            gap: 12px;
            font-family: var(--font-display);
            font-size: 18px;
            font-weight: 800;
            color: var(--ink);
            margin-bottom: 20px;
        }

        .qform-step {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            flex-shrink: 0;
            background: var(--orange);
            color: var(--white);
            font-size: 13px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qform-grid {
            display: grid;
            gap: 16px;
        }

        .qform-grid--2 {
            grid-template-columns: repeat(2, 1fr);
        }

        .qform-grid--3 {
            grid-template-columns: repeat(3, 1fr);
        }

        .qform-grid--full {
            grid-column: 1 / -1;
        }

        .form-hint {
            font-size: 11px;
            color: var(--gray-400);
            margin-top: 4px;
        }

        .required {
            color: var(--error);
        }

        .part-lookup-result {
            margin-top: 8px;
            padding: 10px 14px;
            background: var(--gray-50);
            border-radius: var(--radius);
            font-size: 13px;
        }

        .part-lookup-result.found {
            background: #F0FDF4;
            border: 1px solid #86EFAC;
        }

        .part-lookup-result.not-found {
            background: #FEF2F2;
            border: 1px solid #FECACA;
        }

        .qform-submit-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            padding: 24px 32px;
            background: var(--gray-50);
            border-top: 1px solid var(--gray-100);
        }

        .qform-submit-info {
            font-size: 12px;
            color: var(--gray-500);
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .btn-xl {
            padding: 14px 32px;
            font-size: 15px;
        }

        /* ── Sidebar ──────────────────────────────────────────── */
        .quote-sidebar {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .quote-sidebar-card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-xl);
            padding: 24px;
        }

        .quote-sidebar-card--dark {
            background: var(--ink);
            border-color: var(--ink);
        }

        .qsc-title {
            font-family: var(--font-display);
            font-size: 16px;
            font-weight: 800;
            color: var(--ink);
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--orange);
            display: inline-block;
        }

        .qsc-steps {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .qsc-step {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .qsc-step-num {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            flex-shrink: 0;
            background: var(--orange);
            color: var(--white);
            font-size: 11px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 2px;
        }

        .qsc-step-icon {
            display: none;
        }

        .qsc-step-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 2px;
        }

        .qsc-step-text {
            font-size: 12px;
            color: var(--gray-500);
            line-height: 1.5;
        }

        .qsc-contacts {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .qsc-contact-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border-radius: var(--radius);
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            transition: border-color var(--transition), background var(--transition);
            text-decoration: none;
        }

        .qsc-contact-item:hover {
            border-color: var(--orange);
            background: var(--orange-pale);
        }

        .qsc-contact-icon {
            width: 36px;
            height: 36px;
            border-radius: var(--radius-sm);
            flex-shrink: 0;
            background: rgba(224, 92, 26, .1);
            color: var(--orange);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
        }

        .qsc-contact-label {
            font-size: 11px;
            color: var(--gray-400);
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .qsc-contact-value {
            font-size: 13px;
            font-weight: 600;
            color: var(--ink);
        }

        .qsc-trust-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .qsc-trust-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: var(--gray-400);
        }

        .qsc-trust-item i {
            color: var(--orange);
            width: 16px;
            text-align: center;
        }

        @media (max-width: 900px) {
            .quote-layout {
                grid-template-columns: 1fr;
            }

            .quote-sidebar {
                display: none;
            }

            .qform-grid--2,
            .qform-grid--3 {
                grid-template-columns: 1fr;
            }

            .qform-section {
                padding: 20px;
            }

            .qform-submit-row {
                flex-direction: column;
                align-items: stretch;
                text-align: center;
            }

            .qform-submit-row .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        const makeSelect = document.getElementById('make_id');
        const modelSelect = document.getElementById('model_id');
        const modelHint = modelSelect?.nextElementSibling;

        // ── Shared: load models for a given make, optionally pre-select one ────
        async function loadModelsForMake(makeId, preselectModelId = null) {
            if (!makeId) {
                modelSelect.innerHTML = '<option value="">Select Model…</option>';
                modelSelect.disabled = true;
                if (modelHint) modelHint.textContent = 'Select a make first';
                return;
            }
            modelSelect.disabled = true;
            modelSelect.innerHTML = '<option>Loading…</option>';
            if (modelHint) modelHint.textContent = 'Loading models…';
            try {
                const res = await fetch(`/api/makes/${makeId}/models`, {
                    headers: {
                        Accept: 'application/json'
                    }
                });
                const data = await res.json();
                const opts = data.models || [];
                modelSelect.innerHTML = '<option value="">Select Model…</option>' +
                    opts.map(m => {
                        const sel = preselectModelId && String(m.id) === String(preselectModelId) ? ' selected' :
                            '';
                        return `<option value="${m.id}"${sel}>${m.name}${m.year_range ? ' (' + m.year_range + ')' : ''}</option>`;
                    }).join('');
                modelSelect.disabled = opts.length === 0;
                if (modelHint) modelHint.textContent = opts.length ? '' : 'No models found for this make';
            } catch {
                modelSelect.innerHTML = '<option value="">Select Model…</option>';
                modelSelect.disabled = false;
                if (modelHint) modelHint.textContent = '';
            }
        }

        makeSelect?.addEventListener('change', function() {
            loadModelsForMake(this.value);
        });

        // ── Auto-trigger cascade on page load ─────────────────────────────────
        // When make_slug is in the URL (from part pages), use the slug-based API
        // which looks up the REAL current DB id — avoids stale make_id mismatches.
        const preselectedModelId = '{{ old('model_id', request('model_id')) }}';
        const urlMakeSlug = '{{ request('make_slug') }}';

        setTimeout(async function() {
            if (urlMakeSlug) {
                // Use slug-based lookup — guaranteed to match equipment_models.make_id
                try {
                    const res = await fetch(`/api/makes/slug/${urlMakeSlug}/models`, {
                        headers: {
                            Accept: 'application/json'
                        }
                    });
                    const data = await res.json();
                    const opts = data.models || [];

                    // Update the make select to use the real DB id returned from API
                    if (data.make_id && makeSelect) {
                        const opt = makeSelect.querySelector(`option[value="${data.make_id}"]`);
                        if (opt) makeSelect.value = data.make_id;
                    }

                    modelSelect.innerHTML = '<option value="">Select Model…</option>' +
                        opts.map(m => {
                            const sel = preselectedModelId && String(m.id) === String(preselectedModelId) ?
                                ' selected' : '';
                            return `<option value="${m.id}"${sel}>${m.name}${m.year_range ? ' (' + m.year_range + ')' : ''}</option>`;
                        }).join('');
                    modelSelect.disabled = opts.length === 0;
                    if (modelHint) modelHint.textContent = opts.length ? '' : 'No models found for this make';
                } catch {
                    modelSelect.disabled = false;
                }
            } else {
                // Fallback: use the selected make_id from the dropdown
                const preselectedMakeId = makeSelect?.value;
                if (preselectedMakeId) {
                    loadModelsForMake(preselectedMakeId, preselectedModelId || null);
                }
            }
        }, 0);

        // ── Part lookup: shared function used by button click AND page-load auto-run ──
        async function runPartLookup(partNumber, silent = false) {
            const resultBox = document.getElementById('partLookupResult');
            if (!partNumber || partNumber.length < 2) return;

            if (!silent) {
                resultBox.style.display = '';
                resultBox.className = 'part-lookup-result';
                resultBox.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Looking up part…';
            }

            try {
                const r = await fetch(`/api/parts/lookup?q=${encodeURIComponent(partNumber)}`, {
                    headers: {
                        Accept: 'application/json'
                    }
                });
                const data = await r.json();

                if (data.part) {
                    // ── Show green result box ────────────────────────────────
                    resultBox.style.display = '';
                    resultBox.className = 'part-lookup-result found';
                    resultBox.innerHTML =
                        `<i class="fa-solid fa-circle-check" style="color:#15803D;"></i>
                        <strong>${data.part.name}</strong>${data.part.make ? ' — ' + data.part.make : ''}
                        &nbsp;<a href="${data.part.url}" target="_blank" style="color:var(--orange);font-weight:600;">View Part →</a>`;

                    // ── Autofill part_description if currently empty ─────────
                    const descField = document.getElementById('part_description');
                    if (descField && !descField.value.trim() && data.part.short_description) {
                        descField.value = data.part.short_description;
                    }

                    // ── Auto-select Make and load its models ─────────────────
                    if (data.part.make_id && makeSelect) {
                        const makeOpt = makeSelect.querySelector(`option[value="${data.part.make_id}"]`);
                        if (makeOpt) {
                            makeSelect.value = data.part.make_id;
                            loadModelsForMake(data.part.make_id);
                        }
                    }

                } else {
                    // Only show "not found" when user explicitly clicked Look Up
                    if (!silent) {
                        resultBox.style.display = '';
                        resultBox.className = 'part-lookup-result not-found';
                        resultBox.innerHTML =
                            `<i class="fa-solid fa-circle-info" style="color:#DC2626;"></i>
                            Part number not found in our catalog — submit the quote and we'll source it.`;
                    } else {
                        resultBox.style.display = 'none';
                    }
                }
            } catch {
                resultBox.style.display = 'none';
            }
        }

        // Manual Look Up button
        document.getElementById('partLookupBtn')?.addEventListener('click', function() {
            const q = document.getElementById('part_number').value.trim();
            runPartLookup(q, false);
        });

        // ── Auto-run on page load when arriving from a part page ──────────────
        // The part page passes: part_number, make_id, part_name, part_desc, condition, oem
        // We use those URL params to fill the form instantly (no API call needed when
        // part_name is present), and fall back to a lookup API call when only part_number given.
        const urlPartNumber = '{{ request('part_number') }}';
        const urlPartName = '{{ request('part_name') }}'; // passed from part show page

        if (urlPartNumber) {
            const resultBox = document.getElementById('partLookupResult');

            if (urlPartName) {
                // ── Full data already in URL — show result box immediately ──
                // Delay slightly so the preselectedMakeId cascade above fires first
                setTimeout(function() {
                    const makeName = makeSelect?.options[makeSelect.selectedIndex]?.text || '';
                    resultBox.style.display = '';
                    resultBox.className = 'part-lookup-result found';
                    resultBox.innerHTML =
                        `<i class="fa-solid fa-circle-check" style="color:#15803D;"></i>
                        <strong>${urlPartName}</strong>${makeName && makeName !== 'Select Make…' ? ' — ' + makeName : ''}`;
                    // Models are already being loaded by the preselectedMakeId block above.
                    // No need to call loadModelsForMake again — avoid double-fetch.
                }, 50);
            } else {
                // ── Only part_number in URL — run lookup API ──────────────
                setTimeout(() => runPartLookup(urlPartNumber, false), 200);
            }
        }

        // Submit loading state
        document.getElementById('quoteForm')?.addEventListener('submit', function() {
            const btn = document.getElementById('quoteSubmitBtn');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Submitting…';
            }
        });
    </script>
@endpush
