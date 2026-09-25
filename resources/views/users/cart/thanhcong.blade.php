@extends('layouts.app')

@section('title', 'Đặt hàng thành công - Cosmic Fashion')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/thanhcong.css') }}">
@endpush

@section('content')
<div class="container py-5 my-5 d-flex justify-content-center">
    <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border text-center" style="max-width: 600px; width: 100%;">
        <div class="mb-4 d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px; background-color: #dcfce7; color: #22c55e;">
            <i class="bi bi-check-lg" style="font-size: 3rem;"></i>
        </div>
        
        <h2 class="fw-bold mb-3 text-dark" style="letter-spacing: -0.5px;">ĐẶT HÀNG THÀNH CÔNG!</h2>
        <p class="text-secondary mb-4" style="font-size: 1.05rem;">
            Cảm ơn bạn đã tin tưởng và mua sắm tại <strong class="text-dark">Cosmic Fashion</strong>.<br>
            Mã đơn hàng của bạn là <span class="fw-bold text-primary px-2 py-1 rounded" style="background: #e0f2fe; color: #0284c7 !important;">#{{ $orderCode }}</span>. Chúng tôi sẽ sớm liên hệ để xác nhận.
        </p>
        
        <div class="bg-light p-4 rounded-4 mb-4 border text-start shadow-sm">
            <h6 class="fw-bold text-uppercase border-bottom pb-3 mb-3 text-dark" style="letter-spacing: -0.5px;">THÔNG TIN GIAO HÀNG</h6>
            <div class="d-flex justify-content-between mb-2">
                <span class="fw-bold text-dark">Người nhận:</span> 
                <span class="text-secondary">{{ $name }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="fw-bold text-dark">Số điện thoại:</span> 
                <span class="text-secondary">{{ $phone }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="fw-bold text-dark">Phương thức:</span> 
                <span class="text-secondary">{{ $payment }}</span>
            </div>
        </div>

        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center mt-4">
            <a href="{{ route('user.orders') }}" class="btn fw-bold rounded-3 px-4 py-2 border border-2 text-dark hover-shadow" style="transition: all 0.2s; background: #fff;">
                ĐƠN HÀNG CỦA TÔI
            </a>
            <a href="{{ url('/') }}" class="btn text-white fw-bold rounded-3 px-4 py-2 shadow-sm hover-shadow" style="background-color: #FF6B6B; transition: all 0.2s;">
                TIẾP TỤC MUA SẮM
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        localStorage.removeItem('gioHang');
        window.dispatchEvent(new Event('cartUpdated')); 
    });
</script>
@endpush