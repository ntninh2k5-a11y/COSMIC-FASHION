@extends('layouts.app')

@section('title', 'Ưu Đãi Đặc Biệt - Cosmic Fashion')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/sale.css') }}">
@endpush

@section('content')

@if($saleProducts->isEmpty())
    <div class="container py-5 text-center mt-5">
        <h4 class="text-secondary">Hiện tại chưa có chương trình Sale nào.</h4>
    </div>
@else
    <main class="py-4">
        <div class="container">
            <div class="khung-tieu-de-bu text-center mt-3 mb-5">
                <h1 class="chu-tieu-de-bu text-uppercase fw-bold">ƯU ĐÃI ĐẶC BIỆT</h1>
                <p class="chu-phu-bu text-secondary">Săn ngay những thiết kế hot nhất với mức giá không thể bỏ lỡ.</p>
            </div>

            <div class="row mb-4">
                
                <div class="col-md-8 mb-4 mb-md-0">
                    @if($spTo)
                        <a href="{{ url('/chi-tiet-san-pham/' . $spTo->id) }}" class="link-sp-asym d-block position-relative text-decoration-none">
                            <div class="khung-anh-asym-to position-relative">
                                @if($spTo->discount_percent > 0)
                                    <span class="nhan-den-asym bg-danger text-white border-0">-{{ $spTo->discount_percent }}%</span>
                                @endif
                                <img src="{{ asset($spTo->image_url ?? 'images/default.jpg') }}" alt="{{ $spTo->name }}" class="anh-asym-to w-100" style="object-fit: cover;">
                            </div>
                            <div class="thong-tin-asym mt-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="ten-sp-asym-to mb-1 text-dark">{{ $spTo->name }}</h3>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="gia-sp-asym-to text-danger fw-bold fs-4">{{ number_format($spTo->sale_price, 0, ',', '.') }}đ</span>
                                        <del class="text-secondary">{{ number_format($spTo->price, 0, ',', '.') }}đ</del>
                                    </div>
                                </div>
                                <div class="nut-xem-them-asym text-uppercase bg-dark text-white px-4 py-2 rounded-1">Mua Ngay</div>
                            </div>
                        </a>
                    @endif
                </div>

                <div class="col-md-4">
                    <div class="row">
                        @foreach($spNhoBenCanh as $idx => $sp)
                            <div class="col-6 col-md-12 {{ $idx === 0 ? 'mb-0 mb-md-4' : '' }}">
                                <a href="{{ url('/chi-tiet-san-pham/' . $sp->id) }}" class="link-sp-asym d-block position-relative text-decoration-none">
                                    <div class="khung-anh-asym-nho position-relative {{ $idx === 1 ? 'mt-0 mt-md-2' : '' }}">
                                        @if($sp->discount_percent > 0)
                                            <span class="nhan-den-asym bg-danger text-white border-0">-{{ $sp->discount_percent }}%</span>
                                        @endif
                                        <img src="{{ asset($sp->image_url ?? 'images/default.jpg') }}" alt="{{ $sp->name }}" class="anh-asym-nho w-100" style="aspect-ratio: 3/4; object-fit: cover;">
                                    </div>
                                    <div class="thong-tin-asym mt-2">
                                        <div class="ten-sp-asym text-secondary mb-1">{{ $sp->name }}</div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="gia-sp-asym fw-bold text-danger">{{ number_format($sp->sale_price, 0, ',', '.') }}đ</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                @foreach($spHangDuoi as $sp)
                    <div class="col-6 col-md-4 mb-4">
                        <a href="{{ url('/chi-tiet-san-pham/' . $sp->id) }}" class="link-sp-asym d-block position-relative text-decoration-none">
                            <div class="khung-anh-asym-nho position-relative">
                                @if($sp->discount_percent > 0)
                                    <span class="nhan-den-asym bg-danger text-white border-0">-{{ $sp->discount_percent }}%</span>
                                @endif
                                <img src="{{ asset($sp->image_url ?? 'images/default.jpg') }}" alt="{{ $sp->name }}" class="anh-asym-nho w-100" style="aspect-ratio: 3/4; object-fit: cover;">
                            </div>
                            <div class="thong-tin-asym mt-3">
                                <div class="ten-sp-asym text-secondary mb-1">{{ $sp->name }}</div>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="gia-sp-asym fw-bold text-danger">{{ number_format($sp->sale_price, 0, ',', '.') }}đ</span>
                                </div>
                                
                                @if($sp->variants && $sp->variants->count() > 0)
                                    <div class="o-mau-sac-moi d-flex gap-1 pt-1">
                                        @foreach($sp->variants->whereNotNull('color')->unique('color') as $variant)
                                            <span class="mau-sac-tron-asym shadow-sm" 
                                                  style="background-color: {{ $variant->color }}; border: 1px solid #eee; width: 18px; height: 18px; border-radius: 50%; display: inline-block;">
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

        </div>
    </main>
@endif

@endsection