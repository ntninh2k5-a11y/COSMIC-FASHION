<?php
$file = 'resources/views/users/products/detail.blade.php';
$lines = file($file);

array_splice($lines, 35, 9, [
'                <div class="d-flex gap-2 align-items-stretch">
',
'                    <div class="d-none d-md-flex flex-column gap-2" style="width: 100px; flex-shrink: 0;" id="productThumbnails">
',
'                        @foreach($allImages as $index => $img)
',
'                            <div class="khung-thumb {{ $index === 0 ? \'dang-chon\' : \'\' }} w-100" style="flex: 1; height: 0; cursor: pointer; border-radius: 12px; overflow: hidden; border: 2px solid transparent;" onclick="changeMainImage({{ $index }}, \'{{ asset($img[\'url\']) }}\', this)">
',
'                                <img src="{{ asset($img[\'url\']) }}" class="w-100 h-100" style="object-fit: cover;" alt="thumb {{ $index }}">
',
'                            </div>
',
'                        @endforeach
',
'                    </div>
',
'                    
'
]);

file_put_contents($file, implode("", $lines));
echo "Done";
