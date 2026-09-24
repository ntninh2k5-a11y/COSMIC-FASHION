@extends('admin.layouts.admin')

@section('title', 'Thêm Banner - Admin')

@section('page-title', 'THÊM BANNER MỚI')

@section('content')
<div class="row">
    <div class="col-12 col-xl-8">
        <div class="neo-card">
            <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label class="fw-bold mb-2">Hình ảnh Banner <span class="text-danger">*</span></label>
                    <input type="file" name="image" class="form-control border-dark border-2 rounded-0" accept="image/*" required>
                    <small class="text-secondary mt-1 d-block">Khuyên dùng ảnh ngang (tỷ lệ 16:9 hoặc 21:9) cho banner chính.</small>
                    @error('image')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="fw-bold mb-2">Tiêu đề (Tùy chọn)</label>
                    <input type="text" name="title" class="form-control border-dark border-2 rounded-0 fw-bold" value="{{ old('title') }}" placeholder="Nhập tiêu đề banner">
                    @error('title')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="fw-bold mb-2">Đường dẫn / Link (Tùy chọn)</label>
                    <input type="text" name="link" class="form-control border-dark border-2 rounded-0" value="{{ old('link') }}" placeholder="https://...">
                    <small class="text-secondary mt-1 d-block">Khi khách hàng click vào banner sẽ chuyển đến trang này.</small>
                    @error('link')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="fw-bold mb-2">Thứ tự hiển thị</label>
                        <input type="number" name="order" class="form-control border-dark border-2 rounded-0 fw-bold" value="{{ old('order', 0) }}">
                        <small class="text-secondary mt-1 d-block">Số nhỏ hơn sẽ hiển thị trước.</small>
                    </div>

                    <div class="col-md-6 mb-4 d-flex align-items-end pb-2">
                        <div class="form-check">
                            <input class="form-check-input border-dark border-2 rounded-0" type="checkbox" name="is_active" id="isActive" value="1" {{ old('is_active', true) ? 'checked' : '' }} style="width: 20px; height: 20px;">
                            <label class="form-check-label fw-bold ms-2 pt-1" for="isActive">
                                Hiển thị ngay
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="neo-btn flex-grow-1">THÊM BANNER</button>
                    <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-dark fw-bold rounded-0 border-2" style="width: 150px;">HỦY BỎ</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
