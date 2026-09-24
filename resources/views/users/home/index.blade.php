@extends('layouts.app')

@section('title', 'Trang Chủ - Cosmic Fashion')

@section('content')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/home.css') }}">
@endpush

<main class="pb-5">
    @if(isset($banners) && $banners->count() > 0)
    <!-- Banners thương hiệu -->
    <div id="brandBannerCarousel" class="carousel slide mb-5" data-bs-ride="carousel" data-bs-interval="3000">
        @if($banners->count() > 1)
        <div class="carousel-indicators">
            @foreach($banners as $index => $banner)
            <button type="button" data-bs-target="#brandBannerCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}" aria-label="Slide {{ $index + 1 }}"></button>
            @endforeach
        </div>
        @endif
        
        <div class="carousel-inner">
            @foreach($banners as $index => $banner)
            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                @if($banner->link)
                    <a href="{{ $banner->link }}">
                        <img src="{{ asset($banner->image_url) }}" class="d-block w-100 object-fit-cover" alt="{{ $banner->title }}" style="height: 70vh; min-height: 400px; max-height: 800px;">
                    </a>
                @else
                    <img src="{{ asset($banner->image_url) }}" class="d-block w-100 object-fit-cover" alt="{{ $banner->title }}" style="height: 70vh; min-height: 400px; max-height: 800px;">
                @endif
            </div>
            @endforeach
        </div>
        
        @if($banners->count() > 1)
        <button class="carousel-control-prev" type="button" data-bs-target="#brandBannerCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#brandBannerCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
        @endif
    </div>
    @endif

    <div class="container">

        <div class="row mb-5 g-1">
            <div class="col-md-4">
                <div class="hero-card">
                    <img src="{{ asset('img_react/nam.jpg') }}" class="hero-img" alt="Men">
                    <div class="hero-btn">
                        <a href="{{ route('frontend.category.detail', 'nam') }}" class="text-font">SHOP NAM</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="hero-card">
                    <img src="{{ asset('img_react/nu.jpg') }}" class="hero-img" alt="Women">
                    <div class="hero-btn">
                        <a href="{{ route('frontend.category.detail', 'nu') }}" class="text-font">SHOP NỮ</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="hero-card">
                    <img src="{{ asset('img_react/kid.jpg') }}" class="hero-img" alt="Kids">
                    <div class="hero-btn">
                        <a href="{{ route('frontend.category.detail', 'tre_em') }}" class="text-font">SHOP TRẺ EM</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-5 g-0 overflow-hidden shadow-sm sale-banner-bg">

            <div class="col-md-6 p-4 p-md-5 d-flex flex-column justify-content-center text-start">
                <div class="mb-4">
                    <div class="titleSale text-white d-inline-block px-3 py-1 fw-bold text-uppercase sale-badge">
                        Thời gian có hạn
                    </div>
                </div>

                <h2 class="fw-bold mb-3 sale-heading">
                    Ưu đãi đặc biệt
                </h2>

                <p class="text-secondary mb-4 sale-desc">
                    Tiết kiệm lên đến 30% cho bộ sưu tập mới nhất của chúng tôi.
                    Chỉ diễn ra trong thời gian ngắn.
                </p>

                <div>
                    <a href="{{ route('shop.sale') }}" class="btn text-white px-4 py-2 text-uppercase btn-sale-shop">
                        Mua sắm ngay
                    </a>
                </div>
            </div>

            <div class="col-md-6">
                <img src="https://images.unsplash.com/photo-1613945407943-59cd755fd69e?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3"
                     alt="Sale Banner"
                     class="w-100 h-100 sale-banner-img">
            </div>
        </div>

        <div class="mb-5 pt-4">
            <h2 class="fw-bold mb-4 section-heading">
                Giảm giá
            </h2>

            <div class="row g-4 mb-5">
                @forelse($khoSanPhamGiamGia as $sp)
                    <div class="col-6 col-md-3">
                        @include('partials.product_card_php', ['sp' => $sp])
                    </div>
                @empty
                    <div class="text-center w-100 text-secondary">
                        Chưa có sản phẩm giảm giá nào.
                    </div>
                @endforelse
            </div>

            <div class="text-center">
                <a href="{{ route('shop.sale') }}" class="btn btn-outline-secondary rounded-0 px-4 py-2 btn-view-all">
                    Xem tất cả các mặt hàng giảm giá
                </a>
            </div>
        </div>

    </div>
</main>

@endsection