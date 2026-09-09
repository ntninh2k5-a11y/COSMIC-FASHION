@extends('layouts.app')

@section('title', 'Giỏ Hàng - Cosmic Fashion')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
    <style>
        .tang-giam-so-luong {
            height: 38px;
            overflow: hidden;
        }
        .nut-tang-giam {
            width: 36px;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: all 0.2s ease;
            outline: none;
        }
        .nut-tang-giam:hover:not([disabled]) {
            background-color: #f1f3f5 !important;
            color: #1a1a1a !important;
        }
        .nut-tang-giam[disabled] {
            opacity: 0.4;
            cursor: not-allowed;
        }
        .so-luong-hien-thi {
            width: 45px;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 0.95rem;
        }
        .nut-xoa-sp {
            transition: transform 0.2s ease, opacity 0.2s;
            opacity: 0.7;
        }
        .nut-xoa-sp:hover {
            transform: scale(1.15);
            opacity: 1;
        }
    </style>
@endpush

@section('content')
<main class="py-5">
    <div class="container">
        <div class="khung-tieu-de-gio-hang mb-5">
            <h2 class="tieu-de-chinh-gio-hang text-uppercase">Giỏ Hàng Của Bạn</h2>
            <p class="tieu-de-phu-gio-hang text-secondary">
                Bạn đang có {{ $cartItems->sum('quantity') }} sản phẩm trong giỏ hàng
            </p>
        </div>

        <div class="row">
            <div class="col-md-8 mb-5 mb-md-0">
                <div class="danh-sach-sp-gio">
                    @if($cartItems->count() > 0)
                        @foreach($cartItems as $item)
                            <div class="o-sp-gio d-flex align-items-center py-3 border-bottom" 
                                 data-id="{{ $item->id }}" 
                                 data-price="{{ $item->product->discount_percent > 0 ? $item->product->sale_price : $item->product->price }}">
                                 
                                <img src="{{ asset($item->product->image_url ?? 'images/default.jpg') }}"
                                     alt="{{ $item->product->name }}"
                                     class="anh-sp-gio border rounded"
                                     style="width: 100px; height: 100px; object-fit: cover;">

                                <div class="thong-tin-sp-gio flex-grow-1 px-3 px-md-4">
                                    <a href="{{ route('product.detail', $item->product_id) }}" class="text-dark text-decoration-none">
                                        <h4 class="ten-sp-gio m-0 fs-5 mb-1">
                                            {{ $item->product->name }}
                                        </h4>
                                    </a>

                                    @if($item->variant)
                                        <div class="phan-loai-sp-gio text-secondary mt-1" style="font-size: 0.9rem;">
                                            Phân loại:
                                            <strong>{{ isset($item->variant->color) ? ($colorNames[strtoupper($item->variant->color)] ?? $item->variant->color) : '' }}</strong>
                                            /
                                            <strong>{{ $item->variant->size ?? '' }}</strong>
                                        </div>
                                    @endif
                                </div>

                                <div class="dieu-chinh-sp-gio d-flex align-items-center justify-content-end">
                                    <div class="tang-giam-so-luong d-flex align-items-center me-3 me-md-5 border rounded bg-white">
                                        <button type="button"
                                                onclick="capNhatSoLuong({{ $item->id }}, {{ $item->quantity - 1 }})"
                                                class="nut-tang-giam border-0 bg-transparent fs-5 text-secondary"
                                                {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                            -
                                        </button>

                                        <span class="so-luong-hien-thi fw-bold border-start border-end">
                                            {{ $item->quantity }}
                                        </span>

                                        <button type="button"
                                                onclick="capNhatSoLuong({{ $item->id }}, {{ $item->quantity + 1 }})"
                                                class="nut-tang-giam border-0 bg-transparent fs-5 text-secondary">
                                            +
                                        </button>
                                    </div>

                                    <div class="gia-sp-gio fw-bold me-4 d-none d-md-block text-danger fs-5" style="min-width: 120px; text-align: right;">
                                        {{ number_format(($item->product->discount_percent > 0 ? $item->product->sale_price : $item->product->price) * $item->quantity, 0, ',', '.') }}đ
                                    </div>

                                    <button type="button"
                                            onclick="xoaSanPham({{ $item->id }})"
                                            class="nut-xoa-sp text-secondary border-0 bg-transparent fs-5"
                                            title="Xóa sản phẩm">
                                        <img src="{{ asset('img_react/trash3.svg') }}" alt="Xóa" style="width: 20px;">
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div id="gio-hang-trong" class="text-center py-5 bg-light rounded mt-3 border">
                            <h5 class="text-secondary mb-4">
                                Giỏ hàng của bạn đang trống
                            </h5>
                            <a href="{{ route('home') }}" class="btn btn-dark px-4 py-2 text-uppercase fw-bold rounded-0">
                                Tiếp tục mua sắm
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-md-4">
                <div class="khung-tong-ket p-4 sticky-md-top border rounded-3 bg-light shadow-sm" style="top: 20px;">
                    <h3 class="tieu-de-tong-ket text-uppercase mb-4 fs-5 fw-bold border-bottom pb-3">
                        Tổng Đơn Hàng
                    </h3>

                    <div class="dong-tong-ket d-flex justify-content-between mb-3 text-secondary">
                        <span class="chu-tong-ket">
                            Tạm tính ({{ $cartItems->sum('quantity') }} sản phẩm)
                        </span>
                        <span class="so-tong-ket fw-bold">
                            {{ number_format($total, 0, ',', '.') }}đ
                        </span>
                    </div>

                    <div class="dong-tong-ket d-flex justify-content-between mb-3 border-bottom pb-3 text-secondary">
                        <span class="chu-tong-ket">
                            Phí vận chuyển
                        </span>
                        <span class="so-tong-ket text-success fw-bold">
                            Miễn phí
                        </span>
                    </div>

                    <div class="dong-tong-ket d-flex justify-content-between mb-4 mt-3">
                        <span class="chu-tong-ket fw-bold" style="font-size: 18px;">
                            Thành tiền
                        </span>
                        <span class="so-tong-ket text-danger fw-bold" style="font-size: 24px;">
                            {{ number_format($total, 0, ',', '.') }}đ
                        </span>
                    </div>

                    <p class="ghi-chu-thue text-secondary small mb-4 fst-italic">
                        *Đã bao gồm VAT. Phí vận chuyển có thể thay đổi ở bước thanh toán.
                    </p>

                    <button id="btn-thanh-toan"
                            class="nut-thanh-toan w-100 text-uppercase btn btn-dark py-3 fw-bold fs-6 rounded-0"
                            {{ $cartItems->isEmpty() ? 'disabled' : '' }}>
                        Tiến Hành Thanh Toán
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
                alert(data.message || 'Không thể cập nhật giỏ hàng');
            }
        })
        .catch(error => {
            alert('Có lỗi xảy ra khi cập nhật giỏ hàng');
        });
    };

    window.xoaSanPham = function (id) {
        if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) {
            return;
        }

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
                alert(data.message || 'Không thể xóa sản phẩm');
            }
        })
        .catch(error => {
            alert('Có lỗi xảy ra khi xóa sản phẩm');
        });
    };
</script>
@endpush