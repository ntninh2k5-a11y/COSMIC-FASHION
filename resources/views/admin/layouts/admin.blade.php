<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quản trị - Cosmic Fashion')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
    @stack('styles')
</head>
<body>

    {{-- SIDEBAR --}}
    <div class="admin-sidebar">
        <div class="sidebar-brand">
            <span class="sidebar-brand-title">Cosmic</span>
            <span class="sidebar-brand-sub">Admin Panel</span>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-section-label">Tổng quan</div>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid"></i> Bảng điều khiển
            </a>

            <div class="sidebar-section-label" style="margin-top:8px;">Quản lý nội dung</div>
            <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="bi bi-bag"></i> Sản phẩm
            </a>
            <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="bi bi-folder"></i> Danh mục
            </a>
            <a href="{{ route('admin.banners.index') }}" class="sidebar-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                <i class="bi bi-images"></i> Banner
            </a>

            <div class="sidebar-section-label" style="margin-top:8px;">Giao dịch</div>
            <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i> Đơn hàng
            </a>
            <a href="{{ route('admin.vouchers.index') }}" class="sidebar-link {{ request()->routeIs('admin.vouchers.*') ? 'active' : '' }}">
                <i class="bi bi-ticket-perforated"></i> Voucher
            </a>

            <div class="sidebar-section-label" style="margin-top:8px;">Hệ thống</div>
            <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Tài khoản
            </a>
            <a href="{{ route('admin.footer_menus.index') }}" class="sidebar-link {{ request()->routeIs('admin.footer_menus.*') ? 'active' : '' }}">
                <i class="bi bi-layout-text-sidebar"></i> Chân trang
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="{{ route('home') }}" target="_blank">
                <i class="bi bi-box-arrow-up-right"></i> Xem Website
            </a>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="admin-content">
        {{-- TOP BAR --}}
        <div class="admin-topbar">
            <h1 class="admin-topbar-title">@yield('page-title', 'Dashboard')</h1>
            <div class="admin-topbar-right">
                <div class="admin-topbar-user">
                    <div class="admin-topbar-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</div>
                    <span>{{ Auth::user()->name ?? 'Admin' }}</span>
                </div>
                <a class="btn-admin-logout" href="{{ route('logout') }}">
                    <i class="bi bi-box-arrow-right"></i> Đăng xuất
                </a>
            </div>
        </div>

        {{-- SESSION ALERTS --}}
        @if(session('success'))
            <div class="admin-alert admin-alert-success">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="admin-alert admin-alert-error">
                <i class="bi bi-x-circle-fill fs-5"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        @if(session('warning'))
            <div class="admin-alert admin-alert-warning">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>