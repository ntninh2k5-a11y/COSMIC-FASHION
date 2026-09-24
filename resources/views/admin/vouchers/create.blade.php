@extends('admin.layouts.admin')

@section('title', 'Thêm Voucher - Admin')

@section('page-title', 'THÊM MỚI VOUCHER')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="neo-card">
            <h4 class="fw-bolder mb-4 border-bottom border-dark border-2 pb-2">Tạo Mã Giảm Giá Mới</h4>
            
            <form action="{{ route('admin.vouchers.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="fw-bolder mb-2 text-dark">MÃ VOUCHER (CODE)</label>
                    <input type="text" name="code" class="form-control neo-input text-uppercase" required>
                    @error('code')
                        <div class="text-danger mt-1 fw-bold">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="fw-bolder mb-2 text-dark">LOẠI GIẢM GIÁ</label>
                        <select name="discountType" class="form-select neo-input" required>
                            <option value="percent">Theo phần trăm (%)</option>
                            <option value="fixed">Số tiền cố định (VNĐ)</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="fw-bolder mb-2 text-dark">MỨC GIẢM GIÁ</label>
                        <input type="number" name="discountValue" class="form-control neo-input" min="0" required>
                        @error('discountValue')
                            <div class="text-danger mt-1 fw-bold">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="fw-bolder mb-2 text-dark">ĐƠN TỐI THIỂU (VNĐ)</label>
                        <input type="number" name="minOrderValue" class="form-control neo-input" value="0" min="0">
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="fw-bolder mb-2 text-dark">GIẢM TỐI ĐA (VNĐ - Tùy chọn)</label>
                        <input type="number" name="maxDiscountAmount" class="form-control neo-input" min="0">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-4">
                        <label class="fw-bolder mb-2 text-dark">SỐ LƯỢNG MÃ</label>
                        <input type="number" name="usageLimit" class="form-control neo-input" min="1">
                    </div>
                    <div class="col-md-4 mb-4">
                        <label class="fw-bolder mb-2 text-dark">NGÀY BẮT ĐẦU</label>
                        <input type="date" name="startDate" class="form-control neo-input" required>
                        @error('startDate')
                            <div class="text-danger mt-1 fw-bold">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-4">
                        <label class="fw-bolder mb-2 text-dark">NGÀY KẾT THÚC</label>
                        <input type="date" name="endDate" class="form-control neo-input" required>
                        @error('endDate')
                            <div class="text-danger mt-1 fw-bold">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="fw-bolder mb-2 text-dark">TRẠNG THÁI</label>
                    <select name="isActive" class="form-select neo-input" required>
                        <option value="1">Kích hoạt</option>
                        <option value="0">Tạm dừng</option>
                    </select>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.vouchers.index') }}" class="neo-btn bg-secondary text-white text-decoration-none">HỦY</a>
                    <button type="submit" class="neo-btn border-0">TẠO VOUCHER</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection