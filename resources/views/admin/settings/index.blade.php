@extends('layouts.admin')
@section('title', 'Site Settings')

@section('breadcrumb')
    <span class="breadcrumb-current">Settings</span>
@endsection

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Site Settings</h1>
            <p class="page-subtitle">Manage global site configuration</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert--success" style="margin-bottom:20px;">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    @php
        $settingsGroups = [
            'general' => ['icon' => 'fa-building', 'label' => 'General'],
            'seo' => ['icon' => 'fa-magnifying-glass', 'label' => 'SEO & Meta'],
            'social' => ['icon' => 'fa-share-nodes', 'label' => 'Social Media'],
            'shipping' => ['icon' => 'fa-truck-fast', 'label' => 'Shipping'],
            'notifications' => ['icon' => 'fa-bell', 'label' => 'Notifications'],
            'appearance' => ['icon' => 'fa-palette', 'label' => 'Appearance'],
        ];
    @endphp

    <div class="card">
        <div class="card-header"><span class="card-title">All Settings</span></div>
        <div class="card-body" style="padding:0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Key</th>
                        <th>Label</th>
                        <th>Group</th>
                        <th>Value</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($groups as $groupName => $groupSettings)
                        @foreach ($groupSettings as $setting)
                            <tr>
                                <td><code style="font-size:11px;">{{ $setting->key }}</code></td>
                                <td style="font-size:13px;">{{ $setting->label }}</td>
                                <td><span class="badge badge--gray">{{ $setting->group_name }}</span></td>
                                <td
                                    style="font-size:12px;max-width:300px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    {{ Str::limit($setting->value, 60) ?: '—' }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.settings.group', $setting->group_name) }}"
                                        class="btn btn--ghost btn--sm">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Quick links to each group --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:12px;margin-top:20px;">
        @foreach ($settingsGroups as $key => $meta)
            <a href="{{ route('admin.settings.group', $key) }}"
                style="display:flex;align-items:center;gap:10px;padding:14px 16px;background:var(--white);border:1px solid var(--card-border);border-radius:var(--radius);text-decoration:none;color:var(--ink);font-size:13px;font-weight:500;transition:all 0.15s;">
                <i class="fa-solid {{ $meta['icon'] }}" style="color:var(--primary);width:16px;"></i>
                {{ $meta['label'] }}
                <i class="fa-solid fa-chevron-right" style="margin-left:auto;font-size:10px;color:var(--gray-400);"></i>
            </a>
        @endforeach
    </div>

@endsection
