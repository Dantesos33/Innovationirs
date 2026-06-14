@extends('layouts.admin')
@section('title', 'Contact #' . $contact->id)

@section('breadcrumb')
    <a href="{{ route('admin.contacts.index') }}">Contact Messages</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">Message #{{ $contact->id }}</span>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Message #{{ $contact->id }} — {{ $contact->full_name }}</h1>
        <p class="page-subtitle">Received {{ $contact->created_at->format('F j, Y \a\t g:i A') }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.contacts.index') }}" class="btn btn--ghost">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
        <button class="btn btn--secondary"
                data-delete-url="{{ route('admin.contacts.destroy', $contact) }}"
                data-delete-label="Message #{{ $contact->id }}">
            <i class="fa-solid fa-trash"></i> Delete
        </button>
    </div>
</div>

<div class="detail-layout">

    {{-- ── Left: Message Thread ──────────────────────────── --}}
    <div style="display:flex;flex-direction:column;gap:20px;">

        {{-- Original Message --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Original Message</span>
                <span class="badge badge--{{ match($contact->status) {
                    'new'=>'orange','open'=>'blue','in_progress'=>'yellow','resolved'=>'green',default=>'gray'
                } }}">{{ ucfirst(str_replace('_',' ',$contact->status)) }}</span>
            </div>
            <div class="card-body">
                {{-- Customer Info Grid --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid var(--card-border);">
                    <div>
                        <div class="form-label" style="margin-bottom:3px;">Name</div>
                        <div style="font-weight:500;">{{ $contact->full_name }}</div>
                    </div>
                    <div>
                        <div class="form-label" style="margin-bottom:3px;">Email</div>
                        <div><a href="mailto:{{ $contact->email }}" style="color:var(--primary);">{{ $contact->email }}</a></div>
                    </div>
                    @if($contact->phone)
                    <div>
                        <div class="form-label" style="margin-bottom:3px;">Phone</div>
                        <div>{{ $contact->phone }}</div>
                    </div>
                    @endif
                    @if($contact->company)
                    <div>
                        <div class="form-label" style="margin-bottom:3px;">Company</div>
                        <div>{{ $contact->company }}</div>
                    </div>
                    @endif
                </div>

                {{-- Subject --}}
                @if($contact->subject)
                <div style="margin-bottom:14px;">
                    <div class="form-label" style="margin-bottom:3px;">Subject</div>
                    <div style="font-weight:600;font-size:15px;">{{ $contact->subject }}</div>
                </div>
                @endif

                {{-- Message Body --}}
                <div>
                    <div class="form-label" style="margin-bottom:6px;">Message</div>
                    <div style="background:var(--gray-50);border:1px solid var(--card-border);border-radius:var(--radius);padding:16px;line-height:1.7;font-size:13px;">
                        {!! nl2br(e($contact->message)) !!}
                    </div>
                </div>

                {{-- Tracking --}}
                @if($contact->ip_address || $contact->referrer_url)
                <div style="margin-top:14px;padding-top:14px;border-top:1px solid var(--card-border);">
                    <div style="font-size:11px;color:var(--text-muted);display:flex;gap:20px;flex-wrap:wrap;">
                        @if($contact->ip_address)<span>IP: {{ $contact->ip_address }}</span>@endif
                        @if($contact->referrer_url)<span>Referred from: {{ Str::limit($contact->referrer_url, 60) }}</span>@endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Reply Thread --}}
        @if($contact->replies->count())
        <div class="card">
            <div class="card-header">
                <span class="card-title">Conversation Thread</span>
                <span class="badge badge--gray">{{ $contact->replies->count() }} {{ Str::plural('reply', $contact->replies->count()) }}</span>
            </div>
            <div class="card-body">
                <div class="message-thread">
                    @foreach($contact->replies as $reply)
                        <div>
                            <div class="message-bubble {{ $reply->is_admin ? 'message-bubble--admin' : 'message-bubble--customer' }}">
                                {!! nl2br(e($reply->message)) !!}
                            </div>
                            <div class="message-meta" style="{{ $reply->is_admin ? 'text-align:right;' : '' }}">
                                @if($reply->is_admin)
                                    Reply by <strong>{{ $reply->admin?->name ?? 'Admin' }}</strong>
                                    @if($reply->email_sent)
                                        &nbsp;<span class="badge badge--green" style="font-size:9px;">
                                            <i class="fa-solid fa-envelope"></i> Emailed
                                        </span>
                                    @endif
                                @else
                                    Customer response
                                @endif
                                &middot; {{ $reply->created_at->format('M d, Y g:i A') }}
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
                <form action="{{ route('admin.contacts.reply', $contact) }}" method="POST">
                    @csrf
                    <div class="form-group" style="margin-bottom:14px;">
                        <label class="form-label" for="reply_message">
                            Reply to {{ $contact->email }}
                        </label>
                        <textarea name="message" id="reply_message"
                                  class="form-control {{ $errors->has('message') ? 'form-control--error' : '' }}"
                                  rows="6"
                                  placeholder="Type your reply to this customer…" required>{{ old('message') }}</textarea>
                        @error('message')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
                        <label class="form-check">
                            <input type="checkbox" name="send_email" value="1" checked>
                            Send reply via email to customer
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

        {{-- Status & Assignment --}}
        <div class="form-sidebar-card">
            <div class="form-sidebar-section">
                <div class="form-sidebar-title">Status</div>
                <select id="quickStatusSelect" class="form-control"
                        data-url="{{ route('admin.contacts.status', $contact) }}">
                    <option value="new"         {{ $contact->status === 'new'         ? 'selected' : '' }}>New</option>
                    <option value="open"        {{ $contact->status === 'open'        ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ $contact->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="resolved"    {{ $contact->status === 'resolved'    ? 'selected' : '' }}>Resolved</option>
                </select>
                <div class="form-hint" style="margin-top:6px;">Changes save automatically</div>
            </div>

            <div class="form-sidebar-section">
                <form action="{{ route('admin.contacts.update', $contact) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="form-sidebar-title">Assign To</div>
                    <div class="form-group" style="margin-bottom:12px;">
                        <select name="assigned_to" class="form-control">
                            <option value="">— Unassigned —</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->id }}"
                                    {{ $contact->assigned_to == $admin->id ? 'selected' : '' }}>
                                    {{ $admin->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom:12px;">
                        <label class="form-label">Internal Notes</label>
                        <textarea name="admin_notes" class="form-control" rows="3"
                                  placeholder="Private notes (not visible to customer)…">{{ $contact->admin_notes }}</textarea>
                    </div>
                    <button type="submit" class="btn btn--secondary w-full" style="justify-content:center;">
                        Save Notes
                    </button>
                </form>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="form-sidebar-card">
            <div class="form-sidebar-section">
                <div class="form-sidebar-title">Quick Actions</div>
                <div style="display:flex;flex-direction:column;gap:8px;">
                    <a href="mailto:{{ $contact->email }}" class="btn btn--secondary w-full" style="justify-content:center;">
                        <i class="fa-solid fa-envelope"></i> Email {{ $contact->first_name }}
                    </a>
                    @if($contact->phone)
                    <a href="tel:{{ $contact->phone }}" class="btn btn--secondary w-full" style="justify-content:center;">
                        <i class="fa-solid fa-phone"></i> Call {{ $contact->phone }}
                    </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Contact Summary --}}
        <div class="form-sidebar-card">
            <div class="form-sidebar-section">
                <div class="form-sidebar-title">Contact Summary</div>
                <table style="width:100%;font-size:12px;border-collapse:collapse;">
                    <tr>
                        <td style="padding:5px 0;color:var(--text-muted);width:90px;">Name</td>
                        <td style="padding:5px 0;font-weight:500;">{{ $contact->full_name }}</td>
                    </tr>
                    <tr>
                        <td style="padding:5px 0;color:var(--text-muted);">Email</td>
                        <td style="padding:5px 0;">{{ $contact->email }}</td>
                    </tr>
                    @if($contact->phone)
                    <tr>
                        <td style="padding:5px 0;color:var(--text-muted);">Phone</td>
                        <td style="padding:5px 0;">{{ $contact->phone }}</td>
                    </tr>
                    @endif
                    @if($contact->company)
                    <tr>
                        <td style="padding:5px 0;color:var(--text-muted);">Company</td>
                        <td style="padding:5px 0;">{{ $contact->company }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="padding:5px 0;color:var(--text-muted);">Replies</td>
                        <td style="padding:5px 0;">{{ $contact->replies->count() }}</td>
                    </tr>
                    <tr>
                        <td style="padding:5px 0;color:var(--text-muted);">Received</td>
                        <td style="padding:5px 0;">{{ $contact->created_at->format('M d, Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>

    </div>

</div>

@endsection
