@extends('admin.layouts.admin')

@section('title', 'Sửa Banner - Admin')

@section('page-title', 'SỬA BANNER')

@section('content')
<div class="row">
    <div class="col-12 col-xl-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <h2 class="admin-card-title"><i class="bi bi-images text-muted"></i> Chỉnh sửa Banner</h2>
            </div>
            <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @if($banner->image_url)
                <div class="mb-4">
                    <label class="form-label fw-semibold">Hình ảnh hiện tại</label>
                    <div>
                        <img src="{{ asset($banner->image_url) }}" alt="Banner" class="rounded-3 border" style="max-height: 200px;">
                    </div>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" value="1" id="delete_image" name="delete_image">
                        <label class="form-check-label text-danger small" for="delete_image">
                            <i class="bi bi-trash me-1"></i>Xóa ảnh hiện tại
                        </label>
                    </div>
                </div>
                @endif

                <div class="mb-3">
                    <label class="form-label fw-semibold">Thay đổi hình ảnh</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <small class="text-muted mt-1 d-block">Bỏ trống nếu muốn giữ nguyên ảnh hiện tại.</small>
                    @error('image')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Tiêu đề (Tùy chọn)</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $banner->title) }}">
                    @error('title')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Đường dẫn / Link (Tùy chọn)</label>
                    <input type="text" name="link" class="form-control" value="{{ old('link', $banner->link) }}">
                    @error('link')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold">Thứ tự hiển thị</label>
                        <input type="number" name="order" class="form-control" value="{{ old('order', $banner->order) }}">
                    </div>

                    <div class="col-md-6 mb-4 d-flex align-items-end pb-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" {{ old('is_active', $banner->is_active) ? 'checked' : '' }} style="width: 44px; height: 22px;">
                            <label class="form-check-label fw-semibold ms-2 pt-1" for="isActive">
                                Hiển thị trạng thái
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn-primary-admin flex-grow-1 justify-content-center">
                        <i class="bi bi-check-lg"></i> LƯU THAY ĐỔI
                    </button>
                    <a href="{{ route('admin.banners.index') }}" class="btn-outline-admin">HỦY BỎ</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
