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
                    
                    @if($addresses->count() > 0)
                    {{-- Chọn địa chỉ đã lưu --}}
                    <div class="mb-4">
                        <label class="fw-bold text-secondary mb-2" style="font-size: 0.85rem;">CHỌN ĐỊA CHỈ GIAO HÀNG</label>
                        <select class="form-select rounded-3 py-2 border" id="address-selector" onchange="fillAddress(this)">
                            @foreach($addresses as $addr)
                            <option value="{{ $addr->id }}"
                                    data-name="{{ $addr->receiver_name }}"
                                    data-phone="{{ $addr->phone_number }}"
                                    data-address="{{ $addr->receiver_address }}"
                                    data-note="{{ $addr->note }}"
                                    {{ $addr->is_default ? 'selected' : '' }}>
                                {{ $addr->receiver_name }} — {{ $addr->receiver_address }}
                                {{ $addr->is_default ? '(Mặc định)' : '' }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="p-3 rounded-3 mb-4" id="selected-address-card" style="background: #f8fffe; border: 1.5px solid #4ECDC4;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-geo-alt-fill" style="color: #4ECDC4;"></i>
                            <span class="fw-bold" id="display-name"></span>
                            <span class="text-muted">|</span>
                            <span class="text-muted" id="display-phone"></span>
                        </div>
                        <p class="mb-0 text-muted" style="font-size: 0.9rem;" id="display-address"></p>
                    </div>

                    <input type="hidden" name="fullname" id="input-fullname">
                    <input type="hidden" name="customer_phone" id="input-phone">
                    <input type="hidden" name="shipping_address" id="input-address">

                    <div class="mb-4">
                        <label class="fw-bold text-secondary mb-2" style="font-size: 0.85rem;">EMAIL</label>
                        <input type="email" class="form-control rounded-3 py-2 border" name="email" required>
                    </div>

                    <div class="text-end mb-3">
                        <a href="{{ route('user.addresses') }}" class="text-decoration-none small fw-semibold" style="color: #FF6B6B;">
                            <i class="bi bi-plus-circle me-1"></i>Quản lý sổ địa chỉ
                        </a>
                    </div>
                    @else
                    {{-- Chưa có địa chỉ → nhập thủ công --}}
                    <div class="alert border rounded-3 mb-4 d-flex align-items-center gap-2" style="background: #fff9f0; border-color: #ffe0b2 !important;">
                        <i class="bi bi-info-circle text-warning"></i>
                        <span style="font-size:0.85rem;">Bạn chưa lưu địa chỉ nào. <a href="{{ route('user.addresses') }}" class="fw-bold" style="color:#FF6B6B;">Thêm địa chỉ</a> để checkout nhanh hơn!</span>
                    </div>
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
                    @endif

                    <div class="mb-4">
                        <label class="fw-bold text-secondary mb-2" style="font-size: 0.85rem;">GHI CHÚ ĐƠN HÀNG</label>
                        <textarea class="form-control rounded-3 py-2 border" name="notes" rows="3" id="input-notes"></textarea>
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
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="fw-bold text-dark mb-1" style="font-size: 0.95rem; line-height: 1.3;">{{ $item->product->name }}</div>
                                    @if($item->variant)
                                        @php
                                            $hexCode = strtoupper($item->variant->color);
                                            $colorName = $colorNames[$hexCode] ?? 'Màu';
                                        @endphp
                                        <div class="text-secondary mb-1 d-flex align-items-center" style="font-size: 0.85rem;">
                                            <span class="d-inline-block rounded-circle border shadow-sm" style="width:14px; height:14px; background-color:{{ $hexCode }}; margin-right:6px;"></span>
                                            <span>{{ $colorName }} ({{ $hexCode }})</span>
                                            <span class="mx-2 text-light">|</span> 
                                            <span>Size: {{ $item->variant->size }}</span>
                                        </div>
                                    @endif
                                    <div class="text-secondary fw-semibold mt-1" style="font-size: 0.85rem;">Số lượng: {{ $item->quantity }}</div>
                                </div>
                                <div class="text-end ms-2">
                                    <div class="fw-bold text-danger" style="font-size: 0.95rem;">{{ number_format(($item->product->discount_percent > 0 ? $item->product->sale_price : $item->product->price) * $item->quantity, 0, ',', '.') }}đ</div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mb-4 pb-4 border-bottom">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="fw-bold text-secondary mb-0" style="font-size: 0.85rem;">MÃ GIẢM GIÁ</label>
                            <button type="button" class="btn btn-sm btn-link text-decoration-none text-primary fw-semibold p-0" data-bs-toggle="modal" data-bs-target="#voucherModal">
                                <i class="bi bi-tags me-1"></i>Chọn Voucher
                            </button>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <input type="text" id="voucher-input" class="form-control rounded-3 border text-uppercase py-2" placeholder="Nhập hoặc chọn mã giảm giá" value="{{ request('voucher_code') }}">
                            <button type="button" class="btn text-white fw-bold px-4 rounded-3 shadow-sm flex-shrink-0" style="background-color: #4ECDC4;" onclick="applyDiscount()">ÁP DỤNG</button>
                        </div>
                        
                        @if(session('voucher_error'))
                            <div class="text-danger mt-2 fw-bold" style="font-size: 0.85rem;"><i class="bi bi-exclamation-circle me-1"></i>{{ session('voucher_error') }}</div>
                        @endif
                        @if($voucher)
                            <div class="text-success mt-2 fw-bold" style="font-size: 0.85rem;">
                                <i class="bi bi-check-circle me-1"></i>Đã áp dụng mã: {{ $voucher->code }}
                                @if($voucher->discountType === 'percent')
                                    (-{{ (float)$voucher->discountValue }}%, giảm {{ number_format($discountAmount, 0, ',', '.') }}đ)
                                @else
                                    (-{{ number_format($discountAmount, 0, ',', '.') }}đ)
                                @endif
                                <a href="javascript:void(0)" onclick="removeVoucher()" class="text-danger ms-2"><i class="bi bi-x-circle"></i> Bỏ</a>
                            </div>
                        @endif
                    </div>

                    {{-- SỬ DỤNG ĐIỂM TÍCH LŨY --}}
                    @if($userPoints > 0)
                    <div class="mb-4 pb-4 border-bottom">
                        <label class="fw-bold text-secondary mb-2" style="font-size: 0.85rem;">
                            <i class="bi bi-star-fill text-warning me-1"></i>SỬ DỤNG ĐIỂM TÍCH LŨY
                        </label>
                        
                        <div class="card border-warning mb-3 shadow-sm rounded-3">
                            <div class="card-body bg-warning bg-opacity-10 py-2 px-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-secondary fw-medium" style="font-size:0.9rem;">Điểm hiện có:</span>
                                    <span class="fw-bold text-warning" style="font-size:1.1rem;">{{ number_format($userPoints) }} điểm</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <span class="text-secondary fw-medium" style="font-size:0.9rem;">Tương đương:</span>
                                    <span class="fw-bold text-dark" style="font-size:1rem;">{{ number_format($userPoints * 1000, 0, ',', '.') }}đ</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <input type="number" id="points-input" class="form-control rounded-3 border py-2" placeholder="Nhập số điểm muốn dùng" min="0" max="{{ $userPoints }}" value="{{ $pointsUsed > 0 ? $pointsUsed : '' }}">
                            <button type="button" class="btn text-white fw-bold px-4 rounded-3 shadow-sm flex-shrink-0" style="background-color: #f59e0b;" onclick="applyDiscount()">DÙNG</button>
                        </div>
                        
                        <small class="text-muted d-block mt-2" style="font-size: 0.8rem;">1 điểm = 1.000đ. Áp dụng sau voucher.</small>

                        @if($pointsUsed > 0)
                            <div class="text-success mt-2 fw-bold" style="font-size: 0.85rem;">
                                <i class="bi bi-check-circle me-1"></i>Đã dùng {{ number_format($pointsUsed) }} điểm (giảm {{ number_format($pointsDiscount, 0, ',', '.') }}đ)
                                <a href="javascript:void(0)" onclick="removePoints()" class="text-danger ms-2"><i class="bi bi-x-circle"></i> Bỏ</a>
                            </div>
                        @endif
                    </div>
                    @endif

                    <input type="hidden" name="points_used" value="{{ $pointsUsed }}">

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
                        @if($pointsDiscount > 0)
                            <div class="d-flex justify-content-between mb-3 text-secondary fw-medium">
                                <span><i class="bi bi-star-fill text-warning me-1"></i>Điểm tích lũy ({{ number_format($pointsUsed) }} điểm)</span>
                                <span class="fw-bold text-danger">-{{ number_format($pointsDiscount, 0, ',', '.') }}đ</span>
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

<!-- Modal Chọn Voucher -->
<div class="modal fade" id="voucherModal" tabindex="-1" aria-labelledby="voucherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="voucherModalLabel"><i class="bi bi-ticket-perforated text-primary me-2"></i>Chọn mã giảm giá</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-3 pb-4">
                @if(isset($availableVouchers) && $availableVouchers->count() > 0)
                    <div class="d-flex flex-column gap-3">
                        @foreach($availableVouchers as $v)
                            @php
                                $isValid = $subtotal >= $v->minOrderValue;
                            @endphp
                            <div class="card border {{ $isValid ? 'border-primary shadow-sm' : 'border-light bg-light opacity-75' }} rounded-3 position-relative overflow-hidden">
                                <div class="row g-0">
                                    <div class="col-4 text-white d-flex flex-column justify-content-center align-items-center p-2 text-center" style="background: {{ $isValid ? 'linear-gradient(135deg, #FF6B6B, #4ECDC4)' : '#a8b3b9' }}; border-right: 2px dashed rgba(255,255,255,0.5);">
                                        <span class="fs-4 fw-bold">
                                            @if($v->discountType === 'percent')
                                                {{ (float)$v->discountValue }}%
                                            @else
                                                {{ number_format($v->discountValue/1000, 0, ',', '.') }}K
                                            @endif
                                        </span>
                                        <span class="small opacity-75 text-uppercase fw-semibold" style="font-size: 0.7rem;">Giảm giá</span>
                                    </div>
                                    <div class="col-8 p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <span class="badge bg-light text-dark border fw-bold font-monospace">{{ $v->code }}</span>
                                            @if($isValid)
                                                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 py-1 fw-bold shadow-sm" onclick="selectVoucher('{{ $v->code }}')">DÙNG</button>
                                            @endif
                                        </div>
                                        <div class="small fw-medium text-dark mt-2 mb-1">
                                            Đơn tối thiểu {{ number_format($v->minOrderValue, 0, ',', '.') }}đ
                                            @if($v->discountType === 'percent' && $v->maxDiscountAmount)
                                                <br><span class="text-secondary" style="font-size: 0.8rem;">(Tối đa {{ number_format($v->maxDiscountAmount, 0, ',', '.') }}đ)</span>
                                            @endif
                                        </div>
                                        <div class="small text-muted" style="font-size: 0.75rem;">
                                            HSD: {{ \Carbon\Carbon::parse($v->endDate)->format('d/m/Y') }}
                                        </div>
                                        @if(!$isValid)
                                            <div class="small text-danger mt-1 fw-medium" style="font-size: 0.75rem;">
                                                Mua thêm {{ number_format($v->minOrderValue - $subtotal, 0, ',', '.') }}đ để áp dụng
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <!-- Dấu cắt khoét 2 bên -->
                                <div style="position:absolute; width:16px; height:16px; background:#fff; border-radius:50%; top:50%; left:-8px; transform:translateY(-50%); z-index:2;"></div>
                                <div style="position:absolute; width:16px; height:16px; background:#fff; border-radius:50%; top:50%; left:33.333%; transform:translate(-50%, -50%); z-index:2; border-right:1px solid #dee2e6;"></div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-ticket text-light" style="font-size: 3rem;"></i>
                        <p class="mt-2 mb-0 fw-medium">Không có mã giảm giá nào phù hợp</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-fill address from selector
    function fillAddress(sel) {
        const opt = sel.options[sel.selectedIndex];
        document.getElementById('display-name').textContent = opt.dataset.name;
        document.getElementById('display-phone').textContent = opt.dataset.phone;
        document.getElementById('display-address').textContent = opt.dataset.address;
        document.getElementById('input-fullname').value = opt.dataset.name;
        document.getElementById('input-phone').value = opt.dataset.phone;
        document.getElementById('input-address').value = opt.dataset.address;
        // Pre-fill note if address has one
        const notesInput = document.getElementById('input-notes');
        if (opt.dataset.note && !notesInput.value) {
            notesInput.value = opt.dataset.note;
        }
    }

    // Auto-fill default address on page load
    document.addEventListener('DOMContentLoaded', function() {
        const selector = document.getElementById('address-selector');
        if (selector) {
            fillAddress(selector);
        }
    });

    // Prevent double submit
    document.getElementById('form-thanh-toan').addEventListener('submit', function() {
        let btn = document.querySelector('button[type="submit"]');
        btn.innerText = 'ĐANG XỬ LÝ...';
        btn.style.pointerEvents = 'none';
        btn.style.opacity = '0.7';
    });

    function applyDiscount() {
        let code = document.getElementById('voucher-input').value.trim();
        let pointsEl = document.getElementById('points-input');
        let points = pointsEl ? pointsEl.value.trim() : '';
        let params = [];
        if (code) params.push('voucher_code=' + encodeURIComponent(code));
        if (points && parseInt(points) > 0) params.push('use_points=' + encodeURIComponent(points));
        let url = "{{ route('checkout') }}";
        if (params.length > 0) url += '?' + params.join('&');
        window.location.href = url;
    }

    function selectVoucher(code) {
        document.getElementById('voucher-input').value = code;
        var myModalEl = document.getElementById('voucherModal');
        var modal = bootstrap.Modal.getInstance(myModalEl);
        if(modal) modal.hide();
        applyDiscount();
    }

    function removeVoucher() {
        document.getElementById('voucher-input').value = '';
        applyDiscount();
    }

    function removePoints() {
        document.getElementById('points-input').value = '';
        applyDiscount();
    }
</script>
@endpush