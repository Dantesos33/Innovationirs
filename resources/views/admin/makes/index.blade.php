@extends('layouts.admin')
@section('title', 'Makes & Brands')
@section('breadcrumb')
    <span class="breadcrumb-current">Makes & Brands</span>
@endsection
@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Makes & Brands</h1>
            <p class="page-subtitle">Equipment manufacturers in catalog</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.makes.create') }}" class="btn btn--primary">
                <i class="fa-solid fa-plus"></i> Add Make
            </a>
        </div>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Make / Brand</th>
                        <th>Parts</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($makes as $make)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:12px;">
                                    @if ($make->logo)
                                        <img src="{{ $make->logo->public_url }}" class="table-thumb" alt="">
                                    @else
                                        <div class="table-thumb-placeholder"><i class="fa-solid fa-industry"></i></div>
                                    @endif
                                    <div>
                                        <div class="table-name">{{ $make->name }}</div>
                                        <div class="table-meta">/{{ $make->slug }}-equipment-parts</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge--blue">{{ $make->parts->count() }}</span></td>
                            <td>
                                <span class="badge badge--{{ $make->is_active ? 'green' : 'red' }}">
                                    {{ $make->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.makes.edit', $make) }}" class="action-btn action-btn--edit"><i
                                            class="fa-solid fa-pen"></i></a>
                                    <button class="action-btn action-btn--delete"
                                        data-delete-url="{{ route('admin.makes.destroy', $make) }}"
                                        data-delete-label="{{ $make->name }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="fa-solid fa-industry"></i></div>
                                    <div class="empty-state-title">No makes yet</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
