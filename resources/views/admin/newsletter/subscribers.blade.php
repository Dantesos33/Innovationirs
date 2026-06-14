@extends('layouts.admin')
@section('title', 'Newsletter')
@section('breadcrumb')
    <span class="breadcrumb-current">Newsletter</span>
@endsection
@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Newsletter</h1>
            <p class="page-subtitle">{{ number_format($totalActive) }} active subscribers</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.newsletter.export') }}" class="btn btn--secondary">
                <i class="fa-solid fa-download"></i> Export List
            </a>
            <a href="{{ route('admin.newsletter.compose') }}" class="btn btn--primary">
                <i class="fa-solid fa-paper-plane"></i> New Campaign
            </a>
        </div>
    </div>

    {{-- Sub-Nav --}}
    <div style="display:flex;gap:4px;margin-bottom:20px;border-bottom:1px solid var(--card-border);padding-bottom:0;">
        <a href="{{ route('admin.newsletter.subscribers') }}"
            style="padding:10px 16px;font-size:13px;font-weight:500;border-bottom:2px solid {{ request()->routeIs('admin.newsletter.subscribers') ? 'var(--primary)' : 'transparent' }};color:{{ request()->routeIs('admin.newsletter.subscribers') ? 'var(--primary)' : 'var(--text-muted)' }};">
            Subscribers
        </a>
        <a href="{{ route('admin.newsletter.campaigns') }}"
            style="padding:10px 16px;font-size:13px;font-weight:500;border-bottom:2px solid {{ request()->routeIs('admin.newsletter.campaigns') ? 'var(--primary)' : 'transparent' }};color:{{ request()->routeIs('admin.newsletter.campaigns') ? 'var(--primary)' : 'var(--text-muted)' }};">
            Campaigns
        </a>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Source</th>
                        <th>Status</th>
                        <th>Subscribed</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscribers as $sub)
                        <tr>
                            <td class="table-name">{{ $sub->email }}</td>
                            <td style="font-size:12px;">{{ $sub->first_name }} {{ $sub->last_name }}</td>
                            <td style="font-size:12px;">{{ $sub->source ?? '—' }}</td>
                            <td>
                                <span class="badge badge--{{ $sub->is_active ? 'green' : 'gray' }}">
                                    {{ $sub->is_active ? 'Active' : 'Unsubscribed' }}
                                </span>
                            </td>
                            <td style="font-size:12px;">{{ $sub->subscribed_at?->format('M d, Y') }}</td>
                            <td>
                                <div class="table-actions">
                                    <button class="action-btn action-btn--delete"
                                        data-delete-url="{{ route('admin.newsletter.remove', $sub->id) }}"
                                        data-delete-label="{{ $sub->email }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="fa-solid fa-paper-plane"></i></div>
                                    <div class="empty-state-title">No subscribers yet</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($subscribers->hasPages())
            <div class="pagination-wrap">{{ $subscribers->withQueryString()->links('vendor.pagination.simple-admin') }}
            </div>
        @endif
    </div>
@endsection
