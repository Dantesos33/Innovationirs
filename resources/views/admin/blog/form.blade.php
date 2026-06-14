@extends('layouts.admin')
@section('title', isset($post) ? 'Edit Post' : 'New Blog Post')

@section('breadcrumb')
    <a href="{{ route('admin.blog.index') }}">Blog</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">{{ isset($post) ? 'Edit Post' : 'New Post' }}</span>
@endsection

@section('content')

    <form action="{{ isset($post) ? route('admin.blog.update', $post) : route('admin.blog.store') }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @if (isset($post))
            @method('PUT')
        @endif

        {{-- Global validation errors --}}
        @if ($errors->any())
            <div class="alert alert--error" style="margin-bottom:20px;">
                <i class="fa-solid fa-circle-exclamation"></i>
                <ul style="margin:0;padding-left:16px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="page-header">
            <div>
                <h1 class="page-title">{{ isset($post) ? 'Edit Post' : 'New Blog Post' }}</h1>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.blog.index') }}" class="btn btn--ghost">Cancel</a>
                <button type="submit" name="status_action" value="draft" class="btn btn--secondary">Save Draft</button>
                <button type="submit" name="status_action" value="publish" class="btn btn--primary">
                    <i class="fa-solid fa-globe"></i> Publish
                </button>
            </div>
        </div>

        <div class="form-layout form-layout--wide">

            {{-- Main --}}
            <div style="display:flex;flex-direction:column;gap:20px;">

                <div class="card">
                    <div class="card-body" style="display:flex;flex-direction:column;gap:16px;">

                        <div class="form-group">
                            <label class="form-label" for="title">Title <span class="required">*</span></label>
                            <input type="text" id="slugSource" name="title"
                                class="form-control {{ $errors->has('title') ? 'form-control--error' : '' }}"
                                value="{{ old('title', $post->title ?? '') }}" placeholder="Post title…" required
                                style="font-size:18px;font-weight:600;">
                            @error('title')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="slug">URL Slug</label>
                            <input type="text" id="slug" name="slug" class="form-control"
                                value="{{ old('slug', $post->slug ?? '') }}" placeholder="auto-generated">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="excerpt">Excerpt</label>
                            <textarea name="excerpt" id="excerpt" class="form-control" rows="2"
                                placeholder="Short summary shown in listings…">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- Content Editor --}}
                <div class="card">
                    <div class="card-header"><span class="card-title">Content</span></div>
                    <div class="rich-editor-wrap" data-rich-editor="content_input"
                        style="border:none;border-radius:0 0 var(--radius-lg) var(--radius-lg);">
                        <div class="rich-editor-toolbar">
                            <button data-cmd="bold" title="Bold"><i class="fa-solid fa-bold"></i></button>
                            <button data-cmd="italic" title="Italic"><i class="fa-solid fa-italic"></i></button>
                            <button data-cmd="underline" title="Underline"><i class="fa-solid fa-underline"></i></button>
                            <span style="width:1px;background:var(--card-border);margin:0 4px;align-self:stretch;"></span>
                            <button data-cmd="formatBlock" data-arg="h2"><i class="fa-solid fa-heading"></i> H2</button>
                            <button data-cmd="formatBlock" data-arg="h3"><i class="fa-solid fa-heading"></i> H3</button>
                            <span style="width:1px;background:var(--card-border);margin:0 4px;align-self:stretch;"></span>
                            <button data-cmd="insertUnorderedList" title="Bullet list"><i
                                    class="fa-solid fa-list-ul"></i></button>
                            <button data-cmd="insertOrderedList" title="Numbered list"><i
                                    class="fa-solid fa-list-ol"></i></button>
                            <button data-cmd="insertHorizontalRule" title="Divider"><i
                                    class="fa-solid fa-minus"></i></button>
                            <span style="width:1px;background:var(--card-border);margin:0 4px;align-self:stretch;"></span>
                            <button data-cmd="createLink" title="Insert Link"
                                onclick="event.preventDefault();const u=prompt('URL:');if(u)document.execCommand('createLink',false,u)"><i
                                    class="fa-solid fa-link"></i></button>
                            <button data-cmd="removeFormat"><i class="fa-solid fa-text-slash"></i></button>
                        </div>
                        <div class="rich-editor-content" contenteditable="true" style="min-height:360px;">
                            {{ old('content', $post->content ?? '') }}</div>
                    </div>{{-- end rich-editor-wrap --}}
                    @error('content')
                        <span class="form-error" style="padding:4px 12px 8px;">{{ $message }}</span>
                    @enderror
                    <input type="hidden" name="content" id="content_input"
                        value="{{ old('content', $post->content ?? '') }}">
                </div>

                {{-- SEO --}}
                <div class="card">
                    <div class="card-header"><span class="card-title">SEO</span></div>
                    <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">
                        <div class="form-group">
                            <label class="form-label">Meta Title</label>
                            <input type="text" name="meta_title" class="form-control" maxlength="255"
                                value="{{ old('meta_title', $post->meta_title ?? '') }}"
                                placeholder="Defaults to post title">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Meta Description</label>
                            <textarea name="meta_description" class="form-control" rows="2" maxlength="500"
                                placeholder="Defaults to excerpt">{{ old('meta_description', $post->meta_description ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Sidebar --}}
            <div style="display:flex;flex-direction:column;gap:20px;">

                <div class="form-sidebar-card">
                    <div class="form-sidebar-section">
                        <div class="form-sidebar-title">Publish Settings</div>
                        <div class="form-group" style="margin-bottom:12px;">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control">
                                <option value="draft"
                                    {{ old('status', $post->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft
                                </option>
                                <option value="published"
                                    {{ old('status', $post->status ?? '') === 'published' ? 'selected' : '' }}>Published
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Publish Date</label>
                            <input type="datetime-local" name="published_at" class="form-control"
                                value="{{ old('published_at', isset($post->published_at) ? $post->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}">
                        </div>
                    </div>
                    <div class="form-sidebar-section">
                        <div style="display:flex;gap:10px;">
                            <button type="submit" name="status_action" value="draft" class="btn btn--secondary"
                                style="flex:1;justify-content:center;">
                                Save Draft
                            </button>
                            <button type="submit" name="status_action" value="publish" class="btn btn--primary"
                                style="flex:1;justify-content:center;">
                                Publish
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Category & Tags --}}
                <div class="form-sidebar-card">
                    <div class="form-sidebar-section">
                        <div class="form-sidebar-title">Category</div>
                        <select name="blog_category_id" class="form-control">
                            <option value="">— No Category —</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('blog_category_id', $post->blog_category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-sidebar-section">
                        <div class="form-sidebar-title">Tags</div>
                        <div style="display:flex;flex-wrap:wrap;gap:6px;">
                            @foreach ($tags as $tag)
                                <label
                                    style="display:flex;align-items:center;gap:4px;padding:4px 10px;border:1px solid var(--card-border);border-radius:20px;cursor:pointer;font-size:11px;">
                                    <input type="checkbox" name="tag_ids[]" value="{{ $tag->id }}"
                                        style="accent-color:var(--primary);"
                                        {{ in_array($tag->id, old('tag_ids', isset($post) ? $post->tags->pluck('id')->toArray() : [])) ? 'checked' : '' }}>
                                    {{ $tag->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Featured Image --}}
                <div class="form-sidebar-card">
                    <div class="form-sidebar-section">
                        <div class="form-sidebar-title">Featured Image</div>
                        @if (isset($post) && $post->featuredImage)
                            <div style="margin-bottom:10px;">
                                <img src="{{ $post->featuredImage->public_url }}" alt=""
                                    style="width:100%;border-radius:var(--radius);object-fit:cover;max-height:140px;">
                            </div>
                        @endif
                        <div class="image-upload-area" data-image-upload="featured">
                            <input type="file" name="featured_image" accept="image/*">
                            <div class="upload-icon"><i class="fa-solid fa-image"></i></div>
                            <div class="upload-text">
                                {{ isset($post) && $post->featuredImage ? 'Replace image' : 'Upload featured image' }}
                            </div>
                            <div class="upload-hint">JPG, PNG, WebP — max 5MB</div>
                        </div>
                    </div>
                </div>

                {{-- Author --}}
                <div class="form-sidebar-card">
                    <div class="form-sidebar-section">
                        <div class="form-sidebar-title">Author</div>
                        <select name="admin_id" class="form-control">
                            @foreach ($authors as $author)
                                <option value="{{ $author->id }}"
                                    {{ old('admin_id', $post->admin_id ?? auth('admin')->id()) == $author->id ? 'selected' : '' }}>
                                    {{ $author->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>

        </div>

    </form>
@endsection
