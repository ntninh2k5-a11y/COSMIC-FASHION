@extends('admin.layouts.admin')

@section('title', 'Quản lý Voucher - Admin')

@section('page-title', 'QUẢN LÝ VOUCHER')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="neo-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bolder m-0">Danh sách Voucher</h4>
                <a href="{{ route('admin.vouchers.create') }}" class="neo-btn text-decoration-none">
                    + THÊM MỚI
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success fw-bold">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-borderless align-middle">
                    <thead class="border-bottom border-dark border-2">
                        <tr>
                            <th class="fw-bolder text-dark">MÃ VOUCHER</th>
                            <th class="fw-bolder text-dark">GIẢM GIÁ</th>
                            <th class="fw-bolder text-dark">ĐƠN TỐI THIỂU</th>
                            <th class="fw-bolder text-dark">THỜI GIAN</th>
                            <th class="fw-bolder text-dark">ĐÃ DÙNG</th>
                            <th class="fw-bolder text-dark text-center">TRẠNG THÁI</th>
                            <th class="fw-bolder text-dark text-center">HÀNH ĐỘNG</th>
                        </tr>
                    </thead>
                    <tbody class="fw-bold text-secondary">
                        @forelse($vouchers as $voucher)
                            <tr>
                                <td class="text-dark text-uppercase">{{ $voucher->code }}</td>
                                <td>
                                    @if($voucher->discountType == 'percent')
                                        <span class="text-danger">{{ $voucher->discountValue }}%</span>
                                    @else
                                        <span class="text-danger">{{ number_format($voucher->discountValue, 0, ',', '.') }}đ</span>
                                    @endif
                                </td>
                                <td>{{ number_format($voucher->minOrderValue, 0, ',', '.') }}đ</td>
                                <td>
                                    <small class="d-block">Từ: {{ \Carbon\Carbon::parse($voucher->startDate)->format('d/m/Y') }}</small>
                                    <small class="d-block">Đến: {{ \Carbon\Carbon::parse($voucher->endDate)->format('d/m/Y') }}</small>
                                </td>
                                <td>{{ $voucher->usageCount }} / {{ $voucher->usageLimit ?? '∞' }}</td>
                                <td class="text-center">
                                    @if($voucher->isActive == 1)
                                        <span class="text-success">Hoạt động</span>
                                    @else
                                        <span class="text-danger">Tạm dừng</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.vouchers.edit', $voucher->id) }}" class="neo-btn-sm me-1 text-decoration-none">SỬA</a>
                                    <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="neo-btn-sm bg-danger text-white border-0">XÓA</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-secondary">Chưa có mã giảm giá nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection