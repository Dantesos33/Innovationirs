@extends('layouts.admin')
@section('title', isset($model) ? 'Edit Model' : 'Add Model')

@section('breadcrumb')
    <a href="{{ route('admin.equipment-models.index') }}">Equipment Models</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">{{ isset($model) ? 'Edit: ' . $model->name : 'Add Model' }}</span>
@endsection

@section('content')

    <form
        action="{{ isset($model) ? route('admin.equipment-models.update', $model) : route('admin.equipment-models.store') }}"
        method="POST">
        @csrf
        @if (isset($model))
            @method('PUT')
        @endif

        <div class="page-header">
            <div>
                <h1 class="page-title">{{ isset($model) ? 'Edit Model' : 'Add Equipment Model' }}</h1>
                @if (isset($model))
                    <p class="page-subtitle">{{ $model->make?->name ?? '' }}</p>
                @endif
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.equipment-models.index') }}" class="btn btn--ghost">Cancel</a>
                <button type="submit" class="btn btn--primary">
                    <i class="fa-solid fa-floppy-disk"></i>
                    {{ isset($model) ? 'Save Changes' : 'Create Model' }}
                </button>
            </div>
        </div>

        <div class="form-layout">

            <div style="display:flex;flex-direction:column;gap:20px;">

                <div class="card">
                    <div class="card-header"><span class="card-title">Model Information</span></div>
                    <div class="card-body">
                        <div class="form-grid form-grid--2" style="gap:16px;">

                            <div class="form-group form-group--full">
                                <label class="form-label" for="make_id">Make / Brand <span
                                        class="required">*</span></label>
                                <select id="make_id" name="make_id"
                                    class="form-control {{ $errors->has('make_id') ? 'form-control--error' : '' }}"
                                    required>
                                    <option value="">— Select Make —</option>
                                    @foreach ($makes as $make)
                                        <option value="{{ $make->id }}"
                                            {{ old('make_id', $model->make_id ?? '') == $make->id ? 'selected' : '' }}>
                                            {{ $make->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('make_id')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group form-group--full">
                                <label class="form-label" for="name">Model Name <span class="required">*</span></label>
                                <input type="text" id="slugSource" name="name"
                                    class="form-control {{ $errors->has('name') ? 'form-control--error' : '' }}"
                                    value="{{ old('name', $model->name ?? '') }}" placeholder="e.g. 320C, 430E, D6T"
                                    required>
                                @error('name')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group form-group--full">
                                <label class="form-label" for="slug">URL Slug</label>
                                <input type="text" id="slug" name="slug" class="form-control"
                                    value="{{ old('slug', $model->slug ?? '') }}" placeholder="auto-generated">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="year_start">Year Start</label>
                                <input type="number" id="year_start" name="year_start"
                                    class="form-control {{ $errors->has('year_start') ? 'form-control--error' : '' }}"
                                    value="{{ old('year_start', $model->year_start ?? '') }}" min="1900"
                                    max="{{ date('Y') + 2 }}" placeholder="e.g. 1998">
                                @error('year_start')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="year_end">Year End</label>
                                <input type="number" id="year_end" name="year_end"
                                    class="form-control {{ $errors->has('year_end') ? 'form-control--error' : '' }}"
                                    value="{{ old('year_end', $model->year_end ?? '') }}" min="1900"
                                    max="{{ date('Y') + 2 }}" placeholder="Leave blank if still in production">
                                @error('year_end')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group form-group--full">
                                <label class="form-label" for="description">Notes</label>
                                <textarea name="description" id="description" class="form-control" rows="3"
                                    placeholder="Optional notes about this model variant, serial ranges, etc.">{{ old('description', $model->description ?? '') }}</textarea>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            {{-- Sidebar --}}
            <div style="display:flex;flex-direction:column;gap:20px;">

                <div class="form-sidebar-card">
                    <div class="form-sidebar-section">
                        <div class="form-sidebar-title">Settings</div>
                        <label class="toggle-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input class="toggle-input" type="checkbox" name="is_active" value="1"
                                {{ old('is_active', $model->is_active ?? true) ? 'checked' : '' }}>
                            <span class="toggle-track"></span>
                            <span class="toggle-label">Active</span>
                        </label>
                    </div>
                    <div class="form-sidebar-section">
                        <button type="submit" class="btn btn--primary w-full" style="justify-content:center;">
                            <i class="fa-solid fa-floppy-disk"></i>
                            {{ isset($model) ? 'Save Changes' : 'Create Model' }}
                        </button>
                    </div>
                </div>

                {{-- Info --}}
                <div class="card" style="padding:16px;">
                    <div style="font-size:12px;color:var(--text-muted);line-height:1.7;">
                        <p style="font-weight:600;color:var(--text-base);margin-bottom:6px;">
                            <i class="fa-solid fa-circle-info" style="color:var(--info);"></i> About Models
                        </p>
                        Equipment models are linked to parts to define compatibility.
                        When a customer uses the Quick Quote form, they can select a make and model
                        to help identify the exact part they need.
                    </div>
                </div>

            </div>

        </div>

    </form>

@endsection
