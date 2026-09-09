<div class="top-bar-bg">
    <div class="container">
        <ul class="menu-list">
            <li>
                <a href="{{ route('categories') }}" class="menu-font {{ request()->routeIs('categories') ? 'fw-bold' : '' }}">
                    Thể Loại
                </a>
            </li>
            <li>
                <a href="{{ route('frontend.category.detail', 'nam') }}" class="menu-font {{ request()->is('danh-muc/nam') ? 'fw-bold' : '' }}">
                    Nam
                </a>
            </li>
            <li>
                <a href="{{ route('frontend.category.detail', 'nu') }}" class="menu-font {{ request()->is('danh-muc/nu') ? 'fw-bold' : '' }}">
                    Nữ
                </a>
            </li>
            <li>
                <a href="{{ route('frontend.category.detail', 'tre_em') }}" class="menu-font {{ request()->is('danh-muc/tre_em') ? 'fw-bold' : '' }}">
                    Trẻ Em
                </a>
            </li>
            <li>
                <a href="{{ route('frontend.category.detail', 'phu_kien') }}" class="menu-font {{ request()->is('danh-muc/phu_kien') ? 'fw-bold' : '' }}">
                    Phụ Kiện
                </a>
            </li>
            <li>
                <a href="{{ route('shop.sale') }}" class="menu-font {{ request()->routeIs('shop.sale') ? 'fw-bold' : '' }}">
                    Ưu Đãi
                </a>
            </li>
        </ul>
    </div>
</div>