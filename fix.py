import sys
with open(r'C:\xampp\htdocs\laravel\ninh\resources\views\admin\products\edit.blade.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

new_content = '''                        <div class="p-3 border rounded mb-3 bg-light">
                            <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-image"></i> ?nh s?n ph?m CHÍNH</h6>
                            @if($product->image_url)
                                <div class="mb-3 text-center bg-white p-2 border rounded">
                                    <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}" width="150" style="border-radius: 8px; object-fit: cover;">
                                    <div class="form-check mt-2 text-start">
                                        <input class="form-check-input" type="checkbox" value="1" id="delete_image" name="delete_image">
                                        <label class="form-check-label text-danger" for="delete_image" style="font-size: 0.9rem;">
                                            Xóa ?nh hi?n t?i
                                        </label>
                                    </div>
                                </div>
                            @endif
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" onchange="previewImage(event)">
                            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="mt-3">
                                <img id="preview" src="#" style="max-width: 100%; display: none; border-radius: 8px; border: 1px solid #ddd;">
                            </div>
                        </div>

                        <div class="p-3 border rounded mb-3 bg-light">
                            <h6 class="fw-bold mb-3 text-secondary"><i class="bi bi-images"></i> ?nh s?n ph?m PH? (Gallery)</h6>
                            @if($product->images && $product->images->count() > 0)
                                <div class="mb-3 bg-white p-2 border rounded d-flex gap-2 flex-wrap">
                                    @foreach($product->images as $img)
                                        <img src="{{ asset($img->image_url) }}" alt="gallery" width="60" height="60" style="border-radius: 4px; object-fit: cover; border: 1px solid #ddd;">
                                    @endforeach
                                </div>
                                <div class="form-check mt-2 mb-3">
                                    <input class="form-check-input" type="checkbox" value="1" id="delete_gallery" name="delete_gallery">
                                    <label class="form-check-label text-danger" for="delete_gallery" style="font-size: 0.9rem;">
                                        Xóa t?t c? ?nh chi ti?t cu (n?u mu?n thay th? b?ng ?nh m?i)
                                    </label>
                                </div>
                            @endif
                            <input type="file" name="gallery_images[]" class="form-control" accept="image/*" multiple>
                            <small class="text-muted d-block mt-1">Có th? ch?n nhi?u ?nh cùng lúc d? làm ?nh ph?.</small>
                        </div>
'''

del lines[67:105]
lines.insert(67, new_content.replace('$', '$'))

with open(r'C:\xampp\htdocs\laravel\ninh\resources\views\admin\products\edit.blade.php', 'w', encoding='utf-8') as f:
    f.writelines(lines)
print('Done!')
