@extends('emails.layouts.master')

@section('email_title', 'New Job Application — ' . $application->careerPosting->title)
@section('hero_class', 'email-hero--blue')
@section('hero_icon') 📋 @endsection
@section('hero_title', 'New Job Application')
@section('hero_sub', 'Someone has applied for: ' . $application->careerPosting->title)

@section('email_body')

    <p class="email-greeting">Received {{ $application->created_at->format('g:i A') }} &mdash;
        {{ $application->created_at->format('F j, Y') }}</p>

    <p><span class="status-badge status-badge--new">New Application — Needs Review</span></p>

    {{-- Applicant Info --}}
    <div class="info-box info-box--blue">
        <div class="info-box-title">Applicant Details</div>
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td
                    style="padding:7px 0;font-size:13px;color:#1D4ED8;font-weight:500;border-bottom:1px solid #BFDBFE;width:40%;">
                    Name</td>
                <td
                    style="padding:7px 0;font-size:13px;color:#111113;font-weight:700;border-bottom:1px solid #BFDBFE;text-align:right;">
                    {{ $application->full_name }}</td>
            </tr>
            <tr>
                <td style="padding:7px 0;font-size:13px;color:#1D4ED8;font-weight:500;border-bottom:1px solid #BFDBFE;">Email
                </td>
                <td style="padding:7px 0;font-size:13px;font-weight:600;border-bottom:1px solid #BFDBFE;text-align:right;">
                    <a href="mailto:{{ $application->email }}" style="color:#1D4ED8;">{{ $application->email }}</a>
                </td>
            </tr>
            @if ($application->phone)
                <tr>
                    <td style="padding:7px 0;font-size:13px;color:#1D4ED8;font-weight:500;border-bottom:1px solid #BFDBFE;">
                        Phone</td>
                    <td
                        style="padding:7px 0;font-size:13px;color:#111113;font-weight:600;border-bottom:1px solid #BFDBFE;text-align:right;">
                        {{ $application->phone }}</td>
                </tr>
            @endif
            @if ($application->city)
                <tr>
                    <td style="padding:7px 0;font-size:13px;color:#1D4ED8;font-weight:500;border-bottom:1px solid #BFDBFE;">
                        Location</td>
                    <td
                        style="padding:7px 0;font-size:13px;color:#111113;font-weight:600;border-bottom:1px solid #BFDBFE;text-align:right;">
                        {{ $application->city }}</td>
                </tr>
            @endif
            <tr>
                <td style="padding:7px 0;font-size:13px;color:#1D4ED8;font-weight:500;">Position Applied</td>
                <td style="padding:7px 0;font-size:13px;color:#111113;font-weight:700;text-align:right;">
                    {{ $application->careerPosting->title }}</td>
            </tr>
        </table>
    </div>

    @if ($application->cover_letter)
        <p style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Cover Letter:</p>
        <div class="message-block" style="border-left-color:#1D4ED8;">
            {!! nl2br(e($application->cover_letter)) !!}
        </div>
    @endif

    <div style="display:flex;gap:12px;justify-content:center;margin:28px 0;">
        <a href="{{ route('admin.job-applications.show', $application->id) }}" class="email-btn email-btn--secondary">
            View in Admin Panel
        </a>
        <a href="mailto:{{ $application->email }}?subject=Re: Your Application - {{ rawurlencode($application->careerPosting->title) }}"
            class="email-btn">
            Reply to Applicant
        </a>
    </div>

    <hr class="email-divider">

    <p style="font-size:12px;color:#9CA3AF;text-align:center;margin:0;">
        Application #{{ $application->id }}
        &nbsp;&middot;&nbsp; {{ $application->created_at->format('M d, Y g:i A') }}
        @if ($application->cv_original_name)
            &nbsp;&middot;&nbsp; CV: {{ $application->cv_original_name }}
        @endif
    </p>

@endsection
