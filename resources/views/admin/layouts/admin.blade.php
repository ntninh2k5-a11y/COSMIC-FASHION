<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quản trị - Cosmic Fashion')</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        body {
            font-family: 'Inter', sans-serif !important;
        }
    </style>
</head>
<body>

    <div class="admin-sidebar">
        <h3 class="fw-bolder mb-4 text-center" style="letter-spacing: 1px;">COSMIC ADMIN</h3>
        
        <a href="{{ route('admin.dashboard') }}" 
           class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            Tổng Quan
        </a>

        <a href="{{ route('admin.products.index') }}" 
           class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            Quản lý Sản Phẩm
        </a>

        <a href="{{ route('admin.categories.index') }}" 
           class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            Quản lý Danh Mục
        </a>

        <a href="{{ route('admin.orders.index') }}" 
           class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            Quản lý Đơn Hàng
        </a>

        <a href="{{ route('admin.users.index') }}" 
           class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            Quản lý Tài Khoản
        </a>
        
        <a href="{{ route('home') }}" class="sidebar-link mt-5 text-center" style="background: #1a1a1a; color: white;">
            Xem Website
        </a>
    </div>

    <div class="admin-content">
        <div class="admin-header">
            <h2 class="fw-bolder m-0 text-uppercase">@yield('page-title')</h2>
            <div class="d-flex align-items-center">
                <span class="fw-bold me-3">Chào, {{ Auth::user()->name ?? 'Admin' }}</span>
                
                <a class="neo-btn text-decoration-none" href="{{ route('logout') }}">
                    ĐĂNG XUẤT
                </a>
            </div>
        </div>

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>