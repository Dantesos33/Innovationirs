@extends('layouts.app')

@section('meta_title',
    'Apply — ' .
    $career->title .
    ' | ' .
    config(
    'amsparts.company_name',
    'Parts Plus Innovation
    Solutions',
    ))
@section('meta_description', 'Apply for the ' . $career->title . ' position at ' . config('amsparts.company_name',
    'Parts Plus Innovation Solutions'))
@section('body_class', 'page-careers-apply')

@section('content')

    <div class="page-hero">
        <div class="container">
            @include('partials.breadcrumb', [
                'crumbs' => [
                    ['label' => 'Careers', 'url' => route('careers')],
                    ['label' => 'Apply', 'url' => null],
                ],
            ])
            <div class="page-hero-label">Now Hiring</div>
            <h1 class="page-hero-title">{{ $career->title }}</h1>
            <div class="apply-hero-meta">
                @if ($career->department)
                    <span><i class="fa-solid fa-building"></i> {{ $career->department }}</span>
                @endif
                @if ($career->location)
                    <span><i class="fa-solid fa-location-dot"></i> {{ $career->location }}</span>
                @endif
                <span><i class="fa-regular fa-clock"></i> {{ $career->job_type_label }}</span>
                @if ($career->salary_range)
                    <span><i class="fa-solid fa-dollar-sign"></i> {{ $career->salary_range }}</span>
                @endif
            </div>
        </div>
    </div>

    <div class="section section--warm">
        <div class="container">
            <div class="apply-layout">

                {{-- ── Application Form ── --}}
                <div>

                    @if (session('success'))
                        <div class="alert alert-success" style="margin-bottom:24px;">
                            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-error" style="margin-bottom:24px;">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <ul style="margin:6px 0 0 16px;padding:0;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="contact-form-wrap" data-reveal>
                        <div class="contact-form-header">
                            <h2 class="contact-form-title">Submit Your Application</h2>
                            <p class="contact-form-sub">All fields marked <span style="color:var(--orange);">*</span> are
                                required. Upload your CV in PDF or Word format.</p>
                        </div>

                        <form action="{{ route('careers.apply.store', $career) }}" method="POST"
                            enctype="multipart/form-data" class="contact-form">
                            @csrf

                            <div class="form-row form-row--2">
                                <div class="form-group">
                                    <label class="form-label" for="first_name">First Name <span
                                            class="req">*</span></label>
                                    <input type="text" id="first_name" name="first_name"
                                        class="form-input {{ $errors->has('first_name') ? 'form-input--error' : '' }}"
                                        value="{{ old('first_name') }}" required>
                                    @error('first_name')
                                        <span class="form-error-msg">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="last_name">Last Name <span
                                            class="req">*</span></label>
                                    <input type="text" id="last_name" name="last_name"
                                        class="form-input {{ $errors->has('last_name') ? 'form-input--error' : '' }}"
                                        value="{{ old('last_name') }}" required>
                                    @error('last_name')
                                        <span class="form-error-msg">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row form-row--2">
                                <div class="form-group">
                                    <label class="form-label" for="email">Email Address <span
                                            class="req">*</span></label>
                                    <input type="email" id="email" name="email"
                                        class="form-input {{ $errors->has('email') ? 'form-input--error' : '' }}"
                                        value="{{ old('email') }}" required>
                                    @error('email')
                                        <span class="form-error-msg">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="phone">Phone Number</label>
                                    <input type="tel" id="phone" name="phone" class="form-input"
                                        value="{{ old('phone') }}">
                                </div>
                            </div>

                            <div class="form-row form-row--2">
                                <div class="form-group">
                                    <label class="form-label" for="city">City / Location</label>
                                    <input type="text" id="city" name="city" class="form-input"
                                        value="{{ old('city') }}" placeholder="e.g. Middletown, OH">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="linkedin_url">LinkedIn Profile</label>
                                    <input type="url" id="linkedin_url" name="linkedin_url"
                                        class="form-input {{ $errors->has('linkedin_url') ? 'form-input--error' : '' }}"
                                        value="{{ old('linkedin_url') }}" placeholder="https://linkedin.com/in/...">
                                    @error('linkedin_url')
                                        <span class="form-error-msg">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="cv">Upload CV / Resume <span
                                        class="req">*</span></label>
                                <div class="file-upload-area {{ $errors->has('cv') ? 'file-upload-area--error' : '' }}">
                                    <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" required>
                                    <div class="file-upload-icon"><i class="fa-solid fa-file-arrow-up"></i></div>
                                    <div class="file-upload-text">Click to upload or drag & drop</div>
                                    <div class="file-upload-hint">PDF, DOC, DOCX — max 5MB</div>
                                </div>
                                @error('cv')
                                    <span class="form-error-msg">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="cover_letter">Cover Letter</label>
                                <textarea id="cover_letter" name="cover_letter" class="form-textarea" rows="6"
                                    placeholder="Tell us why you'd be a great fit for this role…">{{ old('cover_letter') }}</textarea>
                            </div>

                            <div class="form-submit-row">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fa-solid fa-paper-plane"></i> Submit Application
                                </button>
                                <a href="{{ route('careers') }}" class="btn btn-ghost">
                                    <i class="fa-solid fa-arrow-left"></i> Back to Jobs
                                </a>
                            </div>

                        </form>
                    </div>
                </div>

                {{-- ── Job Details Sidebar ── --}}
                <div>
                    <div class="apply-job-sidebar" data-reveal>
                        <div class="apply-sidebar-title">About This Role</div>

                        @if ($career->description)
                            <div class="apply-sidebar-section">
                                <div class="apply-sidebar-label">Description</div>
                                <div class="apply-sidebar-body">{{ $career->description }}</div>
                            </div>
                        @endif

                        @if ($career->requirements)
                            <div class="apply-sidebar-section">
                                <div class="apply-sidebar-label">Requirements</div>
                                <div class="apply-sidebar-body">{{ $career->requirements }}</div>
                            </div>
                        @endif

                        @if ($career->benefits)
                            <div class="apply-sidebar-section">
                                <div class="apply-sidebar-label">Benefits</div>
                                <div class="apply-sidebar-body">{{ $career->benefits }}</div>
                            </div>
                        @endif

                        <div class="apply-sidebar-section" style="border-bottom:none;padding-bottom:0;">
                            <div class="apply-sidebar-label">Posted</div>
                            <div style="font-size:13px;color:var(--gray-600);">{{ $career->posted_at->format('F j, Y') }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .apply-hero-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            margin-top: 12px;
            font-size: 14px;
            color: rgba(255, 255, 255, .8);
        }

        .apply-hero-meta i {
            color: var(--orange);
            margin-right: 4px;
        }

        .apply-layout {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 32px;
            align-items: start;
        }

        @media(max-width:900px) {
            .apply-layout {
                grid-template-columns: 1fr;
            }
        }

        .file-upload-area {
            border: 2px dashed var(--gray-300);
            border-radius: var(--radius-lg);
            padding: 28px 20px;
            text-align: center;
            cursor: pointer;
            transition: border-color var(--transition), background var(--transition);
            position: relative;
            background: var(--white);
        }

        .file-upload-area:hover {
            border-color: var(--orange);
            background: var(--orange-pale);
        }

        .file-upload-area--error {
            border-color: var(--error);
        }

        .file-upload-area input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }

        .file-upload-icon {
            font-size: 28px;
            color: var(--orange);
            margin-bottom: 8px;
        }

        .file-upload-text {
            font-size: 14px;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 4px;
        }

        .file-upload-hint {
            font-size: 12px;
            color: var(--gray-400);
        }

        .form-row--2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        @media(max-width:600px) {
            .form-row--2 {
                grid-template-columns: 1fr;
            }
        }

        .req {
            color: var(--orange);
        }

        .form-error-msg {
            font-size: 12px;
            color: var(--error);
            margin-top: 4px;
            display: block;
        }

        .form-submit-row {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-top: 8px;
            flex-wrap: wrap;
        }

        .apply-job-sidebar {
            background: var(--white);
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-xl);
            overflow: hidden;
            position: sticky;
            top: 100px;
        }

        .apply-sidebar-title {
            font-family: var(--font-display);
            font-size: 16px;
            font-weight: 700;
            color: var(--white);
            background: var(--orange);
            padding: 14px 20px;
        }

        .apply-sidebar-section {
            padding: 16px 20px;
            border-bottom: 1px solid var(--gray-100);
        }

        .apply-sidebar-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--orange);
            margin-bottom: 6px;
        }

        .apply-sidebar-body {
            font-size: 13px;
            color: var(--gray-600);
            line-height: 1.65;
            white-space: pre-line;
        }
    </style>
@endpush
