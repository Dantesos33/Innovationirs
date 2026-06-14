@extends('layouts.admin')

@section('title', 'Add New Part')

@section('content')
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Add New Part</h1>
            <p class="admin-page-subtitle">Create a new parts listing</p>
        </div>
        <a href="{{ route('admin.parts.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Parts
        </a>
    </div>

    <form action="{{ route('admin.parts.store') }}" method="POST" enctype="multipart/form-data" id="partForm">
        @csrf

        <div class="row g-4">

            {{-- ── LEFT COLUMN (main fields) ─────────────────────────────────── --}}
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
                                value="{{ old('name') }}" placeholder="e.g. Hydraulic Pump Assembly" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Part Number</label>
                                <input type="text" name="part_number"
                                    class="form-control @error('part_number') is-invalid @enderror"
                                    value="{{ old('part_number') }}" placeholder="e.g. 1234567">
                                @error('part_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">OEM Part Number</label>
                                <input type="text" name="oem_part_number"
                                    class="form-control @error('oem_part_number') is-invalid @enderror"
                                    value="{{ old('oem_part_number') }}" placeholder="e.g. CAT-1234567">
                                @error('oem_part_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">SKU</label>
                                <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror"
                                    value="{{ old('sku') }}" placeholder="Internal SKU">
                                @error('sku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label fw-semibold">Short Description</label>
                            <input type="text" name="short_description"
                                class="form-control @error('short_description') is-invalid @enderror"
                                value="{{ old('short_description') }}"
                                placeholder="Brief one-line description (shown in listings)" maxlength="500">
                            <div class="form-text">Max 500 characters. Displayed in search results and listings.</div>
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-3">
                            <label class="form-label fw-semibold">Full Description</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                rows="8" placeholder="Detailed product description...">{{ old('description') }}</textarea>
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
                                            {{ old('make_id') == $make->id ? 'selected' : '' }}>
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
                                            {{ old('equipment_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('equipment_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Compatible Models</label>
                            <div class="model-select-wrapper border rounded p-3"
                                style="max-height: 200px; overflow-y: auto;">
                                @foreach ($models as $model)
                                    <div class="form-check">
                                        <input class="form-check-input model-checkbox" type="checkbox" name="model_ids[]"
                                            value="{{ $model->id }}" id="model_{{ $model->id }}"
                                            data-make="{{ $model->make_id }}"
                                            {{ in_array($model->id, old('model_ids', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="model_{{ $model->id }}">
                                            {{ $model->make->name ?? '' }} {{ $model->name }}
                                            @if ($model->year_start)
                                                ({{ $model->year_start }}{{ $model->year_end ? '–' . $model->year_end : '+' }})
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-text">Select all equipment models this part fits.</div>
                        </div>

                        <div>
                            <label class="form-label fw-semibold">Compatibility Notes</label>
                            <textarea name="compatibility_notes" class="form-control @error('compatibility_notes') is-invalid @enderror"
                                rows="3" placeholder="Additional fitment notes, serial number ranges, etc.">{{ old('compatibility_notes') }}</textarea>
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
                                    <input type="number" name="price" step="0.01" min="0"
                                        class="form-control @error('price') is-invalid @enderror"
                                        value="{{ old('price') }}" placeholder="0.00">
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Sale Price ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="sale_price" step="0.01" min="0"
                                        class="form-control @error('sale_price') is-invalid @enderror"
                                        value="{{ old('sale_price') }}" placeholder="Leave blank if no sale">
                                    @error('sale_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">Must be less than regular price.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Stock Quantity</label>
                                <input type="number" name="stock_quantity" min="-1"
                                    class="form-control @error('stock_quantity') is-invalid @enderror"
                                    value="{{ old('stock_quantity', 0) }}" placeholder="0">
                                <div class="form-text">Use -1 for unlimited.</div>
                                @error('stock_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Stock Status <span
                                        class="text-danger">*</span></label>
                                <select name="stock_status"
                                    class="form-select @error('stock_status') is-invalid @enderror" required>
                                    <option value="in_stock" {{ old('stock_status') == 'in_stock' ? 'selected' : '' }}>In
                                        Stock</option>
                                    <option value="out_of_stock"
                                        {{ old('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                                    <option value="on_order" {{ old('stock_status') == 'on_order' ? 'selected' : '' }}>On
                                        Order</option>
                                    <option value="call_for_availability"
                                        {{ old('stock_status') == 'call_for_availability' ? 'selected' : '' }}>Call for
                                        Availability</option>
                                </select>
                                @error('stock_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Condition Type <span
                                        class="text-danger">*</span></label>
                                <select name="condition_type"
                                    class="form-select @error('condition_type') is-invalid @enderror" required>
                                    <option value="new" {{ old('condition_type', 'new') == 'new' ? 'selected' : '' }}>
                                        New</option>
                                    <option value="used" {{ old('condition_type') == 'used' ? 'selected' : '' }}>Used
                                    </option>
                                    <option value="rebuilt" {{ old('condition_type') == 'rebuilt' ? 'selected' : '' }}>
                                        Rebuilt</option>
                                    <option value="salvage" {{ old('condition_type') == 'salvage' ? 'selected' : '' }}>
                                        Salvage</option>
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
                                <label class="form-label fw-semibold">Warranty Type <span
                                        class="text-danger">*</span></label>
                                <select name="warranty_type"
                                    class="form-select @error('warranty_type') is-invalid @enderror" required>
                                    <option value="none" {{ old('warranty_type', 'none') == 'none' ? 'selected' : '' }}>
                                        No Warranty</option>
                                    <option value="30_days" {{ old('warranty_type') == '30_days' ? 'selected' : '' }}>30
                                        Days</option>
                                    <option value="90_days" {{ old('warranty_type') == '90_days' ? 'selected' : '' }}>90
                                        Days</option>
                                    <option value="6_months" {{ old('warranty_type') == '6_months' ? 'selected' : '' }}>6
                                        Months</option>
                                    <option value="1_year" {{ old('warranty_type') == '1_year' ? 'selected' : '' }}>1
                                        Year</option>
                                    <option value="2_years" {{ old('warranty_type') == '2_years' ? 'selected' : '' }}>2
                                        Years</option>
                                    <option value="3_years" {{ old('warranty_type') == '3_years' ? 'selected' : '' }}>3
                                        Years</option>
                                    <option value="custom" {{ old('warranty_type') == 'custom' ? 'selected' : '' }}>
                                        Custom</option>
                                </select>
                                @error('warranty_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Warranty Notes</label>
                                <input type="text" name="warranty_notes"
                                    class="form-control @error('warranty_notes') is-invalid @enderror"
                                    value="{{ old('warranty_notes') }}" placeholder="Custom warranty details">
                                @error('warranty_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Weight (lbs)</label>
                                <input type="number" name="weight_lbs" step="0.01" min="0"
                                    class="form-control @error('weight_lbs') is-invalid @enderror"
                                    value="{{ old('weight_lbs') }}" placeholder="0.00">
                                @error('weight_lbs')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Dimensions</label>
                                <input type="text" name="dimensions"
                                    class="form-control @error('dimensions') is-invalid @enderror"
                                    value="{{ old('dimensions') }}" placeholder='e.g. 12" x 8" x 6"'>
                                @error('dimensions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="ships_worldwide"
                                        value="1" id="shipsWorldwide" {{ old('ships_worldwide') ? 'checked' : '' }}>
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
                            <input type="text" name="meta_title"
                                class="form-control @error('meta_title') is-invalid @enderror"
                                value="{{ old('meta_title') }}" placeholder="Leave blank to auto-generate"
                                maxlength="255">
                            @error('meta_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label fw-semibold">Meta Description</label>
                            <textarea name="meta_description" class="form-control @error('meta_description') is-invalid @enderror"
                                rows="2" placeholder="Leave blank to use short description" maxlength="500">{{ old('meta_description') }}</textarea>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

            </div>{{-- /col-lg-8 --}}

            {{-- ── RIGHT COLUMN (image, categories, status) ───────────────────── --}}
            <div class="col-lg-4">

                {{-- Status --}}
                <div class="admin-card mb-4">
                    <div class="admin-card-header">
                        <h5 class="admin-card-title"><i class="fas fa-toggle-on me-2"></i>Status</h5>
                    </div>
                    <div class="admin-card-body">
                        <label class="form-label fw-semibold">Listing Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active
                                (visible)</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive (hidden)
                            </option>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="mt-3">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="is_featured" value="1"
                                    id="isFeatured" {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label" for="isFeatured">Featured Part</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="california_prop65" value="1"
                                    id="prop65" {{ old('california_prop65') ? 'checked' : '' }}>
                                <label class="form-check-label" for="prop65">California Prop 65 Warning</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="sample_image_shown" value="1"
                                    id="sampleImage" {{ old('sample_image_shown') ? 'checked' : '' }}>
                                <label class="form-check-label" for="sampleImage">Sample/Stock Image Used</label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Primary Image --}}
                <div class="admin-card mb-4">
                    <div class="admin-card-header">
                        <h5 class="admin-card-title"><i class="fas fa-image me-2"></i>Primary Image</h5>
                    </div>
                    <div class="admin-card-body">
                        <div id="imagePreview" class="mb-3 text-center" style="display:none;">
                            <img id="previewImg" src="" alt="Preview" class="img-fluid rounded"
                                style="max-height: 200px;">
                        </div>
                        <div class="upload-dropzone" id="imageDropzone">
                            <i class="fas fa-cloud-upload-alt fa-2x mb-2 text-muted"></i>
                            <p class="mb-1 text-muted small">Click to select or drag & drop</p>
                            <p class="text-muted" style="font-size:11px;">JPG, PNG, WebP — max 5MB</p>
                        </div>
                        <input type="file" name="image" id="imageInput"
                            class="d-none @error('image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
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
                        <div class="form-text mb-2">First selected category will be the primary.</div>
                        @foreach ($categories as $category)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="category_ids[]"
                                    value="{{ $category->id }}" id="cat_{{ $category->id }}"
                                    {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}>
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
                        <textarea name="condition_notes" class="form-control @error('condition_notes') is-invalid @enderror" rows="3"
                            placeholder="Notes about condition (used/rebuilt parts)">{{ old('condition_notes') }}</textarea>
                        @error('condition_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

            </div>{{-- /col-lg-4 --}}
        </div>{{-- /row --}}

        {{-- Submit bar --}}
        <div class="admin-submit-bar">
            <button type="submit" class="btn btn-primary btn-lg px-5">
                <i class="fas fa-save me-2"></i>Save Part
            </button>
            <a href="{{ route('admin.parts.index') }}" class="btn btn-secondary btn-lg ms-2">Cancel</a>
        </div>

    </form>
@endsection

@push('scripts')
    <script>
        // Image preview
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

        // Filter models by selected make
        const makeSelect = document.getElementById('makeSelect');
        const modelCheckboxes = document.querySelectorAll('.model-checkbox');

        makeSelect.addEventListener('change', function() {
            const makeId = this.value;
            modelCheckboxes.forEach(cb => {
                const row = cb.closest('.form-check');
                if (!makeId || cb.dataset.make === makeId) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
@endpush
