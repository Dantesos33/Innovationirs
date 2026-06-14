@extends('layouts.admin')
@section('title', 'Applications — ' . $career->title)

@section('breadcrumb')
    <a href="{{ route('admin.careers.index') }}">Careers</a>
    <span class="breadcrumb-sep">/</span>
    <a href="{{ route('admin.job-applications.index') }}">Applications</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">{{ $career->title }}</span>
@endsection

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">{{ $career->title }}</h1>
            <p class="page-subtitle">{{ $applications->total() }} application{{ $applications->total() !== 1 ? 's' : '' }}
            </p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.job-applications.index') }}" class="btn btn--ghost">
                <i class="fa-solid fa-arrow-left"></i> All Applications
            </a>
            <a href="{{ route('admin.careers.edit', $career) }}" class="btn btn--secondary">
                <i class="fa-solid fa-pen"></i> Edit Posting
            </a>
        </div>
    </div>

    {{-- Status breakdown --}}
    <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:20px;">
        @foreach (['new' => 'orange', 'reviewed' => 'blue', 'shortlisted' => 'green', 'rejected' => 'red', 'hired' => 'green'] as $s => $color)
            @php $count = $applications->where('status', $s)->count(); @endphp
            @if ($count > 0)
                <span class="badge badge--{{ $color }}">{{ ucfirst($s) }}: {{ $count }}</span>
            @endif
        @endforeach
    </div>

    <div class="card">
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Applicant</th>
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
                                @if ($app->phone)
                                    <div class="table-meta">{{ $app->phone }}</div>
                                @endif
                                @if ($app->city)
                                    <div class="table-meta"><i class="fa-solid fa-location-dot"></i> {{ $app->city }}
                                    </div>
                                @endif
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
                            <td><span class="badge badge--{{ $app->status_color }}">{{ $app->status_label }}</span></td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.job-applications.show', $app) }}"
                                        class="action-btn action-btn--edit">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
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
                            <td colspan="5">
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="fa-solid fa-file-lines"></i></div>
                                    <div class="empty-state-title">No applications for this position yet</div>
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
