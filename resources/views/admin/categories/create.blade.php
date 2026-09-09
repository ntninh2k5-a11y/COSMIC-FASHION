@extends('admin.layouts.admin')

@section('title', 'Thêm Danh Mục - Admin')

@section('page-title', 'THÊM DANH MỤC')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="neo-card">
            <h4 class="fw-bolder mb-4 border-bottom border-dark border-2 pb-2">Thêm Danh Mục Mới</h4>
            
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="fw-bolder mb-2 text-dark">TÊN DANH MỤC</label>
                    <input type="text" name="name" class="form-control neo-input" required>
                    @error('name')
                        <div class="text-danger mt-1 fw-bold">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="fw-bolder mb-2 text-dark">ẢNH ĐẠI DIỆN</label>
                    <input type="file" name="image" class="form-control neo-input" accept="image/*">
                    @error('image')
                        <div class="text-danger mt-1 fw-bold">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="fw-bolder mb-2 text-dark">TRẠNG THÁI</label>
                    <select name="status" class="form-select neo-input" required>
                        <option value="1">Hiển thị</option>
                        <option value="0">Đang ẩn</option>
                    </select>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.categories.index') }}" class="neo-btn bg-secondary text-white text-decoration-none">HỦY</a>
                    <button type="submit" class="neo-btn border-0">LƯU DANH MỤC</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection