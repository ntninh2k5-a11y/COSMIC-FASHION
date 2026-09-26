@extends('admin.layouts.admin')

@section('title', 'Sửa Danh Mục - Admin')

@section('page-title', 'SỬA DANH MỤC')

@section('content')
<div class="row">
    <div class="col-12 col-xl-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <h2 class="admin-card-title"><i class="bi bi-folder text-muted"></i> Chỉnh sửa Danh mục</h2>
            </div>

            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Tên danh mục</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Danh mục cha</label>
                        <select name="parent_id" class="form-select">
                            <option value="">-- Không có (Danh mục gốc) --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $category->parent_id == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Ảnh đại diện</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    @if($category->image_url)
                        <div class="mt-3 d-flex align-items-start gap-3">
                            <img src="{{ asset($category->image_url) }}" alt="{{ $category->name }}" class="rounded-3 border" style="width: 80px; height: 80px; object-fit: cover;">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" value="1" id="delete_image" name="delete_image">
                                <label class="form-check-label text-danger small" for="delete_image">
                                    <i class="bi bi-trash me-1"></i>Xóa ảnh hiện tại
                                </label>
                            </div>
                        </div>
                    @endif
                    @error('image')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Trạng thái</label>
                    <select name="status" class="form-select" required>
                        <option value="1" {{ $category->status == 1 ? 'selected' : '' }}>Hiển thị</option>
                        <option value="0" {{ $category->status == 0 ? 'selected' : '' }}>Đang ẩn</option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn-primary-admin flex-grow-1 justify-content-center">
                        <i class="bi bi-check-lg"></i> CẬP NHẬT
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="btn-outline-admin">HỦY BỎ</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
