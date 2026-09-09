@extends('layouts.app')

@section('title', 'Trang Chủ - Cosmic Fashion')

@section('content')

<main class="py-5">
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

        <div class="row mb-5 g-0 overflow-hidden shadow-sm" style="background-color: #F6F5F2;">

            <div class="col-md-6 p-4 p-md-5 d-flex flex-column justify-content-center text-start">
                <div class="mb-4">
                    <div class="titleSale text-white d-inline-block px-3 py-1 fw-bold" style="background-color: #d51616; width: fit-content; font-size: 0.8rem;">
                        Thời gian có hạn
                    </div>
                </div>

                <h2 class="fw-bold mb-3" style="font-size: 2.5rem; color: #1a1a1a;">
                    Ưu đãi đặc biệt
                </h2>

                <p class="text-secondary mb-4" style="font-size: 1.1rem;">
                    Tiết kiệm lên đến 30% cho bộ sưu tập mới nhất của chúng tôi.
                    Chỉ diễn ra trong thời gian ngắn.
                </p>

                <div>
                    <a href="{{ route('shop.sale') }}" class="btn text-white px-4 py-2" style="background-color: #D35400; border: none;">
                        Mua sắm ngay
                    </a>
                </div>
            </div>

            <div class="col-md-6">
                <img src="https://images.unsplash.com/photo-1613945407943-59cd755fd69e?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3"
                     alt="Sale Banner"
                     class="w-100 h-100"
                     style="object-fit: cover; min-height: 350px;">
            </div>
        </div>

        <div class="mb-5 pt-4">
            <h2 class="fw-bold mb-4" style="color: #1a1a1a;">
                Giảm giá
            </h2>

            <div class="row g-4 mb-5">
                @forelse($khoSanPhamGiamGia as $sp)
                    <div class="col-6 col-md-3">
                        <a href="{{ route('product.detail', $sp->id) }}" class="text-decoration-none text-dark product-card-home">

                            <div class="position-relative mb-3 overflow-hidden">
                                <img src="{{ asset($sp->image_url ?? 'images/default.jpg') }}"
                                     alt="{{ $sp->name }}"
                                     class="w-100 product-img-home"
                                     style="aspect-ratio: 3/4; object-fit: cover;">

                                <span class="position-absolute top-0 end-0 m-2 badge bg-danger rounded-0" style="font-size: 0.8rem; padding: 6px 12px;">
                                    -{{ $sp->discount_percent }}%
                                </span>
                            </div>

                            <h6 class="mb-1 fw-normal text-secondary ten-sp-home">
                                {{ $sp->name }}
                            </h6>

                            <div>
                                <span class="text-danger fw-bold me-2">
                                    {{ number_format($sp->sale_price, 0, ',', '.') }}đ
                                </span>

                                <del class="text-secondary small">
                                    {{ number_format($sp->price, 0, ',', '.') }}đ
                                </del>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="text-center w-100 text-secondary">
                        Chưa có sản phẩm giảm giá nào.
                    </div>
                @endforelse
            </div>

            <div class="text-center">
                <a href="{{ route('shop.sale') }}" class="btn btn-outline-secondary rounded-0 px-4 py-2" style="border-color: #ddd; color: #1a1a1a;">
                    Xem tất cả các mặt hàng giảm giá
                </a>
            </div>
        </div>

    </div>
</main>

@endsection