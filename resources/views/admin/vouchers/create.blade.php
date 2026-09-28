@extends('admin.layouts.admin')

@section('title', 'Thêm Voucher - Admin')

@section('page-title', 'THÊM MỚI VOUCHER')

@section('content')
<div class="row">
    <div class="col-12 col-xl-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <h2 class="admin-card-title"><i class="bi bi-ticket-perforated text-muted"></i> Tạo Mã Giảm Giá Mới</h2>
            </div>
            
            <form action="{{ route('admin.vouchers.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="form-label fw-semibold">MÃ VOUCHER (CODE)</label>
                    <input type="text" name="code" class="form-control text-uppercase" required>
                    @error('code')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold">LOẠI GIẢM GIÁ</label>
                        <select name="discountType" class="form-select" required>
                            <option value="percent">Theo phần trăm (%)</option>
                            <option value="fixed">Số tiền cố định (VNĐ)</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold">MỨC GIẢM GIÁ</label>
                        <input type="number" name="discountValue" class="form-control" min="0" required>
                        @error('discountValue')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold">ĐƠN TỐI THIỂU (VNĐ)</label>
                        <input type="number" name="minOrderValue" class="form-control" value="0" min="0">
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold">GIẢM TỐI ĐA (VNĐ - Tùy chọn)</label>
                        <input type="number" name="maxDiscountAmount" class="form-control" min="0">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-4">
                        <label class="form-label fw-semibold">SỐ LƯỢNG MÃ</label>
                        <input type="number" name="usageLimit" class="form-control" min="1">
                        <small class="text-muted mt-1 d-block">Bỏ trống nếu không giới hạn.</small>
                    </div>
                    <div class="col-md-4 mb-4">
                        <label class="form-label fw-semibold">NGÀY BẮT ĐẦU</label>
                        <input type="date" name="startDate" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-4">
                        <label class="form-label fw-semibold">NGÀY KẾT THÚC</label>
                        <input type="date" name="endDate" class="form-control" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">TRẠNG THÁI</label>
                    <select name="isActive" class="form-select">
                        <option value="1">Hoạt động</option>
                        <option value="0">Tạm dừng</option>
                    </select>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.vouchers.index') }}" class="btn-outline-admin text-muted">HỦY BỎ</a>
                    <button type="submit" class="btn-primary-admin px-4"><i class="bi bi-plus-lg"></i> TẠO MỚI</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection