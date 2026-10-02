@extends('admin.layouts.admin')

@section('title', 'Thêm Danh Mục - Admin')

@section('page-title', 'THÊM DANH MỤC')

@section('content')
<div class="row">
    <div class="col-12 col-xl-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <h2 class="admin-card-title"><i class="bi bi-folder-plus text-muted"></i> Thêm Danh Mục Mới</h2>
            </div>
            
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="form-label fw-semibold">TÊN DANH MỤC</label>
                    <input type="text" name="name" class="form-control" required>
                    @error('name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">DANH MỤC CHA</label>
                    <select name="parent_id" class="form-select">
                        <option value="">-- Không có (Danh mục gốc) --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">ẢNH ĐẠI DIỆN</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    @error('image')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">TRẠNG THÁI</label>
                    <select name="status" class="form-select" required>
                        <option value="1">Hiển thị</option>
                        <option value="0">Đang ẩn</option>
                    </select>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.categories.index') }}" class="btn-outline-admin text-muted">HỦY BỎ</a>
                    <button type="submit" class="btn-primary-admin px-4"><i class="bi bi-check2"></i> LƯU DANH MỤC</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection