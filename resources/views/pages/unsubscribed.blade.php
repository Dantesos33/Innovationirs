{{-- resources/views/pages/unsubscribed.blade.php --}}
@extends('layouts.app')

@section('meta_title', 'Unsubscribed | ' . config('amsparts.company_name', 'Parts Plus Innovation Solutions'))
@section('meta_robots', 'noindex, nofollow')
@section('body_class', 'page-unsubscribed')

@section('content')

    <div class="section section--warm" style="min-height:60vh;display:flex;align-items:center;">
        <div class="container" style="text-align:center;max-width:560px;">

            <div class="unsub-icon" data-reveal>
                <i class="fa-solid fa-envelope-circle-check"></i>
            </div>

            <h1 class="unsub-title" data-reveal>You've Been Unsubscribed</h1>

            @if (isset($subscriber) && $subscriber->email)
                <p class="unsub-body" data-reveal>
                    <strong>{{ $subscriber->email }}</strong> has been removed from our newsletter list.
                    You won't receive any further marketing emails from us.
                </p>
            @else
                <p class="unsub-body" data-reveal>
                    Your email has been removed from our newsletter list.
                    You won't receive any further marketing emails from us.
                </p>
            @endif

            <p class="unsub-note" data-reveal>
                You'll still receive transactional emails related to any active quote requests or orders.
            </p>

            <div class="unsub-actions" data-reveal>
                <a href="{{ route('home') }}" class="btn btn-primary">
                    <i class="fa-solid fa-house"></i> Back to Homepage
                </a>
                <a href="{{ route('parts.index') }}" class="btn btn-ghost">
                    Browse Parts
                </a>
            </div>

            <div class="unsub-resubscribe" data-reveal>
                <p>Unsubscribed by mistake?</p>
                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="unsub-resub-form">
                    @csrf
                    <input type="hidden" name="email" value="{{ $subscriber->email ?? '' }}">
                    <button type="submit" class="btn btn-outline" style="font-size:13px;">
                        <i class="fa-solid fa-rotate-left"></i> Re-subscribe
                    </button>
                </form>
            </div>

        </div>
    </div>

@endsection

@push('styles')
    <style>
        .unsub-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--orange-pale);
            color: var(--orange);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            margin: 0 auto 24px;
        }

        .unsub-title {
            font-family: var(--font-display);
            font-size: clamp(1.8rem, 4vw, 2.6rem);
            font-weight: 800;
            color: var(--ink);
            margin-bottom: 12px;
        }

        .unsub-body {
            font-size: 15px;
            color: var(--gray-600);
            line-height: 1.7;
            margin-bottom: 12px;
        }

        .unsub-note {
            font-size: 13px;
            color: var(--gray-400);
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius);
            padding: 10px 16px;
            margin-bottom: 28px;
        }

        .unsub-actions {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 36px;
        }

        .unsub-resubscribe {
            padding-top: 24px;
            border-top: 1px solid var(--gray-200);
            font-size: 13px;
            color: var(--gray-500);
        }

        .unsub-resubscribe p {
            margin-bottom: 10px;
        }

        .unsub-resub-form {
            display: inline-block;
        }
    </style>
@endpush
