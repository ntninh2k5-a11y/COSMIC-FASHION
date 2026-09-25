@extends('layouts.app')

@section('title', 'Ưu Đãi Đặc Biệt - Cosmic Fashion')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/sale.css') }}">
@endpush

@section('content')

@if($saleProducts->isEmpty())
    <div class="container py-5 text-center mt-5">
        <h4 class="text-secondary">Hiện tại chưa có chương trình Sale nào.</h4>
    </div>
@else

{{-- ===== HERO BANNER ===== --}}
<section class="sale-hero position-relative overflow-hidden">
    <div class="sale-hero-bg"></div>
    <div class="container position-relative z-1 py-5">
        <div class="row align-items-center min-vh-sale-hero">
            <div class="col-lg-6 text-white">
                <div class="sale-tag mb-3">🔥 Flash Sale</div>
                <h1 class="sale-hero-title">ƯU ĐÃI<br><span class="sale-hero-highlight">ĐẶC BIỆT</span></h1>
                <p class="sale-hero-sub mb-4">Săn ngay những thiết kế hot nhất với mức giá không thể bỏ lỡ.</p>

                <div class="sale-stats d-flex gap-4">
                    <div>
                        <div class="sale-stat-num">{{ $saleProducts->count() }}</div>
                        <div class="sale-stat-label">Sản phẩm</div>
                    </div>
                    <div>
                        <div class="sale-stat-num">{{ $saleProducts->max('discount_percent') }}%</div>
                        <div class="sale-stat-label">Giảm tối đa</div>
                    </div>
                </div>
            </div>

            {{-- Hero product carousel (top 3) --}}
            @php $top3 = $saleProducts->sortByDesc('discount_percent')->take(3)->values(); @endphp
            @if($top3->count() > 0)
            <div class="col-lg-6 d-flex justify-content-center mt-4 mt-lg-0">
                <div class="hero-carousel-wrap">

                    {{-- Slides --}}
                    @foreach($top3 as $i => $sp)
                    <div class="hero-slide {{ $i === 0 ? 'active' : '' }}" data-index="{{ $i }}">
                        <div class="hero-product-showcase">
                            <div class="hero-product-badge">-{{ $sp->discount_percent }}%</div>
                            <a href="{{ route('product.detail', $sp->id) }}">
                                <img src="{{ asset($sp->image_url ?? 'images/default.jpg') }}"
                                     alt="{{ $sp->name }}"
                                     class="hero-product-img">
                            </a>
                            <div class="hero-product-info">
                                <div class="hero-product-name">{{ $sp->name }}</div>
                                <div class="d-flex align-items-baseline gap-2">
                                    <span class="hero-product-price">{{ number_format($sp->sale_price, 0, ',', '.') }}đ</span>
                                    <del class="hero-product-old">{{ number_format($sp->price, 0, ',', '.') }}đ</del>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    {{-- Dot indicators --}}
                    <div class="hero-dots">
                        @foreach($top3 as $i => $sp)
                            <button class="hero-dot {{ $i === 0 ? 'active' : '' }}" data-dot="{{ $i }}" onclick="goToSlide({{ $i }})"></button>
                        @endforeach
                    </div>

                </div>
            </div>
            @endif
        </div>
    </div>
    {{-- Decorative blobs --}}
    <div class="sale-blob sale-blob-1"></div>
    <div class="sale-blob sale-blob-2"></div>
</section>

{{-- ===== BỘ LỌC ===== --}}
<section class="sale-filter-bar bg-white sticky-top shadow-sm" style="top: 0; z-index: 200;">
    <div class="container">
        <div class="d-flex align-items-center gap-2 py-3 overflow-auto" style="white-space: nowrap;">
            <span class="loc-tieu-de">Lọc:</span>

            <button class="loc-chu-item active" onclick="locSanPham('tat-ca', this)">
                Tất cả ({{ $saleProducts->count() }})
            </button>

            @php
                $discountGroups = [
                    'duoi-20' => ['label' => '< 20%', 'min' => 0,  'max' => 19],
                    '20-30'   => ['label' => '20–30%','min' => 20, 'max' => 30],
                    'tren-30' => ['label' => '> 30%', 'min' => 31, 'max' => 100],
                ];
            @endphp

            @foreach($discountGroups as $key => $group)
                @if($saleProducts->where('discount_percent', '>=', $group['min'])->where('discount_percent', '<=', $group['max'])->count() > 0)
                    <button class="loc-chu-item" onclick="locSanPham('{{ $key }}', this)">
                        Giảm {{ $group['label'] }}
                        ({{ $saleProducts->where('discount_percent', '>=', $group['min'])->where('discount_percent', '<=', $group['max'])->count() }})
                    </button>
                @endif
            @endforeach

            <div class="ms-auto d-flex align-items-center gap-2 flex-shrink-0">
                <span class="loc-tieu-de">Sắp xếp:</span>
                <select class="form-select form-select-sm" style="width: auto; border-radius: 50rem; border-color: #F0F0F0; font-size: 13px;" onchange="sapXep(this.value)">
                    <option value="discount-desc">Giảm nhiều nhất</option>
                    <option value="price-asc">Giá tăng dần</option>
                    <option value="price-desc">Giá giảm dần</option>
                </select>
            </div>
        </div>
    </div>
</section>

{{-- ===== LƯỚI SẢN PHẨM ===== --}}
<main class="py-5" style="background: #fafafa;">
    <div class="container">

        {{-- Ticker tape --}}
        <div class="sale-ticker mb-4 overflow-hidden rounded-pill">
            <div class="sale-ticker-inner">
                @for ($i = 0; $i < 4; $i++)
                    <span>🔥 FLASH SALE</span>
                    <span>⚡ Giảm đến {{ $saleProducts->max('discount_percent') }}%</span>
                    <span>🛍️ {{ $saleProducts->count() }} sản phẩm ưu đãi</span>
                    <span>⏰ Thời gian có hạn</span>
                @endfor
            </div>
        </div>

        <div class="row g-3" id="luoi-san-pham-sale">
            @foreach($saleProducts as $index => $sp)
                <div class="col-6 col-md-4 col-lg-3 sale-item"
                     data-discount="{{ $sp->discount_percent }}"
                     data-price="{{ $sp->sale_price }}"
                     data-discount-group="{{ $sp->discount_percent < 20 ? 'duoi-20' : ($sp->discount_percent <= 30 ? '20-30' : 'tren-30') }}">
                    <div class="sale-card h-100">
                        @include('partials.product_card_php', ['sp' => $sp, 'alignLeft' => true])
                    </div>
                </div>
            @endforeach
        </div>

        <div id="khong-co-sp" class="text-center py-5 d-none">
            <p class="text-muted">Không có sản phẩm nào trong bộ lọc này.</p>
        </div>

    </div>
</main>

@endif
@endsection

@push('scripts')
<script>
    // ---- Lọc ----
    function locSanPham(nhom, btn) {
        document.querySelectorAll('.loc-chu-item').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const items = document.querySelectorAll('.sale-item');
        let visible = 0;
        items.forEach(item => {
            const show = nhom === 'tat-ca' || item.dataset.discountGroup === nhom;
            item.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        document.getElementById('khong-co-sp').classList.toggle('d-none', visible > 0);
    }

    // ---- Sắp xếp ----
    function sapXep(kieu) {
        const grid = document.getElementById('luoi-san-pham-sale');
        const items = [...grid.querySelectorAll('.sale-item')];

        items.sort((a, b) => {
            const discA = parseInt(a.dataset.discount);
            const discB = parseInt(b.dataset.discount);
            const priceA = parseInt(a.dataset.price);
            const priceB = parseInt(b.dataset.price);

            if (kieu === 'discount-desc') return discB - discA;
            if (kieu === 'price-asc')     return priceA - priceB;
            if (kieu === 'price-desc')    return priceB - priceA;
            return 0;
        });

        items.forEach(item => grid.appendChild(item));
    }

    // ---- Hero Carousel ----
    let currentSlide = 0;
    const slides = document.querySelectorAll('.hero-slide');
    const dots   = document.querySelectorAll('.hero-dot');
    let autoTimer = null;

    function goToSlide(index) {
        // Xoá active cũ
        slides[currentSlide]?.classList.remove('active');
        slides[currentSlide]?.classList.add('leaving');
        dots[currentSlide]?.classList.remove('active');

        currentSlide = (index + slides.length) % slides.length;

        slides[currentSlide]?.classList.add('active');
        dots[currentSlide]?.classList.add('active');

        // Xoá class leaving sau khi animation xong
        setTimeout(() => {
            document.querySelectorAll('.hero-slide.leaving').forEach(s => s.classList.remove('leaving'));
        }, 600);

        // Reset timer
        clearInterval(autoTimer);
        autoTimer = setInterval(() => goToSlide(currentSlide + 1), 3000);
    }

    // Khởi động auto-rotate
    if (slides.length > 1) {
        autoTimer = setInterval(() => goToSlide(currentSlide + 1), 3000);
    }
</script>
@endpush