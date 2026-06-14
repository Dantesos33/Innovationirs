{{-- resources/views/pages/careers.blade.php --}}
@extends('layouts.app')

@section('meta_title', 'Careers | ' . config('amsparts.company_name', 'Parts Plus Innovation Solutions'))
@section('meta_description', 'Join the ' . config('amsparts.company_name', 'Parts Plus Innovation Solutions') . ' team. View open positions
    and learn about working with us.')
@section('body_class', 'page-careers')

@section('content')

    <div class="page-hero">
        <div class="container">
            @include('partials.breadcrumb', ['crumbs' => [['label' => 'Careers', 'url' => null]]])
            <div class="page-hero-label">Join the Team</div>
            <h1 class="page-hero-title">Work at {{ config('amsparts.company_name', 'Parts Plus Innovation Solutions') }}</h1>
            <p class="page-hero-sub">
                We're a fast-moving team that takes heavy equipment parts seriously.
                If you want to build a career in an industry that keeps the world moving — we'd love to meet you.
            </p>
        </div>
    </div>

    <div class="section section--warm">
        <div class="container">

            {{-- Why join us --}}
            <div class="careers-why-grid" data-reveal>
                @foreach ([['fa-people-group', 'Small Team, Big Impact', 'You won\'t get lost here. Every person on our team matters and your work is visible.'], ['fa-chart-line', 'Room to Grow', 'We promote from within. Prove yourself and the opportunities are there.'], ['fa-earth-americas', 'Global Reach', 'We serve customers in 50+ countries. It\'s an international business with a close-knit team.'], ['fa-medal', 'Industry Leaders', 'We\'ve been doing this for ' . config('amsparts.years_experience', 20) . '+ years. You\'ll learn from people who know heavy equipment cold.']] as [$icon, $title, $text])
                    <div class="careers-why-card">
                        <div class="careers-why-icon"><i class="fa-solid fa-{{ $icon }}"></i></div>
                        <h3 class="careers-why-title">{{ $title }}</h3>
                        <p class="careers-why-text">{{ $text }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Open positions --}}
            <div style="margin-top:52px;">
                <div class="section-header" style="text-align:left;max-width:none;margin-bottom:24px;" data-reveal>
                    <span class="section-label">Now Hiring</span>
                    <h2 class="section-title">Open Positions</h2>
                </div>

                @forelse($jobs as $department => $positions)
                    <div class="careers-dept-group" data-reveal>
                        <div class="careers-dept-label">{{ $department }}</div>
                        <div class="careers-jobs-list">
                            @foreach ($positions as $job)
                                <div class="careers-job-card">
                                    <div class="careers-job-main">
                                        <h3 class="careers-job-title">{{ $job->title }}</h3>
                                        <div class="careers-job-meta">
                                            @if ($job->location)
                                                <span><i class="fa-solid fa-location-dot"></i> {{ $job->location }}</span>
                                            @endif
                                            @if ($job->type)
                                                <span><i class="fa-regular fa-clock"></i> {{ $job->type }}</span>
                                            @endif
                                            @if ($job->salary_range)
                                                <span><i class="fa-solid fa-dollar-sign"></i>
                                                    {{ $job->salary_range }}</span>
                                            @endif
                                        </div>
                                        @if ($job->short_description)
                                            <p class="careers-job-desc">{{ Str::limit($job->short_description, 180) }}</p>
                                        @endif
                                    </div>
                                    <div class="careers-job-actions">
                                        @if ($job->application_url)
                                            <a href="{{ $job->application_url }}" target="_blank" rel="noopener"
                                                class="btn btn-primary btn-sm">
                                                Apply Now <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('careers.apply', $job) }}" class="btn btn-primary btn-sm">
                                                Apply Now <i class="fa-solid fa-arrow-right"></i>
                                            </a>
                                        @endif
                                        @if ($job->posted_at)
                                            <span class="careers-job-date">Posted
                                                {{ $job->posted_at->diffForHumans() }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    {{-- No open positions --}}
                    <div class="careers-no-jobs" data-reveal>
                        <div class="empty-state-icon"><i class="fa-solid fa-briefcase"></i></div>
                        <h3 style="font-family:var(--font-display);font-size:1.4rem;font-weight:700;margin-bottom:8px;">
                            No Open Positions Right Now
                        </h3>
                        <p style="color:var(--gray-500);font-size:14px;margin-bottom:20px;max-width:420px;">
                            We don't have any active postings at the moment, but we're always interested in
                            hearing from talented people. Send us your résumé and we'll keep you in mind.
                        </p>
                        <a href="{{ route('contact') }}?subject={{ urlencode('General Application') }}"
                            class="btn btn-primary">
                            <i class="fa-solid fa-envelope"></i> Send a General Application
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- Open application CTA --}}
            @if ($jobs->count())
                <div class="makes-cta-box" style="margin-top:48px;" data-reveal>
                    <div class="makes-cta-icon"><i class="fa-solid fa-user-plus"></i></div>
                    <div>
                        <div class="makes-cta-title">Don't see a fit?</div>
                        <p class="makes-cta-text">
                            Send us a general application. We're always growing and great people don't always show up when a
                            job is posted.
                        </p>
                    </div>
                    <a href="{{ route('contact') }}?subject={{ urlencode('General Application') }}"
                        class="btn btn-primary">
                        Send General Application
                    </a>
                </div>
            @endif

        </div>
    </div>

@endsection

@push('styles')
    <style>
        .careers-why-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 18px;
        }

        .careers-why-card {
            padding: 24px;
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-xl);
            transition: box-shadow var(--transition-md), transform var(--transition-md), border-color var(--transition-md);
        }

        .careers-why-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-3px);
            border-color: var(--orange);
        }

        .careers-why-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius);
            background: var(--orange-pale);
            color: var(--orange);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 14px;
        }

        .careers-why-title {
            font-family: var(--font-display);
            font-size: 18px;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 8px;
        }

        .careers-why-text {
            font-size: 13px;
            color: var(--gray-500);
            line-height: 1.6;
        }

        .careers-dept-group {
            margin-bottom: 36px;
        }

        .careers-dept-label {
            font-family: var(--font-display);
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: var(--orange);
            margin-bottom: 12px;
            padding-left: 4px;
        }

        .careers-jobs-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .careers-job-card {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
            background: var(--white);
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-lg);
            padding: 20px 24px;
            transition: border-color var(--transition), box-shadow var(--transition);
        }

        .careers-job-card:hover {
            border-color: var(--orange);
            box-shadow: var(--shadow);
        }

        .careers-job-main {
            flex: 1;
            min-width: 0;
        }

        .careers-job-title {
            font-family: var(--font-display);
            font-size: 20px;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 8px;
        }

        .careers-job-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            font-size: 12px;
            color: var(--gray-500);
            margin-bottom: 10px;
        }

        .careers-job-meta i {
            color: var(--orange);
        }

        .careers-job-desc {
            font-size: 13px;
            color: var(--gray-600);
            line-height: 1.6;
        }

        .careers-job-actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 8px;
            flex-shrink: 0;
        }

        .careers-job-date {
            font-size: 11px;
            color: var(--gray-400);
        }

        .careers-no-jobs {
            text-align: center;
            padding: 48px 20px;
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-xl);
        }

        @media (max-width: 640px) {
            .careers-job-card {
                flex-direction: column;
            }

            .careers-job-actions {
                align-items: flex-start;
            }
        }
    </style>
@endpush
