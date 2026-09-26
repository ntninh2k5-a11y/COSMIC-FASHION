@extends('layouts.app')

@section('title', 'Trang Cá Nhân - Cosmic Fashion')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/profile.css') }}">
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
                    <a href="{{ route('user.addresses') }}" class="nav-link {{ request()->routeIs('user.addresses') ? 'active' : '' }}">
                        <i class="bi bi-geo-alt"></i> Sổ địa chỉ
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

