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

        {{-- === SẢN PHẨM ĐƯỢC XEM NHIỀU NHẤT — Carousel === --}}
        <div class="mb-5 pt-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold section-heading mb-0">
                    <i class="bi bi-fire text-danger"></i> Được xem nhiều nhất
                </h2>
                {{-- Carousel navigation arrows --}}
                <div class="d-flex gap-2">
                    <button class="btn-carousel-nav" type="button" data-bs-target="#mostViewedCarousel" data-bs-slide="prev" aria-label="Trước">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button class="btn-carousel-nav" type="button" data-bs-target="#mostViewedCarousel" data-bs-slide="next" aria-label="Sau">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>

            @if($sanPhamXemNhieu->count() > 0)
            <div id="mostViewedCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                <div class="carousel-inner">
                    @foreach($sanPhamXemNhieu->chunk(4) as $chunkIndex => $chunk)
                    <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                        <div class="row g-4">
                            @foreach($chunk as $sp)
                            <div class="col-6 col-md-3">
                                @include('partials.product_card_php', ['sp' => $sp])
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Indicators --}}
                @if($sanPhamXemNhieu->count() > 4)
                <div class="d-flex justify-content-center gap-2 mt-4">
                    @foreach($sanPhamXemNhieu->chunk(4) as $i => $chunk)
                    <button type="button" data-bs-target="#mostViewedCarousel" data-bs-slide-to="{{ $i }}"
                        class="carousel-dot {{ $i === 0 ? 'active' : '' }}" aria-label="Slide {{ $i + 1 }}"></button>
                    @endforeach
                </div>
                @endif
            </div>
            @else
                <div class="text-center w-100 text-secondary py-5">
                    <i class="bi bi-eye fs-1 d-block mb-3 opacity-25"></i>
                    Chưa có dữ liệu lượt xem.
                </div>
            @endif
        </div>

    </div>
</main>

@endsection