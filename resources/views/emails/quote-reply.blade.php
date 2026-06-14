@extends('emails.layouts.master')

@section('email_title', 'Re: Your Quote Request — Parts Plus Innovation Solutions')
@section('hero_class', '')
@section('hero_icon') 💬 @endsection
@section('hero_title', 'Reply to Your Quote Request')
@section('hero_sub', 'Our team has responded to your parts inquiry.')

@section('email_body')

    <p class="email-greeting">Hi {{ $quote->first_name }},</p>

    <p>Thank you for contacting Parts Plus Innovation Solutions. Our team has reviewed your request and sent you the
        following reply:</p>

    {{-- Admin Reply --}}
    <div class="message-block">
        {!! nl2br(e($reply->message)) !!}
    </div>

    {{-- Original Request Summary --}}
    <div class="info-box">
        <div class="info-box-title">Your Original Request &mdash; Quote #{{ $quote->id }}</div>
        <table width="100%" cellpadding="0" cellspacing="0">
            @if ($quote->part_number)
                <tr>
                    <td style="padding:6px 0;font-size:13px;color:#6B7280;width:40%;border-bottom:1px solid #E5E7EB;">Part
                        Number</td>
                    <td
                        style="padding:6px 0;font-size:13px;color:#111113;font-weight:600;border-bottom:1px solid #E5E7EB;text-align:right;">
                        {{ $quote->part_number }}</td>
                </tr>
            @endif
            @if ($quote->make)
                <tr>
                    <td style="padding:6px 0;font-size:13px;color:#6B7280;border-bottom:1px solid #E5E7EB;">Make / Model</td>
                    <td
                        style="padding:6px 0;font-size:13px;color:#111113;font-weight:600;border-bottom:1px solid #E5E7EB;text-align:right;">
                        {{ $quote->make }}{{ $quote->model ? ' ' . $quote->model : '' }}</td>
                </tr>
            @endif
            <tr>
                <td style="padding:6px 0;font-size:13px;color:#6B7280;">Submitted</td>
                <td style="padding:6px 0;font-size:13px;color:#111113;font-weight:600;text-align:right;">
                    {{ $quote->created_at->format('M d, Y') }}</td>
            </tr>
        </table>
    </div>

    <p>Have questions or need to add more detail? Simply reply to this email and we'll get back to you.</p>

    <div class="btn-wrap">
        <a href="{{ url('/contact') }}" class="email-btn">Contact Us</a>
    </div>

    <hr class="email-divider">

    <p style="font-size:13px;color:#374151;">
        Best regards,<br>
        <strong>{{ $reply->admin?->name ?? 'The Parts Plus Innovation Solutions Team' }}</strong><br>
        <span style="color:#6B7280;">Parts Plus Innovation Solutions &mdash; {{ config('amsparts.phone', '') }}</span>
    </p>

    @if ($signature = config('amsparts.quote_email_signature'))
        <p style="font-size:12px;color:#9CA3AF;white-space:pre-line;">{{ $signature }}</p>
    @endif

@endsection

@section('footer_extra')
    <p class="footer-unsub" style="margin-top:14px;font-size:11px;color:#6B7280;">
        You received this email because you submitted a quote request on <a href="{{ url('/') }}"
            style="color:#9CA3AF;">{{ config('app.url') }}</a>.
    </p>
@endsection
