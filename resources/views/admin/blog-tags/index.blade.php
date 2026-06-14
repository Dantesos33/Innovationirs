@extends('layouts.admin')
@section('title', 'Blog Tags')

@section('breadcrumb')
    <a href="{{ route('admin.blog.index') }}">Blog</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">Tags</span>
@endsection

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Blog Tags</h1>
            <p class="page-subtitle">{{ $tags->count() }} tags</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.blog.index') }}" class="btn btn--ghost">
                <i class="fa-solid fa-arrow-left"></i> Back to Blog
            </a>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 360px;gap:20px;align-items:start;">

        {{-- Tag List --}}
        <div class="card">
            <div class="card-header"><span class="card-title">All Tags</span></div>
            <div class="table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Tag</th>
                            <th>Slug</th>
                            <th>Posts</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tags as $tag)
                            <tr>
                                <td>
                                    <span class="badge badge--blue" style="font-size:12px;">
                                        {{ $tag->name }}
                                    </span>
                                </td>
                                <td style="font-size:12px;color:var(--text-muted);">{{ $tag->slug }}</td>
                                <td>
                                    <span class="badge badge--gray">{{ $tag->posts_count ?? 0 }}</span>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        {{-- Inline edit via small form --}}
                                        <button type="button" class="action-btn action-btn--edit"
                                            onclick="document.getElementById('editTag{{ $tag->id }}').style.display='flex';"
                                            title="Rename">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <button class="action-btn action-btn--delete"
                                            data-delete-url="{{ route('admin.blog-tags.destroy', $tag) }}"
                                            data-delete-label="{{ $tag->name }}">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                    {{-- Inline rename form --}}
                                    <form id="editTag{{ $tag->id }}"
                                        action="{{ route('admin.blog-tags.update', $tag) }}" method="POST"
                                        style="display:none;align-items:center;gap:6px;margin-top:6px;">
                                        @csrf @method('PUT')
                                        <input type="text" name="name" value="{{ $tag->name }}"
                                            class="form-control" style="width:160px;padding:5px 8px;font-size:12px;">
                                        <button type="submit" class="btn btn--primary btn--sm">Save</button>
                                        <button type="button" class="btn btn--ghost btn--sm"
                                            onclick="document.getElementById('editTag{{ $tag->id }}').style.display='none';">
                                            Cancel
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="fa-solid fa-tags"></i></div>
                                        <div class="empty-state-title">No tags yet</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Add Tag --}}
        <div class="form-sidebar-card">
            <div class="form-sidebar-section">
                <div class="form-sidebar-title">Add New Tag</div>
                <form action="{{ route('admin.blog-tags.store') }}" method="POST">
                    @csrf
                    <div class="form-group" style="margin-bottom:12px;">
                        <label class="form-label" for="tag_name">Tag Name <span class="required">*</span></label>
                        <input type="text" id="tag_name" name="name"
                            class="form-control {{ $errors->has('name') ? 'form-control--error' : '' }}"
                            value="{{ old('name') }}" placeholder="e.g. Maintenance" required autofocus>
                        @error('name')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn--primary w-full" style="justify-content:center;">
                        <i class="fa-solid fa-plus"></i> Add Tag
                    </button>
                </form>
            </div>
            <div class="form-sidebar-section">
                <div style="font-size:12px;color:var(--text-muted);line-height:1.6;">
                    <i class="fa-solid fa-circle-info" style="color:var(--info);margin-right:4px;"></i>
                    Tags are assigned to posts from the blog post editor. You can rename or delete tags here.
                    Deleting a tag removes it from all associated posts.
                </div>
            </div>
        </div>

    </div>

@endsection
