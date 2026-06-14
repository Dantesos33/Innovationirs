{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="en" data-theme="admin">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Parts Plus Innovation Solutions Admin</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Admin CSS --}}
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    @stack('styles')
</head>

<body class="admin-body">

    {{-- ═══════════════════════════════════════════
     SIDEBAR
═══════════════════════════════════════════ --}}
    <aside class="sidebar" id="sidebar">

        {{-- Logo --}}
        <div class="sidebar-logo">
            <a href="{{ route('admin.dashboard') }}" class="logo-link">
                <div class="logo-icon">
                    <i class="fa-solid fa-gears"></i>
                </div>
                <div class="logo-text">
                    <span class="logo-name">Parts Plus Innovation Solutions</span>
                    <span class="logo-sub">Admin Panel</span>
                </div>
            </a>
        </div>

        {{-- Admin Info --}}
        <div class="sidebar-user">
            <div class="user-avatar">
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <div class="user-info">
                <span class="user-name">{{ auth('admin')->user()->name ?? 'Admin' }}</span>
                <span class="user-role">{{ ucfirst(auth('admin')->user()->role ?? 'Staff') }}</span>
            </div>
        </div>

        {{-- Navigation --}}

        <nav class="sidebar-nav">
            <div class="mynav">
                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-gauge-high nav-icon"></i>
                    <span>Dashboard</span>
                </a>

                {{-- CATALOG GROUP --}}
                <div class="nav-group-label">Catalog</div>

                <a href="{{ route('admin.parts.index') }}"
                    class="nav-item {{ request()->routeIs('admin.parts.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-screwdriver-wrench nav-icon"></i>
                    <span>Parts</span>
                    @php $activePartsCount = \App\Models\Part::where('status','active')->count(); @endphp
                    <span class="nav-badge">{{ $activePartsCount }}</span>
                </a>

                <a href="{{ route('admin.categories.index') }}"
                    class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-layer-group nav-icon"></i>
                    <span>Categories</span>
                </a>

                <a href="{{ route('admin.makes.index') }}"
                    class="nav-item {{ request()->routeIs('admin.makes.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-industry nav-icon"></i>
                    <span>Makes / Brands</span>
                </a>

                <a href="{{ route('admin.equipment-types.index') }}"
                    class="nav-item {{ request()->routeIs('admin.equipment-types.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-tractor nav-icon"></i>
                    <span>Equipment Types</span>
                </a>

                <a href="{{ route('admin.equipment-models.index') }}"
                    class="nav-item {{ request()->routeIs('admin.equipment-models.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-sitemap nav-icon"></i>
                    <span>Equipment Models</span>
                </a>

                {{-- SALES GROUP --}}
                <div class="nav-group-label">Sales</div>

                <a href="{{ route('admin.quotes.index') }}"
                    class="nav-item {{ request()->routeIs('admin.quotes.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-invoice-dollar nav-icon"></i>
                    <span>Quote Requests</span>
                    @if (($unreadQuotes ?? 0) > 0)
                        <span class="nav-badge nav-badge--alert">{{ $unreadQuotes }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.contacts.index') }}"
                    class="nav-item {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-envelope nav-icon"></i>
                    <span>Contact Messages</span>
                    @if (($unreadContacts ?? 0) > 0)
                        <span class="nav-badge nav-badge--alert">{{ $unreadContacts }}</span>
                    @endif
                </a>

                {{-- CONTENT GROUP --}}
                <div class="nav-group-label">Content</div>

                <a href="{{ route('admin.blog.index') }}"
                    class="nav-item {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-newspaper nav-icon"></i>
                    <span>Blog</span>
                </a>

                <a href="{{ route('admin.testimonials.index') }}"
                    class="nav-item {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-star nav-icon"></i>
                    <span>Testimonials</span>
                </a>

                <a href="{{ route('admin.faqs.index') }}"
                    class="nav-item {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-circle-question nav-icon"></i>
                    <span>FAQs</span>
                </a>

                <a href="{{ route('admin.careers.index') }}"
                    class="nav-item {{ request()->routeIs('admin.careers.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-briefcase nav-icon"></i>
                    <span>Career Postings</span>
                </a>

                <a href="{{ route('admin.job-applications.index') }}"
                    class="nav-item {{ request()->routeIs('admin.job-applications.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-tie nav-icon"></i>
                    <span>Job Applications</span>
                    @if (($pendingApplications ?? 0) > 0)
                        <span class="nav-badge nav-badge--alert">{{ $pendingApplications }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.gallery.index') }}"
                    class="nav-item {{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-images nav-icon"></i>
                    <span>Gallery</span>
                </a>

                {{-- HEAVY DUTY TOOLS GROUP --}}
                <div class="nav-group-label">Heavy Duty Tools</div>

                <a href="{{ route('admin.heavy-duty-tools.index') }}"
                    class="nav-item {{ request()->routeIs('admin.heavy-duty-tools.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-hammer nav-icon"></i>
                    <span>Tools Catalog</span>
                </a>

                <a href="{{ route('admin.tool-orders.index') }}"
                    class="nav-item {{ request()->routeIs('admin.tool-orders.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-bag-shopping nav-icon"></i>
                    <span>Tool Orders</span>
                    @php
                        $newToolOrders = \App\Models\ToolOrder::where('payment_status', 'paid')
                            ->where('fulfillment_status', 'pending')
                            ->count();
                    @endphp
                    @if ($newToolOrders > 0)
                        <span class="badge badge-danger nav-badge">{{ $newToolOrders }}</span>
                    @endif
                </a>

                {{-- MARKETING GROUP --}}
                <div class="nav-group-label">Marketing</div>

                <a href="{{ route('admin.newsletter.subscribers') }}"
                    class="nav-item {{ request()->routeIs('admin.newsletter.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-paper-plane nav-icon"></i>
                    <span>Newsletter</span>
                </a>

                {{-- SYSTEM GROUP --}}
                <div class="nav-group-label">System</div>

                <a href="{{ route('admin.media.index') }}"
                    class="nav-item {{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-photo-film nav-icon"></i>
                    <span>Media Library</span>
                </a>

                @if (auth('admin')->user()?->isSuperAdmin())
                    <a href="{{ route('admin.users.index') }}"
                        class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-users-gear nav-icon"></i>
                        <span>Admin Users</span>
                    </a>
                @endif

                <a href="{{ route('admin.settings.index') }}"
                    class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-sliders nav-icon"></i>
                    <span>Settings</span>
                </a>

                {{-- Logout --}}
                <div class="nav-group-label">Account</div>
                <a href="{{ url('/') }}" class="nav-item" target="_blank">
                    <i class="fa-solid fa-arrow-up-right-from-square nav-icon"></i>
                    <span>View Site</span>
                </a>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-item nav-item--logout">
                        <i class="fa-solid fa-right-from-bracket nav-icon"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    {{-- ═══════════════════════════════════════════
     MAIN CONTENT AREA
═══════════════════════════════════════════ --}}
    <div class="admin-main" id="adminMain">

        {{-- Top Bar --}}
        <header class="topbar">
            <div class="topbar-left">
                <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="breadcrumb-nav">
                    @yield('breadcrumb')
                </div>
            </div>
            <div class="topbar-right">
                <a href="{{ route('admin.parts.create') }}" class="topbar-quick-add" title="Add New Part">
                    <i class="fa-solid fa-plus"></i>
                </a>
                <a href="{{ route('admin.quotes.index') }}"
                    class="topbar-notif {{ ($unreadQuotes ?? 0) > 0 ? 'has-dot' : '' }}" title="Quotes">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                </a>
                <div class="topbar-admin">
                    <i class="fa-solid fa-circle-user"></i>
                    <span>{{ auth('admin')->user()->name ?? '' }}</span>
                </div>
            </div>
        </header>

        {{-- Flash Messages --}}
        <div class="flash-container">
            @if (session('success'))
                <div class="flash flash--success">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('success') }}
                    <button class="flash-close">&times;</button>
                </div>
            @endif
            @if (session('error'))
                <div class="flash flash--error">
                    <i class="fa-solid fa-circle-xmark"></i>
                    {{ session('error') }}
                    <button class="flash-close">&times;</button>
                </div>
            @endif
            @if (session('warning'))
                <div class="flash flash--warning">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    {{ session('warning') }}
                    <button class="flash-close">&times;</button>
                </div>
            @endif
            @if ($errors->any())
                <div class="flash flash--error">
                    <i class="fa-solid fa-circle-xmark"></i>
                    Please fix the errors below.
                    <button class="flash-close">&times;</button>
                </div>
            @endif
        </div>

        {{-- Page Content --}}
        <main class="page-content">
            @yield('content')
        </main>

    </div>{{-- end .admin-main --}}

    {{-- ═══════════════════════════════════════════
     GLOBAL MODAL (Delete Confirm)
═══════════════════════════════════════════ --}}
    <div class="modal-overlay" id="deleteModal" style="display:none;">
        <div class="modal">
            <div class="modal-icon modal-icon--danger">
                <i class="fa-solid fa-trash"></i>
            </div>
            <h3 class="modal-title">Confirm Delete</h3>
            <p class="modal-body" id="deleteModalMessage">Are you sure you want to delete this item? This cannot be
                undone.</p>
            <div class="modal-actions">
                <button class="btn btn--ghost" id="deleteCancel">Cancel</button>
                <form id="deleteForm" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn--danger">Delete</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Admin JS --}}
    <script src="{{ asset('js/admin.js') }}"></script>
    @stack('scripts')
</body>

</html>
