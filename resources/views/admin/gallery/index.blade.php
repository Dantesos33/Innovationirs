@extends('layouts.admin')
@section('title', 'Gallery Manager')

@section('breadcrumb')
    <span class="breadcrumb-current">Gallery</span>
@endsection

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Media Gallery</h1>
            <p class="page-subtitle">{{ number_format($total) }} images in public gallery</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('gallery') }}" target="_blank" class="btn btn--ghost">
                <i class="fa-solid fa-eye"></i> View Public Gallery
            </a>
            <button type="button" class="btn btn--primary" id="uploadBtn">
                <i class="fa-solid fa-cloud-arrow-up"></i> Upload Images
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert--success" style="margin-bottom:20px;">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Upload Zone --}}
    <div class="card" style="margin-bottom:24px;">
        <div class="card-header"><span class="card-title">Upload New Images</span></div>
        <div class="card-body">
            <form id="galleryUploadForm" action="{{ route('admin.gallery.upload') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="image-upload-area" id="dropZone" style="margin-bottom:16px;">
                    <input type="file" id="mediaUploadInput" name="files[]" multiple
                        accept="image/jpeg,image/png,image/webp,image/gif"
                        style="position:absolute;inset:0;opacity:0;width:100%;height:100%;cursor:pointer;">
                    <div class="upload-icon"><i class="fa-solid fa-images"></i></div>
                    <div class="upload-text">Drop images here or click to select</div>
                    <div class="upload-hint">JPG, PNG, WebP, GIF — max 8MB each. Multiple files supported.</div>
                </div>
                <div id="uploadPreview" style="display:none;margin-bottom:12px;">
                    <div id="previewGrid"
                        style="display:grid;grid-template-columns:repeat(auto-fill,minmax(80px,1fr));gap:8px;"></div>
                </div>
                <div style="display:flex;gap:12px;align-items:center;">
                    <input type="text" name="alt_text" class="form-control"
                        placeholder="Alt text for all uploaded images (optional)" style="max-width:400px;">
                    <button type="submit" class="btn btn--primary" id="submitUpload" style="display:none;">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Upload
                        <span id="uploadCount"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Search --}}
    <form method="GET" action="{{ route('admin.gallery.index') }}" style="margin-bottom:16px;">
        <div class="filters-bar">
            <div class="filter-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search by filename or alt text…">
            </div>
            <div class="filter-actions">
                @if (request('search'))
                    <a href="{{ route('admin.gallery.index') }}" class="btn btn--ghost btn--sm">Clear</a>
                @endif
                <button type="submit" class="btn btn--secondary btn--sm">Search</button>
            </div>
        </div>
    </form>

    {{-- Image Grid --}}
    @if ($images->count())
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px;margin-bottom:24px;">
            @foreach ($images as $image)
                <div class="card" style="overflow:hidden;" data-image-id="{{ $image->id }}">
                    {{-- Thumbnail --}}
                    <div style="aspect-ratio:4/3;overflow:hidden;background:var(--gray-100);cursor:pointer;"
                        onclick="openLightbox('{{ $image->public_url }}','{{ addslashes($image->alt_text ?? $image->original_name) }}')">
                        <img src="{{ $image->public_url }}" alt="{{ $image->alt_text }}"
                            style="width:100%;height:100%;object-fit:cover;transition:transform 0.2s;" loading="lazy"
                            onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='scale(1)'">
                    </div>

                    {{-- Info --}}
                    <div style="padding:10px 12px;">
                        <div style="font-size:11px;font-weight:600;color:var(--ink);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:4px;"
                            title="{{ $image->original_name }}">
                            {{ $image->original_name }}
                        </div>
                        <div style="font-size:10px;color:var(--text-faint);margin-bottom:8px;">
                            {{ $image->file_size_formatted }}
                            @if ($image->width)
                                · {{ $image->width }}×{{ $image->height }}px
                            @endif
                        </div>

                        {{-- Inline edit alt text --}}
                        <input type="text" value="{{ $image->alt_text }}" placeholder="Alt text…" class="form-control"
                            style="font-size:11px;padding:4px 8px;margin-bottom:8px;"
                            onchange="updateAltText({{ $image->id }}, this.value)">

                        <div style="display:flex;gap:6px;">
                            <button onclick="copyUrl('{{ $image->public_url }}')" class="btn btn--ghost btn--sm"
                                style="font-size:10px;flex:1;" title="Copy URL">
                                <i class="fa-solid fa-copy"></i> Copy URL
                            </button>
                            <button onclick="deleteImage({{ $image->id }}, this)" class="btn btn--danger btn--sm"
                                style="font-size:10px;" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($images->hasPages())
            <div class="pagination-wrap">
                {{ $images->onEachSide(2)->links('vendor.pagination.simple-admin') }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fa-solid fa-images"></i></div>
            <h3 class="empty-state-title">No Gallery Images Yet</h3>
            <p class="empty-state-text">Upload images above to build your public gallery.</p>
        </div>
    @endif

    {{-- Lightbox --}}
    <div id="lightbox"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.92);z-index:9999;
        align-items:center;justify-content:center;flex-direction:column;gap:16px;"
        onclick="closeLightbox()">
        <button
            style="position:absolute;top:20px;right:20px;background:none;border:none;color:white;font-size:28px;cursor:pointer;"
            onclick="closeLightbox()">×</button>
        <img id="lightboxImg" src="" alt=""
            style="max-width:90vw;max-height:80vh;object-fit:contain;border-radius:4px;">
        <div id="lightboxCaption" style="color:rgba(255,255,255,0.7);font-size:13px;"></div>
    </div>

@endsection

@push('scripts')
    <script>
        // Upload preview
        document.getElementById('mediaUploadInput').addEventListener('change', function() {
            const files = Array.from(this.files);
            if (!files.length) return;
            const preview = document.getElementById('uploadPreview');
            const grid = document.getElementById('previewGrid');
            const btn = document.getElementById('submitUpload');
            const count = document.getElementById('uploadCount');
            grid.innerHTML = '';
            files.forEach(f => {
                const reader = new FileReader();
                reader.onload = e => {
                    const div = document.createElement('div');
                    div.style.cssText =
                        'aspect-ratio:1;overflow:hidden;border-radius:4px;background:var(--gray-100);';
                    div.innerHTML =
                        `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
                    grid.appendChild(div);
                };
                reader.readAsDataURL(f);
            });
            preview.style.display = 'block';
            btn.style.display = 'inline-flex';
            count.textContent = ` (${files.length} file${files.length > 1 ? 's' : ''})`;
        });

        // Update alt text via AJAX
        function updateAltText(id, value) {
            fetch(`/admin/gallery/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-HTTP-Method-Override': 'PATCH'
                },
                body: JSON.stringify({
                    alt_text: value
                })
            });
        }

        // Delete image
        function deleteImage(id, btn) {
            if (!confirm('Delete this image from the gallery?')) return;
            fetch(`/admin/gallery/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-HTTP-Method-Override': 'DELETE'
                },
            }).then(r => r.json()).then(data => {
                if (data.success) btn.closest('[data-image-id]').remove();
            });
        }

        // Copy URL
        function copyUrl(url) {
            navigator.clipboard.writeText(url).then(() => {
                alert('URL copied to clipboard.');
            });
        }

        // Lightbox
        function openLightbox(url, caption) {
            document.getElementById('lightboxImg').src = url;
            document.getElementById('lightboxCaption').textContent = caption;
            document.getElementById('lightbox').style.display = 'flex';
        }

        function closeLightbox() {
            document.getElementById('lightbox').style.display = 'none';
        }
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeLightbox();
        });
    </script>
@endpush
