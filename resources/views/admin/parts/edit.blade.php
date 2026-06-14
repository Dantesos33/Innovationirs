@extends('layouts.admin')

@section('title', 'Edit Part: ' . $part->name)

@section('content')
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Edit Part</h1>
            <p class="admin-page-subtitle">{{ $part->name }} &mdash; #{{ $part->part_number }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ $part->url }}" target="_blank" class="btn btn-outline-secondary">
                <i class="fas fa-external-link-alt me-2"></i>View on Site
            </a>
            <a href="{{ route('admin.parts.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Parts
            </a>
        </div>
    </div>

    <form action="{{ route('admin.parts.update', $part) }}" method="POST" enctype="multipart/form-data" id="partForm">
        @csrf
        @method('PUT')

        <div class="row g-4">

            {{-- ── LEFT COLUMN ──────────────────────────────────────────────────── --}}
            <div class="col-lg-8">

                {{-- Basic Information --}}
                <div class="admin-card mb-4">
                    <div class="admin-card-header">
                        <h5 class="admin-card-title"><i class="fas fa-info-circle me-2"></i>Basic Information</h5>
                    </div>
                    <div class="admin-card-body">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Part Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $part->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Part Number</label>
                                <input type="text" name="part_number"
                                    class="form-control @error('part_number') is-invalid @enderror"
                                    value="{{ old('part_number', $part->part_number) }}">
                                @error('part_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">OEM Part Number</label>
                                <input type="text" name="oem_part_number"
                                    class="form-control @error('oem_part_number') is-invalid @enderror"
                                    value="{{ old('oem_part_number', $part->oem_part_number) }}">
                                @error('oem_part_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">SKU</label>
                                <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror"
                                    value="{{ old('sku', $part->sku) }}">
                                @error('sku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label fw-semibold">Short Description</label>
                            <input type="text" name="short_description"
                                class="form-control @error('short_description') is-invalid @enderror"
                                value="{{ old('short_description', $part->short_description) }}" maxlength="500">
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-3">
                            <label class="form-label fw-semibold">Full Description</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                rows="8">{{ old('description', $part->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Compatibility --}}
                <div class="admin-card mb-4">
                    <div class="admin-card-header">
                        <h5 class="admin-card-title"><i class="fas fa-cogs me-2"></i>Compatibility & Fitment</h5>
                    </div>
                    <div class="admin-card-body">

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Make</label>
                                <select name="make_id" class="form-select @error('make_id') is-invalid @enderror"
                                    id="makeSelect">
                                    <option value="">— All Makes / Universal —</option>
                                    @foreach ($makes as $make)
                                        <option value="{{ $make->id }}"
                                            {{ old('make_id', $part->make_id) == $make->id ? 'selected' : '' }}>
                                            {{ $make->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('make_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Equipment Type</label>
                                <select name="equipment_type_id"
                                    class="form-select @error('equipment_type_id') is-invalid @enderror">
                                    <option value="">— All Equipment Types —</option>
                                    @foreach ($equipmentTypes as $type)
                                        <option value="{{ $type->id }}"
                                            {{ old('equipment_type_id', $part->equipment_type_id) == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('equipment_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        @php $selectedModelIds = old('model_ids', $part->fitsModels->pluck('id')->toArray()); @endphp
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Compatible Models</label>
                            <div class="model-select-wrapper border rounded p-3"
                                style="max-height: 200px; overflow-y: auto;">
                                @foreach ($models as $model)
                                    <div class="form-check">
                                        <input class="form-check-input model-checkbox" type="checkbox" name="model_ids[]"
                                            value="{{ $model->id }}" id="model_{{ $model->id }}"
                                            data-make="{{ $model->make_id }}"
                                            {{ in_array($model->id, $selectedModelIds) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="model_{{ $model->id }}">
                                            {{ $model->make->name ?? '' }} {{ $model->name }}
                                            @if ($model->year_start)
                                                ({{ $model->year_start }}{{ $model->year_end ? '–' . $model->year_end : '+' }})
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="form-label fw-semibold">Compatibility Notes</label>
                            <textarea name="compatibility_notes" class="form-control @error('compatibility_notes') is-invalid @enderror"
                                rows="3">{{ old('compatibility_notes', $part->compatibility_notes) }}</textarea>
                            @error('compatibility_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Pricing & Stock --}}
                <div class="admin-card mb-4">
                    <div class="admin-card-header">
                        <h5 class="admin-card-title"><i class="fas fa-dollar-sign me-2"></i>Pricing & Stock</h5>
                    </div>
                    <div class="admin-card-body">

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Price ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    price) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Sale Price ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    sale_price) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Stock Quantity</label>
                                <input type="number" name="stock_quantity" min="-1"
                                    class="form-control @error('stock_quantity') is-invalid @enderror"
                                    value="{{ old('stock_quantity', $part->stock_quantity) }}">
                                @error('stock_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Stock Status</label>
                                <select name="stock_status"
                                    class="form-select @error('stock_status') is-invalid @enderror" required>
                                    @foreach (['in_stock' => 'In Stock', 'out_of_stock' => 'Out of Stock', 'on_order' => 'On Order', 'call_for_availability' => 'Call for Availability'] as $val => $label)
                                        <option value="{{ $val }}"
                                            {{ old('stock_status', $part->stock_status) == $val ? 'selected' : '' }}>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('stock_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Condition Type</label>
                                <select name="condition_type"
                                    class="form-select @error('condition_type') is-invalid @enderror" required>
                                    @foreach (['new' => 'New', 'used' => 'Used', 'rebuilt' => 'Rebuilt', 'salvage' => 'Salvage'] as $val => $label)
                                        <option value="{{ $val }}"
                                            {{ old('condition_type', $part->condition_type) == $val ? 'selected' : '' }}>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('condition_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Warranty & Shipping --}}
                <div class="admin-card mb-4">
                    <div class="admin-card-header">
                        <h5 class="admin-card-title"><i class="fas fa-shield-alt me-2"></i>Warranty & Shipping</h5>
                    </div>
                    <div class="admin-card-body">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Warranty Type</label>
                                <select name="warranty_type" class="form-select" required>
                                    @foreach (['none' => 'No Warranty', '30_days' => '30 Days', '90_days' => '90 Days', '6_months' => '6 Months', '1_year' => '1 Year', '2_years' => '2 Years', '3_years' => '3 Years', 'custom' => 'Custom'] as $val => $label)
                                        <option value="{{ $val }}"
                                            {{ old('warranty_type', $part->warranty_type) == $val ? 'selected' : '' }}>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Warranty Notes</label>
                                <input type="text" name="warranty_notes" class="form-control"
                                    value="{{ old('warranty_notes', $part->warranty_notes) }}">
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Weight (lbs)</label>
                                <input type="number" name="weight_lbs" step="0.01" min="0"
                                    class="form-control" value="{{ old('weight_lbs', $part->weight_lbs) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Dimensions</label>
                                <input type="text" name="dimensions" class="form-control"
                                    value="{{ old('dimensions', $part->dimensions) }}">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="ships_worldwide"
                                        value="1" id="shipsWorldwide"
                                        {{ old('ships_worldwide', $part->ships_worldwide) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="shipsWorldwide">Ships
                                        Worldwide</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SEO --}}
                <div class="admin-card mb-4">
                    <div class="admin-card-header">
                        <h5 class="admin-card-title"><i class="fas fa-search me-2"></i>SEO</h5>
                    </div>
                    <div class="admin-card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Meta Title</label>
                            <input type="text" name="meta_title" class="form-control"
                                value="{{ old('meta_title', $part->meta_title) }}" maxlength="255">
                        </div>
                        <div>
                            <label class="form-label fw-semibold">Meta Description</label>
                            <textarea name="meta_description" class="form-control" rows="2" maxlength="500">{{ old('meta_description', $part->meta_description) }}</textarea>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── RIGHT COLUMN ─────────────────────────────────────────────────── --}}
            <div class="col-lg-4">

                {{-- Status --}}
                <div class="admin-card mb-4">
                    <div class="admin-card-header">
                        <h5 class="admin-card-title"><i class="fas fa-toggle-on me-2"></i>Status</h5>
                    </div>
                    <div class="admin-card-body">
                        <label class="form-label fw-semibold">Listing Status</label>
                        <select name="status" class="form-select" required>
                            @foreach (['active' => 'Active (visible)', 'inactive' => 'Inactive (hidden)', 'draft' => 'Draft', 'archived' => 'Archived'] as $val => $label)
                                <option value="{{ $val }}"
                                    {{ old('status', $part->status) == $val ? 'selected' : '' }}>{{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <div class="mt-3">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="is_featured" value="1"
                                    id="isFeatured" {{ old('is_featured', $part->is_featured) ? 'checked' : '' }}>
                                <label class="form-check-label" for="isFeatured">Featured Part</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="california_prop65" value="1"
                                    id="prop65"
                                    {{ old('california_prop65', $part->california_prop65) ? 'checked' : '' }}>
                                <label class="form-check-label" for="prop65">California Prop 65 Warning</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="sample_image_shown" value="1"
                                    id="sampleImage"
                                    {{ old('sample_image_shown', $part->sample_image_shown) ? 'checked' : '' }}>
                                <label class="form-check-label" for="sampleImage">Sample/Stock Image Used</label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Current Image --}}
                <div class="admin-card mb-4">
                    <div class="admin-card-header">
                        <h5 class="admin-card-title"><i class="fas fa-image me-2"></i>Primary Image</h5>
                    </div>
                    <div class="admin-card-body">
                        @if ($part->primaryImage)
                            <div class="mb-3 text-center">
                                <img src="{{ $part->primaryImage->public_url }}" alt="{{ $part->name }}"
                                    class="img-fluid rounded" style="max-height: 180px;">
                                <p class="text-muted small mt-1">Current image — upload below to replace</p>
                            </div>
                        @endif
                        <div id="imagePreview" class="mb-3 text-center" style="display:none;">
                            <img id="previewImg" src="" alt="New Preview" class="img-fluid rounded"
                                style="max-height: 180px;">
                            <p class="text-muted small mt-1">New image (not saved yet)</p>
                        </div>
                        <div class="upload-dropzone" id="imageDropzone">
                            <i class="fas fa-cloud-upload-alt fa-2x mb-2 text-muted"></i>
                            <p class="mb-0 text-muted small">Click to select replacement image</p>
                        </div>
                        <input type="file" name="image" id="imageInput" class="d-none"
                            accept="image/jpeg,image/png,image/webp">
                        @error('image')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Categories --}}
                <div class="admin-card mb-4">
                    <div class="admin-card-header">
                        <h5 class="admin-card-title"><i class="fas fa-tags me-2"></i>Categories</h5>
                    </div>
                    <div class="admin-card-body">
                        @php $selectedCatIds = old('category_ids', $part->categories->pluck('id')->toArray()); @endphp
                        @foreach ($categories as $category)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="category_ids[]"
                                    value="{{ $category->id }}" id="cat_{{ $category->id }}"
                                    {{ in_array($category->id, $selectedCatIds) ? 'checked' : '' }}>
                                <label class="form-check-label" for="cat_{{ $category->id }}">
                                    {{ $category->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Condition Notes --}}
                <div class="admin-card mb-4">
                    <div class="admin-card-header">
                        <h5 class="admin-card-title"><i class="fas fa-clipboard me-2"></i>Condition Notes</h5>
                    </div>
                    <div class="admin-card-body">
                        <textarea name="condition_notes" class="form-control" rows="3">{{ old('condition_notes', $part->condition_notes) }}</textarea>
                    </div>
                </div>

                {{-- Meta Info --}}
                <div class="admin-card mb-4">
                    <div class="admin-card-header">
                        <h5 class="admin-card-title"><i class="fas fa-chart-bar me-2"></i>Statistics</h5>
                    </div>
                    <div class="admin-card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Views</span>
                            <strong>{{ number_format($part->views) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Created</span>
                            <strong>{{ $part->created_at->format('M d, Y') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Updated</span>
                            <strong>{{ $part->updated_at->format('M d, Y') }}</strong>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="admin-submit-bar">
            <button type="submit" class="btn btn-primary btn-lg px-5">
                <i class="fas fa-save me-2"></i>Update Part
            </button>
            <a href="{{ route('admin.parts.index') }}" class="btn btn-secondary btn-lg ms-2">Cancel</a>
            <button type="button" class="btn btn-danger btn-lg ms-auto"
                onclick="confirmDelete('{{ route('admin.parts.destroy', $part) }}', 'Delete this part?')">
                <i class="fas fa-trash me-2"></i>Delete
            </button>
        </div>

    </form>
@endsection

@push('scripts')
    <script>
        const imageInput = document.getElementById('imageInput');
        const imageDropzone = document.getElementById('imageDropzone');
        const imagePreview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');

        imageDropzone.addEventListener('click', () => imageInput.click());
        imageDropzone.addEventListener('dragover', e => {
            e.preventDefault();
            imageDropzone.classList.add('dragover');
        });
        imageDropzone.addEventListener('dragleave', () => imageDropzone.classList.remove('dragover'));
        imageDropzone.addEventListener('drop', e => {
            e.preventDefault();
            imageDropzone.classList.remove('dragover');
            if (e.dataTransfer.files[0]) {
                imageInput.files = e.dataTransfer.files;
                showPreview(e.dataTransfer.files[0]);
            }
        });
        imageInput.addEventListener('change', () => {
            if (imageInput.files[0]) showPreview(imageInput.files[0]);
        });

        function showPreview(file) {
            const reader = new FileReader();
            reader.onload = e => {
                previewImg.src = e.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    </script>
@endpush
