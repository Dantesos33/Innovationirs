@extends('layouts.admin')
@section('title', isset($category) ? 'Edit Blog Category' : 'Add Blog Category')

@section('breadcrumb')
    <a href="{{ route('admin.blog.index') }}">Blog</a>
    <span class="breadcrumb-sep">/</span>
    <a href="{{ route('admin.blog-categories.index') }}">Categories</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">{{ isset($category) ? 'Edit' : 'Add' }}</span>
@endsection

@section('content')

    <form
        action="{{ isset($category) ? route('admin.blog-categories.update', $category) : route('admin.blog-categories.store') }}"
        method="POST">
        @csrf
        @if (isset($category))
            @method('PUT')
        @endif

        <div class="page-header">
            <div>
                <h1 class="page-title">{{ isset($category) ? 'Edit Category' : 'Add Blog Category' }}</h1>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.blog-categories.index') }}" class="btn btn--ghost">Cancel</a>
                <button type="submit" class="btn btn--primary">
                    <i class="fa-solid fa-floppy-disk"></i> Save
                </button>
            </div>
        </div>

        <div class="form-layout">

            <div style="display:flex;flex-direction:column;gap:20px;">
                <div class="card">
                    <div class="card-header"><span class="card-title">Category Details</span></div>
                    <div class="card-body" style="display:flex;flex-direction:column;gap:16px;">

                        <div class="form-group">
                            <label class="form-label" for="name">Name <span class="required">*</span></label>
                            <input type="text" id="slugSource" name="name"
                                class="form-control {{ $errors->has('name') ? 'form-control--error' : '' }}"
                                value="{{ old('name', $category->name ?? '') }}" placeholder="e.g. Maintenance Tips"
                                required>
                            @error('name')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="slug">URL Slug</label>
                            <input type="text" id="slug" name="slug" class="form-control"
                                value="{{ old('slug', $category->slug ?? '') }}" placeholder="auto-generated">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Optional description…">{{ old('description', $category->description ?? '') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Meta Title</label>
                            <input type="text" name="meta_title" class="form-control" maxlength="255"
                                value="{{ old('meta_title', $category->meta_title ?? '') }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Meta Description</label>
                            <textarea name="meta_description" class="form-control" rows="2" maxlength="500">{{ old('meta_description', $category->meta_description ?? '') }}</textarea>
                        </div>

                    </div>
                </div>
            </div>

            <div style="display:flex;flex-direction:column;gap:20px;">
                <div class="form-sidebar-card">
                    <div class="form-sidebar-section">
                        <div class="form-sidebar-title">Settings</div>
                        <div style="display:flex;flex-direction:column;gap:10px;">
                            <label class="toggle-switch">
                                <input type="hidden" name="is_active" value="0">
                                <input class="toggle-input" type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
                                <span class="toggle-track"></span>
                                <span class="toggle-label">Active</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-sidebar-section">
                        <div class="form-group">
                            <label class="form-label">Display Order</label>
                            <input type="number" name="sort_order" class="form-control" min="0"
                                value="{{ old('sort_order', $category->sort_order ?? 0) }}">
                        </div>
                    </div>
                    <div class="form-sidebar-section">
                        <button type="submit" class="btn btn--primary w-full" style="justify-content:center;">
                            <i class="fa-solid fa-floppy-disk"></i> Save
                        </button>
                    </div>
                </div>
            </div>

        </div>

    </form>

@endsection
