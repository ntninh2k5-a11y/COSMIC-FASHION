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
                @php
                    $allImages = collect([['url' => $product->image_url]]);
                    if ($product->images && $product->images->count() > 0) {
                        foreach($product->images as $img) {
                            $allImages->push(['url' => $img->image_url]);
                        }
                    }
                    // If only 1 image, duplicate it a bit to show the feature for now, or just show whatever is there.
                    if ($allImages->count() == 1) {
                        for($i=0; $i<3; $i++) $allImages->push(['url' => $product->image_url]);
                    }
                @endphp
                <div class="d-flex gap-2 align-items-stretch">
                    <div class="d-none d-md-flex flex-column gap-2" style="width: 100px; flex-shrink: 0;" id="productThumbnails">
                        @foreach($allImages as $index => $img)
                            <div class="khung-thumb {{ $index === 0 ? 'dang-chon' : '' }} w-100" style="flex: 1; height: 0; cursor: pointer; border-radius: 12px; overflow: hidden; border: 2px solid transparent;" onclick="changeMainImage({{ $index }}, '{{ asset($img['url']) }}', this)">
                                <img src="{{ asset($img['url']) }}" class="w-100 h-100" style="object-fit: cover;" alt="thumb {{ $index }}">
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="khung-anh-chinh flex-grow-1 position-relative rounded overflow-hidden">
                        @if($product->discount_percent > 0)
                            <span class="position-absolute top-0 end-0 m-2 badge bg-danger rounded-1" style="z-index: 10; font-size: 0.85rem;">
                                -{{ $product->discount_percent }}%
                            </span>
                        @endif
                        <img src="{{ asset($allImages[0]['url']) }}" id="mainProductImage" class="anh-cover w-100" style="object-fit: cover; transition: opacity 0.3s ease-in-out;" alt="{{ $product->name }}">
                    </div>
                </div>
            </div>

            <div class="col-md-6 d-flex flex-column">
                

                <div class="d-flex align-items-baseline gap-2 mb-2">
                    @if($product->discount_percent > 0)
                        <div class="gia-sp fw-bolder text-danger" style="font-size: 2rem;">{{ number_format($product->sale_price, 0, ",", ".") }}đ</div>
                        <del class="text-secondary" style="font-size: 1.2rem;">{{ number_format($product->price, 0, ",", ".") }}đ</del>
                        <span class="badge bg-danger rounded-1 ms-1 px-2 py-1" style="font-size: 0.9rem;">-{{ $product->discount_percent }}%</span>
                    @else
                        <div class="gia-sp fw-bolder text-danger" style="font-size: 2rem;">{{ number_format($product->price, 0, ",", ".") }}đ</div>
                    @endif
                </div>
                
                <h1 class="tieu-de-sp mb-4 text-dark" style="font-size: 1.6rem; font-weight: 500;">{{ $product->name }}</h1>
                
                @if(count($colors) > 0)
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <span class="text-dark" style="font-size: 1rem;">Màu sắc: </span>
                            <span class="ms-1 text-dark" id="ten-mau-hien-thi"></span>
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
                        <div class="d-flex justify-content-between align-items-end mb-3">
                            <div>
                                <span class="text-dark" style="font-size: 1rem;">Kích thước: </span>
                                <span class="ms-1 text-dark" id="ten-size-hien-thi"></span>
                            </div>
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

                <div class="d-flex gap-3 align-items-center mt-3 mb-5">
                    <div class="khung-so-luong bg-white border rounded-pill py-2 px-3 d-flex align-items-center justify-content-between shadow-sm" style="width: 140px; height: 52px;">
                        <button onclick="thayDoiSoLuong(-1)" class="nut-so-luong fs-4 border-0 bg-transparent text-secondary d-flex align-items-center justify-content-center" style="width:30px;">-</button>
                        <div id="hien-thi-so-luong" class="so-luong-hien-thi fw-medium fs-5">1</div>
                        <button onclick="thayDoiSoLuong(1)" class="nut-so-luong fs-4 border-0 bg-transparent text-secondary d-flex align-items-center justify-content-center" style="width:30px;">+</button>
                    </div>
                    
                    <button onclick="themVaoGio()" class="nut-them-gio flex-grow-1 h-100 d-flex align-items-center justify-content-center gap-2 text-white fw-medium border-0 rounded-pill shadow-sm" style="height: 52px; background-color: #FF6B6B; font-size: 1.1rem; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                        Thêm vào giỏ 
                        <i class="bi bi-bag"></i>
                    </button>
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

        const currentImageUrl = document.getElementById('mainProductImage').src;
        // Chuyển URL tuyệt đối thành relative path (bỏ đi phần domain của asset())
        // Hoặc gửi nguyên cục cũng được, nhưng tốt nhất gửi URL đầy đủ
        // Tuy nhiên `asset` trả về URL tuyệt đối, khi hiện lại ở giỏ hàng,
        // nếu gắn `asset(image_url)` vào URL tuyệt đối thì sẽ bị lỗi
        // Nên dùng URL tương đối.
        const originUrl = window.location.origin;
        let relativeImg = currentImageUrl;
        if(currentImageUrl.startsWith(originUrl)) {
            relativeImg = currentImageUrl.replace(originUrl + '/', '');
        }

        const payload = {
            product_id: sanPhamHienTai.id,
            variant_id: variantId,
            quantity: soLuong,
            image_url: relativeImg
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

                const currentImageUrl = document.getElementById('mainProductImage').src;
                showCartToast(sanPhamHienTai.name, currentImageUrl, variantStr, sanPhamHienTai.priceNum, soLuong);
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
            <a href="{{ route('cart') }}" class="btn text-white fw-bold w-100 py-2 d-block text-center text-decoration-none rounded-pill shadow-sm mt-3" style="background-color: #FF6B6B; font-size: 0.85rem; letter-spacing: 0.5px;">XEM GIỎ HÀNG VÀ THANH TOÁN</a>
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

    // --- LOGIC SLIDER ẢNH ---
    const thumbnails = document.querySelectorAll('#productThumbnails .khung-thumb');
    const mainImage = document.getElementById('mainProductImage');

    function changeMainImage(index, url, thumbElement) {
        // Cập nhật ảnh chính với hiệu ứng fade
        mainImage.style.opacity = '0';
        setTimeout(() => {
            mainImage.src = url;
            mainImage.style.opacity = '1';
        }, 150); // Đợi mờ 1 nửa mới đổi src
        
        // Đổi class dang-chon
        thumbnails.forEach(t => t.classList.remove('dang-chon'));
        if (thumbElement) {
            thumbElement.classList.add('dang-chon');
        } else if (thumbnails[index]) {
            thumbnails[index].classList.add('dang-chon');
        }
    }
</script>
@endpush
