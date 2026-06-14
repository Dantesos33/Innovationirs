@extends('layouts.admin')
@section('title', isset($faq) ? 'Edit FAQ' : 'Add FAQ')

@section('breadcrumb')
    <a href="{{ route('admin.faqs.index') }}">FAQs</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">{{ isset($faq) ? 'Edit' : 'Add FAQ' }}</span>
@endsection

@section('content')

    <form action="{{ isset($faq) ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}" method="POST">
        @csrf
        @if (isset($faq))
            @method('PUT')
        @endif

        <div class="page-header">
            <div>
                <h1 class="page-title">{{ isset($faq) ? 'Edit FAQ' : 'Add New FAQ' }}</h1>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.faqs.index') }}" class="btn btn--ghost">Cancel</a>
                <button type="submit" class="btn btn--primary">
                    <i class="fa-solid fa-floppy-disk"></i> Save FAQ
                </button>
            </div>
        </div>

        <div class="form-layout">

            <div style="display:flex;flex-direction:column;gap:20px;">
                <div class="card">
                    <div class="card-header"><span class="card-title">FAQ Content</span></div>
                    <div class="card-body" style="display:flex;flex-direction:column;gap:16px;">

                        <div class="form-group">
                            <label class="form-label" for="question">Question <span class="required">*</span></label>
                            <input type="text" id="question" name="question"
                                class="form-control {{ $errors->has('question') ? 'form-control--error' : '' }}"
                                value="{{ old('question', $faq->question ?? '') }}"
                                placeholder="e.g. Do you ship internationally?" required>
                            @error('question')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="answer">Answer <span class="required">*</span></label>
                            <div class="rich-editor-wrap" data-rich-editor="answer_input">
                                <div class="rich-editor-toolbar">
                                    <button data-cmd="bold"><i class="fa-solid fa-bold"></i></button>
                                    <button data-cmd="italic"><i class="fa-solid fa-italic"></i></button>
                                    <button data-cmd="insertUnorderedList"><i class="fa-solid fa-list-ul"></i></button>
                                    <button data-cmd="insertOrderedList"><i class="fa-solid fa-list-ol"></i></button>
                                    <button data-cmd="createLink"
                                        onclick="event.preventDefault();const u=prompt('URL:');if(u)document.execCommand('createLink',false,u)">
                                        <i class="fa-solid fa-link"></i>
                                    </button>
                                    <button data-cmd="removeFormat"><i class="fa-solid fa-text-slash"></i></button>
                                </div>
                                <div class="rich-editor-content" contenteditable="true" style="min-height:160px;">
                                    {{ old('answer', $faq->answer ?? '') }}</div>
                            </div>
                            <input type="hidden" name="answer" id="answer_input"
                                value="{{ old('answer', $faq->answer ?? '') }}">
                            @error('answer')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            <div style="display:flex;flex-direction:column;gap:20px;">
                <div class="form-sidebar-card">
                    <div class="form-sidebar-section">
                        <div class="form-sidebar-title">Settings</div>
                        <div class="form-group" style="margin-bottom:12px;">
                            <label class="form-label">Category</label>
                            <input type="text" name="category" class="form-control"
                                value="{{ old('category', $faq->category ?? '') }}"
                                placeholder="e.g. Shipping, Returns, Parts" list="faq-categories">
                            <datalist id="faq-categories">
                                @foreach ($existingCategories ?? [] as $cat)
                                    <option value="{{ $cat }}">
                                @endforeach
                            </datalist>
                            <span class="form-hint">Used to group FAQs on the page</span>
                        </div>
                        <div class="form-group" style="margin-bottom:12px;">
                            <label class="form-label">Display Order</label>
                            <input type="number" name="sort_order" class="form-control" min="0"
                                value="{{ old('sort_order', $faq->sort_order ?? 0) }}">
                        </div>
                        <label class="toggle-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input class="toggle-input" type="checkbox" name="is_active" value="1"
                                {{ old('is_active', $faq->is_active ?? true) ? 'checked' : '' }}>
                            <span class="toggle-track"></span>
                            <span class="toggle-label">Active (shown on site)</span>
                        </label>
                    </div>
                    <div class="form-sidebar-section">
                        <button type="submit" class="btn btn--primary w-full" style="justify-content:center;">
                            <i class="fa-solid fa-floppy-disk"></i> Save FAQ
                        </button>
                    </div>
                </div>
            </div>

        </div>

    </form>

@endsection
