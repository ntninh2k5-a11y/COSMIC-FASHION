@extends('layouts.app')

@section('title', 'Kết quả tìm kiếm - Cosmic Fashion')

@section('content')

<main class="py-5 bg-white">
    <div class="container">

        <div class="text-center mb-5 mt-3 p-4" style="background-color: #F6F5F2;">
            <h2 class="fw-bold text-uppercase mb-2" style="letter-spacing: 1px;">
                Kết quả tìm kiếm
            </h2>
            <p class="text-secondary mb-0">
                Tìm thấy <strong>{{ $ketQuaLoc->count() }}</strong> sản phẩm cho từ khóa "{{ $tuKhoa }}"
            </p>
        </div>

        <div class="row g-4 mb-5">

            @forelse($ketQuaLoc as $item)
                <div class="col-6 col-md-3">
                    <a href="{{ url('/chi-tiet-san-pham/' . $item->id) }}" class="text-decoration-none text-dark d-block">
                        <div class="the-san-pham-tim-kiem mb-4">

                            <div class="khung-anh position-relative mb-3 overflow-hidden">
                                @if($item->discount_percent > 0)
                                    <span class="position-absolute top-0 end-0 m-2 badge bg-danger rounded-0" style="z-index: 1;">
                                        -{{ $item->discount_percent }}%
                                    </span>
                                @endif
                                <img
                                    src="{{ asset($item->image_url ?? 'images/default.jpg') }}"
                                    alt="{{ $item->name }}"
                                    class="w-100"
                                    style="aspect-ratio: 3/4; object-fit: cover;"
                                >
                            </div>

                            <div class="text-start">
                                <h6 class="text-dark fw-bold mb-1" style="font-size: 0.95rem;">
                                    {{ $item->name }}
                                </h6>

                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-danger fw-bold">
                                        {{ number_format($item->discount_percent > 0 ? $item->sale_price : $item->price, 0, ',', '.') }}đ
                                    </span>
                                    
                                    @if($item->discount_percent > 0)
                                        <del class="text-secondary small">
                                            {{ number_format($item->price, 0, ',', '.') }}đ
                                        </del>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </a>
                </div>

            @empty
                <div class="text-center w-100 py-5">
                    <img
                        src="https://cdn-icons-png.flaticon.com/512/6134/6134065.png"
                        alt="not found"
                        style="width: 100px; opacity: 0.5;"
                    >
                    <p class="mt-3 text-secondary">
                        Rất tiếc, COSMIC không tìm thấy sản phẩm nào phù hợp với từ khóa của bạn.
                    </p>
                </div>
            @endforelse

        </div>
    </div>
</main>

@endsection