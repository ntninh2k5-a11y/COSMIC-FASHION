@extends('layouts.app')

@section('title', 'Thanh toán - Cosmic Fashion')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/thanhtoan.css') }}">
@endpush

@section('content')

<div class="container py-5">
    <div class="mb-5 text-center">
        <h2 class="fw-bolder text-uppercase" style="letter-spacing: 1px; color: #1a1a1a;">THANH TOÁN ĐƠN HÀNG</h2>
        <p class="text-secondary fw-bold">Vui lòng kiểm tra và điền đầy đủ thông tin giao hàng</p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger mb-4">{{ session('error') }}</div>
    @endif

    <form id="form-thanh-toan" action="{{ route('order.store') }}" method="POST">
        @csrf
        
        <input type="hidden" name="total_amount" value="{{ $total }}">
        
        @foreach($cartItems as $index => $item)
            <input type="hidden" name="cart_items[{{ $index }}][product_id]" value="{{ $item->product_id }}">
            <input type="hidden" name="cart_items[{{ $index }}][variant_id]" value="{{ $item->variant_id }}">
            <input type="hidden" name="cart_items[{{ $index }}][quantity]" value="{{ $item->quantity }}">
            <input type="hidden" name="cart_items[{{ $index }}][price]" value="{{ $item->product->discount_percent > 0 ? $item->product->sale_price : $item->product->price }}">
        @endforeach

        <div class="row">
            <div class="col-md-7">
                <div class="neo-card mb-4">
                    <h5 class="fw-bolder border-bottom border-dark pb-2 mb-4 border-2">THÔNG TIN GIAO HÀNG</h5>
                    <div class="mb-3">
                        <label class="fw-bolder mb-2" style="font-size: 0.8rem;">HỌ VÀ TÊN</label>
                        <input type="text" class="form-control neo-input" name="fullname" value="{{ session('user_name') }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="fw-bolder mb-2" style="font-size: 0.8rem;">SỐ ĐIỆN THOẠI</label>
                            <input type="text" class="form-control neo-input" name="customer_phone" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bolder mb-2" style="font-size: 0.8rem;">EMAIL</label>
                            <input type="email" class="form-control neo-input" name="email" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bolder mb-2" style="font-size: 0.8rem;">ĐỊA CHỈ NHẬN HÀNG</label>
                        <input type="text" class="form-control neo-input" name="shipping_address" required>
                    </div>
                    <div class="mb-4">
                        <label class="fw-bolder mb-2" style="font-size: 0.8rem;">GHI CHÚ ĐƠN HÀNG</label>
                        <textarea class="form-control neo-input" name="notes" rows="3"></textarea>
                    </div>
                    
                    <h5 class="fw-bolder border-bottom border-dark pb-2 mb-4 border-2 mt-5">PHƯƠNG THỨC THANH TOÁN</h5>
                    <div class="form-check mb-2">
                        <input class="form-check-input border-dark" type="radio" name="payment_method" id="bank" value="bank" checked>
                        <label class="form-check-label fw-bold text-dark" for="bank">Chuyển khoản ngân hàng</label>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="neo-card mb-4" style="background-color: #f8f9fa;">
                    <h5 class="fw-bolder border-bottom border-dark pb-2 mb-4 border-2">TỔNG ĐƠN HÀNG</h5>
                    
                    <div style="max-height: 350px; overflow-y: auto; overflow-x: hidden; padding-right: 10px;" class="mb-4">
                        @foreach($cartItems as $item)
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div>
                                    <img src="{{ asset($item->product->image_url ?? 'images/default.jpg') }}" alt="{{ $item->product->name }}" class="rounded" style="width: 70px; height: 70px; object-fit: cover; border: 2px solid #1a1a1a;">
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <div class="fw-bolder text-dark" style="font-size: 1.05rem;">{{ $item->product->name }}</div>
                                    @if($item->variant)
                                        <small class="text-secondary fw-bold">Phân loại: {{ isset($item->variant->color) ? ($colorNames[strtoupper($item->variant->color)] ?? $item->variant->color) : '' }} / {{ $item->variant->size }}</small>
                                    @endif
                                </div>
                                <div class="text-end">
                                    <div class="fw-bolder text-danger" style="font-size: 1.05rem;">{{ number_format(($item->product->discount_percent > 0 ? $item->product->sale_price : $item->product->price) * $item->quantity, 0, ',', '.') }}đ</div>
                                    <small class="text-secondary fw-bold">SL: x{{ $item->quantity }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-top border-dark border-2 pt-4 mt-3">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-secondary fw-bold">Tạm tính ({{ $cartItems->sum('quantity') }} sản phẩm)</span>
                            <span class="fw-bolder text-dark">{{ number_format($total, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="text-secondary fw-bold">Phí vận chuyển</span>
                            <span class="fw-bolder text-success">Miễn phí</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-top border-dark border-2 pt-4">
                            <h4 class="fw-bolder m-0 text-dark">Thành tiền</h4>
                            <h3 class="fw-bolder m-0 text-danger">{{ number_format($total, 0, ',', '.') }}đ</h3>
                        </div>
                    </div>
                    
                    <small class="d-block text-secondary mt-4 mb-4" style="font-size: 0.75rem; font-style: italic;">*Đã bao gồm VAT. Phí vận chuyển có thể thay đổi ở bước thanh toán.</small>

                    <button type="submit" class="btn w-100 neo-btn-black">TIẾN HÀNH THANH TOÁN</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('form-thanh-toan').addEventListener('submit', function() {
        let btn = document.querySelector('.neo-btn-black');
        btn.innerText = 'ĐANG XỬ LÝ...';
        btn.style.pointerEvents = 'none';
    });
</script>
@endpush