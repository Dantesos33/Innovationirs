@extends('layouts.admin')
@section('title', 'Career Postings')

@section('breadcrumb')
    <span class="breadcrumb-current">Career Postings</span>
@endsection

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Career Postings</h1>
            <p class="page-subtitle">{{ $careers->total() }} job postings</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.careers.create') }}" class="btn btn--primary">
                <i class="fa-solid fa-plus"></i> Add Posting
            </a>
        </div>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Type</th>
                        <th>Location</th>
                        <th>Expires</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($careers as $career)
                        <tr>
                            <td>
                                <div class="table-name">{{ $career->title }}</div>
                                @if ($career->salary_range)
                                    <div class="table-meta">{{ $career->salary_range }}</div>
                                @endif
                            </td>
                            <td style="font-size:12px;">{{ $career->department ?? '—' }}</td>
                            <td>
                                <span class="badge badge--blue" style="font-size:10px;">
                                    {{ str_replace('_', ' ', ucfirst($career->job_type)) }}
                                </span>
                            </td>
                            <td style="font-size:12px;">{{ $career->location ?? '—' }}</td>
                            <td style="font-size:12px;">
                                @if ($career->expires_at)
                                    @if ($career->is_expired)
                                        <span style="color:var(--error);">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            Expired {{ $career->expires_at->format('M d') }}
                                        </span>
                                    @else
                                        {{ $career->expires_at->format('M d, Y') }}
                                    @endif
                                @else
                                    <span style="color:var(--text-muted);">No expiry</span>
                                @endif
                            </td>
                            <td>
                                <span
                                    class="badge badge--{{ $career->is_active && !$career->is_expired ? 'green' : 'red' }}">
                                    {{ $career->is_active && !$career->is_expired ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.careers.edit', $career) }}"
                                        class="action-btn action-btn--edit"><i class="fa-solid fa-pen"></i></a>
                                    <button class="action-btn action-btn--delete"
                                        data-delete-url="{{ route('admin.careers.destroy', $career) }}"
                                        data-delete-label="{{ $career->title }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="fa-solid fa-briefcase"></i></div>
                                    <div class="empty-state-title">No career postings</div>
                                    <div class="empty-state-text" style="margin-top:12px;">
                                        <a href="{{ route('admin.careers.create') }}" class="btn btn--primary btn--sm">Add
                                            First Posting</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($careers->hasPages())
            <div class="pagination-wrap">{{ $careers->links('vendor.pagination.simple-admin') }}</div>
        @endif
    </div>

@endsection
