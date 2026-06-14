@extends('layouts.admin')
@section('title', isset($category) ? 'Edit Category' : 'Add Category')

@section('breadcrumb')
    <a href="{{ route('admin.categories.index') }}">Categories</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">{{ isset($category) ? 'Edit: ' . $category->name : 'Add Category' }}</span>
@endsection

@section('content')

    <form action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
        method="POST" enctype="multipart/form-data">
        @csrf
        @if (isset($category))
            @method('PUT')
        @endif

        <div class="page-header">
            <div>
                <h1 class="page-title">{{ isset($category) ? 'Edit Category' : 'Add New Category' }}</h1>
                @if (isset($category))
                    <p class="page-subtitle">{{ $category->parts_count ?? 0 }} parts assigned</p>
                @endif
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.categories.index') }}" class="btn btn--ghost">Cancel</a>
                <button type="submit" class="btn btn--primary">
                    <i class="fa-solid fa-floppy-disk"></i>
                    {{ isset($category) ? 'Save Changes' : 'Create Category' }}
                </button>
            </div>
        </div>

        <div class="form-layout">

            {{-- Main --}}
            <div style="display:flex;flex-direction:column;gap:20px;">

                <div class="card">
                    <div class="card-header"><span class="card-title">Category Details</span></div>
                    <div class="card-body" style="display:flex;flex-direction:column;gap:16px;">

                        <div class="form-group">
                            <label class="form-label" for="name">Name <span class="required">*</span></label>
                            <input type="text" id="slugSource" name="name"
                                class="form-control {{ $errors->has('name') ? 'form-control--error' : '' }}"
                                value="{{ old('name', $category->name ?? '') }}" placeholder="e.g. Hydraulic Pumps"
                                required>
                            @error('name')
                                <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i>
                                    {{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="slug">URL Slug</label>
                            <input type="text" id="slug" name="slug"
                                class="form-control {{ $errors->has('slug') ? 'form-control--error' : '' }}"
                                value="{{ old('slug', $category->slug ?? '') }}" placeholder="auto-generated">
                            <span class="form-hint">Used in URL: /parts/<strong>hydraulic-pumps</strong></span>
                            @error('slug')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="4"
                                placeholder="Describe what parts fall under this category…">{{ old('description', $category->description ?? '') }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- SEO --}}
                <div class="card">
                    <div class="card-header"><span class="card-title">SEO</span></div>
                    <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">
                        <div class="form-group">
                            <label class="form-label" for="meta_title">Meta Title</label>
                            <input type="text" name="meta_title" id="meta_title" class="form-control" maxlength="255"
                                value="{{ old('meta_title', $category->meta_title ?? '') }}"
                                placeholder="Defaults to category name">
                            <span class="form-hint">Recommended: under 60 characters</span>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="meta_description">Meta Description</label>
                            <textarea name="meta_description" id="meta_description" class="form-control" rows="2" maxlength="500"
                                placeholder="Defaults to description">{{ old('meta_description', $category->meta_description ?? '') }}</textarea>
                            <span class="form-hint">Recommended: under 160 characters</span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Sidebar --}}
            <div style="display:flex;flex-direction:column;gap:20px;">

                {{-- Publish --}}
                <div class="form-sidebar-card">
                    <div class="form-sidebar-section">
                        <div class="form-sidebar-title">Settings</div>

                        <div style="display:flex;flex-direction:column;gap:12px;">
                            <label class="toggle-switch">
                                <input type="hidden" name="is_active" value="0">
                                <input class="toggle-input" type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
                                <span class="toggle-track"></span>
                                <span class="toggle-label">Active (visible on site)</span>
                            </label>

                            <label class="toggle-switch">
                                <input type="hidden" name="is_featured" value="0">
                                <input class="toggle-input" type="checkbox" name="is_featured" value="1"
                                    {{ old('is_featured', $category->is_featured ?? false) ? 'checked' : '' }}>
                                <span class="toggle-track"></span>
                                <span class="toggle-label">Featured on homepage</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-sidebar-section">
                        <div class="form-group">
                            <label class="form-label" for="sort_order">Display Order</label>
                            <input type="number" name="sort_order" id="sort_order" class="form-control" min="0"
                                value="{{ old('sort_order', $category->sort_order ?? 0) }}" placeholder="0">
                            <span class="form-hint">Lower number = shown first</span>
                        </div>
                    </div>

                    <div class="form-sidebar-section">
                        <button type="submit" class="btn btn--primary w-full" style="justify-content:center;">
                            <i class="fa-solid fa-floppy-disk"></i>
                            {{ isset($category) ? 'Save Changes' : 'Create Category' }}
                        </button>
                    </div>
                </div>

                {{-- Image --}}
                <div class="form-sidebar-card">
                    <div class="form-sidebar-section">
                        <div class="form-sidebar-title">Category Image</div>

                        @if (isset($category) && $category->image)
                            <div style="margin-bottom:10px;">
                                <img src="{{ $category->image->public_url }}" alt="{{ $category->name }}"
                                    style="width:100%;border-radius:var(--radius);object-fit:cover;max-height:140px;border:1px solid var(--card-border);">
                            </div>
                            <p class="form-hint" style="margin-bottom:8px;">Upload new to replace</p>
                        @endif

                        <div class="image-upload-area" data-image-upload="cat-image">
                            <input type="file" name="image" accept="image/jpeg,image/png,image/webp">
                            <div class="upload-icon"><i class="fa-solid fa-image"></i></div>
                            <div class="upload-text">Click or drag image here</div>
                            <div class="upload-hint">JPG, PNG, WebP — max 5MB<br>Recommended: 600×400px</div>
                        </div>
                        @error('image')
                            <span class="form-error" style="margin-top:6px;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

            </div>

        </div>

    </form>

@endsection
