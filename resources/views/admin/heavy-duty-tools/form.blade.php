@extends('layouts.admin')

@section('title', isset($tool) ? 'Edit Tool' : 'Add Tool')

@section('breadcrumb')
    <a href="{{ route('admin.heavy-duty-tools.index') }}">Heavy Duty Tools</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">{{ isset($tool) ? 'Edit: ' . Str::limit($tool->name, 40) : 'Add New Tool' }}</span>
@endsection

@section('content')

    <form action="{{ isset($tool) ? route('admin.heavy-duty-tools.update', $tool) : route('admin.heavy-duty-tools.store') }}"
        method="POST" enctype="multipart/form-data" id="toolForm">
        @csrf
        @if (isset($tool))
            @method('PUT')
        @endif

        <div class="page-header">
            <div>
                <h1 class="page-title">{{ isset($tool) ? 'Edit Tool' : 'Add New Tool' }}</h1>
                @if (isset($tool))
                    <p class="page-subtitle">ID #{{ $tool->id }} &middot; {{ $tool->views }} views</p>
                @endif
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.heavy-duty-tools.index') }}" class="btn btn--ghost">Cancel</a>
                <button type="submit" class="btn btn--primary">
                    <i class="fa-solid fa-floppy-disk"></i>
                    {{ isset($tool) ? 'Save Changes' : 'Create Tool' }}
                </button>
            </div>
        </div>

        <div class="form-layout">

            {{-- ── LEFT COLUMN ──────────────────────────────────────── --}}
            <div style="display:flex;flex-direction:column;gap:20px;">

                {{-- Basic Info --}}
                <div class="card">
                    <div class="card-header"><span class="card-title">Basic Information</span></div>
                    <div class="card-body">
                        <div class="form-grid" style="gap:18px;">

                            <div class="form-group form-group--full">
                                <label class="form-label" for="name">Tool Name <span class="required">*</span></label>
                                <input type="text" id="slugSource" name="name"
                                    class="form-control {{ $errors->has('name') ? 'form-control--error' : '' }}"
                                    value="{{ old('name', $tool->name ?? '') }}"
                                    placeholder="e.g. Heavy Duty Hydraulic Torque Wrench Set" required>
                                @error('name')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="sku">SKU</label>
                                <input type="text" id="sku" name="sku"
                                    class="form-control {{ $errors->has('sku') ? 'form-control--error' : '' }}"
                                    value="{{ old('sku', $tool->sku ?? '') }}" placeholder="e.g. HDT-TRQ-001">
                                @error('sku')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="part_number">Part Number</label>
                                <input type="text" id="part_number" name="part_number" class="form-control"
                                    value="{{ old('part_number', $tool->part_number ?? '') }}"
                                    placeholder="e.g. TW-HD-001">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="brand">Brand</label>
                                <input type="text" id="brand" name="brand" class="form-control"
                                    value="{{ old('brand', $tool->brand ?? '') }}" placeholder="e.g. Snap-on, DeWalt">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="model_number">Model Number</label>
                                <input type="text" id="model_number" name="model_number" class="form-control"
                                    value="{{ old('model_number', $tool->model_number ?? '') }}"
                                    placeholder="Manufacturer model #">
                            </div>

                            <div class="form-group form-group--full">
                                <label class="form-label" for="slug">URL Slug</label>
                                <input type="text" id="slug" name="slug" class="form-control"
                                    value="{{ old('slug', $tool->slug ?? '') }}" placeholder="auto-generated from name">
                                <span class="form-hint">Leave blank to auto-generate</span>
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
                                placeholder="Brief summary shown in listings (max 1000 chars)" maxlength="1000">{{ old('short_description', $tool->short_description ?? '') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="description">Full Description</label>
                            <div class="rich-editor-wrap" data-rich-editor="description_input">
                                <div class="rich-editor-toolbar">
                                    <button type="button" data-cmd="bold" title="Bold"><i
                                            class="fa-solid fa-bold"></i></button>
                                    <button type="button" data-cmd="italic" title="Italic"><i
                                            class="fa-solid fa-italic"></i></button>
                                    <button type="button" data-cmd="underline" title="Underline"><i
                                            class="fa-solid fa-underline"></i></button>
                                    <button type="button" data-cmd="insertUnorderedList" title="Bullet List"><i
                                            class="fa-solid fa-list-ul"></i></button>
                                    <button type="button" data-cmd="insertOrderedList" title="Numbered List"><i
                                            class="fa-solid fa-list-ol"></i></button>
                                </div>
                                <div class="rich-editor-body" contenteditable="true" id="description_editor"
                                    data-placeholder="Full product description, features, use cases…"
                                    style="min-height:160px;">{!! old('description', $tool->description ?? '') !!}</div>
                            </div>
                            <input type="hidden" name="description" id="description_input"
                                value="{{ old('description', $tool->description ?? '') }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="specifications">Specifications</label>
                            <div class="rich-editor-wrap" data-rich-editor="specifications_input">
                                <div class="rich-editor-toolbar">
                                    <button type="button" data-cmd="bold"><i class="fa-solid fa-bold"></i></button>
                                    <button type="button" data-cmd="insertUnorderedList"><i
                                            class="fa-solid fa-list-ul"></i></button>
                                    <button type="button" data-cmd="insertOrderedList"><i
                                            class="fa-solid fa-list-ol"></i></button>
                                </div>
                                <div class="rich-editor-body" contenteditable="true" id="specifications_editor"
                                    data-placeholder="Technical specs: dimensions, capacity, torque range, drive size, materials…"
                                    style="min-height:120px;">{!! old('specifications', $tool->specifications ?? '') !!}</div>
                            </div>
                            <input type="hidden" name="specifications" id="specifications_input"
                                value="{{ old('specifications', $tool->specifications ?? '') }}">
                        </div>

                    </div>
                </div>

                {{-- Pricing --}}
                <div class="card">
                    <div class="card-header"><span class="card-title">Pricing</span></div>
                    <div class="card-body">
                        <div class="form-grid" style="gap:18px;">

                            <div class="form-group">
                                <label class="form-label" for="price">Regular Price ($) <span
                                        class="required">*</span></label>
                                <input type="number" id="price" name="price" step="0.01" min="0"
                                    class="form-control {{ $errors->has('price') ? 'form-control--error' : '' }}"
                                    value="{{ old('price', $tool->price ?? '') }}" placeholder="0.00" required>
                                @error('price')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="sale_price">Sale Price ($)</label>
                                <input type="number" id="sale_price" name="sale_price" step="0.01" min="0"
                                    class="form-control {{ $errors->has('sale_price') ? 'form-control--error' : '' }}"
                                    value="{{ old('sale_price', $tool->sale_price ?? '') }}"
                                    placeholder="Leave blank if not on sale">
                                @error('sale_price')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Inventory --}}
                <div class="card">
                    <div class="card-header"><span class="card-title">Inventory &amp; Shipping</span></div>
                    <div class="card-body">
                        <div class="form-grid" style="gap:18px;">

                            <div class="form-group">
                                <label class="form-label" for="stock_quantity">Stock Quantity</label>
                                <input type="number" id="stock_quantity" name="stock_quantity" min="0"
                                    class="form-control" value="{{ old('stock_quantity', $tool->stock_quantity ?? 0) }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="stock_status">Stock Status <span
                                        class="required">*</span></label>
                                <select id="stock_status" name="stock_status" class="form-control" required>
                                    <option value="in_stock"
                                        {{ old('stock_status', $tool->stock_status ?? 'in_stock') === 'in_stock' ? 'selected' : '' }}>
                                        In Stock</option>
                                    <option value="out_of_stock"
                                        {{ old('stock_status', $tool->stock_status ?? '') === 'out_of_stock' ? 'selected' : '' }}>
                                        Out of Stock</option>
                                    <option value="on_order"
                                        {{ old('stock_status', $tool->stock_status ?? '') === 'on_order' ? 'selected' : '' }}>
                                        On Order</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="weight_lbs">Weight (lbs)</label>
                                <input type="number" id="weight_lbs" name="weight_lbs" step="0.01" min="0"
                                    class="form-control" value="{{ old('weight_lbs', $tool->weight_lbs ?? '') }}"
                                    placeholder="0.00">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="dimensions">Dimensions</label>
                                <input type="text" id="dimensions" name="dimensions" class="form-control"
                                    value="{{ old('dimensions', $tool->dimensions ?? '') }}"
                                    placeholder="e.g. 12 × 6 × 4 in">
                            </div>

                            <div class="form-group form-group--full">
                                <label class="toggle-label">
                                    <input type="hidden" name="ships_worldwide" value="0">
                                    <input type="checkbox" name="ships_worldwide" value="1"
                                        {{ old('ships_worldwide', $tool->ships_worldwide ?? true) ? 'checked' : '' }}>
                                    <span class="toggle-switch"></span>
                                    Ships Worldwide
                                </label>
                            </div>

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
                                value="{{ old('meta_title', $tool->meta_title ?? '') }}"
                                placeholder="Defaults to tool name">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="meta_description">Meta Description</label>
                            <textarea id="meta_description" name="meta_description" class="form-control" rows="2" maxlength="500"
                                placeholder="Short SEO description (max 500 chars)">{{ old('meta_description', $tool->meta_description ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── RIGHT COLUMN ─────────────────────────────────────── --}}
            <div style="display:flex;flex-direction:column;gap:20px;">

                {{-- Publish --}}
                <div class="card">
                    <div class="card-header"><span class="card-title">Publish</span></div>
                    <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">

                        <div class="form-group">
                            <label class="form-label" for="status">Status</label>
                            <select id="status" name="status" class="form-control">
                                <option value="active"
                                    {{ old('status', $tool->status ?? 'active') === 'active' ? 'selected' : '' }}>Active
                                    (Public)</option>
                                <option value="inactive"
                                    {{ old('status', $tool->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive
                                    (Hidden)</option>
                                <option value="draft"
                                    {{ old('status', $tool->status ?? '') === 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="sort_order">Sort Order</label>
                            <input type="number" id="sort_order" name="sort_order" min="0" class="form-control"
                                value="{{ old('sort_order', $tool->sort_order ?? 0) }}">
                            <span class="form-hint">Lower number = appears first</span>
                        </div>

                        <div style="display:flex;flex-direction:column;gap:10px;padding-top:4px;">
                            <label class="toggle-label">
                                <input type="hidden" name="is_featured" value="0">
                                <input type="checkbox" name="is_featured" value="1"
                                    {{ old('is_featured', $tool->is_featured ?? false) ? 'checked' : '' }}>
                                <span class="toggle-switch"></span>
                                Featured (show on homepage)
                            </label>
                        </div>

                    </div>
                </div>

                {{-- Primary Image --}}
                <div class="card">
                    <div class="card-header"><span class="card-title">Primary Image</span></div>
                    <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">

                        {{-- Current primary image preview --}}
                        <div id="primaryImagePreview">
                            @if (isset($tool) && $tool->primaryImage)
                                <img src="{{ $tool->primaryImage->public_url }}" alt="{{ $tool->name }}"
                                    style="width:100%;border-radius:8px;object-fit:cover;max-height:220px;">
                            @else
                                <div class="image-placeholder">
                                    <i class="fa-solid fa-hammer"></i>
                                    <span>No image yet</span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label class="form-label">Upload New Image</label>
                            <input type="file" name="primary_image" id="primaryImageFile" accept="image/*"
                                class="form-control">
                            <span class="form-hint">JPG, PNG, WebP — max 5MB</span>
                        </div>

                        <input type="hidden" name="primary_image_id" id="primaryImageId"
                            value="{{ old('primary_image_id', $tool->primary_image_id ?? '') }}">

                        {{-- Media library picker button --}}
                        <button type="button" class="btn btn--secondary btn--sm" onclick="openMediaPicker('primary')">
                            <i class="fa-solid fa-photo-film"></i> Choose from Media Library
                        </button>

                    </div>
                </div>

                {{-- Gallery Images --}}
                <div class="card">
                    <div class="card-header"><span class="card-title">Gallery Images</span></div>
                    <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">

                        {{-- Existing gallery images --}}
                        @if (isset($tool) && $tool->images->isNotEmpty())
                            <div class="gallery-grid" id="galleryGrid">
                                @foreach ($tool->images as $img)
                                    <div class="gallery-item" data-id="{{ $img->id }}">
                                        <img src="{{ $img->public_url }}" alt="">
                                        <button type="button" class="gallery-remove"
                                            data-url="{{ route('admin.heavy-duty-tools.remove-image', $tool) }}"
                                            data-image-id="{{ $img->id }}">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted" id="galleryEmpty">No gallery images yet.</p>
                        @endif

                        <div class="form-group">
                            <label class="form-label">Upload Gallery Images</label>
                            <input type="file" name="gallery_images[]" multiple accept="image/*"
                                class="form-control">
                            <span class="form-hint">Select multiple files — each max 5MB</span>
                        </div>

                        <input type="hidden" name="gallery_media_ids" id="galleryMediaIds" value="">
                        <button type="button" class="btn btn--secondary btn--sm" onclick="openMediaPicker('gallery')">
                            <i class="fa-solid fa-photo-film"></i> Add from Media Library
                        </button>

                    </div>
                </div>

            </div>

        </div>{{-- /form-layout --}}
    </form>

    {{-- Media Library Picker Modal --}}
    <div class="modal-overlay" id="mediaPickerOverlay" style="display:none;"
        onclick="if(event.target===this)closeMediaPicker()">
        <div class="modal" style="max-width:860px;width:92vw;max-height:80vh;display:flex;flex-direction:column;">
            <div class="modal-header">
                <h3 class="modal-title">Media Library</h3>
                <button type="button" class="modal-close" onclick="closeMediaPicker()">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body" style="overflow-y:auto;flex:1;">
                <iframe id="mediaPickerFrame" src="{{ route('admin.media.picker') }}"
                    style="width:100%;min-height:420px;border:0;"></iframe>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // ── Rich editor sync ──────────────────────────────────────────────────
        document.querySelectorAll('.rich-editor-wrap').forEach(wrap => {
            const targetId = wrap.dataset.richEditor;
            const editor = wrap.querySelector('.rich-editor-body');
            const hidden = document.getElementById(targetId);

            if (!editor || !hidden) return;

            wrap.querySelectorAll('[data-cmd]').forEach(btn => {
                btn.addEventListener('mousedown', e => {
                    e.preventDefault();
                    document.execCommand(btn.dataset.cmd, false, null);
                    editor.focus();
                });
            });

            editor.addEventListener('input', () => hidden.value = editor.innerHTML);
        });

        // ── Slug auto-gen ─────────────────────────────────────────────────────
        const slugSource = document.getElementById('slugSource');
        const slugField = document.getElementById('slug');
        let slugEdited = slugField?.value?.length > 0;

        slugSource?.addEventListener('input', () => {
            if (slugEdited) return;
            slugField.value = slugSource.value
                .toLowerCase().trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        });

        slugField?.addEventListener('input', () => {
            slugEdited = slugField.value.length > 0;
        });

        // ── Primary image file preview ────────────────────────────────────────
        document.getElementById('primaryImageFile')?.addEventListener('change', function() {
            if (!this.files[0]) return;
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('primaryImagePreview').innerHTML =
                    `<img src="${e.target.result}" style="width:100%;border-radius:8px;object-fit:cover;max-height:220px;">`;
            };
            reader.readAsDataURL(this.files[0]);
        });

        // ── Gallery remove ────────────────────────────────────────────────────
        document.querySelectorAll('.gallery-remove').forEach(btn => {
            btn.addEventListener('click', function() {
                if (!confirm('Remove this image?')) return;
                fetch(this.dataset.url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: new URLSearchParams({
                        image_id: this.dataset.imageId,
                        _token: '{{ csrf_token() }}'
                    }),
                }).then(r => r.json()).then(data => {
                    if (data.success) {
                        this.closest('.gallery-item').remove();
                    }
                });
            });
        });

        // ── Media Picker ──────────────────────────────────────────────────────
        let pickerMode = 'primary';

        function openMediaPicker(mode) {
            pickerMode = mode;
            document.getElementById('mediaPickerOverlay').style.display = 'flex';
        }

        function closeMediaPicker() {
            document.getElementById('mediaPickerOverlay').style.display = 'none';
        }

        // Message from media picker iframe
        window.addEventListener('message', function(e) {
            if (!e.data?.mediaId) return;
            closeMediaPicker();

            if (pickerMode === 'primary') {
                document.getElementById('primaryImageId').value = e.data.mediaId;
                document.getElementById('primaryImagePreview').innerHTML =
                    `<img src="${e.data.url}" style="width:100%;border-radius:8px;object-fit:cover;max-height:220px;">`;
            } else {
                const cur = document.getElementById('galleryMediaIds').value;
                const ids = cur ? cur.split(',') : [];
                if (!ids.includes(String(e.data.mediaId))) ids.push(e.data.mediaId);
                document.getElementById('galleryMediaIds').value = ids.join(',');

                // Show preview
                let grid = document.getElementById('galleryGrid');
                if (!grid) {
                    grid = document.createElement('div');
                    grid.id = 'galleryGrid';
                    grid.className = 'gallery-grid';
                    document.getElementById('galleryEmpty')?.remove();
                    document.querySelector('.gallery-grid')?.before(grid) || document.querySelector(
                        '[name="gallery_images[]"]')?.closest('.form-group')?.before(grid);
                }
                const item = document.createElement('div');
                item.className = 'gallery-item';
                item.innerHTML = `<img src="${e.data.url}" alt=""><span class="gallery-new-badge">New</span>`;
                grid.appendChild(item);
            }
        });
    </script>

    <style>
        .form-layout {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 24px;
            align-items: start;
        }

        @media (max-width: 1024px) {
            .form-layout {
                grid-template-columns: 1fr;
            }
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            gap: 8px;
        }

        .gallery-item {
            position: relative;
            border-radius: 6px;
            overflow: hidden;
            aspect-ratio: 1;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .gallery-remove {
            position: absolute;
            top: 3px;
            right: 3px;
            background: rgba(0, 0, 0, .65);
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .gallery-new-badge {
            position: absolute;
            bottom: 3px;
            left: 3px;
            background: var(--color-background-success);
            color: var(--color-text-success);
            font-size: 9px;
            padding: 1px 5px;
            border-radius: 3px;
        }

        .image-placeholder {
            background: var(--color-background-secondary);
            border: 2px dashed var(--color-border-secondary);
            border-radius: 8px;
            padding: 32px 16px;
            text-align: center;
            color: var(--color-text-tertiary);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .image-placeholder i {
            font-size: 2rem;
        }

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .55);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal {
            background: var(--color-background-primary);
            border-radius: 12px;
            overflow: hidden;
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid var(--color-border-tertiary);
        }

        .modal-title {
            margin: 0;
            font-size: 16px;
            font-weight: 500;
        }

        .modal-close {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
            color: var(--color-text-secondary);
            padding: 4px;
        }

        .modal-body {
            padding: 0;
        }
    </style>
@endpush
