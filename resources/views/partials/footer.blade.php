<footer class="footer-bg py-5 mt-5">
    <div class="container">
        
        @php
            $footerColumns = \App\Models\FooterMenu::whereNull('parent_id')->where('status', 1)->get();
        @endphp

        <div class="row">
         
            @foreach($footerColumns as $column)
            <div class="col-md-4">
                <div class="footer-title text-uppercase fw-bold mb-3">{{ $column->name }}</div>
                
                @if($column->children && $column->children->where('status', 1)->count() > 0)
                <ul class="footer-list list-unstyled">
                    @foreach($column->children->where('status', 1) as $link)
                        <li class="mb-2">
                            <a href="{{ $link->url }}" class="text-decoration-none text-muted">
                                {{ $link->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
                @endif
                
            </div>
            @endforeach

            <div class="col-md-4">
                <div class="footer-title text-uppercase fw-bold mb-3">MẠNG XÃ HỘI</div>
                <ul class="footer-list list-unstyled mb-4">
                    <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Instagram</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Facebook</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-muted">TikTok</a></li>
                </ul>
                
                <div class="footer-title text-uppercase fw-bold mb-2" style="font-size: 14px;">THANH TOÁN AN TOÀN</div>
                <div class="payment-icons d-flex gap-2">
                    <span class="badge bg-secondary">PayPal</span>
                    <span class="badge bg-secondary">Visa/Mastercard</span>
                </div>
            </div>
            
        </div>
    </div>
</footer>