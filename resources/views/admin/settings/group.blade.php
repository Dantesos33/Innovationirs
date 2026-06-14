@extends('layouts.admin')
@section('title', ucfirst($group) . ' Settings')

@section('breadcrumb')
    <a href="{{ route('admin.settings.index') }}">Settings</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">{{ ucfirst($group) }}</span>
@endsection

@section('content')

    @php
        $settingsGroups = [
            'general' => ['icon' => 'fa-building', 'label' => 'General'],
            'seo' => ['icon' => 'fa-magnifying-glass', 'label' => 'SEO & Meta'],
            'social' => ['icon' => 'fa-share-nodes', 'label' => 'Social Media'],
            'shipping' => ['icon' => 'fa-truck-fast', 'label' => 'Shipping'],
            'notifications' => ['icon' => 'fa-bell', 'label' => 'Notifications'],
            'appearance' => ['icon' => 'fa-palette', 'label' => 'Appearance'],
        ];
        // $groupValues is a flat key=>value array passed from controller
        $v = $groupValues ?? [];
    @endphp

    @if (session('success'))
        <div class="alert alert--success" style="margin-bottom:20px;">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <div style="display:grid;grid-template-columns:220px 1fr;gap:24px;align-items:start;">

        {{-- Sidebar nav --}}
        <div class="form-sidebar-card" style="position:sticky;top:80px;">
            <div style="padding:8px;">
                @foreach ($settingsGroups as $key => $meta)
                    <a href="{{ route('admin.settings.group', $key) }}"
                        style="display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:var(--radius);font-size:13px;
                               font-weight:{{ $activeGroup === $key ? '600' : '400' }};
                               color:{{ $activeGroup === $key ? 'var(--primary)' : 'var(--text-muted)' }};
                               background:{{ $activeGroup === $key ? 'var(--primary-pale)' : 'transparent' }};
                               text-decoration:none;margin-bottom:2px;transition:all 0.15s;">
                        <i class="fa-solid {{ $meta['icon'] }}"
                            style="width:14px;font-size:13px;color:{{ $activeGroup === $key ? 'var(--primary)' : 'var(--gray-400)' }};"></i>
                        {{ $meta['label'] }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Form --}}
        <div>
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                {{-- Send the group name so controller redirects back here --}}
                <input type="hidden" name="group" value="{{ $group }}">

                <div class="card">
                    <div class="card-header">
                        <span class="card-title">
                            <i class="fa-solid {{ $settingsGroups[$group]['icon'] ?? 'fa-gear' }}"
                                style="color:var(--primary);margin-right:8px;"></i>
                            {{ $settingsGroups[$group]['label'] ?? ucfirst($group) }} Settings
                        </span>
                    </div>
                    <div class="card-body" style="display:flex;flex-direction:column;gap:16px;">

                        @if ($settings->isEmpty())
                            <p class="text-muted" style="font-size:13px;">
                                No settings found for this group.
                                They will be created automatically when you save.
                            </p>
                        @endif

                        @foreach ($settings as $setting)
                            <div class="form-group">
                                <label class="form-label" for="s_{{ $setting->key }}">
                                    {{ $setting->label }}
                                </label>

                                @if ($setting->type === 'textarea')
                                    <textarea id="s_{{ $setting->key }}" name="{{ $setting->key }}" class="form-control" rows="3">{{ old($setting->key, $setting->value) }}</textarea>
                                @elseif ($setting->type === 'boolean')
                                    <label class="toggle-switch" style="margin-top:4px;">
                                        <input type="hidden" name="{{ $setting->key }}" value="0">
                                        <input class="toggle-input" type="checkbox" id="s_{{ $setting->key }}"
                                            name="{{ $setting->key }}" value="1"
                                            {{ old($setting->key, $setting->value) ? 'checked' : '' }}>
                                        <span class="toggle-track"></span>
                                        <span class="toggle-label">Enabled</span>
                                    </label>
                                @elseif ($setting->type === 'url')
                                    <input type="url" id="s_{{ $setting->key }}" name="{{ $setting->key }}"
                                        class="form-control" value="{{ old($setting->key, $setting->value) }}"
                                        placeholder="https://">
                                @elseif ($setting->type === 'email')
                                    <input type="email" id="s_{{ $setting->key }}" name="{{ $setting->key }}"
                                        class="form-control" value="{{ old($setting->key, $setting->value) }}">
                                @else
                                    <input type="text" id="s_{{ $setting->key }}" name="{{ $setting->key }}"
                                        class="form-control" value="{{ old($setting->key, $setting->value) }}">
                                @endif

                                @if ($setting->description)
                                    <span class="form-hint">{{ $setting->description }}</span>
                                @endif
                            </div>
                        @endforeach

                    </div>
                </div>

                <div style="display:flex;justify-content:flex-end;margin-top:12px;">
                    <button type="submit" class="btn btn--primary btn--lg">
                        <i class="fa-solid fa-floppy-disk"></i> Save Settings
                    </button>
                </div>

            </form>
        </div>

    </div>

@endsection
