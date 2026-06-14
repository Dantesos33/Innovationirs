@extends('layouts.admin')
@section('title', 'Equipment Models')

@section('breadcrumb')
    <span class="breadcrumb-current">Equipment Models</span>
@endsection

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Equipment Models</h1>
            <p class="page-subtitle">{{ $models->total() }} models across all makes</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.equipment-models.create') }}" class="btn btn--primary">
                <i class="fa-solid fa-plus"></i> Add Model
            </a>
        </div>
    </div>

    <div class="card">

        {{-- Filters --}}
        <form method="GET" action="{{ route('admin.equipment-models.index') }}">
            <div class="filters-bar">
                <div class="filter-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search model name…">
                </div>
                <select name="make" class="filter-select">
                    <option value="">All Makes</option>
                    @foreach ($makes as $make)
                        <option value="{{ $make->id }}" {{ request('make') == $make->id ? 'selected' : '' }}>
                            {{ $make->name }}
                        </option>
                    @endforeach
                </select>
                <div class="filter-actions">
                    @if (request()->hasAny(['search', 'make']))
                        <a href="{{ route('admin.equipment-models.index') }}" class="btn btn--ghost btn--sm">Clear</a>
                    @endif
                    <button type="submit" class="btn btn--secondary btn--sm">Filter</button>
                </div>
            </div>
        </form>

        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Model</th>
                        <th>Make</th>
                        <th>Year Range</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($models as $model)
                        <tr>
                            <td>
                                <div class="table-name">{{ $model->name }}</div>
                                <div class="table-meta">/{{ $model->slug }}</div>
                            </td>
                            <td>
                                <span class="badge badge--gray">{{ $model->make?->name ?? '—' }}</span>
                            </td>
                            <td style="font-size:12px;color:var(--text-muted);">
                                @if ($model->year_start || $model->year_end)
                                    {{ $model->year_start ?? '?' }} – {{ $model->year_end ?? 'Present' }}
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <span class="badge badge--{{ $model->is_active ? 'green' : 'red' }}">
                                    {{ $model->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.equipment-models.edit', $model) }}"
                                        class="action-btn action-btn--edit" title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <button class="action-btn action-btn--delete" title="Delete"
                                        data-delete-url="{{ route('admin.equipment-models.destroy', $model) }}"
                                        data-delete-label="{{ $model->name }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="fa-solid fa-sitemap"></i></div>
                                    <div class="empty-state-title">No equipment models yet</div>
                                    <div class="empty-state-text" style="margin-top:12px;">
                                        <a href="{{ route('admin.equipment-models.create') }}"
                                            class="btn btn--primary btn--sm">
                                            Add First Model
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($models->hasPages())
            <div class="pagination-wrap">
                <span>Showing {{ $models->firstItem() }}–{{ $models->lastItem() }} of {{ $models->total() }}</span>
                {{ $models->withQueryString()->links('vendor.pagination.simple-admin') }}
            </div>
        @endif

    </div>

@endsection
