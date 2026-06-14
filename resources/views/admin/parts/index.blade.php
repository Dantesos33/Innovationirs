@extends('layouts.admin')

@section('title', 'Parts')

@section('breadcrumb')
    <span class="breadcrumb-current">Parts</span>
@endsection

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Parts</h1>
            <p class="page-subtitle">{{ number_format($parts->total()) }} total parts in catalog</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.parts.export') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                class="btn btn--secondary">
                <i class="fa-solid fa-download"></i> Export CSV
            </a>
            <a href="{{ route('admin.parts.create') }}" class="btn btn--primary">
                <i class="fa-solid fa-plus"></i> Add Part
            </a>
        </div>
    </div>

    {{-- Bulk action bar --}}
    <div class="bulk-bar" id="bulkBar">
        <span>Selected: <strong class="bulk-count" id="bulkCount">0</strong></span>
        <button class="btn btn--secondary btn--sm" data-bulk-action="activate">Activate</button>
        <button class="btn btn--secondary btn--sm" data-bulk-action="deactivate">Deactivate</button>
        <button class="btn btn--danger btn--sm" data-bulk-action="delete">Delete</button>
        <form id="bulkForm" method="POST" action="{{ route('admin.parts.bulk') }}">
            @csrf
            <input type="hidden" name="action" value="">
            <input type="hidden" name="ids" value="">
        </form>
    </div>

    <div class="card">

        {{-- Filters --}}
        <form method="GET" action="{{ route('admin.parts.index') }}">
            <div class="filters-bar">
                <div class="filter-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search name, part #, SKU…">
                </div>
                <select name="make" class="filter-select">
                    <option value="">All Makes</option>
                    @foreach ($makes as $make)
                        <option value="{{ $make->id }}" {{ request('make') == $make->id ? 'selected' : '' }}>
                            {{ $make->name }}
                        </option>
                    @endforeach
                </select>
                <select name="category" class="filter-select">
                    <option value="">All Categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                <select name="condition" class="filter-select">
                    <option value="">All Conditions</option>
                    <option value="new" {{ request('condition') === 'new' ? 'selected' : '' }}>New</option>
                    <option value="used" {{ request('condition') === 'used' ? 'selected' : '' }}>Used</option>
                    <option value="rebuilt" {{ request('condition') === 'rebuilt' ? 'selected' : '' }}>Rebuilt</option>
                    <option value="salvage" {{ request('condition') === 'salvage' ? 'selected' : '' }}>Salvage</option>
                </select>
                <select name="status" class="filter-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
                <div class="filter-actions">
                    @if (request()->hasAny(['search', 'make', 'category', 'condition', 'status']))
                        <a href="{{ route('admin.parts.index') }}" class="btn btn--ghost btn--sm">Clear</a>
                    @endif
                    <button type="submit" class="btn btn--secondary btn--sm">Filter</button>
                </div>
            </div>
        </form>

        {{-- Table --}}
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width:40px;">
                            <input type="checkbox" id="selectAll" style="accent-color:var(--primary);">
                        </th>
                        <th>Part</th>
                        <th>Part #</th>
                        <th>Make</th>
                        <th>Condition</th>

                        <th>Stock</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($parts as $part)
                        <tr>
                            <td>
                                <input type="checkbox" class="row-check" value="{{ $part->id }}"
                                    style="accent-color:var(--primary);">
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:12px;">
                                    @if ($part->primaryImage)
                                        <img src="{{ $part->primaryImage->public_url }}" alt="{{ $part->name }}"
                                            class="table-thumb">
                                    @else
                                        <div class="table-thumb-placeholder">
                                            <i class="fa-solid fa-screwdriver-wrench"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="table-name">{{ Str::limit($part->name, 50) }}</div>
                                        @if ($part->sku)
                                            <div class="table-meta">SKU: {{ $part->sku }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="table-name">{{ $part->part_number ?? '—' }}</div>
                                @if ($part->oem_part_number)
                                    <div class="table-meta">OEM: {{ $part->oem_part_number }}</div>
                                @endif
                            </td>
                            <td>{{ $part->make?->name ?? '—' }}</td>
                            <td>
                                <span
                                    class="badge badge--{{ match ($part->condition_type) {
                                        'new' => 'green',
                                        'used' => 'yellow',
                                        'rebuilt' => 'blue',
                                        'salvage' => 'gray',
                                        default => 'gray',
                                    } }}">{{ ucfirst($part->condition_type) }}</span>
                            </td>

                            <td>
                                <span
                                    class="badge badge--{{ match ($part->stock_status) {
                                        'in_stock' => 'green',
                                        'out_of_stock' => 'red',
                                        'on_order' => 'yellow',
                                        'call_for_availability' => 'blue',
                                        default => 'gray',
                                    } }}">
                                    {{ str_replace('_', ' ', ucfirst($part->stock_status)) }}
                                </span>
                            </td>
                            <td>
                                <span
                                    class="badge badge--{{ match ($part->status) {
                                        'active' => 'green',
                                        'inactive' => 'red',
                                        'draft' => 'gray',
                                        'archived' => 'gray',
                                        default => 'gray',
                                    } }}">{{ ucfirst($part->status) }}</span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('parts.show', [$part->slug, $part->part_number ?? 'part', $part->id]) }}"
                                        class="action-btn action-btn--view" title="View on site" target="_blank">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </a>
                                    <a href="{{ route('admin.parts.edit', $part) }}" class="action-btn action-btn--edit"
                                        title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <button class="action-btn action-btn--delete" title="Delete"
                                        data-delete-url="{{ route('admin.parts.destroy', $part) }}"
                                        data-delete-label="{{ $part->name }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="fa-solid fa-screwdriver-wrench"></i></div>
                                    <div class="empty-state-title">No parts found</div>
                                    <div class="empty-state-text">
                                        @if (request()->hasAny(['search', 'make', 'category', 'condition', 'status']))
                                            Try adjusting your filters.
                                        @else
                                            <a href="{{ route('admin.parts.create') }}" class="btn btn--primary"
                                                style="margin-top:12px;">Add your first part</a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($parts->hasPages())
            <div class="pagination-wrap">
                <span>Showing {{ $parts->firstItem() }}–{{ $parts->lastItem() }} of {{ $parts->total() }}</span>
                {{ $parts->withQueryString()->links('vendor.pagination.simple-admin') }}
            </div>
        @endif

    </div>

@endsection
