@extends('layouts.admin')
@section('title', isset($testimonial) ? 'Edit Testimonial' : 'Add Testimonial')

@section('breadcrumb')
    <a href="{{ route('admin.testimonials.index') }}">Testimonials</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">{{ isset($testimonial) ? 'Edit Testimonial' : 'Add Testimonial' }}</span>
@endsection

@section('content')

    <form
        action="{{ isset($testimonial) ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}"
        method="POST">
        @csrf
        @if (isset($testimonial))
            @method('PUT')
        @endif

        <div class="page-header">
            <div>
                <h1 class="page-title">{{ isset($testimonial) ? 'Edit Testimonial' : 'Add Testimonial' }}</h1>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.testimonials.index') }}" class="btn btn--ghost">Cancel</a>
                <button type="submit" class="btn btn--primary">
                    <i class="fa-solid fa-floppy-disk"></i>
                    {{ isset($testimonial) ? 'Save Changes' : 'Add Testimonial' }}
                </button>
            </div>
        </div>

        <div class="form-layout">

            {{-- Main --}}
            <div style="display:flex;flex-direction:column;gap:20px;">

                {{-- Reviewer Info --}}
                <div class="card">
                    <div class="card-header"><span class="card-title">Reviewer Information</span></div>
                    <div class="card-body">
                        <div class="form-grid form-grid--2" style="gap:16px;">

                            <div class="form-group">
                                <label class="form-label" for="reviewer_name">Full Name <span
                                        class="required">*</span></label>
                                <input type="text" id="reviewer_name" name="reviewer_name"
                                    class="form-control {{ $errors->has('reviewer_name') ? 'form-control--error' : '' }}"
                                    value="{{ old('reviewer_name', $testimonial->reviewer_name ?? '') }}"
                                    placeholder="e.g. John Smith" required>
                                @error('reviewer_name')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="reviewer_title">Job Title</label>
                                <input type="text" id="reviewer_title" name="reviewer_title" class="form-control"
                                    value="{{ old('reviewer_title', $testimonial->reviewer_title ?? '') }}"
                                    placeholder="e.g. Fleet Manager">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="company">Company</label>
                                <input type="text" id="company" name="company" class="form-control"
                                    value="{{ old('company', $testimonial->company ?? '') }}"
                                    placeholder="e.g. ABC Construction LLC">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="location">Location</label>
                                <input type="text" id="location" name="location" class="form-control"
                                    value="{{ old('location', $testimonial->location ?? '') }}"
                                    placeholder="e.g. Dallas, TX">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="source">Source</label>
                                <input type="text" id="source" name="source" class="form-control"
                                    value="{{ old('source', $testimonial->source ?? '') }}"
                                    placeholder="e.g. Google Review, Email, Phone">
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Review Content --}}
                <div class="card">
                    <div class="card-header"><span class="card-title">Review Content</span></div>
                    <div class="card-body" style="display:flex;flex-direction:column;gap:16px;">

                        {{-- Star Rating --}}
                        <div class="form-group">
                            <label class="form-label">Rating <span class="required">*</span></label>
                            <div style="display:flex;gap:6px;align-items:center;" id="starRating">
                                @for ($i = 1; $i <= 5; $i++)
                                    <label
                                        style="cursor:pointer;font-size:28px;color:{{ old('rating', $testimonial->rating ?? 5) >= $i ? '#F59E0B' : '#D1D5DB' }};"
                                        class="star-label" data-value="{{ $i }}">
                                        ★
                                        <input type="radio" name="rating" value="{{ $i }}"
                                            style="display:none;"
                                            {{ old('rating', $testimonial->rating ?? 5) == $i ? 'checked' : '' }}>
                                    </label>
                                @endfor
                                <span style="font-size:13px;color:var(--text-muted);margin-left:8px;" id="ratingLabel">
                                    {{ old('rating', $testimonial->rating ?? 5) }}/5 stars
                                </span>
                            </div>
                            @error('rating')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="content">Review Text <span class="required">*</span></label>
                            <textarea name="content" id="content" class="form-control {{ $errors->has('content') ? 'form-control--error' : '' }}"
                                rows="5" placeholder="The customer's review text…" required>{{ old('content', $testimonial->content ?? '') }}</textarea>
                            @error('content')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                </div>

            </div>

            {{-- Sidebar --}}
            <div style="display:flex;flex-direction:column;gap:20px;">

                <div class="form-sidebar-card">
                    <div class="form-sidebar-section">
                        <div class="form-sidebar-title">Display Settings</div>
                        <div style="display:flex;flex-direction:column;gap:12px;">
                            <label class="toggle-switch">
                                <input type="hidden" name="is_active" value="0">
                                <input class="toggle-input" type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', $testimonial->is_active ?? true) ? 'checked' : '' }}>
                                <span class="toggle-track"></span>
                                <span class="toggle-label">Active (shown on site)</span>
                            </label>
                            <label class="toggle-switch">
                                <input type="hidden" name="is_featured" value="0">
                                <input class="toggle-input" type="checkbox" name="is_featured" value="1"
                                    {{ old('is_featured', $testimonial->is_featured ?? false) ? 'checked' : '' }}>
                                <span class="toggle-track"></span>
                                <span class="toggle-label">Featured on homepage</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-sidebar-section">
                        <div class="form-group">
                            <label class="form-label">Display Order</label>
                            <input type="number" name="sort_order" class="form-control" min="0"
                                value="{{ old('sort_order', $testimonial->sort_order ?? 0) }}">
                            <span class="form-hint">Lower = shown first</span>
                        </div>
                    </div>
                    <div class="form-sidebar-section">
                        <button type="submit" class="btn btn--primary w-full" style="justify-content:center;">
                            <i class="fa-solid fa-floppy-disk"></i>
                            {{ isset($testimonial) ? 'Save Changes' : 'Add Testimonial' }}
                        </button>
                    </div>
                </div>

                {{-- Preview --}}
                <div class="card" style="padding:20px;">
                    <div
                        style="font-size:12px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:12px;">
                        Preview</div>
                    <div style="font-size:13px;color:var(--text-muted);font-style:italic;line-height:1.6;margin-bottom:12px;"
                        id="previewContent">
                        Your review text will appear here.
                    </div>
                    <div style="font-size:12px;font-weight:600;">
                        <span id="previewName">Reviewer Name</span>
                        <span style="font-weight:400;color:var(--text-muted);"> — </span>
                        <span id="previewCompany" style="color:var(--text-muted);">Company</span>
                    </div>
                </div>

            </div>

        </div>

    </form>

@endsection

@push('scripts')
    <script>
        // Star rating interaction
        document.querySelectorAll('.star-label').forEach(label => {
            label.addEventListener('click', function() {
                const val = parseInt(this.dataset.value);
                document.querySelectorAll('.star-label').forEach((l, i) => {
                    l.style.color = i < val ? '#F59E0B' : '#D1D5DB';
                });
                document.getElementById('ratingLabel').textContent = val + '/5 stars';
            });
        });

        // Live preview
        const contentInput = document.getElementById('content');
        const nameInput = document.getElementById('reviewer_name');
        const companyInput = document.getElementById('company');

        function updatePreview() {
            document.getElementById('previewContent').textContent =
                contentInput.value || 'Your review text will appear here.';
            document.getElementById('previewName').textContent =
                nameInput.value || 'Reviewer Name';
            document.getElementById('previewCompany').textContent =
                companyInput.value || 'Company';
        }

        [contentInput, nameInput, companyInput].forEach(el => {
            if (el) el.addEventListener('input', updatePreview);
        });
    </script>
@endpush
