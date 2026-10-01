<?php
$file = 'resources/views/users/products/detail.blade.php';
$lines = file($file);

// Replace from line 58 to 120
array_splice($lines, 58, 63, [
'                <div class="d-flex align-items-baseline gap-2 mb-2">
',
'                    @if($product->discount_percent > 0)
',
'                        <div class="gia-sp fw-bolder text-danger" style="font-size: 2rem;">{{ number_format($product->sale_price, 0, ",", ".") }}đ</div>
',
'                        <del class="text-secondary" style="font-size: 1.2rem;">{{ number_format($product->price, 0, ",", ".") }}đ</del>
',
'                        <span class="badge bg-danger rounded-1 ms-1 px-2 py-1" style="font-size: 0.9rem;">-{{ $product->discount_percent }}%</span>
',
'                    @else
',
'                        <div class="gia-sp fw-bolder text-danger" style="font-size: 2rem;">{{ number_format($product->price, 0, ",", ".") }}đ</div>
',
'                    @endif
',
'                </div>
',
'                
',
'                <h1 class="tieu-de-sp mb-4 text-dark" style="font-size: 1.6rem; font-weight: 500;">{{ $product->name }}</h1>
',
'                
',
'                @if(count($colors) > 0)
',
'                    <div class="mb-4">
',
'                        <div class="d-flex align-items-center mb-3">
',
'                            <span class="text-dark" style="font-size: 1rem;">Màu sắc: </span>
',
'                            <span class="ms-1 text-dark" id="ten-mau-hien-thi"></span>
',
'                        </div>
',
'                        <div class="d-flex gap-2 flex-wrap align-items-center">
',
'                            @foreach($colors as $color)
',
'                                <div class="khung-chon-mau" onclick="chonMau(\'{{ $color }}\', this)" data-color="{{ $color }}">
',
'                                    <div class="vong-mau-ngoai">
',
'                                        <div class="vong-mau-trong" style="background-color: {{ $color }};"></div>
',
'                                    </div>
',
'                                    <div class="ten-mau d-none">{{ $colorNames[$color] ?? $color }}</div>
',
'                                </div>
',
'                            @endforeach
',
'                        </div>
',
'                    </div>
',
'                @endif
',
'
',
'                @if(count($sizes) > 0)
',
'                    <div class="mb-4">
',
'                        <div class="d-flex justify-content-between align-items-end mb-3">
',
'                            <div>
',
'                                <span class="text-dark" style="font-size: 1rem;">Kích thước: </span>
',
'                                <span class="ms-1 text-dark" id="ten-size-hien-thi"></span>
',
'                            </div>
',
'                        </div>
',
'                        <div class="d-flex gap-2 flex-wrap">
',
'                            @foreach($sizes as $size)
',
'                                <div onclick="chonSize(\'{{ $size }}\', this)" class="btn-size-item">
',
'                                    {{ $size }}
',
'                                </div>
',
'                            @endforeach
',
'                        </div>
',
'                    </div>
',
'                @endif
',
'
',
'                <div class="d-flex gap-3 align-items-center mt-3 mb-5">
',
'                    <div class="khung-so-luong bg-white border rounded-pill py-2 px-3 d-flex align-items-center justify-content-between shadow-sm" style="width: 140px; height: 52px;">
',
'                        <button onclick="thayDoiSoLuong(-1)" class="nut-so-luong fs-4 border-0 bg-transparent text-secondary d-flex align-items-center justify-content-center" style="width:30px;">-</button>
',
'                        <div id="hien-thi-so-luong" class="so-luong-hien-thi fw-medium fs-5">1</div>
',
'                        <button onclick="thayDoiSoLuong(1)" class="nut-so-luong fs-4 border-0 bg-transparent text-secondary d-flex align-items-center justify-content-center" style="width:30px;">+</button>
',
'                    </div>
',
'                    
',
'                    <button onclick="themVaoGio()" class="nut-them-gio flex-grow-1 h-100 d-flex align-items-center justify-content-center gap-2 text-white fw-medium border-0 rounded-pill shadow-sm" style="height: 52px; background-color: #FF6B6B; font-size: 1.1rem; transition: transform 0.2s;" onmouseover="this.style.transform=\'translateY(-2px)\'" onmouseout="this.style.transform=\'translateY(0)\'">
',
'                        Thêm vào giỏ 
',
'                        <i class="bi bi-bag"></i>
',
'                    </button>
',
'                </div>
'
]);

file_put_contents($file, implode("", $lines));
echo 'Done';
