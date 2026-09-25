

<div class="top-bar-bg position-relative shadow-sm" style="border-bottom: 2px solid rgba(0,0,0,0.08);">
    <div class="container position-static">
        
        @php
            $mainCategories = \App\Models\Category::whereIn('id', [1, 2, 3])->where('status', 1)->get();
        @endphp

        <ul class="menu-list">
            
            @foreach($mainCategories as $parent)
            <li class="nav-item has-mega-menu">
                
                @php
                    $displayName = str_ireplace('Thời trang ', '', $parent->name);
                @endphp

                <a href="{{ route('frontend.category.detail', $parent->slug ?? '') }}" class="text-dark text-uppercase text-decoration-none menu-item-hover {{ request()->is('danh-muc/' . $parent->slug) ? 'active-menu' : '' }}">
                    {{ $displayName }}
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

<style>
/* ===== MEGA MENU REDESIGN ===== */
.mega-menu-container {
    position: absolute;
    top: 100%; left: 0;
    width: 100%;
    background: #fff;
    opacity: 0;
    visibility: hidden;
    transform: translateY(12px);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 999;
    border-top: 3px solid #FF6B6B;
    border-bottom: 1px solid #f3f4f6;
    box-shadow: 0 20px 50px rgba(0,0,0,0.10);
}
.has-mega-menu:hover .mega-menu-container {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.mega-menu-grid {
    display: flex;
    gap: 0;
    flex-wrap: wrap;
    justify-content: center;
}

.mega-col {
    flex: 1;
    min-width: 160px;
    padding: 0 24px;
    border-right: 1px solid #f3f4f6;
    position: relative;
}
.mega-col:last-child { border-right: none; }

.mega-col-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    color: #111;
    text-decoration: none;
    padding-bottom: 14px;
    border-bottom: 1.5px solid #f3f4f6;
    margin-bottom: 14px;
    transition: color 0.2s;
}
.mega-col-title:hover { color: #FF6B6B; }

.mega-col-icon {
    border-radius: 50%;
    object-fit: cover;
    border: 1.5px solid #f0f0f0;
    flex-shrink: 0;
}

.mega-sub-list {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.mega-sub-link {
    font-size: 13.5px;
    color: #555;
    text-decoration: none;
    transition: all 0.2s ease;
    padding-left: 0;
}
.mega-sub-link:hover {
    color: #FF6B6B;
    padding-left: 8px;
}
</style>