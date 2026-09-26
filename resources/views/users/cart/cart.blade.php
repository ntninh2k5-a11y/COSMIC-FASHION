@extends('layouts.app')

@section('title', 'Giỏ Hàng - Cosmic Fashion')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/cart.css') }}">
@endpush

@section('content')
<main class="py-5 bg-light" style="min-height: calc(100vh - 200px);">
    <div class="container">
        <div class="khung-tieu-de-gio-hang mb-5 border-bottom pb-3">
            <h2 class="tieu-de-chinh-gio-hang text-uppercase fw-bold mb-2" style="letter-spacing: -0.5px; color: #2D3436;">Giỏ Hàng Của Bạn</h2>
            <p class="tieu-de-phu-gio-hang text-secondary m-0 fw-bold">
                Bạn đang có {{ $cartItems->sum('quantity') }} sản phẩm trong giỏ hàng
            </p>
        </div>

        <div class="row">
            <div class="col-md-8 mb-5 mb-md-0">
                <div class="danh-sach-sp-gio bg-white shadow-sm rounded-4 p-0 overflow-hidden border">
                    @if($cartItems->count() > 0)
                        @foreach($cartItems as $item)
                            <div class="o-sp-gio d-flex align-items-center p-3 p-md-4 {{ !$loop->last ? 'border-bottom' : '' }} bg-white" 
                                 data-id="{{ $item->id }}" 
                                 data-price="{{ $item->product->discount_percent > 0 ? $item->product->sale_price : $item->product->price }}">
                                 
                                <img src="{{ asset($item->product->image_url ?? 'images/default.jpg') }}"
                                     alt="{{ $item->product->name }}"
                                     class="anh-sp-gio border rounded-3 shadow-sm"
                                     style="width: 100px; height: 100px; object-fit: cover;">

                                <div class="thong-tin-sp-gio flex-grow-1 px-3 px-md-4">
                                    <a href="{{ route('product.detail', $item->product_id) }}" class="text-dark text-decoration-none">
                                        <h4 class="ten-sp-gio m-0 fs-5 mb-2 fw-bold text-truncate" style="max-width: 250px;">
                                            {{ $item->product->name }}
                                        </h4>
                                    </a>

                                    @if($item->variant)
                                        <div class="phan-loai-sp-gio text-secondary mt-1 fw-medium" style="font-size: 0.9rem;">
                                            Phân loại:
                                            <span class="text-dark fw-bold">{{ isset($item->variant->color) ? ($colorNames[strtoupper($item->variant->color)] ?? $item->variant->color) : '' }}</span>
                                            /
                                            <span class="text-dark fw-bold">{{ $item->variant->size ?? '' }}</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="dieu-chinh-sp-gio d-flex align-items-center justify-content-end">
                                    <div class="tang-giam-so-luong d-flex align-items-center me-3 me-md-4 border rounded-3 shadow-sm">
                                        <button type="button"
                                                onclick="capNhatSoLuong({{ $item->id }}, {{ $item->quantity - 1 }})"
                                                class="nut-tang-giam border-0 border-end fs-5"
                                                {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                            -
                                        </button>

                                        <span class="so-luong-hien-thi fw-bold text-dark fs-6">
                                            {{ $item->quantity }}
                                        </span>

                                        <button type="button"
                                                onclick="capNhatSoLuong({{ $item->id }}, {{ $item->quantity + 1 }})"
                                                class="nut-tang-giam border-0 border-start fs-5">
                                            +
                                        </button>
                                    </div>

                                    <div class="gia-sp-gio fw-bolder me-4 d-none d-md-block text-danger fs-5" style="min-width: 130px; text-align: right;">
                                        {{ number_format(($item->product->discount_percent > 0 ? $item->product->sale_price : $item->product->price) * $item->quantity, 0, ',', '.') }}đ
                                    </div>

                                    <button type="button"
                                            onclick="xoaSanPham({{ $item->id }})"
                                            class="nut-xoa-sp btn btn-outline-danger border-0 rounded-3 shadow-sm p-0"
                                            title="Xóa sản phẩm">
                                        <i class="bi bi-trash" style="font-size: 1rem;"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div id="gio-hang-trong" class="text-center py-5 bg-white">
                            <h5 class="text-secondary fw-bold mb-4">
                                Giỏ hàng của bạn đang trống
                            </h5>
                            <a href="{{ route('home') }}" class="neo-btn d-inline-block">
                                TIẾP TỤC MUA SẮM
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-md-4">
                <div class="khung-tong-ket p-4 sticky-md-top bg-white shadow-sm rounded-4 border" style="top: 20px;">
                    <h3 class="tieu-de-tong-ket text-uppercase mb-4 fs-5 fw-bold border-bottom pb-3" style="letter-spacing: -0.5px; color: #2D3436;">
                        Tổng Đơn Hàng
                    </h3>

                    <div class="dong-tong-ket d-flex justify-content-between mb-3 text-secondary fw-medium">
                        <span class="chu-tong-ket">
                            Tạm tính ({{ $cartItems->sum('quantity') }} sản phẩm)
                        </span>
                        <span class="so-tong-ket fw-bold text-dark">
                            {{ number_format($total, 0, ',', '.') }}đ
                        </span>
                    </div>

                    <div class="dong-tong-ket d-flex justify-content-between mb-3 border-bottom pb-3 text-secondary fw-medium">
                        <span class="chu-tong-ket">
                            Phí vận chuyển
                        </span>
                        <span class="so-tong-ket text-success fw-bold">
                            Miễn phí
                        </span>
                    </div>

                    <div class="dong-tong-ket d-flex justify-content-between mb-4 mt-3">
                        <span class="chu-tong-ket fw-bold text-uppercase" style="font-size: 16px;">
                            Thành tiền
                        </span>
                        <span class="so-tong-ket text-danger fw-bolder" style="font-size: 24px;">
                            {{ number_format($total, 0, ',', '.') }}đ
                        </span>
                    </div>

                    <p class="ghi-chu-thue text-secondary small mb-4 fw-medium fst-italic">
                        *Đã bao gồm VAT. Phí vận chuyển có thể thay đổi ở bước thanh toán.
                    </p>

                    <button id="btn-thanh-toan"
                            class="btn w-100 text-white fw-bold py-2 rounded-3 shadow-sm" style="background-color: #FF6B6B;"
                            {{ $cartItems->isEmpty() ? 'disabled' : '' }}>
                        TIẾN HÀNH THANH TOÁN
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btnThanhToan = document.getElementById('btn-thanh-toan');
        if(btnThanhToan) {
            btnThanhToan.addEventListener('click', function () {
                window.location.href = "{{ route('checkout') }}";
            });
        }
    });

    function capNhatGiaoDienGioHang() {
        let tongTien = 0;
        let tongSoLuong = 0;
        const rows = document.querySelectorAll('.o-sp-gio');

        rows.forEach(row => {
            const price = parseInt(row.getAttribute('data-price'));
            const qty = parseInt(row.querySelector('.so-luong-hien-thi').innerText);
            const itemTotal = price * qty;

            tongTien += itemTotal;
            tongSoLuong += qty;

            row.querySelector('.gia-sp-gio').innerText = new Intl.NumberFormat('vi-VN').format(itemTotal) + 'đ';
        });

        if (rows.length === 0) {
            location.reload(); 
            return;
        }

        document.querySelectorAll('.so-tong-ket').forEach(el => {
            if (el.innerText.trim() !== 'Miễn phí') {
                el.innerText = new Intl.NumberFormat('vi-VN').format(tongTien) + 'đ';
            }
        });

        const phuDe = document.querySelector('.tieu-de-phu-gio-hang');
        if (phuDe) {
            phuDe.innerText = `Bạn đang có ${tongSoLuong} sản phẩm trong giỏ hàng`;
        }

        const chuTamTinh = document.querySelector('.dong-tong-ket .chu-tong-ket');
        if (chuTamTinh && chuTamTinh.innerText.includes('Tạm tính')) {
            chuTamTinh.innerText = `Tạm tính (${tongSoLuong} sản phẩm)`;
        }

        window.dispatchEvent(new Event('cartUpdated'));
    }

    window.capNhatSoLuong = function (id, quantity) {
        if (quantity < 1) {
            return;
        }

        fetch(`/gio-hang/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const itemRow = document.querySelector(`.o-sp-gio[data-id="${id}"]`);
                if (itemRow) {
                    itemRow.querySelector('.so-luong-hien-thi').innerText = quantity;

                    const btns = itemRow.querySelectorAll('.nut-tang-giam');
                    btns[0].setAttribute('onclick', `capNhatSoLuong(${id}, ${quantity - 1})`);
                    btns[1].setAttribute('onclick', `capNhatSoLuong(${id}, ${quantity + 1})`);

                    if (quantity <= 1) {
                        btns[0].setAttribute('disabled', 'disabled');
                    } else {
                        btns[0].removeAttribute('disabled');
                    }
                }
                capNhatGiaoDienGioHang();
            } else {
                showGlobalToast(data.message || 'Không thể cập nhật giỏ hàng', 'error');
            }
        })
        .catch(error => {
            showGlobalToast('Có lỗi xảy ra khi cập nhật giỏ hàng', 'error');
        });
    };

    window.xoaSanPham = function (id) {
        showConfirmToast('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?', function() {
            fetch(`/gio-hang/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const itemRow = document.querySelector(`.o-sp-gio[data-id="${id}"]`);
                    if(itemRow) itemRow.remove();
                    capNhatGiaoDienGioHang();
                } else {
                    showGlobalToast(data.message || 'Không thể xóa sản phẩm', 'error');
                }
            })
            .catch(error => {
                showGlobalToast('Có lỗi xảy ra khi xóa sản phẩm', 'error');
            });
        });
    };
</script>
@endpush
