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
                            <img src="{{ asset('icon_react/search.svg') }}" class="my-icon" alt="search" />
                        </span>
                    </form>
                </div>

                <div class="d-inline-block mx-2">
                    @if(!session()->has('user_id'))
                        <a href="{{ route('login') }}" class="text-dark text-decoration-none icon-btn">
                            <img src="{{ asset('icon_react/person.svg') }}" class="my-icon" alt="user" />
                        </a>
                    @else
                        <div class="dropdown d-inline-block icon-btn">
                            <a class="text-dark text-decoration-none fw-bold dropdown-toggle dropdown-user-name" href="#" role="button" data-bs-toggle="dropdown">
                                {{ session('user_name') }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end rounded-0 shadow-sm">
                                @if(session('user_role') === 'admin')
                                <li>
                                    <a class="dropdown-item py-2 fw-bold" href="{{ route('admin.dashboard') }}" style="color: #1A2B4C;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-shield-lock me-1 mb-1" viewBox="0 0 16 16"><path d="M5.338 1.59a61.44 61.44 0 0 0-2.837.856.481.481 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.725 10.725 0 0 0 2.287 2.233c.346.244.652.42.893.533.12.057.218.095.293.118a.55.55 0 0 0 .101.025.615.615 0 0 0 .1-.025c.076-.023.174-.061.294-.118.24-.113.547-.29.893-.533a10.726 10.726 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.775 11.775 0 0 1-2.517 2.453 7.159 7.159 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7.158 7.158 0 0 1-1.048-.625 11.777 11.777 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 62.456 62.456 0 0 1 5.072.56z"/><path d="M9.5 6.5a1.5 1.5 0 0 1-1 1.415l.385 1.99a.5.5 0 0 1-.491.595h-.788a.5.5 0 0 1-.49-.595l.384-1.99A1.5 1.5 0 1 1 9.5 6.5z"/></svg>
                                        Trang quản trị
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider m-0"></li>
                                @endif
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('user.orders') }}">
                                        Đơn hàng của tôi
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
                    <img src="{{ asset('icon_react/cart2.svg') }}" class="my-icon" alt="cart" />
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