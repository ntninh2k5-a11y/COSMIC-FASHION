@extends('layouts.app')

@section('title', 'Trang Cá Nhân - Cosmic Fashion')

@push('styles')
    <style>
        .profile-sidebar {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            padding: 20px 0;
            margin-bottom: 20px;
        }
        .profile-user-info {
            text-align: center;
            padding: 0 20px 20px;
            border-bottom: 1px solid #f0f0f0;
            margin-bottom: 20px;
        }
        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #f0f4f8;
            color: #5DADE2;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .profile-nav .nav-link {
            color: #2D3436;
            padding: 12px 24px;
            border-left: 3px solid transparent;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .profile-nav .nav-link:hover, .profile-nav .nav-link.active {
            background: #f8fbfd;
            color: #5DADE2;
            border-left-color: #5DADE2;
        }
        .profile-nav .nav-link i {
            width: 24px;
            margin-right: 10px;
        }
        .profile-content {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            padding: 30px;
        }
        .neo-input {
            border: 1px solid #d1dde3;
            border-radius: 8px;
            padding: 10px 15px;
        }
        .neo-input:focus {
            border-color: #5DADE2;
            box-shadow: 0 0 0 3px rgba(93, 173, 226, 0.2);
            outline: none;
        }
        .neo-btn {
            background: #5DADE2;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.2s;
        }
        .neo-btn:hover {
            background: #24577A;
            color: white;
        }
    </style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 col-md-4">
            <div class="profile-sidebar">
                <div class="profile-user-info">
                    <div class="profile-avatar overflow-hidden">
                        @if($user->profile && $user->profile->avatar_url)
                            <img src="{{ asset($user->profile->avatar_url) }}" alt="Avatar" class="w-100 h-100 object-fit-cover">
                        @else
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        @endif
                    </div>
                    <h5 class="mb-1">{{ $user->name }}</h5>
                    <p class="text-muted small mb-0">{{ $user->email }}</p>
                    <div class="mt-2 text-warning fw-bold small">
                        <i class="bi bi-star-fill"></i> {{ $user->profile->loyalty_points ?? 0 }} điểm tích lũy
                    </div>
                </div>
                
                <div class="nav flex-column profile-nav">
                    <a href="{{ route('user.profile') }}" class="nav-link {{ request()->routeIs('user.profile') ? 'active' : '' }}">
                        <i class="bi bi-person"></i> Thông tin tài khoản
                    </a>
                    <a href="{{ route('user.orders') }}" class="nav-link {{ request()->routeIs('user.orders') ? 'active' : '' }}">
                        <i class="bi bi-bag"></i> Đơn hàng của tôi
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9 col-md-8">
            <div class="profile-content">
                @yield('profile_content')
            </div>
        </div>
    </div>
</div>
@endsection
