@extends('layouts.app')

@section('title', $categoryName . ' - Cosmic Fashion')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/danh-sach-san-pham.css') }}">
<style>
/* === FILTER BAR === */
.filter-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-top: 1px solid #e5e7eb;
    border-bottom: 1px solid #e5e7eb;
    padding: 12px 0;
    margin-bottom: 32px;
    gap: 12px;
    flex-wrap: wrap;
}
.filter-tabs {
    display: flex;
    gap: 6px;
    align-items: center;
    flex-wrap: wrap;
}
.filter-tab-btn {
    background: none;
    border: none;
    font-size: 0.9rem;
    font-weight: 500;
    color: #6b7280;
    padding: 6px 14px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
}
.filter-tab-btn:hover { color: #111; }
.filter-tab-btn.active { color: #111; font-weight: 700; }
.filter-sort-dropdown {
    position: relative;
}
.filter-sort-dropdown select {
    appearance: none;
    border: none;
    background: none;
    font-size: 0.9rem;
    font-weight: 500;
    color: #111;
    cursor: pointer;
    padding: 6px 24px 6px 10px;
    border-radius: 6px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath fill='%23111' d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 6px center;
}
.filter-sort-dropdown select:focus { outline: none; }

.filter-right {
    display: flex;
    align-items: center;
    gap: 12px;
}
.btn-open-filter {
    display: flex;
    align-items: center;
    gap: 6px;
    background: none;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 7px 16px;
    font-size: 0.88rem;
    font-weight: 600;
    color: #111;
    cursor: pointer;
    transition: all 0.2s;
}
.btn-open-filter:hover { border-color: #111; }

/* === FILTER DRAWER === */
.filter-drawer-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.35);
    z-index: 1050;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}
.filter-drawer-overlay.open {
    opacity: 1;
    visibility: visible;
}
.filter-drawer {
    position: fixed;
    top: 0; right: 0;
    width: 340px;
    max-width: 95vw;
    height: 100vh;
    background: #fff;
    z-index: 1060;
    display: flex;
    flex-direction: column;
    transform: translateX(100%);
    transition: transform 0.35s cubic-bezier(0.4,0,0.2,1);
    box-shadow: -8px 0 30px rgba(0,0,0,0.1);
}
.filter-drawer.open { transform: translateX(0); }
.filter-drawer-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    border-bottom: 1px solid #f3f4f6;
    font-size: 1.05rem;
    font-weight: 700;
}
.btn-close-drawer {
    background: none;
    border: 1px solid #e5e7eb;
    border-radius: 50%;
    width: 34px; height: 34px;
    font-size: 1rem;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.2s;
}
.btn-close-drawer:hover { background: #f3f4f6; }
.filter-drawer-body {
    flex: 1;
    overflow-y: auto;
    padding: 0 24px;
}
.filter-group {
    border-bottom: 1px solid #f3f4f6;
    padding: 18px 0;
}
.filter-group-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
    font-size: 0.95rem;
    font-weight: 500;
    user-select: none;
}
.filter-group-header .toggle-icon {
    font-size: 1.1rem;
    color: #9ca3af;
    transition: transform 0.2s;
}
.filter-group.expanded .toggle-icon { transform: rotate(45deg); }
.filter-group-content {
    display: none;
    margin-top: 14px;
}
.filter-group.expanded .filter-group-content { display: block; }
.filter-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.filter-chip {
    padding: 5px 14px;
    border: 1.5px solid #e5e7eb;
    border-radius: 50rem;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.2s;
    background: #fff;
    user-select: none;
}
.filter-chip:hover { border-color: #111; }
.filter-chip.active {
    background: #111;
    color: #fff;
    border-color: #111;
}
.filter-color-chips { display: flex; flex-wrap: wrap; gap: 10px; }
.filter-color-chip {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
    cursor: pointer;
    font-size: 0.78rem;
    color: #6b7280;
    user-select: none;
}
.color-swatch {
    width: 30px; height: 30px;
    border-radius: 50%;
    border: 2px solid #e5e7eb;
    transition: all 0.2s;
}
.filter-color-chip.active .color-swatch,
.filter-color-chip:hover .color-swatch {
    box-shadow: 0 0 0 2px #fff, 0 0 0 4px #111;
}
.filter-color-chip.active { color: #111; font-weight: 600; }

.filter-drawer-footer {
    padding: 20px 24px;
    border-top: 1px solid #f3f4f6;
    display: flex;
    gap: 12px;
}
.btn-reset-filter {
    flex: 1;
    padding: 12px;
    border: 1.5px solid #e5e7eb;
    background: #fff;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.2s;
}
.btn-reset-filter:hover { border-color: #111; }
.btn-apply-filter {
    flex: 2;
    padding: 12px;
    background: #FF6B6B;
    border: none;
    color: #fff;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.2s;
}
.btn-apply-filter:hover { background: #e55555; }

/* Product card center text */
.category-page-hero {
    background-color: #F6F5F2;
    background-image:
        radial-gradient(ellipse at 20% 50%, rgba(255,107,107,0.07) 0%, transparent 55%),
        radial-gradient(ellipse at 80% 20%, rgba(78,205,196,0.07) 0%, transparent 55%);
}

.product-card-info-center {
    text-align: center;
    padding: 10px 6px 14px;
}
.product-color-dots-center {
    display: flex;
    justify-content: center;
    gap: 6px;
    margin-top: 6px;
}
</style>
@endpush

@section('content')
<main class="py-5">
    <div class="container">
        
        {{-- === HERO BANNER === --}}
        <div class="category-page-hero mb-5 mt-3 position-relative overflow-hidden" style="border-radius: 20px;">
            {{-- Decorative blobs --}}
            <div style="position:absolute; top:-60px; left:-60px; width:220px; height:220px; background: radial-gradient(circle, rgba(255,107,107,0.18) 0%, transparent 70%); border-radius:50%; pointer-events:none;"></div>
            <div style="position:absolute; bottom:-40px; right:-40px; width:280px; height:280px; background: radial-gradient(circle, rgba(78,205,196,0.15) 0%, transparent 70%); border-radius:50%; pointer-events:none;"></div>
            <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); width:400px; height:400px; background: radial-gradient(circle, rgba(255,230,109,0.10) 0%, transparent 65%); border-radius:50%; pointer-events:none;"></div>

            <div class="text-center position-relative py-5 px-4" style="z-index:1;">
                <span class="d-inline-block mb-3" style="font-size: 11px; font-weight: 700; letter-spacing: 3px; text-transform: uppercase; color: #FF6B6B; background: rgba(255,107,107,0.1); padding: 5px 16px; border-radius: 50rem;">COSMIC FASHION</span>
                <h1 class="fw-bold text-uppercase mb-3" style="letter-spacing: 2px; color: #1a1a1a; font-size: clamp(1.8rem, 4vw, 2.8rem);">
                    {{ $categoryName }}
                </h1>
                <p class="text-secondary mx-auto mb-0" style="max-width: 500px; font-size: 0.95rem; line-height: 1.7;">
                    {{ $categoryDesc }}
                </p>
            </div>
        </div>


        {{-- === TOP FILTER BAR === --}}
        <div class="filter-topbar">
            {{-- Left: Sort Tabs --}}
            <div class="filter-tabs">
                <button class="filter-tab-btn active" onclick="chonSapXep('Mặc định', this)">Mặc định</button>
                <button class="filter-tab-btn" onclick="chonSapXep('Mới nhất', this)">Mới nhất</button>
                <div class="filter-sort-dropdown">
                    <select onchange="chonSapXepSelect(this.value)">
                        <option value="">Giá ↕</option>
                        <option value="Giá tăng dần">Giá tăng dần</option>
                        <option value="Giá giảm dần">Giá giảm dần</option>
                    </select>
                </div>
            </div>

            {{-- Right: Filter Button --}}
            <div class="filter-right">
                @if($showFilters)
                    <span id="filter-active-count" class="text-secondary fw-medium" style="font-size: 0.85rem;"></span>
                    <button class="btn-open-filter" onclick="openFilterDrawer()">
                        <i class="bi bi-sliders"></i> Lọc
                    </button>
                @endif
            </div>
        </div>

        {{-- === FILTER DRAWER OVERLAY === --}}
        @if($showFilters)
        <div class="filter-drawer-overlay" id="filterOverlay" onclick="closeFilterDrawer()"></div>

        <div class="filter-drawer" id="filterDrawer">
            <div class="filter-drawer-header">
                <span>Bộ lọc</span>
                <button class="btn-close-drawer" onclick="closeFilterDrawer()">✕</button>
            </div>

            <div class="filter-drawer-body">
                {{-- Kích cỡ --}}
                <div class="filter-group expanded" id="group-size">
                    <div class="filter-group-header" onclick="toggleGroup('group-size')">
                        <span>Kích cỡ</span>
                        <span class="toggle-icon">+</span>
                    </div>
                    <div class="filter-group-content">
                        <div class="filter-chips" id="drawer-kich-co">
                            <span class="filter-chip active" onclick="chonKichCoDrawer('Tất cả', this)">Tất cả</span>
                            <span class="filter-chip" onclick="chonKichCoDrawer('S', this)">S</span>
                            <span class="filter-chip" onclick="chonKichCoDrawer('M', this)">M</span>
                            <span class="filter-chip" onclick="chonKichCoDrawer('L', this)">L</span>
                            <span class="filter-chip" onclick="chonKichCoDrawer('XL', this)">XL</span>
                            <span class="filter-chip" onclick="chonKichCoDrawer('XXL', this)">XXL</span>
                        </div>
                    </div>
                </div>

                {{-- Màu sắc --}}
                <div class="filter-group expanded" id="group-color">
                    <div class="filter-group-header" onclick="toggleGroup('group-color')">
                        <span>Màu sắc</span>
                        <span class="toggle-icon">+</span>
                    </div>
                    <div class="filter-group-content">
                        <div class="filter-color-chips" id="drawer-mau-sac">
                            <div class="filter-color-chip active" onclick="chonMauDrawer('Tất cả', this)">
                                <div class="color-swatch" style="background: linear-gradient(135deg,#ff6b6b,#4ecdc4,#ffe66d,#111); border-color: #e5e7eb;"></div>
                                <span>Tất cả</span>
                            </div>
                            <div class="filter-color-chip" onclick="chonMauDrawer('#000000', this)">
                                <div class="color-swatch" style="background-color: #000000;"></div>
                                <span>Đen</span>
                            </div>
                            <div class="filter-color-chip" onclick="chonMauDrawer('#FFFFFF', this)">
                                <div class="color-swatch" style="background-color: #FFFFFF; border-color: #d1d5db;"></div>
                                <span>Trắng</span>
                            </div>
                            <div class="filter-color-chip" onclick="chonMauDrawer('#000080', this)">
                                <div class="color-swatch" style="background-color: #000080;"></div>
                                <span>Navy</span>
                            </div>
                            <div class="filter-color-chip" onclick="chonMauDrawer('#F5F5DC', this)">
                                <div class="color-swatch" style="background-color: #F5F5DC; border-color: #d1d5db;"></div>
                                <span>Be</span>
                            </div>
                            <div class="filter-color-chip" onclick="chonMauDrawer('#808080', this)">
                                <div class="color-swatch" style="background-color: #808080;"></div>
                                <span>Xám</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="filter-drawer-footer">
                <button class="btn-reset-filter" onclick="resetFilters()">Xóa lọc</button>
                <button class="btn-apply-filter" onclick="applyAndClose()">Áp dụng</button>
            </div>
        </div>
        @endif

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

    // Drawer controls
    function openFilterDrawer() {
        document.getElementById('filterDrawer').classList.add('open');
        document.getElementById('filterOverlay').classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeFilterDrawer() {
        document.getElementById('filterDrawer').classList.remove('open');
        document.getElementById('filterOverlay').classList.remove('open');
        document.body.style.overflow = '';
    }
    function applyAndClose() {
        renderSanPham();
        closeFilterDrawer();
        updateActiveCount();
    }
    function toggleGroup(id) {
        document.getElementById(id).classList.toggle('expanded');
    }

    function updateActiveCount() {
        const el = document.getElementById('filter-active-count');
        if (!el) return;
        let count = 0;
        if (sizeHienTai !== 'Tất cả') count++;
        if (mauHienTai !== 'Tất cả') count++;
        el.textContent = count > 0 ? `${count} bộ lọc đang áp dụng` : '';
    }

    // Sort tabs (top bar)
    function chonSapXep(sapXep, element) {
        sapXepHienTai = sapXep;
        document.querySelectorAll('.filter-tab-btn').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
        // reset price dropdown
        const sel = document.querySelector('.filter-sort-dropdown select');
        if (sel) sel.value = '';
        trangHienTai = 1;
        renderSanPham();
    }
    function chonSapXepSelect(val) {
        if (!val) return;
        sapXepHienTai = val;
        document.querySelectorAll('.filter-tab-btn').forEach(el => el.classList.remove('active'));
        trangHienTai = 1;
        renderSanPham();
    }

    // Drawer filter handlers
    function chonKichCoDrawer(size, element) {
        sizeHienTai = size;
        document.querySelectorAll('#drawer-kich-co .filter-chip').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
    }
    function chonMauDrawer(mau, element) {
        mauHienTai = mau;
        document.querySelectorAll('#drawer-mau-sac .filter-color-chip').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
    }
    function resetFilters() {
        sizeHienTai = 'Tất cả';
        mauHienTai = 'Tất cả';
        document.querySelectorAll('#drawer-kich-co .filter-chip').forEach((el,i) => el.classList.toggle('active', i===0));
        document.querySelectorAll('#drawer-mau-sac .filter-color-chip').forEach((el,i) => el.classList.toggle('active', i===0));
        renderSanPham();
        updateActiveCount();
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
        } else if (sapXepHienTai === 'Mới nhất') {
            ketQuaLoc.sort((a, b) => b.id - a.id);
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
                        vongMauHtml += `<a href="/chi-tiet-san-pham/${sp.id}?color=${encodeURIComponent(color.bg)}" class="d-inline-block rounded-circle" style="background-color: ${color.bg}; width: 18px; height: 18px; border: 1.5px solid #e5e7eb; box-shadow: 0 0 0 1.5px #fff, 0 0 0 2.5px transparent; transition: box-shadow 0.2s;" onmouseover="this.style.boxShadow='0 0 0 1.5px #fff, 0 0 0 3px #FF6B6B'" onmouseout="this.style.boxShadow='0 0 0 1.5px #fff, 0 0 0 2.5px transparent'"></a>`;
                    });
                }

                let discountHtml = sp.discountPercent > 0 ? `<span class="position-absolute top-0 end-0 m-2 badge rounded-2" style="background:#FF6B6B; font-size: 0.78rem; padding: 4px 8px;">-${sp.discountPercent}%</span>` : '';
                let priceHtml = sp.oldPrice 
                    ? `<div class="d-flex align-items-baseline justify-content-center gap-2 mb-1">
                           <span class="fw-bold text-danger" style="font-size: 1.05rem;">${sp.price}</span>
                           <del class="text-secondary" style="font-size: 0.82rem;">${sp.oldPrice}</del>
                       </div>`
                    : `<div class="fw-bold text-dark mb-1" style="font-size: 1.05rem;">${sp.price}</div>`;

                htmlContent += `
                    <div class="col-6 col-md-3 mb-5">
                        <div class="product-card h-100">
                            <a href="/chi-tiet-san-pham/${sp.id}" class="text-decoration-none text-dark d-block">
                                <div class="product-card-img-wrapper position-relative mb-3">
                                    <img src="${sp.image}" alt="${sp.name}" class="product-card-img w-100" style="aspect-ratio:3/4; object-fit:cover;" />
                                    ${discountHtml}
                                    <div class="product-card-overlay">
                                        <span class="product-quick-view">Xem nhanh</span>
                                    </div>
                                </div>
                            </a>
                            <div class="product-card-info-center">
                                ${priceHtml}
                                <h6 class="product-name mb-2" style="text-align:center;">
                                    <a href="/chi-tiet-san-pham/${sp.id}" class="text-decoration-none text-muted">${sp.name}</a>
                                </h6>
                                ${hasFilters && vongMauHtml ? `<div class="product-color-dots-center">${vongMauHtml}</div>` : ''}
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