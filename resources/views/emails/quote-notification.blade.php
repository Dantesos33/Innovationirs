@extends('emails.layouts.master')

@section('email_title', 'New Quote Request #' . $quote->id)
@section('hero_class', '')
@section('hero_icon') 📋 @endsection
@section('hero_title', 'New Quote Request')
@section('hero_sub', 'A customer has submitted a new parts quote request — respond promptly.')

@section('email_body')

    <p class="email-greeting">New request received at {{ $quote->created_at->format('g:i A') }} on
        {{ $quote->created_at->format('F j, Y') }}</p>

    {{-- Status badge --}}
    <p>
        <span class="status-badge status-badge--new">New &mdash; Needs Review</span>
    </p>

    {{-- Customer Info --}}
    <div class="info-box info-box--orange">
        <div class="info-box-title">Customer Information</div>
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td class="info-label"
                    style="padding:7px 0;font-size:13px;color:#6B7280;font-weight:500;border-bottom:1px solid #FED7AA;width:40%;">
                    Name</td>
                <td class="info-value"
                    style="padding:7px 0;font-size:13px;color:#111113;font-weight:600;border-bottom:1px solid #FED7AA;text-align:right;">
                    {{ $quote->full_name }}</td>
            </tr>
            <tr>
                <td class="info-label"
                    style="padding:7px 0;font-size:13px;color:#6B7280;font-weight:500;border-bottom:1px solid #FED7AA;">
                    Email</td>
                <td class="info-value"
                    style="padding:7px 0;font-size:13px;color:#111113;font-weight:600;border-bottom:1px solid #FED7AA;text-align:right;">
                    <a href="mailto:{{ $quote->email }}" style="color:#E05C1A;">{{ $quote->email }}</a>
                </td>
            </tr>
            @if ($quote->phone)
                <tr>
                    <td class="info-label"
                        style="padding:7px 0;font-size:13px;color:#6B7280;font-weight:500;border-bottom:1px solid #FED7AA;">
                        Phone</td>
                    <td class="info-value"
                        style="padding:7px 0;font-size:13px;color:#111113;font-weight:600;border-bottom:1px solid #FED7AA;text-align:right;">
                        {{ $quote->phone }}</td>
                </tr>
            @endif
            @if ($quote->company)
                <tr>
                    <td class="info-label" style="padding:7px 0;font-size:13px;color:#6B7280;font-weight:500;">Company</td>
                    <td class="info-value"
                        style="padding:7px 0;font-size:13px;color:#111113;font-weight:600;text-align:right;">
                        {{ $quote->company }}</td>
                </tr>
            @endif
        </table>
    </div>

    {{-- Part Request --}}
    <div class="info-box">
        <div class="info-box-title">Part Request Details</div>
        <table width="100%" cellpadding="0" cellspacing="0">
            @if ($quote->part_number)
                <tr>
                    <td class="info-label"
                        style="padding:7px 0;font-size:13px;color:#6B7280;font-weight:500;border-bottom:1px solid #E5E7EB;width:40%;">
                        Part Number</td>
                    <td class="info-value"
                        style="padding:7px 0;font-size:13px;color:#111113;font-weight:700;border-bottom:1px solid #E5E7EB;text-align:right;">
                        {{ $quote->part_number }}</td>
                </tr>
            @endif
            @if ($quote->make)
                <tr>
                    <td class="info-label"
                        style="padding:7px 0;font-size:13px;color:#6B7280;font-weight:500;border-bottom:1px solid #E5E7EB;">
                        Make</td>
                    <td class="info-value"
                        style="padding:7px 0;font-size:13px;color:#111113;font-weight:600;border-bottom:1px solid #E5E7EB;text-align:right;">
                        {{ $quote->make }}</td>
                </tr>
            @endif
            @if ($quote->model)
                <tr>
                    <td class="info-label"
                        style="padding:7px 0;font-size:13px;color:#6B7280;font-weight:500;border-bottom:1px solid #E5E7EB;">
                        Model</td>
                    <td class="info-value"
                        style="padding:7px 0;font-size:13px;color:#111113;font-weight:600;border-bottom:1px solid #E5E7EB;text-align:right;">
                        {{ $quote->model }}</td>
                </tr>
            @endif
            @if ($quote->serial_number)
                <tr>
                    <td class="info-label"
                        style="padding:7px 0;font-size:13px;color:#6B7280;font-weight:500;border-bottom:1px solid #E5E7EB;">
                        Serial #</td>
                    <td class="info-value"
                        style="padding:7px 0;font-size:13px;color:#111113;font-weight:600;border-bottom:1px solid #E5E7EB;text-align:right;">
                        {{ $quote->serial_number }}</td>
                </tr>
            @endif
            @if ($quote->quantity > 1)
                <tr>
                    <td class="info-label" style="padding:7px 0;font-size:13px;color:#6B7280;font-weight:500;">Quantity</td>
                    <td class="info-value"
                        style="padding:7px 0;font-size:13px;color:#111113;font-weight:600;text-align:right;">
                        {{ $quote->quantity }}</td>
                </tr>
            @endif
        </table>
    </div>

    {{-- Description --}}
    @if ($quote->part_description)
        <p style="font-weight:600;font-size:13px;color:#374151;margin-bottom:6px;">Part Description:</p>
        <div class="message-block">{{ $quote->part_description }}</div>
    @endif

    @if ($quote->notes)
        <p style="font-weight:600;font-size:13px;color:#374151;margin-bottom:6px;">Additional Notes:</p>
        <div class="message-block" style="border-left-color:#6B7280;">{{ $quote->notes }}</div>
    @endif

    {{-- CTA --}}
    <div class="btn-wrap">
        <a href="{{ route('admin.quotes.show', $quote->id) }}" class="email-btn">
            View &amp; Respond in Admin Panel
        </a>
    </div>

    <hr class="email-divider">

    <p style="font-size:12px;color:#9CA3AF;text-align:center;margin:0;">
        Quote #{{ $quote->id }} &nbsp;&middot;&nbsp;
        Submitted {{ $quote->created_at->format('M d, Y g:i A') }} &nbsp;&middot;&nbsp;
        IP: {{ $quote->ip_address ?? 'N/A' }}
        @if ($quote->utm_source)
            &nbsp;&middot;&nbsp; Source: {{ $quote->utm_source }}
        @endif
    </p>

@endsection
