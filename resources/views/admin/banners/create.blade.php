@extends('admin.layouts.admin')

@section('title', 'Thêm Banner - Admin')

@section('page-title', 'THÊM BANNER MỚI')

@section('content')
<div class="row">
    <div class="col-12 col-xl-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <h2 class="admin-card-title"><i class="bi bi-images text-muted"></i> Thêm Banner Mới</h2>
            </div>
            
            <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-4">
                    <label class="form-label fw-semibold">HÌNH ẢNH BANNER <span class="text-danger">*</span></label>
                    <input type="file" name="image" class="form-control" accept="image/*" required>
                    <small class="text-muted mt-1 d-block">Khuyên dùng ảnh ngang (tỷ lệ 16:9 hoặc 21:9) cho banner chính.</small>
                    @error('image')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">TIÊU ĐỀ (TÙY CHỌN)</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="Nhập tiêu đề banner">
                    @error('title')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">ĐƯỜNG DẪN / LINK (TÙY CHỌN)</label>
                    <input type="text" name="link" class="form-control" value="{{ old('link') }}" placeholder="https://...">
                    <small class="text-muted mt-1 d-block">Khi khách hàng click vào banner sẽ chuyển đến trang này.</small>
                    @error('link')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold">THỨ TỰ HIỂN THỊ</label>
                        <input type="number" name="order" class="form-control" value="{{ old('order', 0) }}">
                        <small class="text-muted mt-1 d-block">Số nhỏ hơn sẽ hiển thị trước.</small>
                    </div>

                    <div class="col-md-6 mb-4 d-flex align-items-center">
                        <div class="form-check form-switch mt-3">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" {{ old('is_active', true) ? 'checked' : '' }} style="width: 40px; height: 20px;">
                            <label class="form-check-label fw-semibold ms-2" for="isActive">Hiển thị ngay</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.banners.index') }}" class="btn-outline-admin text-muted">HỦY BỎ</a>
                    <button type="submit" class="btn-primary-admin px-4"><i class="bi bi-check2"></i> THÊM BANNER</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
