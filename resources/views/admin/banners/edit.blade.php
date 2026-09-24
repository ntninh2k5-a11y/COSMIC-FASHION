@extends('admin.layouts.admin')

@section('title', 'Sửa Banner - Admin')

@section('page-title', 'SỬA BANNER')

@section('content')
<div class="row">
    <div class="col-12 col-xl-8">
        <div class="neo-card">
            <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-4 text-center">
                    <label class="fw-bold d-block mb-2 text-start">Hình ảnh hiện tại</label>
                    <img src="{{ asset($banner->image_url) }}" alt="Banner" class="img-fluid rounded border border-2 border-dark" style="max-height: 200px;">
                </div>

                <div class="mb-3">
                    <label class="fw-bold mb-2">Thay đổi hình ảnh</label>
                    <input type="file" name="image" class="form-control border-dark border-2 rounded-0" accept="image/*">
                    <small class="text-secondary mt-1 d-block">Bỏ trống nếu muốn giữ nguyên ảnh hiện tại.</small>
                    @error('image')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="fw-bold mb-2">Tiêu đề (Tùy chọn)</label>
                    <input type="text" name="title" class="form-control border-dark border-2 rounded-0 fw-bold" value="{{ old('title', $banner->title) }}">
                    @error('title')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="fw-bold mb-2">Đường dẫn / Link (Tùy chọn)</label>
                    <input type="text" name="link" class="form-control border-dark border-2 rounded-0" value="{{ old('link', $banner->link) }}">
                    @error('link')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="fw-bold mb-2">Thứ tự hiển thị</label>
                        <input type="number" name="order" class="form-control border-dark border-2 rounded-0 fw-bold" value="{{ old('order', $banner->order) }}">
                    </div>

                    <div class="col-md-6 mb-4 d-flex align-items-end pb-2">
                        <div class="form-check">
                            <input class="form-check-input border-dark border-2 rounded-0" type="checkbox" name="is_active" id="isActive" value="1" {{ old('is_active', $banner->is_active) ? 'checked' : '' }} style="width: 20px; height: 20px;">
                            <label class="form-check-label fw-bold ms-2 pt-1" for="isActive">
                                Hiển thị trạng thái
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="neo-btn flex-grow-1">LƯU THAY ĐỔI</button>
                    <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-dark fw-bold rounded-0 border-2" style="width: 150px;">HỦY BỎ</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
