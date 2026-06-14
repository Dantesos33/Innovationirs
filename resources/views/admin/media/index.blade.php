@extends('layouts.admin')
@section('title', 'Media Library')

@section('breadcrumb')
    <span class="breadcrumb-current">Media Library</span>
@endsection

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Media Library</h1>
            <p class="page-subtitle">{{ number_format($files->total()) }} files stored</p>
        </div>
        <div class="page-actions">
            <button type="button" class="btn btn--primary" id="uploadBtn"
                onclick="document.getElementById('mediaUploadInput').click()">
                <i class="fa-solid fa-cloud-arrow-up"></i> Upload Files
            </button>
        </div>
    </div>

    {{-- Upload Drop Zone --}}
    <div class="card" style="margin-bottom:20px;">
        <div id="dropZone" class="image-upload-area" style="border-radius:var(--radius-lg);margin:0;position:relative;">
            <form id="uploadForm" action="{{ route('admin.media.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" id="mediaUploadInput" name="files[]" multiple accept="image/*"
                    style="position:absolute;inset:0;opacity:0;width:100%;height:100%;cursor:pointer;">
            </form>
            <div class="upload-icon"><i class="fa-solid fa-images"></i></div>
            <div class="upload-text" style="font-size:15px;">Drop images here or click to upload</div>
            <div class="upload-hint">JPG, PNG, WebP, GIF — max 5MB each. Multiple files supported.</div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card">
        <form method="GET" action="{{ route('admin.media.index') }}">
            <div class="filters-bar">
                <div class="filter-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search filename, alt text…">
                </div>
                <select name="directory" class="filter-select">
                    <option value="">All Folders</option>
                    @foreach (['parts', 'makes', 'categories', 'equipment-types', 'blog', 'gallery'] as $dir)
                        <option value="{{ $dir }}" {{ request('directory') === $dir ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('-', ' ', $dir)) }}
                        </option>
                    @endforeach
                </select>
                <div class="filter-actions">
                    @if (request()->hasAny(['search', 'directory']))
                        <a href="{{ route('admin.media.index') }}" class="btn btn--ghost btn--sm">Clear</a>
                    @endif
                    <button type="submit" class="btn btn--secondary btn--sm">Filter</button>
                </div>
            </div>
        </form>

        {{-- Grid View --}}
        <div style="padding:20px;">
            @if ($files->count())
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:14px;">
                    @foreach ($files as $file)
                        <div class="media-card"
                            style="border:1px solid var(--card-border);border-radius:var(--radius-lg);overflow:hidden;cursor:pointer;transition:box-shadow var(--transition);">
                            {{-- Thumbnail --}}
                            <div style="aspect-ratio:1;background:var(--gray-100);overflow:hidden;position:relative;">
                                @if ($file->is_image)
                                    <img src="{{ $file->public_url }}" alt="{{ $file->alt_text }}"
                                        style="width:100%;height:100%;object-fit:cover;">
                                @else
                                    <div
                                        style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:32px;color:var(--gray-400);">
                                        <i class="fa-solid fa-file"></i>
                                    </div>
                                @endif

                                {{-- Hover overlay --}}
                                <div class="media-overlay"
                                    style="position:absolute;inset:0;background:rgba(0,0,0,0.5);display:none;align-items:center;justify-content:center;gap:8px;">
                                    <a href="{{ $file->public_url }}" target="_blank" class="action-btn"
                                        style="background:white;color:var(--text-base);" title="View">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </a>
                                    <button class="action-btn action-btn--delete" style="background:var(--error-pale);"
                                        data-delete-url="{{ route('admin.media.destroy', $file) }}"
                                        data-delete-label="{{ $file->original_name }}" title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Info --}}
                            <div style="padding:10px;">
                                <div
                                    style="font-size:11px;font-weight:500;color:var(--text-base);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    {{ $file->original_name }}
                                </div>
                                <div
                                    style="font-size:10px;color:var(--text-muted);margin-top:2px;display:flex;justify-content:space-between;">
                                    <span>{{ $file->file_size_formatted }}</span>
                                    @if ($file->width)
                                        <span>{{ $file->width }}×{{ $file->height }}</span>
                                    @endif
                                </div>
                                <div style="font-size:10px;color:var(--text-muted);margin-top:2px;">
                                    {{ ucfirst($file->directory) }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if ($files->hasPages())
                    <div style="margin-top:20px;">
                        <div class="pagination-wrap" style="padding:0;">
                            <span>Showing {{ $files->firstItem() }}–{{ $files->lastItem() }} of
                                {{ $files->total() }}</span>
                            {{ $files->withQueryString()->links('vendor.pagination.simple-admin') }}
                        </div>
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <div class="empty-state-icon"><i class="fa-solid fa-images"></i></div>
                    <div class="empty-state-title">No media files yet</div>
                    <div class="empty-state-text">Upload images to get started</div>
                </div>
            @endif
        </div>

    </div>

@endsection

@push('styles')
    <style>
        .media-card:hover {
            box-shadow: var(--card-shadow-hover);
        }

        .media-card:hover .media-overlay {
            display: flex !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Auto-submit on file select
        document.getElementById('mediaUploadInput')?.addEventListener('change', function() {
            if (this.files.length > 0) {
                document.getElementById('uploadForm').submit();
            }
        });

        // Drag and drop on drop zone
        const dropZone = document.getElementById('dropZone');
        if (dropZone) {
            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('dragover');
            });
            dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('dragover');
                const input = document.getElementById('mediaUploadInput');
                input.files = e.dataTransfer.files;
                input.dispatchEvent(new Event('change'));
            });
        }
    </script>
@endpush
