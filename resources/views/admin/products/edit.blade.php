@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Chỉnh sửa sản phẩm</h1>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $product->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Giá gốc <span class="text-danger">*</span></label>
                                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" 
                                       value="{{ old('price', $product->price) }}" min="0" step="1000" required>
                                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Giá khuyến mãi</label>
                                <input type="number" name="sale_price" class="form-control @error('sale_price') is-invalid @enderror" 
                                       value="{{ old('sale_price', $product->sale_price) }}" min="0" step="1000">
                                @error('sale_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">% Giảm giá</label>
                                <input type="number" name="discount_percent" class="form-control @error('discount_percent') is-invalid @enderror" 
                                       value="{{ old('discount_percent', $product->discount_percent) }}" min="0" max="100">
                                @error('discount_percent') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5">{{ old('description', $product->description) }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Ảnh sản phẩm</label>
                            @if($product->image_url)
                                <div class="mb-2">
                                    <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}" width="150" style="border-radius: 8px; object-fit: cover;">
                                </div>
                            @endif
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" onchange="previewImage(event)">
                            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="mt-3">
                                <img id="preview" src="#" style="max-width: 100%; display: none; border-radius: 8px;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="1" {{ old('status', $product->status) == 1 ? 'selected' : '' }}>Hiển thị</option>
                                <option value="0" {{ old('status', $product->status) == 0 ? 'selected' : '' }}>Ẩn</option>
                            </select>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Size & Màu sắc (Tồn kho)</h5>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addVariantRow()">
                        + Thêm dòng
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle" id="variants-table">
                        <thead class="table-light">
                            <tr>
                                <th width="20%">Size</th>
                                <th width="30%">Màu sắc</th>
                                <th width="25%">Số lượng tồn</th>
                                <th width="15%">Xóa</th>
                            </tr>
                        </thead>
                        <tbody id="variants-body">
                            @forelse($product->variants as $index => $variant)
                                <tr>
                                    <td>
                                        <select name="variants[{{ $index }}][size]" class="form-select" required>
                                            <option value="">Chọn size</option>
                                            @foreach($sizes as $size)
                                                <option value="{{ $size }}" {{ $variant->size == $size ? 'selected' : '' }}>
                                                    {{ $size }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="variants[{{ $index }}][color]" class="form-select" required>
                                            <option value="">Chọn màu</option>
                                            @foreach($colors as $name => $hex)
                                                <option value="{{ $hex }}" {{ $variant->color == $hex ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="variants[{{ $index }}][stock_quantity]" 
                                               class="form-control" value="{{ $variant->stock_quantity }}" min="0" required>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()">Xóa</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td>
                                        <select name="variants[0][size]" class="form-select" required>
                                            <option value="">Chọn size</option>
                                            @foreach($sizes as $size)
                                                <option value="{{ $size }}">{{ $size }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="variants[0][color]" class="form-select" required>
                                            <option value="">Chọn màu</option>
                                            @foreach($colors as $name => $hex)
                                                <option value="{{ $hex }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="variants[0][stock_quantity]" class="form-control" value="0" min="0" required>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()">Xóa</button>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Cập nhật sản phẩm</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let variantIndex = {{ $product->variants->count() > 0 ? $product->variants->count() : 1 }};

    function addVariantRow() {
        const tbody = document.getElementById('variants-body');
        const newRow = document.createElement('tr');

        newRow.innerHTML = `
            <td>
                <select name="variants[${variantIndex}][size]" class="form-select" required>
                    <option value="">Chọn size</option>
                    @foreach($sizes as $size)
                        <option value="{{ $size }}">{{ $size }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select name="variants[${variantIndex}][color]" class="form-select" required>
                    <option value="">Chọn màu</option>
                    @foreach($colors as $name => $hex)
                        <option value="{{ $hex }}">{{ $name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="variants[${variantIndex}][stock_quantity]" class="form-control" value="0" min="0" required>
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()">Xóa</button>
            </td>
        `;

        tbody.appendChild(newRow);
        variantIndex++;
    }

    function previewImage(event) {
        const preview = document.getElementById('preview');
        preview.src = URL.createObjectURL(event.target.files[0]);
        preview.style.display = 'block';
    }
</script>
@endsection