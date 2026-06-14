@extends('layouts.admin')
@section('title', 'Blog Posts')
@section('breadcrumb')
    <span class="breadcrumb-current">Blog</span>
@endsection
@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Blog Posts</h1>
            <p class="page-subtitle">{{ $posts->total() }} posts</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.blog-categories.index') }}" class="btn btn--secondary">Categories</a>
            <a href="{{ route('admin.blog-tags.index') }}" class="btn btn--secondary">Tags</a>
            <a href="{{ route('admin.blog.create') }}" class="btn btn--primary">
                <i class="fa-solid fa-plus"></i> New Post
            </a>
        </div>
    </div>

    <div class="card">
        <form method="GET" action="{{ route('admin.blog.index') }}">
            <div class="filters-bar">
                <div class="filter-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search posts…">
                </div>
                <select name="status" class="filter-select">
                    <option value="">All Status</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
                <select name="category" class="filter-select">
                    <option value="">All Categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}</option>
                    @endforeach
                </select>
                <div class="filter-actions">
                    <button type="submit" class="btn btn--secondary btn--sm">Filter</button>
                </div>
            </div>
        </form>

        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Post</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Published</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:12px;">
                                    @if ($post->featuredImage)
                                        <img src="{{ $post->featuredImage->public_url }}" class="table-thumb"
                                            alt="">
                                    @else
                                        <div class="table-thumb-placeholder"><i class="fa-solid fa-newspaper"></i></div>
                                    @endif
                                    <div>
                                        <div class="table-name">{{ Str::limit($post->title, 50) }}</div>
                                        <div class="table-meta">{{ $post->read_time_minutes }} min read</div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-size:12px;">{{ $post->author?->name }}</td>
                            <td style="font-size:12px;">{{ $post->category?->name ?? '—' }}</td>
                            <td>
                                <span class="badge badge--{{ $post->status === 'published' ? 'green' : 'gray' }}">
                                    {{ ucfirst($post->status) }}
                                </span>
                            </td>
                            <td style="font-size:12px;">{{ number_format($post->views) }}</td>
                            <td style="font-size:12px;">{{ $post->published_at?->format('M d, Y') ?? '—' }}</td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('blog.show', [$post->slug, $post->id]) }}"
                                        class="action-btn action-btn--view" target="_blank" title="View">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </a>
                                    <a href="{{ route('admin.blog.edit', $post) }}" class="action-btn action-btn--edit"><i
                                            class="fa-solid fa-pen"></i></a>
                                    <button class="action-btn action-btn--delete"
                                        data-delete-url="{{ route('admin.blog.destroy', $post) }}"
                                        data-delete-label="{{ $post->title }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="fa-solid fa-newspaper"></i></div>
                                    <div class="empty-state-title">No posts yet</div>
                                    <div class="empty-state-text"><a href="{{ route('admin.blog.create') }}"
                                            class="btn btn--primary" style="margin-top:12px;">Write first post</a></div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($posts->hasPages())
            <div class="pagination-wrap">
                <span>{{ $posts->firstItem() }}–{{ $posts->lastItem() }} of {{ $posts->total() }}</span>
                {{ $posts->withQueryString()->links('vendor.pagination.simple-admin') }}
            </div>
        @endif
    </div>
@endsection
