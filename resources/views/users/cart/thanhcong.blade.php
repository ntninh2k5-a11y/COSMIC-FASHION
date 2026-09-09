@extends('layouts.app')

@section('title', 'Đặt hàng thành công - Cosmic Fashion')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/thanhcong.css') }}">
@endpush

@section('content')
<div class="container py-5 my-5">
    <div class="thanh-cong-card">
        <div class="icon-success">
            ✓
        </div>
        
        <h2 class="fw-bolder mb-3 tc-title">ĐẶT HÀNG THÀNH CÔNG!</h2>
        <p class="text-secondary mb-4">
            Cảm ơn bạn đã tin tưởng và mua sắm tại <strong>Cosmic Fashion</strong>.<br>
            Mã đơn hàng của bạn là <span class="fw-bold text-dark">#{{ $orderCode }}</span>. Chúng tôi sẽ sớm liên hệ để xác nhận.
        </p>
        
        <div class="thong-tin-tom-tat p-4 rounded-3 mb-4 text-start">
            <h6 class="fw-bolder border-bottom pb-2 mb-3">THÔNG TIN GIAO HÀNG</h6>
            <p class="mb-2"><span class="fw-bold text-dark">Người nhận:</span> <span class="text-secondary">{{ $name }}</span></p>
            <p class="mb-2"><span class="fw-bold text-dark">Số điện thoại:</span> <span class="text-secondary">{{ $phone }}</span></p>
            <p class="mb-0"><span class="fw-bold text-dark">Phương thức:</span> <span class="text-secondary">{{ $payment }}</span></p>
        </div>

        <a href="{{ route('user.orders') }}" class="btn-tiep-tuc text-decoration-none d-inline-block mb-2 me-2">ĐƠN HÀNG CỦA TÔI</a>
        <a href="{{ url('/') }}" class="btn-tiep-tuc text-decoration-none d-inline-block mb-2">TIẾP TỤC MUA SẮM</a>
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