<div class="product-card h-100">
    <a href="{{ route('product.detail', $sp->id) }}" class="text-decoration-none text-dark d-block">
        <div class="product-card-img-wrapper position-relative mb-3">
            <img src="{{ asset($sp->image_url ?? 'images/default.jpg') }}"
                 alt="{{ $sp->name }}"
                 class="product-card-img w-100" />
            @if($sp->discount_percent > 0)
                <span class="product-badge-discount">
                    -{{ $sp->discount_percent }}%
                </span>
            @endif
            <div class="product-card-overlay">
                <span class="product-quick-view">Xem nhanh</span>
            </div>
        </div>
    </a>
    <div class="product-card-info {{ isset($alignLeft) && $alignLeft ? '' : 'text-center' }}">
        @if($sp->discount_percent > 0)
            <div class="d-flex align-items-baseline {{ isset($alignLeft) && $alignLeft ? '' : 'justify-content-center' }} gap-2 mb-1">
                <span class="product-price-sale">{{ number_format($sp->discount_percent > 0 ? $sp->sale_price : $sp->price, 0, ',', '.') }}đ</span>
                <del class="product-price-old">{{ number_format($sp->price, 0, ',', '.') }}đ</del>
            </div>
        @else
            <div class="product-price-normal mb-1">{{ number_format($sp->price, 0, ',', '.') }}đ</div>
        @endif
        <h6 class="product-name mb-2">
            <a href="{{ route('product.detail', $sp->id) }}" class="text-decoration-none text-muted">{{ $sp->name }}</a>
        </h6>
        @php
            $colors = $sp->variants ? $sp->variants->pluck('color')->filter()->unique()->values() : [];
        @endphp
        @if(count($colors) > 0)
            <div class="d-flex {{ isset($alignLeft) && $alignLeft ? '' : 'justify-content-center' }} gap-1 mt-1">
                @foreach($colors as $c)
                    <a href="{{ route('product.detail', $sp->id) }}?color={{ urlencode($c) }}"
                       class="color-dot"
                       style="background-color: {{ $c }};"
                       title="{{ $c }}"></a>
                @endforeach
            </div>
        @endif
    </div>
</div>
