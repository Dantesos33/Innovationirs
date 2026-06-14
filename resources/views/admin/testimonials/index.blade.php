@extends('layouts.admin')
@section('title', 'Testimonials')
@section('breadcrumb')
    <span class="breadcrumb-current">Testimonials</span>
@endsection
@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Testimonials</h1>
            <p class="page-subtitle">{{ $testimonials->total() }} customer reviews</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.testimonials.create') }}" class="btn btn--primary">
                <i class="fa-solid fa-plus"></i> Add Testimonial
            </a>
        </div>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Reviewer</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Featured</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($testimonials as $t)
                        <tr>
                            <td>
                                <div class="table-name">{{ $t->reviewer_name }}</div>
                                @if ($t->company)
                                    <div class="table-meta">{{ $t->company }}</div>
                                @endif
                                @if ($t->location)
                                    <div class="table-meta">{{ $t->location }}</div>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex;gap:2px;color:#F59E0B;font-size:12px;">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fa-{{ $i <= $t->rating ? 'solid' : 'regular' }} fa-star"></i>
                                    @endfor
                                </div>
                            </td>
                            <td style="max-width:300px;font-size:12px;color:var(--text-muted);">
                                {{ Str::limit($t->content, 100) }}
                            </td>
                            <td>
                                <span class="badge badge--{{ $t->is_featured ? 'yellow' : 'gray' }}">
                                    {{ $t->is_featured ? 'Featured' : 'No' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge--{{ $t->is_active ? 'green' : 'red' }}">
                                    {{ $t->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.testimonials.edit', $t) }}"
                                        class="action-btn action-btn--edit"><i class="fa-solid fa-pen"></i></a>
                                    <button class="action-btn action-btn--delete"
                                        data-delete-url="{{ route('admin.testimonials.destroy', $t) }}"
                                        data-delete-label="{{ $t->reviewer_name }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="fa-solid fa-star"></i></div>
                                    <div class="empty-state-title">No testimonials yet</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($testimonials->hasPages())
            <div class="pagination-wrap">{{ $testimonials->links('vendor.pagination.simple-admin') }}</div>
        @endif
    </div>
@endsection
