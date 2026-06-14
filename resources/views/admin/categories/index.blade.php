@extends('layouts.admin')
@section('title', 'Categories')
@section('breadcrumb')
    <span class="breadcrumb-current">Categories</span>
@endsection
@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Part Categories</h1>
            <p class="page-subtitle">{{ $categories->total() }} categories</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.categories.create') }}" class="btn btn--primary">
                <i class="fa-solid fa-plus"></i> Add Category
            </a>
        </div>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table class="admin-table" data-sortable="{{ route('admin.categories.reorder') }}">
                <thead>
                    <tr>
                        <th style="width:30px;"></th>
                        <th>Category</th>
                        <th>Parts</th>
                        <th>Featured</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr data-id="{{ $category->id }}">
                            <td><span class="sortable-handle"><i class="fa-solid fa-grip-vertical"></i></span></td>
                            <td>
                                <div style="display:flex;align-items:center;gap:12px;">
                                    @if ($category->image)
                                        <img src="{{ $category->image->public_url }}" class="table-thumb" alt="">
                                    @else
                                        <div class="table-thumb-placeholder"><i class="fa-solid fa-layer-group"></i></div>
                                    @endif
                                    <div>
                                        <div class="table-name">{{ $category->name }}</div>
                                        <div class="table-meta">/parts/{{ $category->slug }}</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge--blue">{{ $category->parts_count ?? 0 }}</span></td>
                            <td>
                                @if ($category->is_featured)
                                    <span class="badge badge--green"><i class="fa-solid fa-star"></i> Yes</span>
                                @else
                                    <span class="badge badge--gray">No</span>
                                @endif
                            </td>
                            <td>{{ $category->sort_order }}</td>
                            <td>
                                <span class="badge badge--{{ $category->is_active ? 'green' : 'red' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                        class="action-btn action-btn--edit"><i class="fa-solid fa-pen"></i></a>
                                    <button class="action-btn action-btn--delete"
                                        data-delete-url="{{ route('admin.categories.destroy', $category) }}"
                                        data-delete-label="{{ $category->name }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="fa-solid fa-layer-group"></i></div>
                                    <div class="empty-state-title">No categories yet</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($categories->hasPages())
            <div class="pagination-wrap">
                {{ $categories->links('vendor.pagination.simple-admin') }}
            </div>
        @endif
    </div>
@endsection
