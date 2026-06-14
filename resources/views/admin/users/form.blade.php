@extends('layouts.admin')
@section('title', isset($user) ? 'Edit User' : 'Add Admin User')

@section('breadcrumb')
    <a href="{{ route('admin.users.index') }}">Admin Users</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">{{ isset($user) ? 'Edit: ' . $user->name : 'Add User' }}</span>
@endsection

@section('content')

    <form action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST">
        @csrf
        @if (isset($user))
            @method('PUT')
        @endif

        <div class="page-header">
            <div>
                <h1 class="page-title">{{ isset($user) ? 'Edit Admin User' : 'Add Admin User' }}</h1>
                @if (isset($user))
                    <p class="page-subtitle">
                        Member since {{ $user->created_at->format('M d, Y') }}
                        &middot; Last login: {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                    </p>
                @endif
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.users.index') }}" class="btn btn--ghost">Cancel</a>
                <button type="submit" class="btn btn--primary">
                    <i class="fa-solid fa-floppy-disk"></i>
                    {{ isset($user) ? 'Save Changes' : 'Create User' }}
                </button>
            </div>
        </div>

        <div class="form-layout">

            {{-- Main Fields --}}
            <div style="display:flex;flex-direction:column;gap:20px;">

                <div class="card">
                    <div class="card-header"><span class="card-title">Account Information</span></div>
                    <div class="card-body">
                        <div class="form-grid form-grid--2" style="gap:16px;">

                            <div class="form-group form-group--full">
                                <label class="form-label" for="name">Full Name <span class="required">*</span></label>
                                <input type="text" id="name" name="name"
                                    class="form-control {{ $errors->has('name') ? 'form-control--error' : '' }}"
                                    value="{{ old('name', $user->name ?? '') }}" placeholder="e.g. John Smith" required>
                                @error('name')
                                    <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i>
                                        {{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group form-group--full">
                                <label class="form-label" for="email">Email Address <span
                                        class="required">*</span></label>
                                <input type="email" id="email" name="email"
                                    class="form-control {{ $errors->has('email') ? 'form-control--error' : '' }}"
                                    value="{{ old('email', $user->email ?? '') }}" placeholder="admin@amsparts.com"
                                    required>
                                @error('email')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Password --}}
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Password</span>
                        @if (isset($user))
                            <span class="text-muted text-small">Leave blank to keep current password</span>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="form-grid form-grid--2" style="gap:16px;">

                            <div class="form-group">
                                <label class="form-label" for="password">
                                    {{ isset($user) ? 'New Password' : 'Password' }}
                                    @if (!isset($user))
                                        <span class="required">*</span>
                                    @endif
                                </label>
                                <div style="position:relative;">
                                    <input type="password" id="password" name="password"
                                        class="form-control {{ $errors->has('password') ? 'form-control--error' : '' }}"
                                        placeholder="Min 8 characters" {{ !isset($user) ? 'required' : '' }}
                                        style="padding-right:40px;">
                                    <button type="button" tabindex="-1"
                                        onclick="const f=document.getElementById('password');f.type=f.type==='password'?'text':'password';"
                                        style="position:absolute;right:10px;top:50%;transform:translateY(-50%);color:var(--gray-400);font-size:13px;">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="password_confirmation">Confirm Password
                                    @if (!isset($user))
                                        <span class="required">*</span>
                                    @endif
                                </label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-control" placeholder="Repeat password"
                                    {{ !isset($user) ? 'required' : '' }}>
                            </div>

                        </div>

                        @if (!isset($user))
                            <div
                                style="margin-top:12px;padding:12px;background:var(--info-pale);border-radius:var(--radius);border:1px solid #BFDBFE;font-size:12px;color:var(--info);">
                                <i class="fa-solid fa-circle-info"></i>
                                Password must be at least 8 characters. Use a mix of letters, numbers, and symbols.
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            {{-- Sidebar --}}
            <div style="display:flex;flex-direction:column;gap:20px;">

                <div class="form-sidebar-card">
                    <div class="form-sidebar-section">
                        <div class="form-sidebar-title">Role & Access</div>

                        <div class="form-group" style="margin-bottom:14px;">
                            <label class="form-label" for="role">Role <span class="required">*</span></label>
                            <select id="role" name="role"
                                class="form-control {{ $errors->has('role') ? 'form-control--error' : '' }}" required>
                                <option value="staff" {{ old('role', $user->role ?? '') === 'staff' ? 'selected' : '' }}>
                                    Staff
                                </option>
                                <option value="admin" {{ old('role', $user->role ?? '') === 'admin' ? 'selected' : '' }}>
                                    Admin
                                </option>
                                <option value="super_admin"
                                    {{ old('role', $user->role ?? '') === 'super_admin' ? 'selected' : '' }}>
                                    Super Admin
                                </option>
                            </select>
                            @error('role')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Role descriptions --}}
                        <div id="roleDesc"
                            style="font-size:11px;color:var(--text-muted);line-height:1.6;padding:10px;background:var(--gray-50);border-radius:var(--radius);border:1px solid var(--card-border);">
                            Select a role above to see its permissions.
                        </div>
                    </div>

                    <div class="form-sidebar-section">
                        <div class="form-sidebar-title">Account Status</div>
                        <label class="toggle-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input class="toggle-input" type="checkbox" name="is_active" value="1"
                                {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}
                                {{ isset($user) && $user->id === auth('admin')->id() ? 'disabled' : '' }}>
                            <span class="toggle-track"></span>
                            <span class="toggle-label">Active (can log in)</span>
                        </label>
                        @if (isset($user) && $user->id === auth('admin')->id())
                            <div style="font-size:11px;color:var(--text-muted);margin-top:6px;">
                                You cannot deactivate your own account.
                            </div>
                        @endif
                    </div>

                    <div class="form-sidebar-section">
                        <button type="submit" class="btn btn--primary w-full" style="justify-content:center;">
                            <i class="fa-solid fa-floppy-disk"></i>
                            {{ isset($user) ? 'Save Changes' : 'Create User' }}
                        </button>
                    </div>
                </div>

                {{-- Last activity --}}
                @if (isset($user))
                    <div class="form-sidebar-card">
                        <div class="form-sidebar-section">
                            <div class="form-sidebar-title">Activity</div>
                            <table style="width:100%;font-size:12px;border-collapse:collapse;">
                                <tr>
                                    <td style="padding:5px 0;color:var(--text-muted);">Last Login</td>
                                    <td style="padding:5px 0;font-weight:500;">
                                        {{ $user->last_login_at ? $user->last_login_at->format('M d, Y g:i A') : 'Never' }}
                                    </td>
                                </tr>
                                @if ($user->last_login_ip)
                                    <tr>
                                        <td style="padding:5px 0;color:var(--text-muted);">Last IP</td>
                                        <td style="padding:5px 0;">{{ $user->last_login_ip }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td style="padding:5px 0;color:var(--text-muted);">Created</td>
                                    <td style="padding:5px 0;">{{ $user->created_at->format('M d, Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                @endif

            </div>

        </div>

    </form>

@endsection

@push('scripts')
    <script>
        const roleDescriptions = {
            staff: 'Can view and manage parts, quotes, and contacts. Cannot access admin user management or site settings.',
            admin: 'Full access to parts, content, quotes, newsletter, and settings. Cannot manage other admin users.',
            super_admin: 'Unrestricted access to everything including admin users, all settings, and all data.'
        };

        const roleSelect = document.getElementById('role');
        const roleDesc = document.getElementById('roleDesc');

        function updateRoleDesc() {
            const desc = roleDescriptions[roleSelect.value] || 'Select a role above to see its permissions.';
            roleDesc.textContent = desc;
        }

        if (roleSelect) {
            roleSelect.addEventListener('change', updateRoleDesc);
            updateRoleDesc();
        }
    </script>
@endpush
