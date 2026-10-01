<?php
$file = 'resources/views/admin/products/edit.blade.php';
$content = file_get_contents($file);

// Replace main image section
$pattern1 = '/<div class="mb-3">\s*<label class="form-label">Ảnh sản phẩm<\/label>.*?<\/div>\s*<\/div>/s';
$replace1 = '<div class="p-3 border rounded mb-3 bg-light">
    <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-image"></i> Ảnh sản phẩm CHÍNH</h6>
    @if($product->image_url)
        <div class="mb-3 text-center bg-white p-2 border rounded">
            <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}" width="150" style="border-radius: 8px; object-fit: cover;">
            <div class="form-check mt-2 text-start">
                <input class="form-check-input" type="checkbox" value="1" id="delete_image" name="delete_image">
                <label class="form-check-label text-danger" for="delete_image" style="font-size: 0.9rem;">
                    Xóa ảnh hiện tại
                </label>
            </div>
        </div>
    @endif
    <input type="file" name="image" class="form-control @error(\'image\') is-invalid @enderror" accept="image/*" onchange="previewImage(event)">
    @error(\'image\') <div class="invalid-feedback">{{ $message }}</div> @enderror
    <div class="mt-3">
        <img id="preview" src="#" style="max-width: 100%; display: none; border-radius: 8px; border: 1px solid #ddd;">
    </div>
</div>';
$content = preg_replace($pattern1, $replace1, $content, 1);

// Replace gallery section
$pattern2 = '/<div class="mb-3">\s*<label class="form-label">Ảnh chi tiết \(Nhiều ảnh\)<\/label>.*?<\/small>\s*<\/div>/s';
$replace2 = '<div class="p-3 border rounded mb-3 bg-light">
    <h6 class="fw-bold mb-3 text-secondary"><i class="bi bi-images"></i> Ảnh sản phẩm PHỤ (Gallery)</h6>
    @if($product->images && $product->images->count() > 0)
        <div class="mb-3 bg-white p-2 border rounded d-flex gap-2 flex-wrap">
            @foreach($product->images as $img)
                <img src="{{ asset($img->image_url) }}" alt="gallery" width="60" height="60" style="border-radius: 4px; object-fit: cover; border: 1px solid #ddd;">
            @endforeach
        </div>
        <div class="form-check mt-2 mb-3">
            <input class="form-check-input" type="checkbox" value="1" id="delete_gallery" name="delete_gallery">
            <label class="form-check-label text-danger" for="delete_gallery" style="font-size: 0.9rem;">
                Xóa tất cả ảnh chi tiết cũ (nếu muốn thay thế bằng ảnh mới)
            </label>
        </div>
    @endif
    <input type="file" name="gallery_images[]" class="form-control" accept="image/*" multiple>
    <small class="text-muted d-block mt-1">Có thể chọn nhiều ảnh cùng lúc để làm ảnh phụ.</small>
</div>';
$content = preg_replace($pattern2, $replace2, $content, 1);

file_put_contents($file, $content);
echo 'Done';
