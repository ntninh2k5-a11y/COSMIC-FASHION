@extends('layouts.app')

@section('title', $categoryName . ' - Cosmic Fashion')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/danh-sach-san-pham.css') }}">
@endpush

@section('content')
<main class="py-5">
    <div class="container">
        
        <div class="text-center mb-5 mt-3 p-5" style="background-color: #F6F5F2;">
            <h1 class="fw-bold text-uppercase mb-3" style="letter-spacing: 2px; color: #1a1a1a;">
                {{ $categoryName }}
            </h1>
            <p class="text-secondary mx-auto" style="max-width: 600px;">
                {{ $categoryDesc }}
            </p>
        </div>

        <div class="thanh-loc-toi-gian d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-5">
            
            <div class="d-flex flex-column flex-md-row gap-4 align-items-md-center">
                @if($showFilters)
                    <div class="loc-nhom" id="nhom-kich-co">
                        <span class="loc-tieu-de fw-bold">KÍCH CỠ:</span>
                        <span class="loc-chu-item active" onclick="chonKichCo('Tất cả', this)">Tất cả</span>
                        <span class="loc-chu-item" onclick="chonKichCo('S', this)">S</span>
                        <span class="loc-chu-item" onclick="chonKichCo('M', this)">M</span>
                        <span class="loc-chu-item" onclick="chonKichCo('L', this)">L</span>
                        <span class="loc-chu-item" onclick="chonKichCo('XL', this)">XL</span>
                        <span class="loc-chu-item" onclick="chonKichCo('XXL', this)">XXL</span>
                    </div>

                    <div class="d-none d-md-block text-secondary" style="user-select: none;">|</div>

                    <div class="loc-nhom" id="nhom-mau-sac">
                        <span class="loc-tieu-de fw-bold">MÀU SẮC:</span>
                        <span class="loc-chu-item active" onclick="chonMau('Tất cả', this)">Tất cả</span>
                        <span class="loc-chu-item" onclick="chonMau('#000000', this)"><span class="bieu-tuong-mau-nho" style="background-color: #000000"></span> Đen</span>
                        <span class="loc-chu-item" onclick="chonMau('#FFFFFF', this)"><span class="bieu-tuong-mau-nho" style="background-color: #FFFFFF"></span> Trắng</span>
                        <span class="loc-chu-item" onclick="chonMau('#000080', this)"><span class="bieu-tuong-mau-nho" style="background-color: #000080"></span> Xanh Navy</span>
                        <span class="loc-chu-item" onclick="chonMau('#F5F5DC', this)"><span class="bieu-tuong-mau-nho" style="background-color: #F5F5DC"></span> Be</span>
                        <span class="loc-chu-item" onclick="chonMau('#808080', this)"><span class="bieu-tuong-mau-nho" style="background-color: #808080"></span> Xám</span>
                    </div>
                @endif
            </div>

            <div class="loc-nhom ms-auto" id="nhom-sap-xep">
                <span class="loc-tieu-de fw-bold">SẮP XẾP:</span>
                <span class="loc-chu-item active" onclick="chonSapXep('Mặc định', this)">Mặc định</span>
                <span class="loc-chu-item" onclick="chonSapXep('Giá tăng dần', this)">Giá tăng dần</span>
                <span class="loc-chu-item" onclick="chonSapXep('Giá giảm dần', this)">Giá giảm dần</span>
            </div>
        </div>

        <div class="row luoi-san-pham" id="khu-vuc-san-pham"></div>
        <div id="khu-vuc-phan-trang" class="phan-trang-js"></div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    const duLieuGoc = {!! $duLieuGoc !!};
    const hasFilters = {{ $showFilters ? 'true' : 'false' }};
    
    let sizeHienTai = 'Tất cả';
    let mauHienTai = 'Tất cả';
    let sapXepHienTai = 'Mặc định';
    
    let trangHienTai = 1;
    const soSanPhamMotTrang = 8;

    function chonKichCo(size, element) {
        sizeHienTai = size;
        document.querySelectorAll('#nhom-kich-co .loc-chu-item').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
        trangHienTai = 1;
        renderSanPham();
    }

    function chonMau(mau, element) {
        mauHienTai = mau;
        document.querySelectorAll('#nhom-mau-sac .loc-chu-item').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
        trangHienTai = 1;
        renderSanPham();
    }

    function chonSapXep(sapXep, element) {
        sapXepHienTai = sapXep;
        document.querySelectorAll('#nhom-sap-xep .loc-chu-item').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
        trangHienTai = 1;
        renderSanPham();
    }
    
    function chuyenTrang(page) {
        trangHienTai = page;
        renderSanPham();
        window.scrollTo({ top: document.getElementById('khu-vuc-san-pham').offsetTop - 100, behavior: 'smooth' });
    }

    function renderSanPham() {
        let ketQuaLoc = [...duLieuGoc];

        if (hasFilters) {
            if (sizeHienTai !== 'Tất cả') {
                ketQuaLoc = ketQuaLoc.filter(sp => sp.sizes && sp.sizes.includes(sizeHienTai));
            }
            if (mauHienTai !== 'Tất cả') {
                ketQuaLoc = ketQuaLoc.filter(sp => sp.colors && sp.colors.some(c => c.bg === mauHienTai));
            }
        }

        if (sapXepHienTai === 'Giá tăng dần') {
            ketQuaLoc.sort((a, b) => a.priceNum - b.priceNum);
        } else if (sapXepHienTai === 'Giá giảm dần') {
            ketQuaLoc.sort((a, b) => b.priceNum - a.priceNum);
        }

        const tongSoTrang = Math.ceil(ketQuaLoc.length / soSanPhamMotTrang);
        const viTriBatDau = (trangHienTai - 1) * soSanPhamMotTrang;
        const sanPhamTrenTrang = ketQuaLoc.slice(viTriBatDau, viTriBatDau + soSanPhamMotTrang);

        const container = document.getElementById('khu-vuc-san-pham');
        const phanTrangContainer = document.getElementById('khu-vuc-phan-trang');
        
        let htmlContent = '';
        let phanTrangHtml = '';

        if (ketQuaLoc.length === 0) {
            htmlContent = `<div class="text-center w-100 py-5 text-secondary">Không tìm thấy sản phẩm nào phù hợp với bộ lọc.</div>`;
        } else {
            sanPhamTrenTrang.forEach(sp => {
                let vongMauHtml = '';
                if (hasFilters && sp.colors && sp.colors.length > 0) {
                    sp.colors.forEach(color => {
                        vongMauHtml += `<a href="/chi-tiet-san-pham/${sp.id}?color=${encodeURIComponent(color.bg)}" class="mau-sac-vong-tron-men d-inline-block rounded-circle" style="background-color: ${color.bg}; width: 20px; height: 20px; border: 1px solid #ddd; box-shadow: 0 0 0 1px #fff, 0 0 0 2px transparent; transition: all 0.2s;" onmouseover="this.style.boxShadow='0 0 0 1px #fff, 0 0 0 2px #333'" onmouseout="this.style.boxShadow='0 0 0 1px #fff, 0 0 0 2px transparent'"></a>`;
                    });
                }

                let discountHtml = sp.discountPercent > 0 ? `<span class="position-absolute top-0 end-0 m-2 badge bg-danger rounded-1" style="font-size: 0.8rem; padding: 4px 8px;">-${sp.discountPercent}%</span>` : '';
                let priceHtml = sp.oldPrice 
                    ? `<div class="d-flex align-items-baseline gap-2 mb-1">
                           <span class="fw-bold text-danger" style="font-size: 1.1rem;">${sp.price}</span>
                           <del class="text-secondary" style="font-size: 0.85rem;">${sp.oldPrice}</del>
                       </div>`
                    : `<div class="fw-bold text-dark mb-1" style="font-size: 1.1rem;">${sp.price}</div>`;

                htmlContent += `
                    <div class="col-6 col-md-3 mb-5">
                        <div class="product-card">
                            <a href="/chi-tiet-san-pham/${sp.id}" class="text-decoration-none text-dark d-block">
                                <div class="position-relative mb-3 overflow-hidden">
                                    <img src="${sp.image}" alt="${sp.name}" class="w-100 rounded-3" style="aspect-ratio: 3/4; object-fit: cover; background-color: #f1f1f1;" />
                                    ${discountHtml}
                                </div>
                            </a>
                            <div class="text-start px-1">
                                ${priceHtml}
                                <h6 class="text-secondary mb-2" style="font-size: 0.95rem; font-weight: normal; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <a href="/chi-tiet-san-pham/${sp.id}" class="text-decoration-none text-secondary">${sp.name}</a>
                                </h6>
                                ${hasFilters ? `<div class="d-flex gap-2 mt-2 align-items-center">${vongMauHtml}</div>` : ''}
                            </div>
                        </div>
                    </div>
                `;
            });
            
            if (tongSoTrang > 1) {
                phanTrangHtml += `<button ${trangHienTai === 1 ? 'disabled' : ''} onclick="chuyenTrang(${trangHienTai - 1})">«</button>`;
                for (let i = 1; i <= tongSoTrang; i++) {
                    phanTrangHtml += `<button class="${trangHienTai === i ? 'active' : ''}" onclick="chuyenTrang(${i})">${i}</button>`;
                }
                phanTrangHtml += `<button ${trangHienTai === tongSoTrang ? 'disabled' : ''} onclick="chuyenTrang(${trangHienTai + 1})">»</button>`;
            }
        }

        container.innerHTML = htmlContent;
        phanTrangContainer.innerHTML = phanTrangHtml;
    }

    document.addEventListener("DOMContentLoaded", function() {
        renderSanPham();
    });
</script>
@endpush