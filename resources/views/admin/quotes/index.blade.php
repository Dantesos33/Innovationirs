@extends('layouts.admin')
@section('title', 'Quote Requests')

@section('breadcrumb')
    <span class="breadcrumb-current">Quote Requests</span>
@endsection

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Quote Requests</h1>
            <p class="page-subtitle">{{ number_format($quotes->total()) }} total requests</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.quotes.export') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                class="btn btn--secondary">
                <i class="fa-solid fa-download"></i> Export CSV
            </a>
        </div>
    </div>

    {{-- Status Quick-Filter Tabs --}}
    <div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;">
        @php
            $statuses = [
                '' => ['label' => 'All', 'color' => 'gray'],
                'new' => ['label' => 'New', 'color' => 'orange'],
                'open' => ['label' => 'Open', 'color' => 'blue'],
                'in_progress' => ['label' => 'In Progress', 'color' => 'yellow'],
                'quoted' => ['label' => 'Quoted', 'color' => 'green'],
                'closed_won' => ['label' => 'Won', 'color' => 'green'],
                'closed_lost' => ['label' => 'Lost', 'color' => 'gray'],
            ];
        @endphp
        @foreach ($statuses as $statusVal => $meta)
            <a href="{{ route('admin.quotes.index', array_merge(request()->except('status', 'page'), $statusVal ? ['status' => $statusVal] : [])) }}"
                class="badge badge--{{ request('status', '') === $statusVal ? $meta['color'] : 'gray' }}"
                style="padding:6px 14px;font-size:12px;cursor:pointer;text-decoration:none;">
                {{ $meta['label'] }}
                @if (isset($statusCounts[$statusVal]))
                    ({{ $statusCounts[$statusVal] }})
                @endif
            </a>
        @endforeach
    </div>

    <div class="card">
        {{-- Filters --}}
        <form method="GET" action="{{ route('admin.quotes.index') }}">
            <div class="filters-bar">
                <div class="filter-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search name, email, part…">
                </div>
                @if (request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <input type="date" name="date_from" class="filter-select" value="{{ request('date_from') }}"
                    placeholder="From date">
                <input type="date" name="date_to" class="filter-select" value="{{ request('date_to') }}"
                    placeholder="To date">
                <select name="assigned_to" class="filter-select">
                    <option value="">All Assignees</option>
                    @foreach ($admins as $admin)
                        <option value="{{ $admin->id }}" {{ request('assigned_to') == $admin->id ? 'selected' : '' }}>
                            {{ $admin->name }}
                        </option>
                    @endforeach
                </select>
                <div class="filter-actions">
                    @if (request()->hasAny(['search', 'date_from', 'date_to', 'assigned_to']))
                        <a href="{{ route('admin.quotes.index', request('status') ? ['status' => request('status')] : []) }}"
                            class="btn btn--ghost btn--sm">Clear</a>
                    @endif
                    <button type="submit" class="btn btn--secondary btn--sm">Filter</button>
                </div>
            </div>
        </form>

        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Part / Description</th>
                        <th>Machine</th>
                        <th>Status</th>
                        <th>Assigned</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($quotes as $quote)
                        <tr {{ $quote->status === 'new' ? 'style=font-weight:600;' : '' }}>
                            <td style="color:var(--text-muted);font-size:12px;">#{{ $quote->id }}</td>
                            <td>
                                <div class="table-name">{{ $quote->full_name }}</div>
                                <div class="table-meta">{{ $quote->email }}</div>
                                @if ($quote->phone)
                                    <div class="table-meta">{{ $quote->phone }}</div>
                                @endif
                            </td>
                            <td>
                                <div style="max-width:200px;font-size:12px;">
                                    @if ($quote->part_number)
                                        <div class="table-name" style="font-size:12px;">{{ $quote->part_number }}</div>
                                    @endif
                                    {{ Str::limit($quote->part_description, 60) }}
                                </div>
                            </td>
                            <td style="font-size:12px;">
                                {{ $quote->make ?? '—' }}
                                @if ($quote->model)
                                    / {{ $quote->model }}
                                @endif
                            </td>
                            <td>
                                <span
                                    class="badge badge--{{ match ($quote->status) {
                                        'new' => 'orange',
                                        'open' => 'blue',
                                        'in_progress' => 'yellow',
                                        'quoted' => 'green',
                                        'closed_won' => 'green',
                                        'closed_lost' => 'gray',
                                        default => 'gray',
                                    } }}">{{ ucfirst(str_replace('_', ' ', $quote->status)) }}</span>
                            </td>
                            <td style="font-size:12px;">{{ $quote->assignedTo?->name ?? '—' }}</td>
                            <td style="font-size:12px;white-space:nowrap;">{{ $quote->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.quotes.show', $quote) }}" class="action-btn action-btn--edit"
                                        title="View & Reply">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <button class="action-btn action-btn--delete" title="Delete"
                                        data-delete-url="{{ route('admin.quotes.destroy', $quote) }}"
                                        data-delete-label="Quote #{{ $quote->id }} from {{ $quote->full_name }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                                    <div class="empty-state-title">No quote requests found</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($quotes->hasPages())
            <div class="pagination-wrap">
                <span>Showing {{ $quotes->firstItem() }}–{{ $quotes->lastItem() }} of {{ $quotes->total() }}</span>
                {{ $quotes->withQueryString()->links('vendor.pagination.simple-admin') }}
            </div>
        @endif
    </div>

@endsection
