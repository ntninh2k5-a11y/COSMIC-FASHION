@extends('layouts.app')

@section('title', 'Quét mã thanh toán - Cosmic Fashion')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/qr.css') }}">
@endpush

@section('content')

<div class="container py-5 my-4">

    @if(session('error'))
        <div class="alert alert-danger mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="qr-card">

        <h3 class="fw-bolder mb-2 qr-title">
            QUÉT MÃ THANH TOÁN
        </h3>

        <p class="text-secondary fw-bold">
            Đơn hàng: #{{ $order->order_code }}
        </p>

        <div class="qr-image-wrapper">
            <img
                src="https://img.vietqr.io/image/MB-0326247205-compact2.png?amount={{ (int) $order->total_amount }}&addInfo={{ urlencode($order->order_code) }}&accountName={{ urlencode('NGUYEN THANH NINH') }}"
                alt="Mã QR Thanh Toán"
                class="qr-img">
        </div>

        <div class="qr-info-box">

            <div class="qr-info-row">
                <span class="text-secondary fw-bold">
                    Ngân hàng:
                </span>
                <span class="fw-bold text-dark">
                    MBBank
                </span>
            </div>

            <div class="qr-info-row">
                <span class="text-secondary fw-bold">
                    Số tài khoản:
                </span>
                <span class="fw-bold text-dark">
                    0326 2472 05
                </span>
            </div>

            <div class="qr-info-row">
                <span class="text-secondary fw-bold">
                    Chủ tài khoản:
                </span>
                <span class="fw-bold text-dark">
                    NGUYEN THANH NINH
                </span>
            </div>

            <div class="qr-info-row">
                <span class="text-secondary fw-bold">
                    Số tiền:
                </span>
                <span class="fw-bolder text-danger" style="font-size: 1.1rem;">
                    {{ number_format($order->total_amount, 0, ',', '.') }}đ
                </span>
            </div>

            <div class="qr-info-row">
                <span class="text-secondary fw-bold">
                    Nội dung CK:
                </span>
                <span class="fw-bolder text-dark">
                    {{ $order->order_code }}
                </span>
            </div>

        </div>

        <div id="payment-status" class="mt-3 text-center">
            <div class="alert alert-warning mb-3">
                Đang chờ thanh toán...
            </div>
        </div>

        <div class="text-center text-secondary fw-bold mb-3">
            Sau khi chuyển khoản thành công, hệ thống sẽ tự động xác nhận.
        </div>

        <a href="{{ route('home') }}"
           class="btn btn-cancel-qr text-decoration-none d-block">
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