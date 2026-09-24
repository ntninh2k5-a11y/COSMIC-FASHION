

<div class="top-bar-bg position-relative shadow-sm" style="border-bottom: 2px solid rgba(0,0,0,0.08);">
    <div class="container position-static">
        
        @php
            // CHỐT CHẶN: Chỉ lấy đúng 3 danh mục gốc có ID là 1 (Nam), 2 (Nữ), 3 (Trẻ em)
            $mainCategories = \App\Models\Category::whereIn('id', [1, 2, 3])->where('status', 1)->get();
        @endphp

        <ul class="menu-list">
            
            @foreach($mainCategories as $parent)
            <li class="nav-item has-mega-menu">
                
                @php
                    // MẸO UI: Tự động xóa chữ "Thời trang " đi, chỉ hiển thị "NAM", "NỮ", "TRẺ EM"
                    $displayName = str_ireplace('Thời trang ', '', $parent->name);
                @endphp

                <!-- Tên danh mục cấp 1 -->
                <a href="{{ route('frontend.category.detail', $parent->slug ?? '') }}" class="text-dark text-uppercase text-decoration-none menu-item-hover {{ request()->is('danh-muc/' . $parent->slug) ? 'active-menu' : '' }}">
                    {{ $displayName }}
                </a>
                
                <!-- Mega Menu Xổ Xuống -->
                @if($parent->children && $parent->children->where('status', 1)->count() > 0)
                <div class="mega-menu-container text-center">
                    <div class="container d-flex pt-4 pb-4">
                        
                        <!-- Cột Danh mục chữ (75%) -->
                        <div class="menu-categories d-flex flex-wrap justify-content-center gap-5 w-75">
                            @foreach($parent->children->where('status', 1) as $child)
                            <div class="category-column d-flex flex-column align-items-center text-center" style="min-width: 180px;">
                                
                                <a href="{{ route('frontend.category.detail', $child->slug ?? '') }}" class="fw-bold text-dark mb-3 text-decoration-none d-flex align-items-center justify-content-center menu-item-hover" style="font-size: 14px;">
                                    @if($child->image_url)
                                        <img src="{{ asset($child->image_url) }}" class="rounded-circle me-2 border" width="35" height="35" style="object-fit: cover;">
                                    @endif
                                    {{ $child->name }}
                                </a>
                                
                                @if($child->children && $child->children->where('status', 1)->count() > 0)
                                    @foreach($child->children->where('status', 1) as $grandchild)
                                    <a href="{{ route('frontend.category.detail', $grandchild->slug ?? '') }}" class="text-secondary text-decoration-none mb-2 menu-item-hover" style="font-size: 13px;">
                                        {{ $grandchild->name }}
                                    </a>
                                    @endforeach
                                @endif
                                
                            </div>
                            @endforeach
                        </div>

                        <!-- Cột Ảnh Banner (25%) -->
                        <div class="menu-banners w-25 border-start text-center">
                            <h6 class="fw-bold mb-3" style="font-size: 14px; letter-spacing: 1px;">BỘ SƯU TẬP</h6>
                            <div class="d-flex justify-content-center">
                                <img src="{{ asset('uploads/categories/1787206327_maytinh-17-8.jpg') }}" class="img-fluid rounded shadow-sm w-100" style="object-fit: cover; height: 160px; max-width: 250px;">
                            </div>
                        </div>
                        
                    </div>
                </div>
                @endif
            </li>
            @endforeach

            <!-- Nút Ưu Đãi -->
            <li class="nav-item">
                <a href="{{ route('shop.sale') }}" class="text-sale text-uppercase text-decoration-none menu-item-hover {{ request()->routeIs('shop.sale') ? 'active-menu' : '' }}">
                    ƯU ĐÃI
                </a>
            </li>
            
        </ul>
    </div>
</div>