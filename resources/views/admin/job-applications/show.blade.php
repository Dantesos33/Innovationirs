@extends('layouts.admin')
@section('title', 'Application #' . $application->id)

@section('breadcrumb')
    <a href="{{ route('admin.careers.index') }}">Careers</a>
    <span class="breadcrumb-sep">/</span>
    <a href="{{ route('admin.job-applications.index') }}">Applications</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">{{ $application->full_name }}</span>
@endsection

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">{{ $application->full_name }}</h1>
            <p class="page-subtitle">
                Applied for <strong>{{ $application->careerPosting->title }}</strong>
                &mdash; {{ $application->created_at->format('F j, Y \a\t g:i A') }}
            </p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.job-applications.by-job', $application->careerPosting) }}" class="btn btn--ghost">
                <i class="fa-solid fa-arrow-left"></i> All for this Job
            </a>
            <a href="mailto:{{ $application->email }}" class="btn btn--secondary">
                <i class="fa-solid fa-envelope"></i> Email Applicant
            </a>
            @if ($application->cv_path)
                <a href="{{ $application->cv_url }}" target="_blank" class="btn btn--primary">
                    <i class="fa-solid fa-file-arrow-down"></i> Download CV
                </a>
            @endif
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert--success" style="margin-bottom:20px;">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <div class="detail-layout">

        {{-- ── Left: Application Details ── --}}
        <div style="display:flex;flex-direction:column;gap:20px;">

            {{-- Applicant Info --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Applicant Details</span>
                    <span class="badge badge--{{ $application->status_color }}">{{ $application->status_label }}</span>
                </div>
                <div class="card-body">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                        <div>
                            <div class="form-label" style="margin-bottom:3px;">Full Name</div>
                            <div style="font-weight:600;">{{ $application->full_name }}</div>
                        </div>
                        <div>
                            <div class="form-label" style="margin-bottom:3px;">Email</div>
                            <div><a href="mailto:{{ $application->email }}"
                                    style="color:var(--primary);">{{ $application->email }}</a></div>
                        </div>
                        @if ($application->phone)
                            <div>
                                <div class="form-label" style="margin-bottom:3px;">Phone</div>
                                <div><a href="tel:{{ $application->phone }}"
                                        style="color:var(--primary);">{{ $application->phone }}</a></div>
                            </div>
                        @endif
                        @if ($application->city)
                            <div>
                                <div class="form-label" style="margin-bottom:3px;">Location</div>
                                <div>{{ $application->city }}</div>
                            </div>
                        @endif
                        @if ($application->linkedin_url)
                            <div>
                                <div class="form-label" style="margin-bottom:3px;">LinkedIn</div>
                                <div><a href="{{ $application->linkedin_url }}" target="_blank"
                                        style="color:var(--primary);">View Profile</a></div>
                            </div>
                        @endif
                        <div>
                            <div class="form-label" style="margin-bottom:3px;">Position Applied</div>
                            <div style="font-weight:600;">{{ $application->careerPosting->title }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CV --}}
            @if ($application->cv_path)
                <div class="card">
                    <div class="card-header"><span class="card-title">CV / Resume</span></div>
                    <div class="card-body" style="display:flex;align-items:center;gap:16px;">
                        <div style="font-size:36px;color:var(--orange);"><i class="fa-solid fa-file-pdf"></i></div>
                        <div style="flex:1;">
                            <div style="font-weight:600;margin-bottom:4px;">{{ $application->cv_original_name }}</div>
                            <div style="font-size:12px;color:var(--text-muted);">Uploaded
                                {{ $application->created_at->format('M d, Y') }}</div>
                        </div>
                        <a href="{{ $application->cv_url }}" target="_blank" class="btn btn--primary btn--sm">
                            <i class="fa-solid fa-eye"></i> View / Download
                        </a>
                    </div>
                </div>
            @endif

            {{-- Cover Letter --}}
            @if ($application->cover_letter)
                <div class="card">
                    <div class="card-header"><span class="card-title">Cover Letter</span></div>
                    <div class="card-body">
                        <div style="font-size:14px;line-height:1.7;color:var(--gray-700);white-space:pre-line;">
                            {{ $application->cover_letter }}</div>
                    </div>
                </div>
            @endif

        </div>

        {{-- ── Right: Actions Sidebar ── --}}
        <div style="display:flex;flex-direction:column;gap:20px;">

            {{-- Update Status --}}
            <div class="form-sidebar-card">
                <form action="{{ route('admin.job-applications.update', $application) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="form-sidebar-section">
                        <div class="form-sidebar-title">Application Status</div>
                        <div class="form-group" style="margin-bottom:12px;">
                            <select name="status" class="form-control">
                                @foreach (['new' => 'New', 'reviewed' => 'Reviewed', 'shortlisted' => 'Shortlisted', 'rejected' => 'Rejected', 'hired' => 'Hired'] as $val => $label)
                                    <option value="{{ $val }}"
                                        {{ $application->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Internal Notes</label>
                            <textarea name="admin_notes" class="form-control" rows="4" placeholder="Notes visible to admin only…">{{ old('admin_notes', $application->admin_notes) }}</textarea>
                        </div>
                    </div>
                    <div class="form-sidebar-section">
                        <button type="submit" class="btn btn--primary w-full" style="justify-content:center;">
                            <i class="fa-solid fa-floppy-disk"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>

            {{-- Quick Actions --}}
            <div class="form-sidebar-card">
                <div class="form-sidebar-section" style="display:flex;flex-direction:column;gap:10px;">
                    <div class="form-sidebar-title">Quick Actions</div>
                    <a href="mailto:{{ $application->email }}" class="btn btn--secondary w-full"
                        style="justify-content:center;">
                        <i class="fa-solid fa-envelope"></i> Email Applicant
                    </a>
                    @if ($application->cv_path)
                        <a href="{{ $application->cv_url }}" target="_blank" class="btn btn--ghost w-full"
                            style="justify-content:center;">
                            <i class="fa-solid fa-file-arrow-down"></i> Download CV
                        </a>
                    @endif
                    <button class="btn btn--ghost w-full" style="justify-content:center;color:var(--error);"
                        data-delete-url="{{ route('admin.job-applications.destroy', $application) }}"
                        data-delete-label="{{ $application->full_name }}">
                        <i class="fa-solid fa-trash"></i> Delete Application
                    </button>
                </div>
            </div>

            {{-- Job Posting Info --}}
            <div class="form-sidebar-card">
                <div class="form-sidebar-section">
                    <div class="form-sidebar-title">Job Posting</div>
                    <div style="display:flex;flex-direction:column;gap:8px;font-size:13px;">
                        <div><span style="color:var(--text-muted);">Title:</span>
                            <strong>{{ $application->careerPosting->title }}</strong></div>
                        @if ($application->careerPosting->department)
                            <div><span style="color:var(--text-muted);">Dept:</span>
                                {{ $application->careerPosting->department }}</div>
                        @endif
                        @if ($application->careerPosting->location)
                            <div><span style="color:var(--text-muted);">Location:</span>
                                {{ $application->careerPosting->location }}</div>
                        @endif
                        <a href="{{ route('admin.job-applications.by-job', $application->careerPosting) }}"
                            class="btn btn--ghost btn--sm" style="margin-top:6px;">
                            View All Applicants
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection
