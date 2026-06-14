@extends('layouts.admin')
@section('title', 'Quote #' . $quote->id)

@section('breadcrumb')
    <a href="{{ route('admin.quotes.index') }}">Quote Requests</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">Quote #{{ $quote->id }}</span>
@endsection

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Quote #{{ $quote->id }} — {{ $quote->full_name }}</h1>
            <p class="page-subtitle">Received {{ $quote->created_at->format('F j, Y \a\t g:i A') }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.quotes.index') }}" class="btn btn--ghost">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
            <button class="action-btn action-btn--delete btn btn--secondary"
                data-delete-url="{{ route('admin.quotes.destroy', $quote) }}"
                data-delete-label="Quote #{{ $quote->id }}">
                <i class="fa-solid fa-trash"></i> Delete
            </button>
        </div>
    </div>

    <div class="detail-layout">

        {{-- ── Left: Thread ─────────────────────────────────── --}}
        <div style="display:flex;flex-direction:column;gap:20px;">

            {{-- Original Request --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Quote Request Details</span>
                    <span
                        class="badge badge--{{ match ($quote->status) {
                            'new' => 'orange',
                            'open' => 'blue',
                            'in_progress' => 'yellow',
                            'quoted' => 'green',
                            'closed_won' => 'green',
                            'closed_lost' => 'gray',
                            default => 'gray',
                        } }}">{{ ucfirst(str_replace('_', ' ', $quote->status)) }}</span>
                </div>
                <div class="card-body">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                        <div>
                            <div class="form-label">Customer Name</div>
                            <div>{{ $quote->full_name }}</div>
                        </div>
                        <div>
                            <div class="form-label">Email</div>
                            <div><a href="mailto:{{ $quote->email }}"
                                    style="color:var(--primary);">{{ $quote->email }}</a></div>
                        </div>
                        @if ($quote->phone)
                            <div>
                                <div class="form-label">Phone</div>
                                <div>{{ $quote->phone }}</div>
                            </div>
                        @endif
                        @if ($quote->company)
                            <div>
                                <div class="form-label">Company</div>
                                <div>{{ $quote->company }}</div>
                            </div>
                        @endif
                    </div>

                    <div style="border-top:1px solid var(--card-border);padding-top:16px;margin-bottom:16px;">
                        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:12px;">
                            @if ($quote->make)
                                <div>
                                    <div class="form-label">Make</div>
                                    <div>{{ $quote->make }}</div>
                                </div>
                            @endif
                            @if ($quote->model)
                                <div>
                                    <div class="form-label">Model</div>
                                    <div>{{ $quote->model }}</div>
                                </div>
                            @endif
                            @if ($quote->serial_number)
                                <div>
                                    <div class="form-label">Serial #</div>
                                    <div>{{ $quote->serial_number }}</div>
                                </div>
                            @endif
                        </div>
                        @if ($quote->part_number)
                            <div style="margin-bottom:12px;">
                                <div class="form-label">Part Number Requested</div>
                                <div style="font-weight:600;font-size:15px;">{{ $quote->part_number }}</div>
                            </div>
                        @endif
                        <div>
                            <div class="form-label">Part Description</div>
                            <div style="margin-top:4px;line-height:1.6;">{{ $quote->part_description }}</div>
                        </div>
                        @if ($quote->quantity > 1)
                            <div style="margin-top:12px;">
                                <div class="form-label">Quantity Needed</div>
                                <div>{{ $quote->quantity }}</div>
                            </div>
                        @endif
                        @if ($quote->notes)
                            <div style="margin-top:12px;">
                                <div class="form-label">Additional Notes</div>
                                <div style="margin-top:4px;line-height:1.6;color:var(--text-muted);">{{ $quote->notes }}
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Tracking --}}
                    @if ($quote->ip_address || $quote->referrer_url)
                        <div style="border-top:1px solid var(--card-border);padding-top:12px;">
                            <div class="form-label" style="margin-bottom:6px;">Tracking</div>
                            <div style="font-size:11px;color:var(--text-muted);display:flex;gap:16px;flex-wrap:wrap;">
                                @if ($quote->ip_address)
                                    <span>IP: {{ $quote->ip_address }}</span>
                                @endif
                                @if ($quote->utm_source)
                                    <span>Source: {{ $quote->utm_source }}</span>
                                @endif
                                @if ($quote->utm_medium)
                                    <span>Medium: {{ $quote->utm_medium }}</span>
                                @endif
                                @if ($quote->utm_campaign)
                                    <span>Campaign: {{ $quote->utm_campaign }}</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Reply Thread --}}
            @if ($quote->replies->count())
                <div class="card">
                    <div class="card-header"><span class="card-title">Conversation Thread</span></div>
                    <div class="card-body">
                        <div class="message-thread">
                            @foreach ($quote->replies as $reply)
                                <div>
                                    <div
                                        class="message-bubble {{ $reply->is_admin ? 'message-bubble--admin' : 'message-bubble--customer' }}">
                                        {!! nl2br(e($reply->message)) !!}
                                    </div>
                                    <div class="message-meta {{ $reply->is_admin ? 'text-right' : '' }}">
                                        @if ($reply->is_admin)
                                            Sent by {{ $reply->admin?->name ?? 'Admin' }}
                                            @if ($reply->email_sent)
                                                <span class="badge badge--green" style="font-size:9px;">Email Sent</span>
                                            @endif
                                        @else
                                            Customer response
                                        @endif
                                        · {{ $reply->created_at->format('M d, Y g:i A') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Reply Form --}}
            <div class="card">
                <div class="card-header"><span class="card-title">Send Reply</span></div>
                <div class="card-body">
                    <form action="{{ route('admin.quotes.reply', $quote) }}" method="POST">
                        @csrf
                        <div class="form-group" style="margin-bottom:14px;">
                            <label class="form-label" for="reply_message">
                                Reply to {{ $quote->email }}
                            </label>
                            <textarea name="message" id="reply_message"
                                class="form-control {{ $errors->has('message') ? 'form-control--error' : '' }}" rows="6"
                                placeholder="Type your reply…" required>{{ old('message') }}</textarea>
                            @error('message')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div style="display:flex;align-items:center;justify-content:space-between;">
                            <label class="form-check">
                                <input type="checkbox" name="send_email" value="1" checked>
                                Send email to customer
                            </label>
                            <button type="submit" class="btn btn--primary">
                                <i class="fa-solid fa-paper-plane"></i> Send Reply
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        {{-- ── Right: Sidebar Controls ──────────────────────── --}}
        <div style="display:flex;flex-direction:column;gap:20px;">

            {{-- Status --}}
            <div class="form-sidebar-card">
                <div class="form-sidebar-section">
                    <div class="form-sidebar-title">Status</div>
                    <select id="quickStatusSelect" class="form-control"
                        data-url="{{ route('admin.quotes.status', $quote) }}">
                        @foreach (['new' => 'New', 'open' => 'Open', 'in_progress' => 'In Progress', 'quoted' => 'Quoted', 'closed_won' => 'Closed — Won', 'closed_lost' => 'Closed — Lost'] as $val => $label)
                            <option value="{{ $val }}" {{ $quote->status === $val ? 'selected' : '' }}>
                                {{ $label }}</option>
                        @endforeach
                    </select>
                    <div class="form-hint" style="margin-top:6px;">Changes save automatically</div>
                </div>
                <div class="form-sidebar-section">
                    <form action="{{ route('admin.quotes.update', $quote->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="form-sidebar-title">Assign To</div>
                        <div class="form-group" style="margin-bottom:12px;">
                            <select name="assigned_to" class="form-control">
                                <option value="">— Unassigned —</option>
                                @foreach ($admins as $admin)
                                    <option value="{{ $admin->id }}"
                                        {{ $quote->assigned_to == $admin->id ? 'selected' : '' }}>
                                        {{ $admin->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom:12px;">
                            <label class="form-label">Admin Notes (internal)</label>
                            <textarea name="admin_notes" class="form-control" rows="3" placeholder="Notes visible only to admins…">{{ $quote->admin_notes }}</textarea>
                        </div>
                        <button type="submit" class="btn btn--secondary w-full" style="justify-content:center;">
                            Save Notes
                        </button>
                    </form>
                </div>
            </div>

            {{-- Customer Info --}}
            <div class="form-sidebar-card">
                <div class="form-sidebar-section">
                    <div class="form-sidebar-title">Customer Quick Info</div>
                    <table style="width:100%;font-size:12px;border-collapse:collapse;">
                        <tr>
                            <td style="padding:4px 0;color:var(--text-muted);">Name</td>
                            <td style="padding:4px 0;font-weight:500;">{{ $quote->full_name }}</td>
                        </tr>
                        <tr>
                            <td style="padding:4px 0;color:var(--text-muted);">Email</td>
                            <td style="padding:4px 0;"><a href="mailto:{{ $quote->email }}"
                                    style="color:var(--primary);">{{ $quote->email }}</a></td>
                        </tr>
                        @if ($quote->phone)
                            <tr>
                                <td style="padding:4px 0;color:var(--text-muted);">Phone</td>
                                <td style="padding:4px 0;">{{ $quote->phone }}</td>
                            </tr>
                        @endif
                        @if ($quote->company)
                            <tr>
                                <td style="padding:4px 0;color:var(--text-muted);">Company</td>
                                <td style="padding:4px 0;">{{ $quote->company }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td style="padding:4px 0;color:var(--text-muted);">Replies</td>
                            <td style="padding:4px 0;">{{ $quote->replies->count() }}</td>
                        </tr>
                    </table>
                    <div style="margin-top:12px;display:flex;gap:8px;flex-direction:column;">
                        <a href="mailto:{{ $quote->email }}" class="btn btn--secondary btn--sm w-full"
                            style="justify-content:center;">
                            <i class="fa-solid fa-envelope"></i> Email Customer
                        </a>
                        @if ($quote->phone)
                            <a href="tel:{{ $quote->phone }}" class="btn btn--secondary btn--sm w-full"
                                style="justify-content:center;">
                                <i class="fa-solid fa-phone"></i> Call Customer
                            </a>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection
