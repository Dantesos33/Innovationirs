@extends('layouts.admin')
@section('title', 'Job Applications')

@section('breadcrumb')
    <a href="{{ route('admin.careers.index') }}">Careers</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">Applications</span>
@endsection

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Job Applications</h1>
            <p class="page-subtitle">{{ $applications->total() }} total applications</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.careers.index') }}" class="btn btn--ghost">
                <i class="fa-solid fa-briefcase"></i> Job Postings
            </a>
        </div>
    </div>

    {{-- Status summary cards --}}
    <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:24px;">
        @foreach (['new' => ['orange', 'New'], 'reviewed' => ['blue', 'Reviewed'], 'shortlisted' => ['green', 'Shortlisted'], 'rejected' => ['red', 'Rejected'], 'hired' => ['green', 'Hired']] as $s => [$color, $label])
            <div class="card" style="padding:16px;text-align:center;">
                <div
                    style="font-size:22px;font-weight:700;color:var(--{{ $color === 'orange' ? 'orange' : ($color === 'red' ? 'error' : 'success') }});">
                    {{ $applications->where('status', $s)->count() }}
                </div>
                <div
                    style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;margin-top:2px;">
                    {{ $label }}</div>
            </div>
        @endforeach
    </div>

    {{-- Filters --}}
    <div class="card" style="padding:16px;margin-bottom:20px;">
        <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
            <div class="form-group" style="margin:0;flex:1;min-width:200px;">
                <input type="text" name="search" class="form-control" placeholder="Search by name or email…"
                    value="{{ request('search') }}">
            </div>
            <div class="form-group" style="margin:0;">
                <select name="career_id" class="form-control">
                    <option value="">All Positions</option>
                    @foreach ($careers as $c)
                        <option value="{{ $c->id }}" {{ request('career_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="margin:0;">
                <select name="status" class="form-control">
                    <option value="">All Statuses</option>
                    @foreach (['new' => 'New', 'reviewed' => 'Reviewed', 'shortlisted' => 'Shortlisted', 'rejected' => 'Rejected', 'hired' => 'Hired'] as $val => $label)
                        <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>
                            {{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn--secondary">Filter</button>
            @if (request()->hasAny(['search', 'status', 'career_id']))
                <a href="{{ route('admin.job-applications.index') }}" class="btn btn--ghost">Clear</a>
            @endif
        </form>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Applicant</th>
                        <th>Position</th>
                        <th>CV</th>
                        <th>Applied</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $app)
                        <tr>
                            <td>
                                <div class="table-name">{{ $app->full_name }}</div>
                                <div class="table-meta">{{ $app->email }}</div>
                                @if ($app->city)
                                    <div class="table-meta"><i class="fa-solid fa-location-dot"></i> {{ $app->city }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div style="font-size:13px;font-weight:500;">{{ $app->careerPosting->title }}</div>
                                <div class="table-meta">{{ $app->careerPosting->department ?? '' }}</div>
                            </td>
                            <td>
                                @if ($app->cv_path)
                                    <a href="{{ $app->cv_url }}" target="_blank" class="btn btn--ghost btn--sm">
                                        <i class="fa-solid fa-file-pdf"></i> View CV
                                    </a>
                                @else
                                    <span style="color:var(--text-muted);font-size:12px;">—</span>
                                @endif
                            </td>
                            <td style="font-size:12px;color:var(--text-muted);">{{ $app->created_at->format('M d, Y') }}
                            </td>
                            <td>
                                <span class="badge badge--{{ $app->status_color }}">{{ $app->status_label }}</span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.job-applications.show', $app) }}"
                                        class="action-btn action-btn--edit" title="View"><i
                                            class="fa-solid fa-eye"></i></a>
                                    <button class="action-btn action-btn--delete"
                                        data-delete-url="{{ route('admin.job-applications.destroy', $app) }}"
                                        data-delete-label="{{ $app->full_name }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="fa-solid fa-file-lines"></i></div>
                                    <div class="empty-state-title">No applications yet</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($applications->hasPages())
            <div class="pagination-wrap">{{ $applications->links('vendor.pagination.simple-admin') }}</div>
        @endif
    </div>

@endsection
