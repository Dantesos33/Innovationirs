@extends('layouts.admin')
@section('title', 'Contact Messages')

@section('breadcrumb')
    <span class="breadcrumb-current">Contact Messages</span>
@endsection

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Contact Messages</h1>
            <p class="page-subtitle">{{ number_format($contacts->total()) }} total messages</p>
        </div>
    </div>

    {{-- Status Quick-Filter Tabs --}}
    <div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;">
        @php
            $statuses = [
                '' => 'All',
                'new' => 'New',
                'open' => 'Open',
                'in_progress' => 'In Progress',
                'resolved' => 'Resolved',
            ];
        @endphp
        @foreach ($statuses as $val => $label)
            <a href="{{ route('admin.contacts.index', array_merge(request()->except('status', 'page'), $val ? ['status' => $val] : [])) }}"
                class="badge badge--{{ request('status', '') === $val ? ($val === 'new' ? 'orange' : ($val === 'resolved' ? 'green' : 'blue')) : 'gray' }}"
                style="padding:6px 14px;font-size:12px;cursor:pointer;text-decoration:none;">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="card">

        <form method="GET" action="{{ route('admin.contacts.index') }}">
            <div class="filters-bar">
                <div class="filter-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search name, email, subject…">
                </div>
                @if (request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <select name="assigned_to" class="filter-select">
                    <option value="">All Assignees</option>
                    @foreach ($admins as $admin)
                        <option value="{{ $admin->id }}" {{ request('assigned_to') == $admin->id ? 'selected' : '' }}>
                            {{ $admin->name }}
                        </option>
                    @endforeach
                </select>
                <div class="filter-actions">
                    @if (request()->hasAny(['search', 'assigned_to']))
                        <a href="{{ route('admin.contacts.index', request('status') ? ['status' => request('status')] : []) }}"
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
                        <th>From</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Assigned</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                        <tr {{ $contact->status === 'new' ? 'style=font-weight:600;' : '' }}>
                            <td style="color:var(--text-muted);font-size:12px;">#{{ $contact->id }}</td>
                            <td>
                                <div class="table-name">{{ $contact->full_name }}</div>
                                <div class="table-meta">{{ $contact->email }}</div>
                                @if ($contact->phone)
                                    <div class="table-meta">{{ $contact->phone }}</div>
                                @endif
                            </td>
                            <td>
                                <div style="max-width:220px;font-size:13px;">
                                    {{ Str::limit($contact->subject ?? 'No Subject', 50) }}
                                </div>
                            </td>
                            <td>
                                <span
                                    class="badge badge--{{ match ($contact->status) {
                                        'new' => 'orange',
                                        'open' => 'blue',
                                        'in_progress' => 'yellow',
                                        'resolved' => 'green',
                                        default => 'gray',
                                    } }}">{{ ucfirst(str_replace('_', ' ', $contact->status)) }}</span>
                            </td>
                            <td style="font-size:12px;">{{ $contact->assignedTo?->name ?? '—' }}</td>
                            <td style="font-size:12px;white-space:nowrap;">
                                {{ $contact->created_at->format('M d, Y') }}
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.contacts.show', $contact) }}"
                                        class="action-btn action-btn--edit" title="View & Reply">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <button class="action-btn action-btn--delete" title="Delete"
                                        data-delete-url="{{ route('admin.contacts.destroy', $contact) }}"
                                        data-delete-label="Message #{{ $contact->id }} from {{ $contact->full_name }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="fa-solid fa-envelope"></i></div>
                                    <div class="empty-state-title">No contact messages found</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($contacts->hasPages())
            <div class="pagination-wrap">
                <span>Showing {{ $contacts->firstItem() }}–{{ $contacts->lastItem() }} of {{ $contacts->total() }}</span>
                {{ $contacts->withQueryString()->links('vendor.pagination.simple-admin') }}
            </div>
        @endif

    </div>

@endsection
