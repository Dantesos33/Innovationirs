@extends('layouts.admin')
@section('title', 'Campaign: ' . Str::limit($campaign->subject, 40))

@section('breadcrumb')
    <a href="{{ route('admin.newsletter.campaigns') }}">Newsletter</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">{{ Str::limit($campaign->subject, 40) }}</span>
@endsection

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">{{ $campaign->subject }}</h1>
            <p class="page-subtitle">
                Created by {{ $campaign->creator?->name }} &middot; {{ $campaign->created_at->format('M d, Y') }}
            </p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.newsletter.campaigns') }}" class="btn btn--ghost">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
            @if ($campaign->status === 'draft')
                <form action="{{ route('admin.newsletter.send') }}" method="POST"
                    onsubmit="return confirm('Send this campaign to {{ number_format($subscriberCount) }} active subscribers? This cannot be undone.');">
                    @csrf
                    <button type="submit" class="btn btn--primary">
                        <i class="fa-solid fa-paper-plane"></i>
                        Send to {{ number_format($subscriberCount) }} Subscribers
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="detail-layout">

        <div style="display:flex;flex-direction:column;gap:20px;">

            {{-- Email Preview --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Email Preview</span>
                    <span
                        class="badge badge--{{ match ($campaign->status) {
                            'draft' => 'gray',
                            'sending' => 'yellow',
                            'sent' => 'green',
                            default => 'gray',
                        } }}">{{ ucfirst($campaign->status) }}</span>
                </div>
                <div class="card-body">
                    {{-- Subject --}}
                    <div
                        style="border:1px solid var(--card-border);border-radius:var(--radius);padding:14px 16px;margin-bottom:12px;background:var(--gray-50);">
                        <div style="font-size:11px;color:var(--text-muted);margin-bottom:3px;">Subject:</div>
                        <div style="font-weight:600;">{{ $campaign->subject }}</div>
                        @if ($campaign->preview_text)
                            <div style="font-size:12px;color:var(--text-muted);margin-top:3px;">
                                {{ $campaign->preview_text }}</div>
                        @endif
                    </div>

                    {{-- Body --}}
                    <div
                        style="border:1px solid var(--card-border);border-radius:var(--radius);padding:24px;min-height:200px;font-size:14px;line-height:1.7;">
                        {!! $campaign->body_html !!}
                    </div>
                </div>
            </div>

        </div>

        {{-- Stats Sidebar --}}
        <div style="display:flex;flex-direction:column;gap:20px;">

            @if ($campaign->status === 'sent')
                <div class="card" style="padding:0;">
                    <div style="padding:16px 20px;border-bottom:1px solid var(--card-border);">
                        <div
                            style="font-size:12px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.06em;">
                            Campaign Stats
                        </div>
                    </div>
                    @php
                        $stats = [
                            'Recipients' => number_format($campaign->recipient_count ?? 0),
                            'Delivered' => number_format($campaign->delivered_count ?? 0),
                            'Opens' => number_format($campaign->open_count ?? 0),
                            'Open Rate' => number_format($campaign->open_rate, 1) . '%',
                            'Sent At' => $campaign->sent_at?->format('M d, Y g:i A'),
                        ];
                    @endphp
                    @foreach ($stats as $label => $val)
                        <div
                            style="display:flex;justify-content:space-between;padding:12px 20px;border-bottom:1px solid var(--card-border);font-size:13px;">
                            <span style="color:var(--text-muted);">{{ $label }}</span>
                            <span style="font-weight:600;">{{ $val }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="form-sidebar-card">
                    <div class="form-sidebar-section">
                        <div class="form-sidebar-title">Ready to Send?</div>
                        <div style="font-size:12px;color:var(--text-muted);margin-bottom:16px;line-height:1.6;">
                            This draft will be sent to
                            <strong style="color:var(--text-base);">{{ number_format($subscriberCount) }} active
                                subscribers</strong>.
                            This action cannot be undone.
                        </div>
                        <form action="{{ route('admin.newsletter.send') }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to send this campaign now?');">
                            @csrf
                            <button type="submit" class="btn btn--primary w-full" style="justify-content:center;">
                                <i class="fa-solid fa-paper-plane"></i>
                                Send Campaign Now
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <div class="form-sidebar-card">
                <div class="form-sidebar-section">
                    <div class="form-sidebar-title">Campaign Details</div>
                    <table style="width:100%;font-size:12px;border-collapse:collapse;">
                        <tr>
                            <td style="padding:5px 0;color:var(--text-muted);">Created</td>
                            <td style="padding:5px 0;font-weight:500;">{{ $campaign->created_at->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td style="padding:5px 0;color:var(--text-muted);">Created By</td>
                            <td style="padding:5px 0;font-weight:500;">{{ $campaign->creator?->name }}</td>
                        </tr>
                        <tr>
                            <td style="padding:5px 0;color:var(--text-muted);">Status</td>
                            <td style="padding:5px 0;font-weight:500;">{{ ucfirst($campaign->status) }}</td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>

    </div>

@endsection
