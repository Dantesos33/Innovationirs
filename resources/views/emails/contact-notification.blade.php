@extends('emails.layouts.master')

@section('email_title', 'New Contact Message #' . $contact->id)
@section('hero_class', 'email-hero--blue')
@section('hero_icon') ✉️ @endsection
@section('hero_title', 'New Contact Message')
@section('hero_sub', 'A visitor has sent a message through the contact form.')

@section('email_body')

    <p class="email-greeting">Received {{ $contact->created_at->format('g:i A') }} &mdash;
        {{ $contact->created_at->format('F j, Y') }}</p>

    <p>
        <span class="status-badge status-badge--new">New &mdash; Needs Response</span>
    </p>

    {{-- Sender Info --}}
    <div class="info-box info-box--blue">
        <div class="info-box-title">Sender Details</div>
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td
                    style="padding:7px 0;font-size:13px;color:#1D4ED8;font-weight:500;border-bottom:1px solid #BFDBFE;width:40%;">
                    Name</td>
                <td
                    style="padding:7px 0;font-size:13px;color:#111113;font-weight:700;border-bottom:1px solid #BFDBFE;text-align:right;">
                    {{ $contact->full_name }}</td>
            </tr>
            <tr>
                <td style="padding:7px 0;font-size:13px;color:#1D4ED8;font-weight:500;border-bottom:1px solid #BFDBFE;">Email
                </td>
                <td style="padding:7px 0;font-size:13px;font-weight:600;border-bottom:1px solid #BFDBFE;text-align:right;">
                    <a href="mailto:{{ $contact->email }}" style="color:#1D4ED8;">{{ $contact->email }}</a>
                </td>
            </tr>
            @if ($contact->phone)
                <tr>
                    <td style="padding:7px 0;font-size:13px;color:#1D4ED8;font-weight:500;border-bottom:1px solid #BFDBFE;">
                        Phone</td>
                    <td
                        style="padding:7px 0;font-size:13px;color:#111113;font-weight:600;border-bottom:1px solid #BFDBFE;text-align:right;">
                        <a href="tel:{{ $contact->phone }}" style="color:#1D4ED8;">{{ $contact->phone }}</a>
                    </td>
                </tr>
            @endif
            @if ($contact->company)
                <tr>
                    <td style="padding:7px 0;font-size:13px;color:#1D4ED8;font-weight:500;">Company</td>
                    <td style="padding:7px 0;font-size:13px;color:#111113;font-weight:600;text-align:right;">
                        {{ $contact->company }}</td>
                </tr>
            @endif
        </table>
    </div>

    {{-- Subject --}}
    @if ($contact->subject)
        <p style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Subject:</p>
        <p style="font-size:15px;font-weight:700;color:#111113;margin-bottom:20px;">{{ $contact->subject }}</p>
    @endif

    {{-- Message --}}
    <p style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Message:</p>
    <div class="message-block" style="border-left-color:#1D4ED8;">
        {!! nl2br(e($contact->message)) !!}
    </div>

    {{-- Quick Actions --}}
    <div style="display:flex;gap:12px;justify-content:center;margin:28px 0;">
        <a href="{{ route('admin.contacts.show', $contact->id) }}" class="email-btn email-btn--secondary">
            View in Admin Panel
        </a>
        <a href="mailto:{{ $contact->email }}?subject=Re: {{ rawurlencode($contact->subject ?? 'Your Message') }}"
            class="email-btn">
            Reply via Email
        </a>
    </div>

    <hr class="email-divider">

    <p style="font-size:12px;color:#9CA3AF;text-align:center;margin:0;">
        Message #{{ $contact->id }}
        &nbsp;&middot;&nbsp; {{ $contact->created_at->format('M d, Y g:i A') }}
        @if ($contact->ip_address)
            &nbsp;&middot;&nbsp; IP: {{ $contact->ip_address }}
        @endif
        @if ($contact->referrer_url)
            &nbsp;&middot;&nbsp; Ref: {{ Str::limit($contact->referrer_url, 50) }}
        @endif
    </p>

@endsection
