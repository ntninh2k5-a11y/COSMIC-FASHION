@extends('layouts.app')

@section('title', 'Quét mã thanh toán - Cosmic Fashion')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/qr.css') }}">
@endpush

@section('content')

<div class="container py-5 my-4 d-flex justify-content-center">

    <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border" style="max-width: 550px; width: 100%;">
        
        @if(session('error'))
            <div class="alert alert-danger rounded-3 border-0 shadow-sm mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success rounded-3 border-0 shadow-sm mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="text-center mb-4 pb-3 border-bottom">
            <h3 class="fw-bold mb-2 text-dark" style="letter-spacing: -0.5px;">
                QUÉT MÃ THANH TOÁN
            </h3>
            <p class="text-secondary fw-medium mb-0">
                Đơn hàng: #{{ $order->order_code }}
            </p>
        </div>

        <div class="text-center mb-4">
            <div class="d-inline-block p-3 rounded-4 border shadow-sm" style="background-color: #FAFAFA;">
                <img
                    src="https://img.vietqr.io/image/MB-0326247205-compact2.png?amount={{ (int) $order->total_amount }}&addInfo={{ urlencode($order->order_code) }}&accountName={{ urlencode('NGUYEN THANH NINH') }}"
                    alt="Mã QR Thanh Toán"
                    class="img-fluid rounded-3" style="max-width: 250px;">
            </div>
        </div>

        <div class="bg-light rounded-4 p-4 mb-4 border">
            <div class="d-flex justify-content-between mb-2">
                <span class="text-secondary fw-medium">Ngân hàng:</span>
                <span class="fw-bold text-dark">MBBank</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-secondary fw-medium">Số tài khoản:</span>
                <span class="fw-bold text-dark">0326 2472 05</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-secondary fw-medium">Chủ tài khoản:</span>
                <span class="fw-bold text-dark">NGUYEN THANH NINH</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-secondary fw-medium">Số tiền:</span>
                <span class="fw-bolder text-danger" style="font-size: 1.15rem;">
                    {{ number_format($order->total_amount, 0, ',', '.') }}đ
                </span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-secondary fw-medium">Nội dung CK:</span>
                <span class="fw-bold text-primary px-2 py-1 rounded" style="background: #e0f2fe; color: #0284c7 !important;">
                    {{ $order->order_code }}
                </span>
            </div>
        </div>

        <div id="payment-status" class="mt-3 text-center">
            <div class="alert alert-warning rounded-3 border-0 shadow-sm mb-3 fw-bold d-flex align-items-center justify-content-center gap-2" style="background-color: #fef08a; color: #854d0e;">
                <div class="spinner-border spinner-border-sm" role="status"></div> Đang chờ thanh toán...
            </div>
        </div>

        <div class="text-center text-secondary fw-medium mb-4" style="font-size: 0.85rem;">
            Sau khi chuyển khoản thành công, hệ thống sẽ tự động xác nhận.
        </div>

        <a href="{{ route('home') }}"
           class="btn btn-outline-secondary fw-bold rounded-3 w-100 py-2 border-2 transition-all">
            HỦY GIAO DỊCH
        </a>

    </div>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const statusUrl = @json(route('payment.status', $order->id));
    const successUrl = @json(route('checkout.success'));
    const statusBox = document.getElementById('payment-status');

    const checkPayment = async () => {
        try {
            const response = await fetch(statusUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                return;
            }

            const data = await response.json();

            if (data.status === 'paid') {
                statusBox.innerHTML = `
                    <div class="alert alert-success mb-3">
                        Thanh toán thành công. Đang chuyển trang...
                    </div>
                `;

                setTimeout(() => {
                    window.location.href = successUrl;
                }, 1000);
            }
        } catch (error) {
            console.error(error);
        }
    };

    checkPayment();

    setInterval(checkPayment, 3000);
});
</script>
@endpush