@extends('layouts.admin')
@section('title', isset($make) ? 'Edit Make' : 'Add Make')

@section('breadcrumb')
    <a href="{{ route('admin.makes.index') }}">Makes & Brands</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">{{ isset($make) ? 'Edit: ' . $make->name : 'Add Make' }}</span>
@endsection

@section('content')

    <form action="{{ isset($make) ? route('admin.makes.update', $make) : route('admin.makes.store') }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @if (isset($make))
            @method('PUT')
        @endif

        <div class="page-header">
            <div>
                <h1 class="page-title">{{ isset($make) ? 'Edit Make' : 'Add New Make' }}</h1>
                @if (isset($make))
                    <p class="page-subtitle">{{ $make->parts->count() }} parts under this make</p>
                @endif
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.makes.index') }}" class="btn btn--ghost">Cancel</a>
                <button type="submit" class="btn btn--primary">
                    <i class="fa-solid fa-floppy-disk"></i>
                    {{ isset($make) ? 'Save Changes' : 'Create Make' }}
                </button>
            </div>
        </div>

        <div class="form-layout">

            {{-- Main --}}
            <div style="display:flex;flex-direction:column;gap:20px;">

                <div class="card">
                    <div class="card-header"><span class="card-title">Brand Details</span></div>
                    <div class="card-body" style="display:flex;flex-direction:column;gap:16px;">

                        <div class="form-group">
                            <label class="form-label" for="name">Brand Name <span class="required">*</span></label>
                            <input type="text" id="slugSource" name="name"
                                class="form-control {{ $errors->has('name') ? 'form-control--error' : '' }}"
                                value="{{ old('name', $make->name ?? '') }}" placeholder="e.g. Caterpillar" required>
                            @error('name')
                                <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i>
                                    {{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="slug">URL Slug</label>
                            <input type="text" id="slug" name="slug"
                                class="form-control {{ $errors->has('slug') ? 'form-control--error' : '' }}"
                                value="{{ old('slug', $make->slug ?? '') }}" placeholder="auto-generated">
                            <span class="form-hint">Used in URL: /<strong>caterpillar</strong>-equipment-parts</span>
                            @error('slug')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="4"
                                placeholder="Brief brand overview shown on the make's listing page…">{{ old('description', $make->description ?? '') }}</textarea>
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
                                value="{{ old('meta_title', $make->meta_title ?? '') }}"
                                placeholder="e.g. Caterpillar Equipment Parts — Parts Plus Innovation Solutions">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="meta_description">Meta Description</label>
                            <textarea name="meta_description" id="meta_description" class="form-control" rows="2" maxlength="500"
                                placeholder="Description for search engines…">{{ old('meta_description', $make->meta_description ?? '') }}</textarea>
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
                                    {{ old('is_active', $make->is_active ?? true) ? 'checked' : '' }}>
                                <span class="toggle-track"></span>
                                <span class="toggle-label">Active (visible on site)</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-sidebar-section">
                        <div class="form-group">
                            <label class="form-label" for="sort_order">Display Order</label>
                            <input type="number" name="sort_order" id="sort_order" class="form-control" min="0"
                                value="{{ old('sort_order', $make->sort_order ?? 0) }}">
                            <span class="form-hint">Lower = shown first in navigation</span>
                        </div>
                    </div>
                    <div class="form-sidebar-section">
                        <button type="submit" class="btn btn--primary w-full" style="justify-content:center;">
                            <i class="fa-solid fa-floppy-disk"></i>
                            {{ isset($make) ? 'Save Changes' : 'Create Make' }}
                        </button>
                    </div>
                </div>

                {{-- Logo --}}
                <div class="form-sidebar-card">
                    <div class="form-sidebar-section">
                        <div class="form-sidebar-title">Brand Logo</div>

                        @if (isset($make) && $make->logo)
                            <div
                                style="margin-bottom:10px;padding:12px;background:var(--gray-50);border-radius:var(--radius);text-align:center;">
                                <img src="{{ $make->logo->public_url }}" alt="{{ $make->name }}"
                                    style="max-height:60px;max-width:140px;object-fit:contain;">
                            </div>
                            <p class="form-hint" style="margin-bottom:8px;">Upload new to replace</p>
                        @endif

                        <div class="image-upload-area" data-image-upload="make-logo">
                            <input type="file" name="logo" accept="image/jpeg,image/png,image/webp,image/svg+xml">
                            <div class="upload-icon"><i class="fa-solid fa-image"></i></div>
                            <div class="upload-text">Click or drag logo here</div>
                            <div class="upload-hint">JPG, PNG, WebP, SVG — max 2MB<br>Transparent PNG preferred</div>
                        </div>
                        @error('logo')
                            <span class="form-error" style="margin-top:6px;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

            </div>

        </div>

    </form>

@endsection
