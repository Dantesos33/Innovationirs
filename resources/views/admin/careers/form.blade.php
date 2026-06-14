@extends('layouts.admin')
@section('title', isset($career) ? 'Edit Posting' : 'Add Posting')

@section('breadcrumb')
    <a href="{{ route('admin.careers.index') }}">Careers</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">{{ isset($career) ? 'Edit: ' . $career->title : 'Add Posting' }}</span>
@endsection

@section('content')

    <form action="{{ isset($career) ? route('admin.careers.update', $career) : route('admin.careers.store') }}"
        method="POST">
        @csrf
        @if (isset($career))
            @method('PUT')
        @endif

        <div class="page-header">
            <div>
                <h1 class="page-title">{{ isset($career) ? 'Edit Career Posting' : 'Add Career Posting' }}</h1>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.careers.index') }}" class="btn btn--ghost">Cancel</a>
                <button type="submit" class="btn btn--primary">
                    <i class="fa-solid fa-floppy-disk"></i> Save Posting
                </button>
            </div>
        </div>

        <div class="form-layout">

            <div style="display:flex;flex-direction:column;gap:20px;">

                <div class="card">
                    <div class="card-header"><span class="card-title">Job Details</span></div>
                    <div class="card-body">
                        <div class="form-grid form-grid--2" style="gap:16px;">

                            <div class="form-group form-group--full">
                                <label class="form-label" for="title">Job Title <span class="required">*</span></label>
                                <input type="text" id="title" name="title"
                                    class="form-control {{ $errors->has('title') ? 'form-control--error' : '' }}"
                                    value="{{ old('title', $career->title ?? '') }}"
                                    placeholder="e.g. Parts Sales Representative" required>
                                @error('title')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Department</label>
                                <input type="text" name="department" class="form-control"
                                    value="{{ old('department', $career->department ?? '') }}"
                                    placeholder="e.g. Sales, Warehouse, Operations">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Location</label>
                                <input type="text" name="location" class="form-control"
                                    value="{{ old('location', $career->location ?? '') }}"
                                    placeholder="e.g. Middletown, OH">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Job Type <span class="required">*</span></label>
                                <select name="job_type"
                                    class="form-control {{ $errors->has('job_type') ? 'form-control--error' : '' }}"
                                    required>
                                    @foreach ([
            'full_time' => 'Full Time',
            'part_time' => 'Part Time',
            'contract' => 'Contract',
            'seasonal' => 'Seasonal',
            'internship' => 'Internship',
        ] as $val => $label)
                                        <option value="{{ $val }}"
                                            {{ old('job_type', $career->job_type ?? 'full_time') === $val ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Salary Range</label>
                                <input type="text" name="salary_range" class="form-control"
                                    value="{{ old('salary_range', $career->salary_range ?? '') }}"
                                    placeholder="e.g. $45,000–$60,000/yr or DOE">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Apply Email</label>
                                <input type="email" name="apply_email" class="form-control"
                                    value="{{ old('apply_email', $career->apply_email ?? config('amsparts.jobs_email')) }}"
                                    placeholder="jobs@example.com">
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Description --}}
                <div class="card">
                    <div class="card-header"><span class="card-title">Job Description</span></div>
                    <div class="card-body" style="display:flex;flex-direction:column;gap:16px;">

                        <div class="form-group">
                            <label class="form-label" for="description">Description <span class="required">*</span></label>
                            <textarea name="description" id="description"
                                class="form-control {{ $errors->has('description') ? 'form-control--error' : '' }}" rows="6"
                                placeholder="Describe the role, day-to-day responsibilities…" required>{{ old('description', $career->description ?? '') }}</textarea>
                            @error('description')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Requirements</label>
                            <textarea name="requirements" class="form-control" rows="5"
                                placeholder="List qualifications, experience, skills required…">{{ old('requirements', $career->requirements ?? '') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Benefits</label>
                            <textarea name="benefits" class="form-control" rows="4"
                                placeholder="List benefits offered: health, dental, 401k, PTO…">{{ old('benefits', $career->benefits ?? '') }}</textarea>
                        </div>

                    </div>
                </div>

            </div>

            {{-- Sidebar --}}
            <div style="display:flex;flex-direction:column;gap:20px;">

                <div class="form-sidebar-card">
                    <div class="form-sidebar-section">
                        <div class="form-sidebar-title">Posting Settings</div>
                        <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:14px;">
                            <label class="toggle-switch">
                                <input type="hidden" name="is_active" value="0">
                                <input class="toggle-input" type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', $career->is_active ?? true) ? 'checked' : '' }}>
                                <span class="toggle-track"></span>
                                <span class="toggle-label">Active (visible on site)</span>
                            </label>
                        </div>
                        <div class="form-group" style="margin-bottom:12px;">
                            <label class="form-label">Posted Date</label>
                            <input type="date" name="posted_at" class="form-control"
                                value="{{ old('posted_at', isset($career->posted_at) ? $career->posted_at->format('Y-m-d') : now()->format('Y-m-d')) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Expiry Date</label>
                            <input type="date" name="expires_at" class="form-control"
                                value="{{ old('expires_at', isset($career->expires_at) ? $career->expires_at->format('Y-m-d') : '') }}">
                            <span class="form-hint">Leave blank for no expiry</span>
                        </div>
                    </div>
                    <div class="form-sidebar-section">
                        <button type="submit" class="btn btn--primary w-full" style="justify-content:center;">
                            <i class="fa-solid fa-floppy-disk"></i> Save Posting
                        </button>
                    </div>
                </div>

            </div>

        </div>

    </form>

@endsection
