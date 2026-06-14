@extends('layouts.admin')

@section('title', isset($part) ? 'Edit Part' : 'Add Part')

@section('breadcrumb')
    <a href="{{ route('admin.parts.index') }}">Parts</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">{{ isset($part) ? 'Edit: ' . Str::limit($part->name, 40) : 'Add New Part' }}</span>
@endsection

@section('content')

    <form action="{{ isset($part) ? route('admin.parts.update', $part) : route('admin.parts.store') }}" method="POST"
        enctype="multipart/form-data" id="partForm">
        @csrf
        @if (isset($part))
            @method('PUT')
        @endif

        <div class="page-header">
            <div>
                <h1 class="page-title">{{ isset($part) ? 'Edit Part' : 'Add New Part' }}</h1>
                @if (isset($part))
                    <p class="page-subtitle">ID #{{ $part->id }} &middot; {{ $part->views }} views</p>
                @endif
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.parts.index') }}" class="btn btn--ghost">Cancel</a>
                <button type="submit" class="btn btn--primary">
                    <i class="fa-solid fa-floppy-disk"></i>
                    {{ isset($part) ? 'Save Changes' : 'Create Part' }}
                </button>
            </div>
        </div>

        <div class="form-layout">

            {{-- ── LEFT COLUMN: Main Fields ─────────────────────── --}}
            <div style="display:flex;flex-direction:column;gap:20px;">

                {{-- Basic Info --}}
                <div class="card">
                    <div class="card-header"><span class="card-title">Basic Information</span></div>
                    <div class="card-body">
                        <div class="form-grid" style="gap:18px;">

                            <div class="form-group form-group--full">
                                <label class="form-label" for="name">
                                    Part Name <span class="required">*</span>
                                </label>
                                <input type="text" id="slugSource" name="name"
                                    class="form-control {{ $errors->has('name') ? 'form-control--error' : '' }}"
                                    value="{{ old('name', $part->name ?? '') }}"
                                    placeholder="e.g. Hydraulic Pump Assembly — Caterpillar 320C" required>
                                @error('name')
                                    <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i>
                                        {{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="part_number">Part Number</label>
                                <input type="text" id="part_number" name="part_number"
                                    class="form-control {{ $errors->has('part_number') ? 'form-control--error' : '' }}"
                                    value="{{ old('part_number', $part->part_number ?? '') }}"
                                    placeholder="e.g. HYD-320-001">
                                @error('part_number')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="oem_part_number">OEM Part Number</label>
                                <input type="text" id="oem_part_number" name="oem_part_number" class="form-control"
                                    value="{{ old('oem_part_number', $part->oem_part_number ?? '') }}"
                                    placeholder="e.g. CAT-123-456">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="sku">SKU</label>
                                <input type="text" id="sku" name="sku"
                                    class="form-control {{ $errors->has('sku') ? 'form-control--error' : '' }}"
                                    value="{{ old('sku', $part->sku ?? '') }}" placeholder="Unique internal SKU">
                                @error('sku')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="slug">URL Slug</label>
                                <input type="text" id="slug" name="slug" class="form-control"
                                    value="{{ old('slug', $part->slug ?? '') }}" placeholder="auto-generated">
                                <span class="form-hint">Leave blank to auto-generate from name</span>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Description --}}
                <div class="card">
                    <div class="card-header"><span class="card-title">Description</span></div>
                    <div class="card-body" style="display:flex;flex-direction:column;gap:16px;">

                        <div class="form-group">
                            <label class="form-label" for="short_description">Short Description</label>
                            <textarea name="short_description" id="short_description" class="form-control" rows="3"
                                placeholder="Brief summary shown in listings (max 500 chars)" maxlength="500">{{ old('short_description', $part->short_description ?? '') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="description">Full Description</label>
                            <div class="rich-editor-wrap" data-rich-editor="description_input">
                                <div class="rich-editor-toolbar">
                                    <button data-cmd="bold" title="Bold"><i class="fa-solid fa-bold"></i></button>
                                    <button data-cmd="italic" title="Italic"><i class="fa-solid fa-italic"></i></button>
                                    <button data-cmd="underline" title="Underline"><i
                                            class="fa-solid fa-underline"></i></button>
                                    <button data-cmd="insertUnorderedList" title="Bullet List"><i
                                            class="fa-solid fa-list-ul"></i></button>
                                    <button data-cmd="insertOrderedList" title="Numbered List"><i
                                            class="fa-solid fa-list-ol"></i></button>
                                    <button data-cmd="formatBlock" data-arg="h3" title="Heading"><i
                                            class="fa-solid fa-heading"></i></button>
                                    <button data-cmd="removeFormat" title="Clear Formatting"><i
                                            class="fa-solid fa-text-slash"></i></button>
                                </div>
                                <div class="rich-editor-content" contenteditable="true">
                                    {{ old('description', $part->description ?? '') }}</div>
                            </div>
                            <input type="hidden" name="description" id="description_input"
                                value="{{ old('description', $part->description ?? '') }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="compatibility_notes">Compatibility Notes</label>
                            <textarea name="compatibility_notes" id="compatibility_notes" class="form-control" rows="3"
                                placeholder="List compatible machines, years, serial ranges…">{{ old('compatibility_notes', $part->compatibility_notes ?? '') }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- Specifications --}}
                <div class="card">
                    <div class="card-header"><span class="card-title">Specifications</span></div>
                    <div class="card-body">
                        <div class="form-grid form-grid--2" style="gap:16px;">

                            <div class="form-group">
                                <label class="form-label" for="weight_lbs">Weight (lbs)</label>
                                <input type="number" id="weight_lbs" name="weight_lbs" class="form-control"
                                    step="0.01" min="0"
                                    value="{{ old('weight_lbs', $part->weight_lbs ?? '') }}" placeholder="0.00">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="dimensions">Dimensions</label>
                                <input type="text" id="dimensions" name="dimensions" class="form-control"
                                    value="{{ old('dimensions', $part->dimensions ?? '') }}"
                                    placeholder='e.g. 12" x 8" x 6"'>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="warranty_type">Warranty</label>
                                <select id="warranty_type" name="warranty_type" class="form-control">
                                    @foreach (['none' => 'No Warranty', '30_days' => '30 Days', '90_days' => '90 Days', '6_months' => '6 Months', '1_year' => '1 Year', '2_years' => '2 Years', '3_years' => '3 Years', 'custom' => 'Custom'] as $val => $label)
                                        <option value="{{ $val }}"
                                            {{ old('warranty_type', $part->warranty_type ?? 'none') === $val ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="warranty_notes">Warranty Notes</label>
                                <input type="text" id="warranty_notes" name="warranty_notes" class="form-control"
                                    value="{{ old('warranty_notes', $part->warranty_notes ?? '') }}"
                                    placeholder="Custom warranty terms…">
                            </div>

                            <div class="form-group form-group--full">
                                <label class="form-label" for="condition_notes">Condition Notes</label>
                                <textarea name="condition_notes" id="condition_notes" class="form-control" rows="2"
                                    placeholder="Describe cosmetic or functional condition…">{{ old('condition_notes', $part->condition_notes ?? '') }}</textarea>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Machine Compatibility --}}
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Machine Compatibility</span>
                        <span class="text-muted text-small">Which models does this part fit?</span>
                    </div>
                    <div class="card-body">
                        <div style="display:flex;flex-wrap:wrap;gap:8px;">
                            @foreach ($models as $model)
                                <label
                                    style="display:flex;align-items:center;gap:6px;padding:6px 12px;border:1px solid var(--card-border);border-radius:var(--radius);cursor:pointer;font-size:12px;transition:all 0.15s;"
                                    class="model-check-label">
                                    <input type="checkbox" name="model_ids[]" value="{{ $model->id }}"
                                        style="accent-color:var(--primary);"
                                        {{ in_array($model->id, old('model_ids', $part->fitsModels->pluck('id')->toArray() ?? [])) ? 'checked' : '' }}>
                                    {{ $model->full_name }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- SEO --}}
                <div class="card">
                    <div class="card-header"><span class="card-title">SEO</span></div>
                    <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">
                        <div class="form-group">
                            <label class="form-label" for="meta_title">Meta Title</label>
                            <input type="text" id="meta_title" name="meta_title" class="form-control"
                                maxlength="255" value="{{ old('meta_title', $part->meta_title ?? '') }}"
                                placeholder="Leave blank to auto-generate">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="meta_description">Meta Description</label>
                            <textarea name="meta_description" id="meta_description" class="form-control" rows="2" maxlength="500"
                                placeholder="Leave blank to use short description">{{ old('meta_description', $part->meta_description ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

            </div>{{-- end left column --}}

            {{-- ── RIGHT COLUMN: Sidebar Fields ────────────────── --}}
            <div style="display:flex;flex-direction:column;gap:20px;">

                {{-- Publish --}}
                <div class="form-sidebar-card">
                    <div class="form-sidebar-section">
                        <div class="form-sidebar-title">Publish</div>
                        <div class="form-group">
                            <label class="form-label" for="status">Status</label>
                            <select id="status" name="status" class="form-control">
                                <option value="draft"
                                    {{ old('status', $part->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft
                                </option>
                                <option value="active"
                                    {{ old('status', $part->status ?? '') === 'active' ? 'selected' : '' }}>Active (Live)
                                </option>
                                <option value="inactive"
                                    {{ old('status', $part->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive
                                    (Hidden)</option>
                                <option value="archived"
                                    {{ old('status', $part->status ?? '') === 'archived' ? 'selected' : '' }}>Archived
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-sidebar-section">
                        <label class="toggle-switch">
                            <input type="hidden" name="is_featured" value="0">
                            <input class="toggle-input" type="checkbox" name="is_featured" value="1"
                                {{ old('is_featured', $part->is_featured ?? false) ? 'checked' : '' }}>
                            <span class="toggle-track"></span>
                            <span class="toggle-label">Featured Part</span>
                        </label>
                        <div style="margin-top:10px;">
                            <label class="toggle-switch">
                                <input type="hidden" name="ships_worldwide" value="0">
                                <input class="toggle-input" type="checkbox" name="ships_worldwide" value="1"
                                    {{ old('ships_worldwide', $part->ships_worldwide ?? true) ? 'checked' : '' }}>
                                <span class="toggle-track"></span>
                                <span class="toggle-label">Ships Worldwide</span>
                            </label>
                        </div>
                        <div style="margin-top:10px;">
                            <label class="toggle-switch">
                                <input type="hidden" name="california_prop65" value="0">
                                <input class="toggle-input" type="checkbox" name="california_prop65" value="1"
                                    {{ old('california_prop65', $part->california_prop65 ?? false) ? 'checked' : '' }}>
                                <span class="toggle-track"></span>
                                <span class="toggle-label">CA Prop 65 Warning</span>
                            </label>
                        </div>
                        <div style="margin-top:10px;">
                            <label class="toggle-switch">
                                <input type="hidden" name="sample_image_shown" value="0">
                                <input class="toggle-input" type="checkbox" name="sample_image_shown" value="1"
                                    {{ old('sample_image_shown', $part->sample_image_shown ?? false) ? 'checked' : '' }}>
                                <span class="toggle-track"></span>
                                <span class="toggle-label">Sample Image Shown</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-sidebar-section">
                        <button type="submit" class="btn btn--primary w-full" style="justify-content:center;">
                            <i class="fa-solid fa-floppy-disk"></i>
                            {{ isset($part) ? 'Save Changes' : 'Create Part' }}
                        </button>
                    </div>
                </div>

                {{-- Classification --}}
                <div class="form-sidebar-card">
                    <div class="form-sidebar-section">
                        <div class="form-sidebar-title">Classification</div>
                        <div class="form-group" style="margin-bottom:12px;">
                            <label class="form-label" for="condition_type">Condition <span
                                    class="required">*</span></label>
                            <select id="condition_type" name="condition_type" class="form-control" required>
                                <option value="new"
                                    {{ old('condition_type', $part->condition_type ?? '') === 'new' ? 'selected' : '' }}>
                                    New</option>
                                <option value="used"
                                    {{ old('condition_type', $part->condition_type ?? '') === 'used' ? 'selected' : '' }}>
                                    Used</option>
                                <option value="rebuilt"
                                    {{ old('condition_type', $part->condition_type ?? '') === 'rebuilt' ? 'selected' : '' }}>
                                    Rebuilt</option>
                                <option value="salvage"
                                    {{ old('condition_type', $part->condition_type ?? '') === 'salvage' ? 'selected' : '' }}>
                                    Salvage</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom:12px;">
                            <label class="form-label" for="make_id">Make / Brand</label>
                            <select id="make_id" name="make_id" class="form-control">
                                <option value="">— Select Make —</option>
                                @foreach ($makes as $make)
                                    <option value="{{ $make->id }}"
                                        {{ old('make_id', $part->make_id ?? '') == $make->id ? 'selected' : '' }}>
                                        {{ $make->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="equipment_type_id">Equipment Type</label>
                            <select id="equipment_type_id" name="equipment_type_id" class="form-control">
                                <option value="">— Select Type —</option>
                                @foreach ($equipmentTypes as $type)
                                    <option value="{{ $type->id }}"
                                        {{ old('equipment_type_id', $part->equipment_type_id ?? '') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-sidebar-section">
                        <div class="form-sidebar-title">Categories</div>
                        <div style="display:flex;flex-direction:column;gap:6px;max-height:200px;overflow-y:auto;">
                            @foreach ($categories as $cat)
                                <label class="form-check">
                                    <input type="checkbox" name="category_ids[]" value="{{ $cat->id }}"
                                        {{ in_array($cat->id, old('category_ids', isset($part) ? $part->categories->pluck('id')->toArray() : [])) ? 'checked' : '' }}>
                                    {{ $cat->name }}
                                </label>
                            @endforeach
                        </div>
                        <div class="form-hint" style="margin-top:6px;">First selected = primary category</div>
                    </div>
                </div>

                {{-- Availability (internal only — no prices shown on frontend) --}}
                <div class="form-sidebar-card">
                    <div class="form-sidebar-section">
                        <div class="form-sidebar-title">Availability</div>
                        <p class="form-hint" style="margin-bottom:12px;">
                            Prices are never shown publicly. Customers request a quote.
                        </p>
                        <div class="form-group" style="margin-bottom:12px;">
                            <label class="form-label" for="stock_quantity">Stock Qty <span
                                    style="font-weight:400;color:var(--text-faint);">(internal)</span></label>
                            <input type="number" id="stock_quantity" name="stock_quantity" class="form-control"
                                min="-1" value="{{ old('stock_quantity', $part->stock_quantity ?? '') }}"
                                placeholder="-1 = unlimited">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="stock_status">Availability Status</label>
                            <select id="stock_status" name="stock_status" class="form-control">
                                <option value="in_stock"
                                    {{ old('stock_status', $part->stock_status ?? 'in_stock') === 'in_stock' ? 'selected' : '' }}>
                                    In Stock</option>
                                <option value="out_of_stock"
                                    {{ old('stock_status', $part->stock_status ?? '') === 'out_of_stock' ? 'selected' : '' }}>
                                    Out of Stock</option>
                                <option value="on_order"
                                    {{ old('stock_status', $part->stock_status ?? '') === 'on_order' ? 'selected' : '' }}>
                                    On Order</option>
                                <option value="call_for_availability"
                                    {{ old('stock_status', $part->stock_status ?? '') === 'call_for_availability' ? 'selected' : '' }}>
                                    Call for Availability</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Primary Image --}}
                <div class="form-sidebar-card">
                    <div class="form-sidebar-section">
                        <div class="form-sidebar-title">Primary Image</div>

                        @if (isset($part) && $part->primaryImage)
                            <div class="image-preview" id="currentImagePreview">
                                <img src="{{ $part->primaryImage->public_url }}" alt="{{ $part->name }}">
                                <button type="button" class="image-preview-remove" data-clear-input="image"
                                    data-clear-flag="remove_image">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                            <p class="form-hint" style="margin-top:8px;">Upload new image to replace</p>
                            <input type="hidden" name="remove_image" id="remove_image" value="0">
                        @endif

                        <div class="image-upload-area" data-image-upload="main"
                            style="{{ isset($part) && $part->primaryImage ? 'margin-top:12px;padding:16px;' : '' }}">
                            <input type="file" id="image" name="image"
                                accept="image/jpeg,image/png,image/webp">
                            <div class="upload-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                            <div class="upload-text">Click or drag image here</div>
                            <div class="upload-hint">JPG, PNG, WebP — max 5MB</div>
                        </div>
                        @error('image')
                            <span class="form-error" style="margin-top:6px;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

            </div>{{-- end right column --}}

        </div>

    </form>

@endsection

@push('styles')
    <style>
        .model-check-label:has(input:checked) {
            background: var(--primary-pale);
            border-color: var(--primary);
            color: var(--primary);
        }
    </style>
@endpush
