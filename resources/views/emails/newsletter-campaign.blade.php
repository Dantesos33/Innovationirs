@extends('emails.layouts.master')

@section('email_title', $campaign->subject)
@section('hero_class', 'email-hero--dark')
@section('hero_icon') 📰 @endsection
@section('hero_title', $campaign->subject)
@section('hero_sub', $campaign->preview_text ?? 'The latest news and updates from Parts Plus Innovation Solutions')

@section('email_body')

    {{-- Personalised greeting --}}
    <p class="email-greeting">
        Hi {{ $subscriber->first_name ?? 'there' }},
    </p>

    {{-- Campaign body HTML --}}
    <div style="font-size:14px;line-height:1.8;color:#374151;">
        {!! $campaign->body_html !!}
    </div>

    <hr class="email-divider">

    {{-- Shop CTA --}}
    <div style="background:#F9FAFB;border:1px solid #E5E7EB;border-radius:10px;padding:24px;text-align:center;margin:0;">
        <p style="font-size:15px;font-weight:700;color:#111113;margin:0 0 6px;">
            Looking for a specific part?
        </p>
        <p style="font-size:13px;color:#6B7280;margin:0 0 18px;">
            Browse our full inventory or submit a quote request — we'll find it for you.
        </p>
        <div>
            <a href="{{ url('/parts') }}" class="email-btn" style="margin-right:10px;">
                Browse Parts
            </a>
            <a href="{{ url('/quote') }}" class="email-btn email-btn--secondary">
                Get a Quote
            </a>
        </div>
    </div>

@endsection

@section('footer_extra')
    <div style="margin-top:16px;padding-top:16px;border-top:1px solid #1F2023;">
        <p class="footer-unsub">
            You're receiving this because you subscribed to updates from Parts Plus Innovation Solutions.<br>
            <a href="{{ url('/newsletter/unsubscribe/' . $subscriber->unsubscribe_token) }}">
                Unsubscribe
            </a>
            &nbsp;&middot;&nbsp;
            <a href="{{ url('/newsletter/preferences/' . $subscriber->unsubscribe_token) }}">
                Update preferences
            </a>
        </p>
    </div>
@endsection
