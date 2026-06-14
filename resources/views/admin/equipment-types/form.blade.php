@extends('layouts.admin')
@section('title', isset($type) ? 'Edit Equipment Type' : 'Add Equipment Type')

@section('breadcrumb')
    <a href="{{ route('admin.equipment-types.index') }}">Equipment Types</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">{{ isset($type) ? 'Edit: ' . $type->name : 'Add Type' }}</span>
@endsection

@section('content')

    <form action="{{ isset($type) ? route('admin.equipment-types.update', $type) : route('admin.equipment-types.store') }}"
        method="POST" enctype="multipart/form-data">
        @csrf
        @if (isset($type))
            @method('PUT')
        @endif

        <div class="page-header">
            <div>
                <h1 class="page-title">{{ isset($type) ? 'Edit Equipment Type' : 'Add Equipment Type' }}</h1>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.equipment-types.index') }}" class="btn btn--ghost">Cancel</a>
                <button type="submit" class="btn btn--primary">
                    <i class="fa-solid fa-floppy-disk"></i>
                    {{ isset($type) ? 'Save Changes' : 'Create Type' }}
                </button>
            </div>
        </div>

        <div class="form-layout">

            <div style="display:flex;flex-direction:column;gap:20px;">

                <div class="card">
                    <div class="card-header"><span class="card-title">Type Details</span></div>
                    <div class="card-body" style="display:flex;flex-direction:column;gap:16px;">

                        <div class="form-group">
                            <label class="form-label" for="name">Name <span class="required">*</span></label>
                            <input type="text" id="slugSource" name="name"
                                class="form-control {{ $errors->has('name') ? 'form-control--error' : '' }}"
                                value="{{ old('name', $type->name ?? '') }}"
                                placeholder="e.g. Excavator, Backhoe, Bulldozer" required>
                            @error('name')
                                <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i>
                                    {{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="slug">URL Slug</label>
                            <input type="text" id="slug" name="slug"
                                class="form-control {{ $errors->has('slug') ? 'form-control--error' : '' }}"
                                value="{{ old('slug', $type->slug ?? '') }}" placeholder="auto-generated">
                            <span class="form-hint">URL: /<strong>excavator</strong>-parts</span>
                            @error('slug')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3"
                                placeholder="Brief description of this equipment type…">{{ old('description', $type->description ?? '') }}</textarea>
                        </div>

                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><span class="card-title">SEO</span></div>
                    <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">
                        <div class="form-group">
                            <label class="form-label">Meta Title</label>
                            <input type="text" name="meta_title" class="form-control" maxlength="255"
                                value="{{ old('meta_title', $type->meta_title ?? '') }}"
                                placeholder="e.g. Excavator Parts — Parts Plus Innovation Solutions">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Meta Description</label>
                            <textarea name="meta_description" class="form-control" rows="2" maxlength="500"
                                placeholder="Search engine description…">{{ old('meta_description', $type->meta_description ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Sidebar --}}
            <div style="display:flex;flex-direction:column;gap:20px;">

                <div class="form-sidebar-card">
                    <div class="form-sidebar-section">
                        <div class="form-sidebar-title">Settings</div>
                        <div style="display:flex;flex-direction:column;gap:12px;">
                            <label class="toggle-switch">
                                <input type="hidden" name="is_active" value="0">
                                <input class="toggle-input" type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', $type->is_active ?? true) ? 'checked' : '' }}>
                                <span class="toggle-track"></span>
                                <span class="toggle-label">Active (visible on site)</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-sidebar-section">
                        <div class="form-group">
                            <label class="form-label">Display Order</label>
                            <input type="number" name="sort_order" class="form-control" min="0"
                                value="{{ old('sort_order', $type->sort_order ?? 0) }}">
                            <span class="form-hint">Lower = appears first</span>
                        </div>
                    </div>
                    <div class="form-sidebar-section">
                        <button type="submit" class="btn btn--primary w-full" style="justify-content:center;">
                            <i class="fa-solid fa-floppy-disk"></i>
                            {{ isset($type) ? 'Save Changes' : 'Create Type' }}
                        </button>
                    </div>
                </div>

                <div class="form-sidebar-card">
                    <div class="form-sidebar-section">
                        <div class="form-sidebar-title">Type Image</div>
                        @if (isset($type) && $type->image)
                            <div style="margin-bottom:10px;">
                                <img src="{{ $type->image->public_url }}" alt="{{ $type->name }}"
                                    style="width:100%;border-radius:var(--radius);object-fit:cover;max-height:140px;border:1px solid var(--card-border);">
                            </div>
                        @endif
                        <div class="image-upload-area" data-image-upload="type-image">
                            <input type="file" name="image" accept="image/jpeg,image/png,image/webp">
                            <div class="upload-icon"><i class="fa-solid fa-image"></i></div>
                            <div class="upload-text">Click or drag image here</div>
                            <div class="upload-hint">JPG, PNG, WebP — max 5MB</div>
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
