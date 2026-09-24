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
                    @include('partials.product_card_php', ['sp' => $item])
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