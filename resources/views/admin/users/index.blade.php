@extends('layouts.admin')
@section('title', 'Admin Users')

@section('breadcrumb')
    <span class="breadcrumb-current">Admin Users</span>
@endsection

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Admin Users</h1>
            <p class="page-subtitle">{{ $users->total() }} team members with panel access</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.users.create') }}" class="btn btn--primary">
                <i class="fa-solid fa-plus"></i> Add User
            </a>
        </div>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Last Login</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:12px;">
                                    <div
                                        style="width:36px;height:36px;border-radius:50%;background:var(--gray-200);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:var(--gray-600);flex-shrink:0;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="table-name">
                                            {{ $user->name }}
                                            @if ($user->id === auth('admin')->id())
                                                <span class="badge badge--blue"
                                                    style="font-size:9px;margin-left:4px;">You</span>
                                            @endif
                                        </div>
                                        <div class="table-meta">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span
                                    class="badge badge--{{ match ($user->role) {
                                        'super_admin' => 'orange',
                                        'admin' => 'blue',
                                        'staff' => 'gray',
                                        default => 'gray',
                                    } }}">
                                    {{ match ($user->role) {
                                        'super_admin' => 'Super Admin',
                                        'admin' => 'Admin',
                                        'staff' => 'Staff',
                                        default => ucfirst($user->role),
                                    } }}
                                </span>
                            </td>
                            <td style="font-size:12px;color:var(--text-muted);">
                                @if ($user->last_login_at)
                                    {{ $user->last_login_at->diffForHumans() }}
                                    <div style="font-size:10px;">{{ $user->last_login_ip }}</div>
                                @else
                                    Never
                                @endif
                            </td>
                            <td>
                                <span class="badge badge--{{ $user->is_active ? 'green' : 'red' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    @if ($user->id !== auth('admin')->id())
                                        {{-- Toggle active --}}
                                        <button class="action-btn action-btn--toggle"
                                            data-toggle-url="{{ route('admin.users.toggle', $user) }}"
                                            title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="fa-solid fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                                        </button>
                                    @endif
                                    <a href="{{ route('admin.users.edit', $user) }}" class="action-btn action-btn--edit"
                                        title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    @if ($user->id !== auth('admin')->id())
                                        <button class="action-btn action-btn--delete"
                                            data-delete-url="{{ route('admin.users.destroy', $user) }}"
                                            data-delete-label="{{ $user->name }}">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="fa-solid fa-users-gear"></i></div>
                                    <div class="empty-state-title">No admin users found</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="pagination-wrap">
                <span>Showing {{ $users->firstItem() }}–{{ $users->lastItem() }} of {{ $users->total() }}</span>
                {{ $users->links('vendor.pagination.simple-admin') }}
            </div>
        @endif
    </div>

    {{-- Role Explanation --}}
    <div class="card" style="margin-top:20px;padding:20px;">
        <div style="font-size:13px;font-weight:600;margin-bottom:12px;color:var(--text-base);">
            <i class="fa-solid fa-shield-halved" style="color:var(--primary);margin-right:6px;"></i>
            Role Permissions
        </div>
        <div
            style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;font-size:12px;color:var(--text-muted);line-height:1.7;">
            <div>
                <div style="font-weight:700;color:var(--primary);margin-bottom:4px;">
                    <span class="badge badge--orange">Super Admin</span>
                </div>
                Full access including managing admin users, all settings, and all data. Cannot be restricted.
            </div>
            <div>
                <div style="font-weight:700;color:var(--info);margin-bottom:4px;">
                    <span class="badge badge--blue">Admin</span>
                </div>
                Full access to parts, orders, content and settings. Cannot manage other admin users.
            </div>
            <div>
                <div style="font-weight:700;color:var(--gray-500);margin-bottom:4px;">
                    <span class="badge badge--gray">Staff</span>
                </div>
                Can view and manage parts, quotes, and contacts. Cannot access settings or user management.
            </div>
        </div>
    </div>

@endsection
