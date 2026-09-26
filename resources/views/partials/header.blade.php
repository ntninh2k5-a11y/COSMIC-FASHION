<div class="top-bar-bg">
    <header class="custom-header">
        <div class="container d-flex justify-content-between align-items-center">

            <div class="header-col">
                <a href="{{ route('home') }}" class="logo-2tc text-decoration-none">
                    <span class="logo-2tc-text">2T</span><span class="logo-2tc-amp">&amp;</span><span class="logo-2tc-text">C</span>
                </a>
            </div>

            <div class="header-col text-center">
                <h1>
                    <a href="{{ route('home') }}" class="brand-name">
                        COSMIC FASHION
                    </a>
                </h1>
            </div>

            <div class="header-col header-right align-items-center d-flex">
                
                <div class="search-container">
                    <form action="{{ route('search') }}" method="GET" id="searchForm" class="d-flex align-items-center m-0">
                        <input
                            type="text"
                            name="q"
                            id="searchInput"
                            placeholder="Tìm kiếm sản phẩm..."
                            class="search-input"
                        />
                        <span class="icon-btn" id="searchIconBtn">
                            <i class="bi bi-search" style="font-size: 1.1rem;"></i>
                        </span>
                    </form>
                </div>

                <div class="d-inline-block mx-2">
                    @if(!session()->has('user_id'))
                        <a href="{{ route('login') }}" class="text-dark text-decoration-none icon-btn">
                            <i class="bi bi-person" style="font-size: 1.3rem;"></i>
                        </a>
                    @else
                        <div class="dropdown d-inline-block icon-btn">
                            <a class="text-dark text-decoration-none fw-bold dropdown-toggle dropdown-user-name d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
                                @if(session()->has('user_avatar'))
                                    <img src="{{ asset(session('user_avatar')) }}" alt="Avatar" class="rounded-circle object-fit-cover" style="width: 28px; height: 28px;">
                                @else
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-primary" style="width: 28px; height: 28px; font-size: 0.9rem;">
                                        {{ strtoupper(substr(session('user_name'), 0, 1)) }}
                                    </div>
                                @endif
                                {{ session('user_name') }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end rounded-0 shadow-sm">
                                @if(session('user_role') === 'admin')
                                <li>
                                    <a class="dropdown-item py-2 fw-bold" href="{{ route('admin.dashboard') }}" style="color: #1A2B4C;">
                                        <i class="bi bi-shield-lock me-1"></i>
                                        Trang quản trị
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider m-0"></li>
                                @endif
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('user.profile') }}">
                                        Trang cá nhân
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider m-0"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 text-danger border-0 bg-transparent btn-logout">
                                            Đăng xuất
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endif
                </div>

                <a href="{{ route('cart') }}" class="d-inline-block position-relative mx-2 icon-btn">
                    <i class="bi bi-bag" style="font-size: 1.2rem;"></i>
                    <span 
                        id="cartBadge"
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none cart-badge" 
                    >
                        0
                    </span>
                </a>
                
            </div>
        </div>
    </header>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        const searchIconBtn = document.getElementById('searchIconBtn');
        const searchInput = document.getElementById('searchInput');
        const searchForm = document.getElementById('searchForm');
        let hienTimKiem = false;

        searchIconBtn.addEventListener('click', function() {
            const tuKhoa = searchInput.value.trim();
            
            if (hienTimKiem && tuKhoa !== '') {
                searchForm.submit();
            } else if (hienTimKiem && tuKhoa === '') {
                alert('Vui lòng nhập từ khóa!');
            } else {
                hienTimKiem = !hienTimKiem;
                if (hienTimKiem) {
                    searchInput.classList.add('active');
                    searchInput.focus();
                } else {
                    searchInput.classList.remove('active');
                }
            }
        });

        function capNhatSoLuongGio() {
            fetch('{{ route('cart.count') }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(res => res.json())
                .then(data => {
                    const cartBadge = document.getElementById('cartBadge');
                    if (data.count > 0) {
                        cartBadge.innerText = data.count;
                        cartBadge.classList.remove('d-none');
                    } else {
                        cartBadge.classList.add('d-none');
                    }
                })
                .catch(err => console.error('Lỗi lấy số lượng giỏ hàng:', err));
        }

        capNhatSoLuongGio();
        window.addEventListener('cartUpdated', capNhatSoLuongGio);
    });
</script>