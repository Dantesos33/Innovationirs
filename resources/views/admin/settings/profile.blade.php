@extends('layouts.admin')
@section('title', 'My Profile')

@section('breadcrumb')
    <a href="{{ route('admin.settings.index') }}">Settings</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">My Profile</span>
@endsection

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">My Profile</h1>
            <p class="page-subtitle">Update your account details and password</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert--success" style="margin-bottom:20px;">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-layout">

            {{-- Main --}}
            <div style="display:flex;flex-direction:column;gap:20px;">

                {{-- Account Details --}}
                <div class="card">
                    <div class="card-header"><span class="card-title">Account Details</span></div>
                    <div class="card-body" style="display:flex;flex-direction:column;gap:16px;">

                        <div class="form-group">
                            <label class="form-label" for="name">Full Name <span class="required">*</span></label>
                            <input type="text" id="name" name="name"
                                class="form-control {{ $errors->has('name') ? 'form-control--error' : '' }}"
                                value="{{ old('name', $admin->name) }}" required>
                            @error('name')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email">Email Address <span class="required">*</span></label>
                            <input type="email" id="email" name="email"
                                class="form-control {{ $errors->has('email') ? 'form-control--error' : '' }}"
                                value="{{ old('email', $admin->email) }}" required>
                            @error('email')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Change Password --}}
                <div class="card">
                    <div class="card-header"><span class="card-title">Change Password</span></div>
                    <div class="card-body" style="display:flex;flex-direction:column;gap:16px;">

                        <p style="font-size:13px;color:var(--text-muted);">Leave these fields blank to keep your current
                            password.</p>

                        <div class="form-group">
                            <label class="form-label" for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password"
                                class="form-control {{ $errors->has('current_password') ? 'form-control--error' : '' }}"
                                autocomplete="current-password">
                            @error('current_password')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="password">New Password</label>
                            <input type="password" id="password" name="password"
                                class="form-control {{ $errors->has('password') ? 'form-control--error' : '' }}"
                                autocomplete="new-password" minlength="8">
                            <span class="form-hint">Minimum 8 characters</span>
                            @error('password')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="password_confirmation">Confirm New Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-control" autocomplete="new-password">
                        </div>

                    </div>
                </div>

            </div>

            {{-- Sidebar --}}
            <div style="display:flex;flex-direction:column;gap:20px;">

                {{-- Avatar --}}
                <div class="form-sidebar-card">
                    <div class="form-sidebar-section">
                        <div class="form-sidebar-title">Profile Photo</div>

                        <div style="display:flex;flex-direction:column;align-items:center;gap:14px;margin-bottom:16px;">
                            <img src="{{ $admin->avatar_url }}" alt="{{ $admin->name }}"
                                style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid var(--card-border);"
                                id="avatarPreview">
                            <span style="font-size:12px;color:var(--text-muted);">{{ $admin->name }}</span>
                        </div>

                        <div class="image-upload-area" data-image-upload="avatar-upload">
                            <input type="file" name="avatar" id="avatarInput" accept="image/jpeg,image/png,image/webp">
                            <div class="upload-icon"><i class="fa-solid fa-camera"></i></div>
                            <div class="upload-text">Click to upload photo</div>
                            <div class="upload-hint">JPG, PNG, WebP — max 2MB<br>Recommended: square image</div>
                        </div>
                        @error('avatar')
                            <span class="form-error" style="margin-top:6px;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Account Info --}}
                <div class="form-sidebar-card">
                    <div class="form-sidebar-section">
                        <div class="form-sidebar-title">Account Info</div>
                        <dl style="display:flex;flex-direction:column;gap:10px;font-size:13px;">
                            <div style="display:flex;justify-content:space-between;">
                                <dt style="color:var(--text-muted);">Role</dt>
                                <dd style="font-weight:500;">{{ ucfirst(str_replace('_', ' ', $admin->role)) }}</dd>
                            </div>
                            <div style="display:flex;justify-content:space-between;">
                                <dt style="color:var(--text-muted);">Last Login</dt>
                                <dd style="font-weight:500;">
                                    {{ $admin->last_login_at ? $admin->last_login_at->diffForHumans() : 'Never' }}
                                </dd>
                            </div>
                            <div style="display:flex;justify-content:space-between;">
                                <dt style="color:var(--text-muted);">Member Since</dt>
                                <dd style="font-weight:500;">{{ $admin->created_at->format('M Y') }}</dd>
                            </div>
                        </dl>
                    </div>
                    <div class="form-sidebar-section">
                        <button type="submit" class="btn btn--primary w-full"
                            style="justify-content:center;display:flex;">
                            <i class="fa-solid fa-floppy-disk"></i> Save Profile
                        </button>
                    </div>
                </div>

            </div>

        </div>

    </form>

@endsection

@push('scripts')
    <script>
        // Live avatar preview
        document.getElementById('avatarInput')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    document.getElementById('avatarPreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
