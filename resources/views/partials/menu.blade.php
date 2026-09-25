
<div class="top-bar-bg position-relative shadow-sm" style="border-bottom: 2px solid rgba(0,0,0,0.08);">
    <div class="container position-static">

        <ul class="menu-list">
            
            @foreach($mainCategories as $parent)
            <li class="nav-item has-mega-menu">
                

                <a href="{{ route('frontend.category.detail', $parent->slug ?? '') }}"
                   class="text-dark text-uppercase text-decoration-none menu-item-hover {{ request()->is('danh-muc/' . $parent->slug) ? 'active-menu' : '' }}">
                    {{ str_ireplace('Thời trang ', '', $parent->name) }}
                </a>
                
                @if($parent->children && $parent->children->where('status', 1)->count() > 0)
                <div class="mega-menu-container">
                    <div class="container py-5">
                        <div class="mega-menu-grid">
                            @foreach($parent->children->where('status', 1) as $child)
                            <div class="mega-col">
                                <a href="{{ route('frontend.category.detail', $child->slug ?? '') }}" class="mega-col-title">
                                    @if($child->image_url)
                                        <img src="{{ asset($child->image_url) }}" class="mega-col-icon" width="28" height="28">
                                    @endif
                                    {{ $child->name }}
                                </a>
                                
                                @if($child->children && $child->children->where('status', 1)->count() > 0)
                                    <ul class="mega-sub-list">
                                        @foreach($child->children->where('status', 1) as $grandchild)
                                        <li>
                                            <a href="{{ route('frontend.category.detail', $grandchild->slug ?? '') }}" class="mega-sub-link d-flex align-items-center gap-2">
                                                @if($grandchild->image_url)
                                                    <img src="{{ asset($grandchild->image_url) }}" class="mega-col-icon" width="24" height="24">
                                                @endif
                                                {{ $grandchild->name }}
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </li>
            @endforeach

            <!-- Nút Ưu Đãi -->
            <li class="nav-item">
                <a href="{{ route('shop.sale') }}" class="text-sale text-uppercase text-decoration-none menu-item-hover {{ request()->routeIs('shop.sale') ? 'active-menu' : '' }}">
                    ƯU ĐÃI ĐẶC BIỆT
                </a>
            </li>
            
        </ul>
    </div>
</div>