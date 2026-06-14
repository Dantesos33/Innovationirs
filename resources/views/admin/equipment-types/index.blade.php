@extends('layouts.admin')
@section('title', 'Equipment Types')

@section('breadcrumb')
    <span class="breadcrumb-current">Equipment Types</span>
@endsection

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Equipment Types</h1>
            <p class="page-subtitle">{{ $types->total() }} equipment classifications</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.equipment-types.create') }}" class="btn btn--primary">
                <i class="fa-solid fa-plus"></i> Add Type
            </a>
        </div>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Equipment Type</th>
                        <th>Parts</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($types as $type)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:12px;">
                                    @if ($type->image)
                                        <img src="{{ $type->image->public_url }}" class="table-thumb" alt="">
                                    @else
                                        <div class="table-thumb-placeholder">
                                            <i class="fa-solid fa-tractor"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="table-name">{{ $type->name }}</div>
                                        <div class="table-meta">/{{ $type->slug }}-parts</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge--blue">{{ $type->parts_count ?? 0 }}</span>
                            </td>
                            <td style="color:var(--text-muted);font-size:12px;">{{ $type->sort_order }}</td>
                            <td>
                                <span class="badge badge--{{ $type->is_active ? 'green' : 'red' }}">
                                    {{ $type->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.equipment-types.edit', $type) }}"
                                        class="action-btn action-btn--edit" title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <button class="action-btn action-btn--delete" title="Delete"
                                        data-delete-url="{{ route('admin.equipment-types.destroy', $type) }}"
                                        data-delete-label="{{ $type->name }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="fa-solid fa-tractor"></i></div>
                                    <div class="empty-state-title">No equipment types yet</div>
                                    <div class="empty-state-text" style="margin-top:12px;">
                                        <a href="{{ route('admin.equipment-types.create') }}"
                                            class="btn btn--primary btn--sm">
                                            Add First Type
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($types->hasPages())
            <div class="pagination-wrap">
                <span>Showing {{ $types->firstItem() }}–{{ $types->lastItem() }} of {{ $types->total() }}</span>
                {{ $types->links('vendor.pagination.simple-admin') }}
            </div>
        @endif
    </div>

@endsection
