@extends('layouts.app')

@section('title', 'Chi tiết sản phẩm - Cosmic Fashion')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/detail.css') }}">
    <style>
        .btn-size.dang-chon { background-color: #212529; color: #fff; }
    </style>
@endpush

@section('content')

<main class="py-5 bg-white">
    <div class="container">
        <div class="row">
            
            <div class="col-md-6 mb-4 mb-md-0">
                <div class="khung-anh-chinh mb-3 position-relative">
                    @if($product->discount_percent > 0)
                        <span class="position-absolute top-0 end-0 m-3 badge bg-danger rounded-0 fs-6 px-3 py-2">
                            -{{ $product->discount_percent }}%
                        </span>
                    @endif
                    <img src="{{ asset($product->image_url ?? 'images/default.jpg') }}" class="anh-cover w-100" alt="{{ $product->name }}">
                </div>
            </div>

            <div class="col-md-6 ps-md-5 d-flex flex-column">
                <h1 class="tieu-de-sp mb-2">{{ $product->name }}</h1>
                
                <div class="d-flex align-items-center mb-3">
                    @if($product->discount_percent > 0)
                        <div class="fw-bold fs-3 text-danger me-3">{{ number_format($product->sale_price, 0, ',', '.') }}đ</div>
                        <del class="text-secondary fs-5">{{ number_format($product->price, 0, ',', '.') }}đ</del>
                    @else
                        <div class="fw-bold fs-3 text-danger me-3">{{ number_format($product->price, 0, ',', '.') }}đ</div>
                    @endif
                </div>
                
                <p class="mo-ta-sp text-secondary mb-4 pb-3 border-bottom">
                    {{ $product->description ?? 'Thiết kế thời thượng, form dáng chuẩn mực. Được làm từ chất liệu cao cấp mang lại sự thoải mái tuyệt đối cho người mặc trong mọi hoạt động hàng ngày.' }}
                </p>

                @if(count($sizes) > 0)
                    <div class="mb-4">
                        <div class="nhan-tieu-de fw-bold mb-2">Kích cỡ</div>
                        <div class="d-flex gap-2 flex-wrap">
                            @foreach($sizes as $size)
                                <button onclick="chonSize('{{ $size }}', this)" class="btn rounded-0 px-4 py-2 fw-bold btn-outline-dark btn-size">
                                    {{ $size }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(count($colors) > 0)
                    <div class="mb-4">
                        <div class="fw-bold mb-2">Màu sắc</div>
                        <div class="d-flex gap-3 flex-wrap align-items-center">
                            @foreach($colors as $color)
                                <div class="text-center khung-chon-mau" onclick="chonMau('{{ $color }}', this)" style="cursor: pointer; min-width: 50px;">
                                    <div class="vong-mau-ngoai">
                                        <div class="vong-mau-trong" style="background-color: {{ $color }};"></div>
                                    </div>
                                    <div class="ten-mau mt-1 small text-secondary">{{ $colorNames[$color] ?? $color }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mb-4">
                    <div class="nhan-tieu-de fw-bold mb-2">Số lượng</div>
                    <div class="khung-so-luong d-flex align-items-center border" style="width: 120px;">
                        <button onclick="thayDoiSoLuong(-1)" class="nut-so-luong btn border-0 px-3 fs-5">-</button>
                        <div id="hien-thi-so-luong" class="so-luong-hien-thi px-2 fw-bold text-center flex-grow-1">1</div>
                        <button onclick="thayDoiSoLuong(1)" class="nut-so-luong btn border-0 px-3 fs-5">+</button>
                    </div>
                </div>

                <button onclick="themVaoGio()" class="nut-them-gio w-100 mt-4 rounded-0 fs-6 btn btn-dark py-3 text-uppercase fw-bold">
                    Thêm vào giỏ hàng
                </button>
            </div>
        </div>
        
        @if(count($relatedProducts) > 0)
        <div class="mt-5 pt-5 border-top">
            <h2 class="fs-5 fw-bold mb-4 text-uppercase">Có thể bạn cũng thích</h2>
            <div class="row g-4 mb-5">
                @foreach($relatedProducts as $sp)
                    <div class="col-6 col-md-3">
                        <a href="{{ url('/chi-tiet-san-pham/' . $sp->id) }}" class="text-decoration-none text-dark">
                            <div class="position-relative mb-3 overflow-hidden khung-anh-chinh">
                                @if($sp->discount_percent > 0)
                                    <span class="position-absolute top-0 start-0 m-2 badge bg-danger rounded-0">-{{ $sp->discount_percent }}%</span>
                                @endif
                                <img src="{{ asset($sp->image_url ?? 'images/default.jpg') }}" alt="{{ $sp->name }}" class="anh-cover w-100">
                            </div>
                            <div class="text-start">
                                <h6 class="mb-1 fw-normal text-secondary">{{ $sp->name }}</h6>
                                <div class="fw-bold text-danger">{{ number_format($sp->discount_percent > 0 ? $sp->sale_price : $sp->price, 0, ',', '.') }}đ</div>
                            </div>
                        </a>
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
        
        document.querySelectorAll('.btn-size').forEach(btn => {
            btn.classList.remove('dang-chon', 'btn-dark');
            btn.classList.add('btn-outline-dark');
        });
        
        element.classList.remove('btn-outline-dark');
        element.classList.add('dang-chon', 'btn-dark');
    }

    function chonMau(mau, element) {
        mauChon = mau;
        
        document.querySelectorAll('.vong-mau-ngoai').forEach(vong => vong.classList.remove('dang-chon'));
        document.querySelectorAll('.ten-mau').forEach(ten => ten.classList.remove('fw-bold', 'text-dark'));
        
        element.querySelector('.vong-mau-ngoai').classList.add('dang-chon');
        element.querySelector('.ten-mau').classList.add('fw-bold', 'text-dark');
    }

    function thayDoiSoLuong(thayDoi) {
        soLuong += thayDoi;
        if (soLuong < 1) soLuong = 1; 
        document.getElementById('hien-thi-so-luong').innerText = soLuong;
    }

    function themVaoGio() {
        @if(count($sizes) > 0)
            if(!sizeChon) {
                alert('Vui lòng chọn Size!');
                return;
            }
        @endif
        
        @if(count($colors) > 0)
            if(!mauChon) {
                alert('Vui lòng chọn Màu sắc!');
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
                alert('Rất tiếc, phiên bản Size và Màu bạn chọn hiện đang hết hàng!');
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
                alert('Thêm vào giỏ hàng thành công!');
                window.dispatchEvent(new Event('cartUpdated')); 
            } else {
                alert('Lỗi: ' + data.message);
            }
        })
        .catch(err => {
            alert('Đã xảy ra lỗi: ' + err.message);
        });
    }
</script>
@endpush