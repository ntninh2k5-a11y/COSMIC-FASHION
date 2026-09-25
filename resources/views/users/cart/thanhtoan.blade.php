@extends('layouts.app')

@section('title', 'Thanh toán - Cosmic Fashion')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/thanhtoan.css') }}">
@endpush

@section('content')
<div class="container py-5">
    <div class="mb-5 text-center">
        <h2 class="fw-bold text-uppercase" style="letter-spacing: -0.5px; color: #2D3436;">THANH TOÁN ĐƠN HÀNG</h2>
        <p class="text-secondary fw-medium">Vui lòng kiểm tra và điền đầy đủ thông tin giao hàng</p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger rounded-3 border-0 shadow-sm mb-4">{{ session('error') }}</div>
    @endif

    <form id="form-thanh-toan" action="{{ route('order.store') }}" method="POST">
        @csrf
        
        <input type="hidden" name="subtotal_amount" value="{{ $subtotal }}">
        <input type="hidden" name="voucher_code" value="{{ $voucher ? $voucher->code : '' }}">
        
        @foreach($cartItems as $index => $item)
            <input type="hidden" name="cart_items[{{ $index }}][product_id]" value="{{ $item->product_id }}">
            <input type="hidden" name="cart_items[{{ $index }}][variant_id]" value="{{ $item->variant_id }}">
            <input type="hidden" name="cart_items[{{ $index }}][quantity]" value="{{ $item->quantity }}">
            <input type="hidden" name="cart_items[{{ $index }}][price]" value="{{ $item->product->discount_percent > 0 ? $item->product->sale_price : $item->product->price }}">
        @endforeach

        <div class="row g-4">
            <div class="col-md-7">
                <div class="bg-white p-4 shadow-sm rounded-4 border mb-4">
                    <h4 class="fw-bold text-uppercase mb-4 fs-5 border-bottom pb-3" style="letter-spacing: -0.5px; color: #2D3436;">THÔNG TIN GIAO HÀNG</h4>
                    
                    <div class="mb-4">
                        <label class="fw-bold text-secondary mb-2" style="font-size: 0.85rem;">HỌ VÀ TÊN</label>
                        <input type="text" class="form-control rounded-3 py-2 border" name="fullname" value="{{ session('user_name') }}" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="fw-bold text-secondary mb-2" style="font-size: 0.85rem;">SỐ ĐIỆN THOẠI</label>
                            <input type="text" class="form-control rounded-3 py-2 border" name="customer_phone" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="fw-bold text-secondary mb-2" style="font-size: 0.85rem;">EMAIL</label>
                            <input type="email" class="form-control rounded-3 py-2 border" name="email" required>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="fw-bold text-secondary mb-2" style="font-size: 0.85rem;">ĐỊA CHỈ NHẬN HÀNG</label>
                        <input type="text" class="form-control rounded-3 py-2 border" name="shipping_address" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="fw-bold text-secondary mb-2" style="font-size: 0.85rem;">GHI CHÚ ĐƠN HÀNG</label>
                        <textarea class="form-control rounded-3 py-2 border" name="notes" rows="3"></textarea>
                    </div>
                    
                    <h4 class="fw-bold text-uppercase mb-4 fs-5 border-bottom pb-3 mt-5" style="letter-spacing: -0.5px; color: #2D3436;">PHƯƠNG THỨC THANH TOÁN</h4>
                    <div class="form-check p-3 border rounded-3 d-flex align-items-center mb-2" style="background-color: #FAFAFA; transition: all 0.2s;">
                        <input class="form-check-input ms-0 me-3 mt-0" type="radio" name="payment_method" id="bank" value="bank" checked style="width: 18px; height: 18px; accent-color: #FF6B6B;">
                        <label class="form-check-label fw-bold text-dark w-100" for="bank" style="cursor: pointer;">
                            <i class="bi bi-bank me-2 text-primary" style="font-size: 1.2rem;"></i> Chuyển khoản ngân hàng
                        </label>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="bg-white p-4 shadow-sm rounded-4 border sticky-md-top" style="top: 20px;">
                    <h4 class="fw-bold text-uppercase mb-4 fs-5 border-bottom pb-3" style="letter-spacing: -0.5px; color: #2D3436;">TỔNG ĐƠN HÀNG</h4>
                    
                    <div style="max-height: 350px; overflow-y: auto; overflow-x: hidden; padding-right: 10px;" class="mb-4">
                        @foreach($cartItems as $item)
                            <div class="d-flex align-items-center mb-4 pb-3 border-bottom border-light">
                                <div class="position-relative">
                                    <img src="{{ asset($item->product->image_url ?? 'images/default.jpg') }}" alt="{{ $item->product->name }}" class="rounded-3 shadow-sm border" style="width: 65px; height: 65px; object-fit: cover;">
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background-color: #FF6B6B; font-size: 0.7rem;">
                                        {{ $item->quantity }}
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="fw-bold text-dark mb-1" style="font-size: 0.95rem; line-height: 1.3;">{{ $item->product->name }}</div>
                                    @if($item->variant)
                                        <small class="text-secondary d-block">{{ isset($item->variant->color) ? ($colorNames[strtoupper($item->variant->color)] ?? $item->variant->color) : '' }} / {{ $item->variant->size }}</small>
                                    @endif
                                </div>
                                <div class="text-end ms-2">
                                    <div class="fw-bold text-danger" style="font-size: 0.95rem;">{{ number_format(($item->product->discount_percent > 0 ? $item->product->sale_price : $item->product->price) * $item->quantity, 0, ',', '.') }}đ</div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mb-4 pb-4 border-bottom">
                        <label class="fw-bold text-secondary mb-2" style="font-size: 0.85rem;">MÃ GIẢM GIÁ</label>
                        <div class="d-flex gap-2">
                            <input type="text" id="voucher-input" class="form-control rounded-3 border text-uppercase py-2" placeholder="Nhập mã giảm giá" value="{{ request('voucher_code') }}">
                            <button type="button" class="btn text-white fw-bold px-4 rounded-3 shadow-sm" style="background-color: #4ECDC4;" onclick="applyVoucher()">ÁP DỤNG</button>
                        </div>
                        @if(session('voucher_error'))
                            <div class="text-danger mt-2 fw-bold" style="font-size: 0.85rem;"><i class="bi bi-exclamation-circle me-1"></i>{{ session('voucher_error') }}</div>
                        @endif
                        @if($voucher)
                            <div class="text-success mt-2 fw-bold" style="font-size: 0.85rem;"><i class="bi bi-check-circle me-1"></i>Đã áp dụng mã: {{ $voucher->code }} (-{{ number_format($discountAmount, 0, ',', '.') }}đ)</div>
                        @endif
                    </div>

                    <div>
                        <div class="d-flex justify-content-between mb-3 text-secondary fw-medium">
                            <span>Tạm tính ({{ $cartItems->sum('quantity') }} sản phẩm)</span>
                            <span class="fw-bold text-dark">{{ number_format($subtotal, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 text-secondary fw-medium">
                            <span>Phí vận chuyển</span>
                            <span class="fw-bold text-success">Miễn phí</span>
                        </div>
                        @if($discountAmount > 0)
                            <div class="d-flex justify-content-between mb-3 text-secondary fw-medium">
                                <span>Giảm giá (Voucher)</span>
                                <span class="fw-bold text-danger">-{{ number_format($discountAmount, 0, ',', '.') }}đ</span>
                            </div>
                        @endif
                        
                        <div class="d-flex justify-content-between align-items-center border-top pt-4 mt-4 mb-4">
                            <span class="fw-bold text-uppercase" style="font-size: 1rem;">Thành tiền</span>
                            <span class="fw-bolder text-danger" style="font-size: 1.5rem;">{{ number_format($total, 0, ',', '.') }}đ</span>
                        </div>
                    </div>
                    
                    <small class="d-block text-secondary mt-3 mb-4 fw-medium fst-italic" style="font-size: 0.8rem;">*Đã bao gồm VAT. Phí vận chuyển có thể thay đổi ở bước thanh toán.</small>

                    <button type="submit" class="btn w-100 text-white fw-bold py-3 rounded-3 shadow-sm fs-6" style="background-color: #FF6B6B; letter-spacing: 0.5px; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(255,107,107,0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 10px rgba(0,0,0,0.05)';">TIẾN HÀNH THANH TOÁN</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('form-thanh-toan').addEventListener('submit', function() {
        let btn = document.querySelector('button[type="submit"]');
        btn.innerText = 'ĐANG XỬ LÝ...';
        btn.style.pointerEvents = 'none';
        btn.style.opacity = '0.7';
    });

    function applyVoucher() {
        let code = document.getElementById('voucher-input').value.trim();
        if(code) {
            window.location.href = "{{ route('checkout') }}?voucher_code=" + encodeURIComponent(code);
        } else {
            window.location.href = "{{ route('checkout') }}";
        }
    }
</script>
@endpush