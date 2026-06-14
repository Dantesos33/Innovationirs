<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>@yield('email_title', 'Parts Plus Innovation Solutions')</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        /* Reset */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            outline: none;
            text-decoration: none;
        }

        /* Base */
        body {
            margin: 0 !important;
            padding: 0 !important;
            background-color: #F3F4F6;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        /* Wrapper */
        .email-wrapper {
            width: 100%;
            background-color: #F3F4F6;
            padding: 32px 16px;
        }

        /* Container */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #FFFFFF;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        }

        /* Header */
        .email-header {
            background-color: #111113;
            padding: 28px 40px;
            text-align: center;
        }

        .email-header-logo {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .email-header-icon {
            width: 40px;
            height: 40px;
            background-color: #E05C1A;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #FFFFFF;
            font-weight: 700;
            line-height: 1;
        }

        .email-header-name {
            font-size: 20px;
            font-weight: 700;
            color: #FFFFFF;
            letter-spacing: -0.02em;
        }

        /* Hero Banner */
        .email-hero {
            background-color: #E05C1A;
            padding: 28px 40px;
        }

        .email-hero--blue {
            background-color: #1D4ED8;
        }

        .email-hero--dark {
            background-color: #1C1C1F;
        }

        .email-hero--green {
            background-color: #15803D;
        }

        .email-hero-icon {
            width: 52px;
            height: 52px;
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: #FFFFFF;
            margin-bottom: 12px;
        }

        .email-hero-title {
            font-size: 22px;
            font-weight: 700;
            color: #FFFFFF;
            margin: 0 0 6px;
            line-height: 1.3;
        }

        .email-hero-sub {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
            margin: 0;
            line-height: 1.5;
        }

        /* Body */
        .email-body {
            padding: 36px 40px;
        }

        /* Typography */
        .email-greeting {
            font-size: 16px;
            font-weight: 600;
            color: #111113;
            margin: 0 0 16px;
        }

        p {
            font-size: 14px;
            line-height: 1.7;
            color: #374151;
            margin: 0 0 16px;
        }

        /* Info Box */
        .info-box {
            background-color: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            padding: 20px 24px;
            margin: 20px 0;
        }

        .info-box--orange {
            background-color: #FFF7ED;
            border-color: #FED7AA;
        }

        .info-box--blue {
            background-color: #EFF6FF;
            border-color: #BFDBFE;
        }

        .info-box-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #6B7280;
            margin: 0 0 12px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 7px 0;
            border-bottom: 1px solid #E5E7EB;
            font-size: 13px;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #6B7280;
            font-weight: 500;
        }

        .info-value {
            color: #111113;
            font-weight: 600;
            text-align: right;
            max-width: 60%;
        }

        /* Message Block */
        .message-block {
            background-color: #F9FAFB;
            border-left: 3px solid #E05C1A;
            border-radius: 0 8px 8px 0;
            padding: 16px 20px;
            margin: 20px 0;
            font-size: 14px;
            line-height: 1.7;
            color: #374151;
        }

        /* Button */
        .btn-wrap {
            text-align: center;
            margin: 28px 0;
        }

        .email-btn {
            display: inline-block;
            background-color: #E05C1A;
            color: #FFFFFF !important;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            padding: 13px 28px;
            border-radius: 8px;
            letter-spacing: 0.01em;
        }

        .email-btn--secondary {
            background-color: #111113;
        }

        .email-btn--outline {
            background-color: transparent;
            color: #E05C1A !important;
            border: 2px solid #E05C1A;
        }

        /* Divider */
        .email-divider {
            border: none;
            border-top: 1px solid #E5E7EB;
            margin: 28px 0;
        }

        /* Badge */
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .status-badge--new {
            background: #FFF7ED;
            color: #C2410C;
        }

        .status-badge--blue {
            background: #EFF6FF;
            color: #1D4ED8;
        }

        .status-badge--green {
            background: #F0FDF4;
            color: #15803D;
        }

        /* Footer */
        .email-footer {
            background-color: #111113;
            padding: 28px 40px;
            text-align: center;
        }

        .footer-links {
            margin-bottom: 14px;
        }

        .footer-links a {
            color: #9CA3AF;
            font-size: 12px;
            text-decoration: none;
            margin: 0 8px;
        }

        .footer-links a:hover {
            color: #FFFFFF;
        }

        .footer-address {
            font-size: 11px;
            color: #6B7280;
            line-height: 1.7;
            margin: 0;
        }

        .footer-unsub {
            margin-top: 14px;
            font-size: 11px;
            color: #6B7280;
        }

        .footer-unsub a {
            color: #9CA3AF;
            text-decoration: underline;
        }

        /* Responsive */
        @media only screen and (max-width: 600px) {
            .email-body {
                padding: 24px 20px !important;
            }

            .email-hero {
                padding: 22px 20px !important;
            }

            .email-header {
                padding: 20px !important;
            }

            .email-footer {
                padding: 22px 20px !important;
            }

            .email-hero-title {
                font-size: 18px !important;
            }
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <table class="email-container" width="100%" cellpadding="0" cellspacing="0" role="presentation">

            {{-- Header --}}
            <tr>
                <td class="email-header">
                    <a href="{{ config('app.url') }}" class="email-header-logo" style="text-decoration:none;">
                        <span class="email-header-icon">A</span>
                        <span class="email-header-name">Parts Plus Innovation Solutions</span>
                    </a>
                </td>
            </tr>

            {{-- Hero --}}
            <tr>
                <td class="email-hero @yield('hero_class')">
                    <div class="email-hero-icon">@yield('hero_icon', '📦')</div>
                    <h1 class="email-hero-title">@yield('hero_title')</h1>
                    <p class="email-hero-sub">@yield('hero_sub', '')</p>
                </td>
            </tr>

            {{-- Body --}}
            <tr>
                <td class="email-body">
                    @yield('email_body')
                </td>
            </tr>

            {{-- Footer --}}
            <tr>
                <td class="email-footer">
                    <div class="footer-links">
                        <a href="{{ url('/') }}">Home</a>
                        <a href="{{ url('/parts') }}">Parts</a>
                        <a href="{{ url('/contact') }}">Contact</a>
                        <a href="{{ url('/privacy') }}">Privacy</a>
                    </div>
                    <p class="footer-address">
                        {{ config('amsparts.company_name', 'Parts Plus Innovation Solutions') }}<br>
                        {{ config('amsparts.address', '') }}<br>
                        {{ config('amsparts.phone', '') }} &nbsp;|&nbsp; {{ config('amsparts.email', '') }}
                    </p>
                    @yield('footer_extra')
                </td>
            </tr>

        </table>
    </div>
</body>

</html>
