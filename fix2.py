import sys
with open(r'C:\xampp\htdocs\laravel\ninh\resources\views\users\products\detail.blade.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

new_content = '''
                <h1 class="tieu-de-sp mb-1 fw-bold text-dark" style="font-size: 1.8rem;">{{ $product->name }}</h1>
                <div class="sku-sp mb-3 text-secondary">Mã SP: SP{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}</div>

                <div class="bg-light p-4 rounded-4 shadow-sm border mb-4">
                    <div class="d-flex align-items-baseline gap-2 mb-4 pb-3 border-bottom">
                        @if($product->discount_percent > 0)
                            <div class="gia-sp fw-bolder text-danger" style="font-size: 1.8rem;">{{ number_format($product->sale_price, 0, ',', '.') }}d</div>
                            <del class="text-secondary fs-5">{{ number_format($product->price, 0, ',', '.') }}d</del>
                        @else
                            <div class="gia-sp fw-bolder text-danger" style="font-size: 1.8rem;">{{ number_format($product->price, 0, ',', '.') }}d</div>
                        @endif
                    </div>
                    
                    @if(count($colors) > 0)
                        <div class="mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <span class="text-dark fw-medium" style="font-size: 0.95rem;">Màu s?c: </span>
                                <span class="ms-1 fw-bold text-dark" id="ten-mau-hien-thi"></span>
                            </div>
                            <div class="d-flex gap-2 flex-wrap align-items-center">
                                @foreach($colors as $color)
                                    <div class="khung-chon-mau" onclick="chonMau('{{ $color }}', this)" data-color="{{ $color }}">
                                        <div class="vong-mau-ngoai">
                                            <div class="vong-mau-trong" style="background-color: {{ $color }};"></div>
                                        </div>
                                        <div class="ten-mau d-none">{{ $colorNames[$color] ?? $color }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(count($sizes) > 0)
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-end mb-2">
                                <div>
                                    <span class="text-dark fw-medium" style="font-size: 0.95rem;">Kích thu?c: </span>
                                    <span class="ms-1 fw-bold text-dark" id="ten-size-hien-thi"></span>
                                </div>
                            </div>
                            <div class="d-flex gap-2 flex-wrap">
                                @foreach($sizes as $size)
                                    <div onclick="chonSize('{{ $size }}', this)" class="btn-size-item">
                                        {{ $size }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="d-flex gap-3 align-items-center">
                        <div class="khung-so-luong bg-white border rounded">
                            <button onclick="thayDoiSoLuong(-1)" class="nut-so-luong fs-5 border-0 bg-transparent">-</button>
                            <div id="hien-thi-so-luong" class="so-luong-hien-thi fw-bold px-3">1</div>
                            <button onclick="thayDoiSoLuong(1)" class="nut-so-luong fs-5 border-0 bg-transparent">+</button>
                        </div>
                        
                        <button onclick="themVaoGio()" class="nut-them-gio flex-grow-1 h-100 d-flex align-items-center justify-content-center gap-2 text-white fw-bold border-0 rounded" style="height: 48px; background-color: #FF6B6B;">
                            Thêm vào gi? 
                            <i class="bi bi-bag"></i>
                        </button>
                    </div>
                </div>
'''

del lines[58:128]
lines.insert(58, new_content.replace('$', '$'))

with open(r'C:\xampp\htdocs\laravel\ninh\resources\views\users\products\detail.blade.php', 'w', encoding='utf-8') as f:
    f.writelines(lines)
print('Done!')
