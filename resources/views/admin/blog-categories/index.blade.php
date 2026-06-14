@extends('layouts.admin')
@section('title', 'Blog Categories')

@section('breadcrumb')
    <a href="{{ route('admin.blog.index') }}">Blog</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">Categories</span>
@endsection

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Blog Categories</h1>
            <p class="page-subtitle">{{ $categories->total() }} categories</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.blog.index') }}" class="btn btn--ghost">
                <i class="fa-solid fa-arrow-left"></i> Back to Blog
            </a>
            <a href="{{ route('admin.blog-categories.create') }}" class="btn btn--primary">
                <i class="fa-solid fa-plus"></i> Add Category
            </a>
        </div>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Posts</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                        <tr>
                            <td>
                                <div class="table-name">{{ $cat->name }}</div>
                                <div class="table-meta">/blog/{{ $cat->slug }}/category/{{ $cat->id }}</div>
                            </td>
                            <td>
                                <span class="badge badge--blue">{{ $cat->posts_count ?? 0 }}</span>
                            </td>
                            <td>
                                <span class="badge badge--{{ $cat->is_active ? 'green' : 'red' }}">
                                    {{ $cat->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.blog-categories.edit', $cat) }}"
                                        class="action-btn action-btn--edit"><i class="fa-solid fa-pen"></i></a>
                                    <button class="action-btn action-btn--delete"
                                        data-delete-url="{{ route('admin.blog-categories.destroy', $cat) }}"
                                        data-delete-label="{{ $cat->name }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="fa-solid fa-folder"></i></div>
                                    <div class="empty-state-title">No blog categories yet</div>
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
