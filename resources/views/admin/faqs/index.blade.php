@extends('layouts.admin')
@section('title', 'FAQs')

@section('breadcrumb')
    <span class="breadcrumb-current">FAQs</span>
@endsection

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">FAQs</h1>
            <p class="page-subtitle">{{ $faqs->total() }} frequently asked questions</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.faqs.create') }}" class="btn btn--primary">
                <i class="fa-solid fa-plus"></i> Add FAQ
            </a>
        </div>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table class="admin-table" data-sortable="{{ route('admin.faqs.reorder') }}">
                <thead>
                    <tr>
                        <th style="width:30px;"></th>
                        <th>Question</th>
                        <th>Category</th>
                        <th>Views</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($faqs as $faq)
                        <tr data-id="{{ $faq->id }}">
                            <td><span class="sortable-handle"><i class="fa-solid fa-grip-vertical"></i></span></td>
                            <td>
                                <div class="table-name" style="max-width:420px;">{{ Str::limit($faq->question, 80) }}</div>
                                <div class="table-meta">{{ Str::limit(strip_tags($faq->answer), 80) }}</div>
                            </td>
                            <td>
                                @if ($faq->category)
                                    <span class="badge badge--blue">{{ ucfirst($faq->category) }}</span>
                                @else
                                    <span style="color:var(--text-faint);font-size:12px;">—</span>
                                @endif
                            </td>
                            <td style="font-size:12px;color:var(--text-muted);">{{ number_format($faq->views) }}</td>
                            <td>
                                <span class="badge badge--{{ $faq->is_active ? 'green' : 'red' }}">
                                    {{ $faq->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.faqs.edit', $faq) }}" class="action-btn action-btn--edit"
                                        title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <button class="action-btn action-btn--delete"
                                        data-delete-url="{{ route('admin.faqs.destroy', $faq) }}"
                                        data-delete-label="{{ Str::limit($faq->question, 50) }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="fa-solid fa-circle-question"></i></div>
                                    <div class="empty-state-title">No FAQs yet</div>
                                    <div class="empty-state-text" style="margin-top:12px;">
                                        <a href="{{ route('admin.faqs.create') }}" class="btn btn--primary btn--sm">Add
                                            First FAQ</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($faqs->hasPages())
            <div class="pagination-wrap">
                {{ $faqs->links('vendor.pagination.simple-admin') }}
            </div>
        @endif
    </div>

@endsection
