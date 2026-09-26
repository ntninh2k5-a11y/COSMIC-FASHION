@extends('layouts.app')

@section('title', 'Chi tiết sản phẩm - Cosmic Fashion')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/detail.css') }}">
@endpush

@section('content')

<main class="py-4 bg-white">
    <div class="container">
        
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb" style="font-size: 0.9rem;">
                <li class="breadcrumb-item"><a href="{{ route('home') ?? '/' }}" class="text-decoration-none text-muted">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #2D3436; font-weight: 500;">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="row g-4">
            
            <div class="col-md-6 mb-4 mb-md-0">
                <div class="d-flex gap-2">
                    <div class="d-none d-md-flex flex-column gap-2" style="width: 64px; flex-shrink: 0;">
                        <div class="khung-thumb dang-chon">
                            <img src="{{ asset($product->image_url ?? 'images/default.jpg') }}" class="w-100 h-100" style="object-fit: cover;" alt="thumb">
                        </div>
                        <div class="khung-thumb">
                            <img src="{{ asset($product->image_url ?? 'images/default.jpg') }}" class="w-100 h-100" style="object-fit: cover;" alt="thumb">
                        </div>
                        <div class="khung-thumb">
                            <img src="{{ asset($product->image_url ?? 'images/default.jpg') }}" class="w-100 h-100" style="object-fit: cover;" alt="thumb">
                        </div>
                        <div class="khung-thumb">
                            <img src="{{ asset($product->image_url ?? 'images/default.jpg') }}" class="w-100 h-100" style="object-fit: cover;" alt="thumb">
                        </div>
                    </div>
                    
                    <div class="khung-anh-chinh flex-grow-1 position-relative">
                        @if($product->discount_percent > 0)
                            <span class="position-absolute top-0 end-0 m-2 badge bg-danger rounded-1" style="z-index: 10; font-size: 0.85rem;">
                                -{{ $product->discount_percent }}%
                            </span>
                        @endif
                        <img src="{{ asset($product->image_url ?? 'images/default.jpg') }}" class="anh-cover" alt="{{ $product->name }}">
                    </div>
                </div>
            </div>

            <div class="col-md-6 d-flex flex-column">
                

                <div class="d-flex align-items-baseline gap-2 mb-1">
                    @if($product->discount_percent > 0)
                        <div class="gia-sp">{{ number_format($product->sale_price, 0, ',', '.') }}đ</div>
                        <del class="text-secondary fs-5">{{ number_format($product->price, 0, ',', '.') }}đ</del>
                    @else
                        <div class="gia-sp">{{ number_format($product->price, 0, ',', '.') }}đ</div>
                    @endif
                </div>
                
                <h1 class="tieu-de-sp mb-1">{{ $product->name }}</h1>
                <div class="sku-sp mb-4">Mã SP: SP{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}</div>

                @if(count($colors) > 0)
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <span class="text-dark" style="font-size: 0.95rem;">Màu sắc: </span>
                            <span class="ms-1 fw-bold text-dark" id="ten-mau-hien-thi"></span>
                        </div>
                        <div class="d-flex gap-2 flex-wrap align-items-center">
                            @foreach($colors as $color)
                                <div class="khung-chon-mau" onclick="chonMau('{{ $color }}', this)" data-color="{{ $color }}">
                                    <div class="vong-mau-ngoai">
                                        <div class="vong-mau-trong" style="background-color: {{ $color }};"></div>
                                    </div>
                                    <div class="ten-mau d-none">{{ $colorNames[$color] ?? $color }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(count($sizes) > 0)
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-end mb-2">
                            <div>
                                <span class="text-dark" style="font-size: 0.95rem;">Kích thước: </span>
                                <span class="ms-1 fw-bold text-dark" id="ten-size-hien-thi"></span>
                            </div>
                            <a href="#" class="text-decoration-none" style="font-size: 0.85rem; color: #4b5563;">Hướng dẫn chọn size</a>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            @foreach($sizes as $size)
                                <div onclick="chonSize('{{ $size }}', this)" class="btn-size-item">
                                    {{ $size }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="d-flex gap-3 align-items-center mb-3">
                    <div class="khung-so-luong">
                        <button onclick="thayDoiSoLuong(-1)" class="nut-so-luong fs-5">-</button>
                        <div id="hien-thi-so-luong" class="so-luong-hien-thi">1</div>
                        <button onclick="thayDoiSoLuong(1)" class="nut-so-luong fs-5">+</button>
                    </div>
                    
                    <button onclick="themVaoGio()" class="nut-them-gio flex-grow-1 h-100 d-flex align-items-center justify-content-center gap-2" style="height: 48px;">
                        Thêm vào giỏ 
                        <i class="bi bi-bag"></i>
                    </button>
                </div>
                
                <div class="text-center mb-4">
                    <a href="#" class="text-decoration-none" style="font-size: 0.85rem; color: #5046e5;">
                        <i class="bi bi-shop me-1"></i>
                        Xem cửa hàng còn sản phẩm
                    </a>
                </div>

                <div class="border-top pt-3">
                    <div class="d-flex align-items-center gap-1 fw-bold mb-3" style="font-size: 0.9rem;">
                        COSMIC cam kết <i class="bi bi-check-circle-fill" style="color:#10b981;"></i>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="border rounded-2 p-2 d-flex align-items-center gap-2 h-100" style="background-color: #fff;">
                                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px; border: 1px solid #eee;">
                                    <i class="bi bi-arrow-repeat"></i>
                                </div>
                                <div style="font-size: 0.75rem; line-height: 1.3;">
                                    Không hài lòng, <strong>đổi trả trong 30 ngày</strong><br>
                                    <a href="#" class="text-decoration-none" style="color: #5046e5;">Xem chính sách &nearr;</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded-2 p-2 d-flex align-items-center gap-2 h-100" style="background-color: #fff;">
                                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px; border: 1px solid #eee;">
                                    <i class="bi bi-truck"></i>
                                </div>
                                <div style="font-size: 0.75rem; line-height: 1.3;">
                                    Giao trong <strong>3-5 ngày</strong> và freeship đơn từ 498k
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        
        @if(count($relatedProducts) > 0)
        <div class="mt-5 pt-5 border-top">
            <h2 class="fs-5 fw-bold mb-4 text-uppercase">Có thể bạn cũng thích</h2>
            <div class="row g-4 mb-5">
                @foreach($relatedProducts as $sp)
                    <div class="col-6 col-md-3 mb-4">
                        @include('partials.product_card_php', ['sp' => $sp])
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</main>
@endsection

@push('scripts')
<script>
    let sizeChon = '';
    let mauChon = '';
    let soLuong = 1;

    const sanPhamHienTai = {
        id: "{{ $product->id }}",
        name: "{{ $product->name }}",
        priceNum: {{ $product->discount_percent > 0 ? $product->sale_price : $product->price }},
        image: "{{ asset($product->image_url ?? 'images/default.jpg') }}"
    };

    const variants = @json($product->variants);

    function chonSize(size, element) {
        sizeChon = size;
        
        document.querySelectorAll('.btn-size-item').forEach(btn => {
            btn.classList.remove('dang-chon');
        });
        
        element.classList.add('dang-chon');
        
        const hienThi = document.getElementById('ten-size-hien-thi');
        if(hienThi) hienThi.innerText = size;
    }

    function chonMau(mau, element) {
        mauChon = mau;
        
        document.querySelectorAll('.vong-mau-ngoai').forEach(vong => vong.classList.remove('dang-chon'));
        
        element.querySelector('.vong-mau-ngoai').classList.add('dang-chon');
        
        const hienThi = document.getElementById('ten-mau-hien-thi');
        if(hienThi) {
            hienThi.innerText = element.querySelector('.ten-mau').innerText;
        }
    }

    function thayDoiSoLuong(thayDoi) {
        soLuong += thayDoi;
        if (soLuong < 1) soLuong = 1; 
        document.getElementById('hien-thi-so-luong').innerText = soLuong;
    }

    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        const initialColor = urlParams.get('color');
        if (initialColor) {
            const el = document.querySelector(`.khung-chon-mau[data-color="${initialColor}"]`);
            if (el) {
                chonMau(initialColor, el);
            }
        }
    });

    function themVaoGio() {
        @if(count($sizes) > 0)
            if(!sizeChon) {
                showErrorToast('Vui lòng chọn Kích thước (Size)!');
                return;
            }
        @endif
        
        @if(count($colors) > 0)
            if(!mauChon) {
                showErrorToast('Vui lòng chọn Màu sắc!');
                return;
            }
        @endif

        let variantId = null;

        if (variants && variants.length > 0) {
            const matched = variants.find(v => {
                let matchSize = true;
                let matchColor = true;
                
                @if(count($sizes) > 0)
                    matchSize = (v.size === sizeChon);
                @endif
                
                @if(count($colors) > 0)
                    matchColor = (v.color === mauChon);
                @endif
                
                return matchSize && matchColor;
            });

            if (matched) {
                variantId = matched.id;
            } else {
                // Không tìm thấy variant → thông báo hết hàng
                showErrorToast('Rất tiếc, phiên bản Size và Màu bạn chọn hiện đang hết hàng!');
                return;
            }
        }

        const payload = {
            product_id: sanPhamHienTai.id,
            variant_id: variantId,
            quantity: soLuong
        };

        fetch('/them-vao-gio', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        })
        .then(async res => {
            if (!res.ok) {
                const errData = await res.json().catch(() => null);
                throw new Error(errData ? (errData.message || 'Lỗi server') : 'Lỗi server (Status: ' + res.status + ')');
            }
            return res.json();
        })
        .then(data => {
            if(data.success) {
                // Lấy thông tin hiển thị
                let variantText = [];
                if (mauChon) variantText.push(document.getElementById('ten-mau-hien-thi').innerText);
                if (sizeChon) variantText.push(sizeChon);
                const variantStr = variantText.join(' / ');

                showCartToast(sanPhamHienTai.name, sanPhamHienTai.image, variantStr, sanPhamHienTai.priceNum, soLuong);
                window.dispatchEvent(new Event('cartUpdated')); 
            } else {
                showErrorToast(data.message);
            }
        })
        .catch(err => {
            showErrorToast('Đã xảy ra lỗi: ' + err.message);
        });
    }

    function showCartToast(productName, productImage, variantText, priceNum, quantity) {
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.style.position = 'fixed';
            container.style.top = '100px';
            container.style.right = '20px';
            container.style.zIndex = '1050';
            document.body.appendChild(container);
        }

        const priceText = new Intl.NumberFormat('vi-VN').format(priceNum) + 'đ';
        const toast = document.createElement('div');
        toast.className = 'neo-card bg-white mb-3 shadow-lg p-3 rounded-4';
        toast.style.width = '350px';
        toast.style.transition = 'all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
        toast.style.transform = 'translateX(120%)';
        toast.style.opacity = '0';
        
        toast.innerHTML = `
            <div class="d-flex justify-content-between align-items-center border-bottom border-dark border-2 pb-2 mb-3">
                <h6 class="fw-bolder m-0 text-uppercase" style="letter-spacing: -0.5px;">
                    <i class="bi bi-check-circle-fill text-success me-1"></i> Đã thêm vào giỏ
                </h6>
                <button type="button" class="btn-close" style="width: 10px; height: 10px;" aria-label="Close"></button>
            </div>
            <div class="d-flex gap-3 mb-3">
                <img src="${productImage}" style="width: 70px; height: 70px; object-fit: cover;" class="border border-dark border-2 rounded-3 shadow-sm">
                <div style="flex: 1; min-width: 0;">
                    <div class="fw-bold text-truncate mb-1" style="font-size: 0.9rem;">${productName}</div>
                    <div class="text-secondary mb-1 fw-medium" style="font-size: 0.8rem;">Phân loại: <span class="text-dark">${variantText}</span></div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <div class="text-danger fw-bolder">${priceText}</div>
                        <div class="fw-bold fs-6">x${quantity}</div>
                    </div>
                </div>
            </div>
            <a href="{{ route('cart') }}" class="neo-btn w-100 py-2 d-block text-center text-decoration-none rounded-3" style="font-size: 0.9rem;">XEM GIỎ HÀNG VÀ THANH TOÁN</a>
        `;

        container.appendChild(toast);

        requestAnimationFrame(() => {
            toast.style.transform = 'translateX(0)';
            toast.style.opacity = '1';
        });

        const closeBtn = toast.querySelector('.btn-close');
        closeBtn.onclick = () => removeToast(toast);

        setTimeout(() => {
            if(toast.parentElement) removeToast(toast);
        }, 5000);
    }

    function showErrorToast(msg) {
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.style.position = 'fixed';
            container.style.top = '100px';
            container.style.right = '20px';
            container.style.zIndex = '1050';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = 'neo-card bg-white mb-3 shadow-lg p-3 border-danger rounded-4';
        toast.style.width = '300px';
        toast.style.transition = 'all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
        toast.style.transform = 'translateX(120%)';
        toast.style.opacity = '0';
        
        toast.innerHTML = `
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                <h6 class="fw-bolder m-0 text-danger text-uppercase">Thông báo</h6>
            </div>
            <div class="fw-medium text-dark" style="font-size: 0.9rem;">${msg}</div>
        `;

        container.appendChild(toast);
        requestAnimationFrame(() => {
            toast.style.transform = 'translateX(0)';
            toast.style.opacity = '1';
        });
        setTimeout(() => {
            if(toast.parentElement) removeToast(toast);
        }, 3000);
    }

    function removeToast(toast) {
        toast.style.transform = 'translateX(120%)';
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }
</script>
@endpush
