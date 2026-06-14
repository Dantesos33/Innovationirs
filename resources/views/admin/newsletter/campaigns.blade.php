@extends('layouts.admin')
@section('title', 'Newsletter Campaigns')

@section('breadcrumb')
    <span class="breadcrumb-current">Newsletter</span>
@endsection

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Newsletter</h1>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.newsletter.compose') }}" class="btn btn--primary">
                <i class="fa-solid fa-plus"></i> New Campaign
            </a>
        </div>
    </div>

    {{-- Sub-Nav --}}
    <div style="display:flex;gap:0;margin-bottom:20px;border-bottom:1px solid var(--card-border);">
        <a href="{{ route('admin.newsletter.subscribers') }}"
            style="padding:10px 18px;font-size:13px;font-weight:500;border-bottom:2px solid transparent;color:var(--text-muted);text-decoration:none;">
            Subscribers
        </a>
        <a href="{{ route('admin.newsletter.campaigns') }}"
            style="padding:10px 18px;font-size:13px;font-weight:500;border-bottom:2px solid var(--primary);color:var(--primary);text-decoration:none;">
            Campaigns
        </a>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Recipients</th>
                        <th>Open Rate</th>
                        <th>Created By</th>
                        <th>Sent</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($campaigns as $campaign)
                        <tr>
                            <td>
                                <div class="table-name">{{ $campaign->subject }}</div>
                                @if ($campaign->preview_text)
                                    <div class="table-meta">{{ Str::limit($campaign->preview_text, 60) }}</div>
                                @endif
                            </td>
                            <td>
                                <span
                                    class="badge badge--{{ match ($campaign->status) {
                                        'draft' => 'gray',
                                        'scheduled' => 'blue',
                                        'sending' => 'yellow',
                                        'sent' => 'green',
                                        default => 'gray',
                                    } }}">{{ ucfirst($campaign->status) }}</span>
                            </td>
                            <td style="font-size:12px;">
                                {{ $campaign->recipient_count ? number_format($campaign->recipient_count) : '—' }}
                            </td>
                            <td style="font-size:12px;">
                                @if ($campaign->status === 'sent' && $campaign->delivered_count > 0)
                                    {{ number_format($campaign->open_rate, 1) }}%
                                @else
                                    —
                                @endif
                            </td>
                            <td style="font-size:12px;">{{ $campaign->creator?->name }}</td>
                            <td style="font-size:12px;white-space:nowrap;">
                                {{ $campaign->sent_at ? $campaign->sent_at->format('M d, Y') : '—' }}
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.newsletter.show', $campaign->id) }}"
                                        class="action-btn action-btn--view" title="View">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    @if ($campaign->status === 'draft')
                                        <button class="action-btn action-btn--delete"
                                            data-delete-url="{{ route('admin.newsletter.remove', $campaign->id) }}"
                                            data-delete-label="{{ $campaign->subject }}">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="fa-solid fa-paper-plane"></i></div>
                                    <div class="empty-state-title">No campaigns yet</div>
                                    <div class="empty-state-text" style="margin-top:12px;">
                                        <a href="{{ route('admin.newsletter.compose') }}" class="btn btn--primary btn--sm">
                                            Create First Campaign
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($campaigns->hasPages())
            <div class="pagination-wrap">
                {{ $campaigns->links('vendor.pagination.simple-admin') }}
            </div>
        @endif
    </div>

@endsection
