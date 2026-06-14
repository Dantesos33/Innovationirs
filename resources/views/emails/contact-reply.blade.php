@extends('emails.layouts.master')

@section('email_title', 'Re: Your Message — Parts Plus Innovation Solutions')
@section('hero_class', 'email-hero--blue')
@section('hero_icon') 💬 @endsection
@section('hero_title', 'We Got Back to You')
@section('hero_sub', 'A member of our team has responded to your message.')

@section('email_body')

    <p class="email-greeting">Hi {{ $contact->first_name }},</p>

    <p>
        Thank you for reaching out to Parts Plus Innovation Solutions. We've reviewed your message
        @if ($contact->subject)
            regarding <strong>"{{ $contact->subject }}"</strong>
        @endif
        and our team has sent the following reply:
    </p>

    {{-- Admin Reply --}}
    <div class="message-block">
        {!! nl2br(e($reply->message)) !!}
    </div>

    <p>
        If you have any further questions, feel free to reply directly to this email or contact us below.
        We're happy to help.
    </p>

    <div style="display:flex;gap:12px;justify-content:center;margin:28px 0;">
        @if (config('amsparts.phone'))
            <a href="tel:{{ config('amsparts.phone') }}" class="email-btn email-btn--secondary">
                📞 Call Us
            </a>
        @endif
        <a href="{{ url('/contact') }}" class="email-btn">
            Send Another Message
        </a>
    </div>

    <hr class="email-divider">

    {{-- Original Message Summary --}}
    <p
        style="font-size:12px;color:#9CA3AF;margin-bottom:8px;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">
        Your Original Message:</p>
    <div
        style="background:#F9FAFB;border:1px solid #E5E7EB;border-radius:6px;padding:14px 18px;font-size:13px;color:#6B7280;line-height:1.7;">
        {{ Str::limit($contact->message, 300) }}
    </div>

    <p style="font-size:13px;color:#374151;margin-top:20px;">
        Best regards,<br>
        <strong>{{ $reply->admin?->name ?? 'The Parts Plus Innovation Solutions Team' }}</strong><br>
        <span style="color:#6B7280;">Parts Plus Innovation Solutions</span>
    </p>

@endsection

@section('footer_extra')
    <p class="footer-unsub" style="margin-top:14px;font-size:11px;color:#6B7280;">
        You received this because you submitted a contact form on
        <a href="{{ url('/') }}" style="color:#9CA3AF;">{{ parse_url(config('app.url'), PHP_URL_HOST) }}</a>.
    </p>
@endsection
