@extends('admin.layouts.admin')

@section('title', 'Thêm Link Footer - Admin')

@section('page-title', 'THÊM MỚI FOOTER')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="neo-card">
            <h4 class="fw-bolder mb-4 border-bottom border-dark border-2 pb-2">Thêm Link Mới</h4>
            
            <form action="{{ route('admin.footer_menus.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="fw-bolder mb-2 text-dark">TÊN HIỂN THỊ</label>
                    <input type="text" name="name" class="form-control neo-input" required>
                    @error('name')
                        <div class="text-danger mt-1 fw-bold">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="fw-bolder mb-2 text-dark">CỘT TIÊU ĐỀ (CHA)</label>
                    <select name="parent_id" class="form-select neo-input">
                        <option value="">-- Trống (Làm cột tiêu đề mới) --</option>
                        @foreach($footerColumns as $col)
                            <option value="{{ $col->id }}">{{ $col->name }}</option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <div class="text-danger mt-1 fw-bold">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="fw-bolder mb-2 text-dark">ĐƯỜNG DẪN (URL)</label>
                    <input type="text" name="url" class="form-control neo-input" value="#">
                    @error('url')
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
                    <a href="{{ route('admin.footer_menus.index') }}" class="neo-btn bg-secondary text-white text-decoration-none">HỦY</a>
                    <button type="submit" class="neo-btn border-0">LƯU THÔNG TIN</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection