<footer class="footer-bg mt-5">
    @php
        $footerColumns = \App\Models\FooterMenu::whereNull('parent_id')
            ->where('status', 1)
            ->orderBy('sort_order')
            ->with(['children' => fn($q) => $q->where('status',1)->orderBy('sort_order')])
            ->get();
    @endphp

    <!-- Main Footer -->
    <div class="py-5">
        <div class="container">
            <div class="row g-5">

                <!-- Brand Column -->
                <div class="col-lg-4 col-md-12 mb-4 mb-lg-0">
                    <div class="mb-4">
                        <a href="{{ route('home') }}" class="text-decoration-none d-block mb-3"
                           style="font-size: 22px; font-weight:800; letter-spacing: 3px; font-family:'Playfair Display',serif; background: linear-gradient(135deg, #FF6B6B, #4ECDC4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                            COSMIC FASHION
                        </a>
                        <p style="font-size: 13px; color: #94a3b8; line-height: 1.8; max-width: 300px;">
                            Thương hiệu thời trang trẻ trung, năng động. Mang phong cách thế giới đến với bạn.
                        </p>
                    </div>

                </div>

                <!-- Dynamic Columns from DB -->
                @foreach($footerColumns as $column)
                <div class="col-lg col-md-4 col-6">
                    <div class="footer-title mb-3">
                        @if($column->icon)
                            <i class="bi {{ $column->icon }} me-1"></i>
                        @endif
                        {{ strtoupper($column->name) }}
                    </div>

                    @if($column->description)
                        <p style="font-size: 12px; color: #64748b; line-height: 1.7; margin-bottom: 12px;">
                            {{ $column->description }}
                        </p>
                    @endif

                    @if($column->children->count() > 0)
                        @php
                            $socialLinks = $column->children->where('type', 'social');
                            $paymentLinks = $column->children->where('type', 'payment');
                            $defaultLinks = $column->children->where('type', 'default');
                        @endphp

                        {{-- Render Default Links --}}
                        @if($defaultLinks->count() > 0)
                            <ul class="footer-list list-unstyled mb-3">
                                @foreach($defaultLinks as $link)
                                    <li class="mb-3">
                                        @if($link->description)
                                            <div class="footer-contact-card">
                                                <div class="footer-contact-icon">
                                                    <i class="bi {{ $link->icon ?: 'bi-telephone-fill' }}"></i>
                                                </div>
                                                <div class="footer-contact-body">
                                                    @if($link->is_static || !$link->url || $link->url === '#')
                                                        <div class="footer-contact-number">{{ $link->name }}</div>
                                                    @else
                                                        <a href="{{ $link->url }}" class="footer-contact-number text-decoration-none">{{ $link->name }}</a>
                                                    @endif
                                                    <div class="footer-contact-desc">{{ $link->description }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="mb-2">
                                                @if($link->is_static)
                                                    <span class="footer-link" style="cursor:default;">
                                                        @if($link->icon)<i class="bi {{ $link->icon }} me-1 opacity-75"></i>@endif
                                                        {{ $link->name }}
                                                    </span>
                                                @else
                                                    <a href="{{ $link->url ?? '#' }}" class="footer-link">
                                                        @if($link->icon)<i class="bi {{ $link->icon }} me-1 opacity-75"></i>@endif
                                                        {{ $link->name }}
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        {{-- Render Social Links --}}
                        @if($socialLinks->count() > 0)
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @foreach($socialLinks as $link)
                                    <a href="{{ $link->url ?? '#' }}" class="footer-social-btn" title="{{ $link->name }}">
                                        @if($link->icon)
                                            <i class="bi {{ $link->icon }}"></i>
                                        @else
                                            {{ substr($link->name, 0, 1) }}
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        {{-- Render Payment Links --}}
                        @if($paymentLinks->count() > 0)
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @foreach($paymentLinks as $link)
                                    <div class="footer-payment-badge">
                                        @if($link->icon)
                                            <i class="bi {{ $link->icon }} me-1"></i>
                                        @endif
                                        {{ $link->name }}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>
                @endforeach

            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="footer-bottom">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <div style="font-size: 12px; color: #475569;">
                    © {{ date('Y') }} <strong style="color: #94a3b8;">Cosmic Fashion</strong>. Tất cả quyền được bảo lưu.
                </div>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="#" class="footer-bottom-link">Chính sách bảo mật</a>
                    <a href="#" class="footer-bottom-link">Điều khoản sử dụng</a>
                    <a href="#" class="footer-bottom-link">Cookie Policy</a>
                </div>
                <div style="font-size: 11px; color: #334155;">
                    Made with <span style="color: #FF6B6B;">♥</span> in Vietnam
                </div>
            </div>
        </div>
    </div>
</footer>