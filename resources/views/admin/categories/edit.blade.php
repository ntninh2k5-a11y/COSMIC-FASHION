@extends('admin.layouts.admin')

@section('title', 'Sửa Danh Mục - Admin')

@section('page-title', 'SỬA DANH MỤC')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="neo-card">
            <h4 class="fw-bolder mb-4 border-bottom border-dark border-2 pb-2">Sửa Danh Mục</h4>
            
            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label class="fw-bolder mb-2 text-dark">TÊN DANH MỤC</label>
                    <input type="text" name="name" class="form-control neo-input" value="{{ $category->name }}" required>
                    @error('name')
                        <div class="text-danger mt-1 fw-bold">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="fw-bolder mb-2 text-dark">ẢNH ĐẠI DIỆN</label>
                    <input type="file" name="image" class="form-control neo-input" accept="image/*">
                    @if($category->image_url)
                        <div class="mt-3">
                            <p class="text-secondary small mb-1 fw-bold">Ảnh hiện tại:</p>
                            <img src="{{ asset($category->image_url) }}" alt="{{ $category->name }}" class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                        </div>
                    @endif
                    @error('image')
                        <div class="text-danger mt-1 fw-bold">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="fw-bolder mb-2 text-dark">TRẠNG THÁI</label>
                    <select name="status" class="form-select neo-input" required>
                        <option value="1" {{ $category->status == 1 ? 'selected' : '' }}>Hiển thị</option>
                        <option value="0" {{ $category->status == 0 ? 'selected' : '' }}>Đang ẩn</option>
                    </select>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.categories.index') }}" class="neo-btn bg-secondary text-white text-decoration-none">HỦY</a>
                    <button type="submit" class="neo-btn border-0">CẬP NHẬT</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection