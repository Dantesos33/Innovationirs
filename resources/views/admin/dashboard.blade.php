@extends('layouts.admin')

@section('title', 'Dashboard')

@section('breadcrumb')
    <span class="breadcrumb-current">Dashboard</span>
@endsection

@section('content')

    {{-- ── Stat Cards ──────────────────────────────────────────── --}}
    <div class="stats-grid">

        <a href="{{ route('admin.parts.index') }}" class="stat-card" style="text-decoration:none;">
            <div class="stat-top">
                <div>
                    <div class="stat-value">{{ number_format($stats['total_parts']) }}</div>
                    <div class="stat-label">Total Parts</div>
                </div>
                <div class="stat-icon stat-icon--orange">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                </div>
            </div>
            <div class="stat-change stat-change--up">
                <i class="fa-solid fa-circle-dot"></i>
                {{ $stats['active_parts'] }} active
            </div>
        </a>

        <a href="{{ route('admin.quotes.index') }}" class="stat-card" style="text-decoration:none;">
            <div class="stat-top">
                <div>
                    <div class="stat-value">{{ number_format($stats['total_quotes']) }}</div>
                    <div class="stat-label">Quote Requests</div>
                </div>
                <div class="stat-icon stat-icon--blue">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                </div>
            </div>
            @if ($stats['new_quotes'] > 0)
                <div class="stat-change stat-change--alert">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    {{ $stats['new_quotes'] }} need attention
                </div>
            @else
                <div class="stat-change stat-change--up">
                    <i class="fa-solid fa-check-circle"></i>
                    All reviewed
                </div>
            @endif
        </a>

        <a href="{{ route('admin.contacts.index') }}" class="stat-card" style="text-decoration:none;">
            <div class="stat-top">
                <div>
                    <div class="stat-value">{{ number_format($stats['total_contacts']) }}</div>
                    <div class="stat-label">Contact Messages</div>
                </div>
                <div class="stat-icon stat-icon--yellow">
                    <i class="fa-solid fa-envelope"></i>
                </div>
            </div>
            @if ($stats['new_contacts'] > 0)
                <div class="stat-change stat-change--alert">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    {{ $stats['new_contacts'] }} unread
                </div>
            @else
                <div class="stat-change stat-change--up">
                    <i class="fa-solid fa-check-circle"></i>
                    All read
                </div>
            @endif
        </a>

        <a href="{{ route('admin.newsletter.subscribers') }}" class="stat-card" style="text-decoration:none;">
            <div class="stat-top">
                <div>
                    <div class="stat-value">{{ number_format($stats['subscribers']) }}</div>
                    <div class="stat-label">Newsletter Subscribers</div>
                </div>
                <div class="stat-icon stat-icon--green">
                    <i class="fa-solid fa-paper-plane"></i>
                </div>
            </div>
            <div class="stat-change stat-change--up">
                <i class="fa-solid fa-circle-dot"></i>
                Active list
            </div>
        </a>

        <a href="{{ route('admin.blog.index') }}" class="stat-card" style="text-decoration:none;">
            <div class="stat-top">
                <div>
                    <div class="stat-value">{{ number_format($stats['blog_posts']) }}</div>
                    <div class="stat-label">Blog Posts</div>
                </div>
                <div class="stat-icon stat-icon--blue">
                    <i class="fa-solid fa-newspaper"></i>
                </div>
            </div>
            <div class="stat-change stat-change--up">
                <i class="fa-solid fa-circle-dot"></i>
                Published
            </div>
        </a>

        <div class="stat-card">
            <div class="stat-top">
                <div>
                    <div class="stat-value">{{ number_format($stats['testimonials']) }}</div>
                    <div class="stat-label">Testimonials</div>
                </div>
                <div class="stat-icon stat-icon--yellow">
                    <i class="fa-solid fa-star"></i>
                </div>
            </div>
            <div class="stat-change stat-change--up">
                <i class="fa-solid fa-circle-dot"></i>
                Active reviews
            </div>
        </div>

    </div>

    {{-- ── Charts ──────────────────────────────────────────────── --}}
    <div class="charts-grid">

        <div class="card">
            <div class="card-header">
                <span class="card-title">Quote Requests — Last 30 Days</span>
                <a href="{{ route('admin.quotes.export') }}" class="btn btn--secondary btn--sm">
                    <i class="fa-solid fa-download"></i> Export
                </a>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="quotesChart"></canvas>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <span class="card-title">Quote Status Breakdown</span>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Recent Activity ─────────────────────────────────────── --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

        {{-- Recent Quotes --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Recent Quote Requests</span>
                <a href="{{ route('admin.quotes.index') }}" class="btn btn--ghost btn--sm">View all</a>
            </div>
            <div class="table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Part</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentQuotes as $quote)
                            <tr>
                                <td>
                                    <div class="table-name">{{ $quote->full_name }}</div>
                                    <div class="table-meta">{{ $quote->email }}</div>
                                </td>
                                <td>
                                    <div
                                        style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:12px;">
                                        {{ Str::limit($quote->part_description, 40) }}
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'new' => 'orange',
                                            'open' => 'blue',
                                            'in_progress' => 'yellow',
                                            'quoted' => 'green',
                                            'closed_won' => 'green',
                                            'closed_lost' => 'gray',
                                        ];
                                        $color = $statusColors[$quote->status] ?? 'gray';
                                    @endphp
                                    <span class="badge badge--{{ $color }}">
                                        {{ ucfirst(str_replace('_', ' ', $quote->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.quotes.show', $quote) }}" class="action-btn action-btn--view">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state" style="padding:30px;">
                                        <p>No quotes yet</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Contacts --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Recent Contact Messages</span>
                <a href="{{ route('admin.contacts.index') }}" class="btn btn--ghost btn--sm">View all</a>
            </div>
            <div class="table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>From</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentContacts as $contact)
                            <tr>
                                <td>
                                    <div class="table-name">{{ $contact->full_name }}</div>
                                    <div class="table-meta">{{ $contact->email }}</div>
                                </td>
                                <td style="font-size:12px;">{{ Str::limit($contact->subject, 30) }}</td>
                                <td>
                                    <span class="badge badge--{{ $contact->status === 'new' ? 'orange' : 'green' }}">
                                        {{ ucfirst($contact->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.contacts.show', $contact) }}"
                                        class="action-btn action-btn--view">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state" style="padding:30px;">
                                        <p>No messages yet</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
    <script>
        window.quotesChartLabels = @json($chartLabels);
        window.quotesChartData = @json($chartData);
        window.statusChartLabels = @json(array_keys($statusBreakdown));
        window.statusChartData = @json(array_values($statusBreakdown));
    </script>
@endpush
