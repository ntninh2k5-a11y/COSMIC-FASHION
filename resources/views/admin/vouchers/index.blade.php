@extends('admin.layouts.admin')
@section('title', 'Voucher - Admin')
@section('page-title', 'Quản lý Voucher')
@section('content')

<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title"><i class="bi bi-ticket-perforated text-muted"></i> Danh sách Voucher</h2>
        <a href="{{ route('admin.vouchers.create') }}" class="btn-primary-admin">
            <i class="bi bi-plus-lg"></i> Thêm Voucher
        </a>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>MÃ VOUCHER</th>
                    <th>LOẠI GIẢM</th>
                    <th>GIÁ TRỊ</th>
                    <th>ĐƠN TỐI THIỂU</th>
                    <th>THỜI GIAN</th>
                    <th class="text-center">ĐÃ DÙNG</th>
                    <th class="text-center">TRẠNG THÁI</th>
                    <th class="text-center">HÀNH ĐỘNG</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vouchers as $voucher)
                    <tr>
                        <td>
                            <span class="fw-bold text-dark text-uppercase"
                                  style="font-family:monospace;letter-spacing:1px;font-size:0.9rem;">
                                {{ $voucher->code }}
                            </span>
                        </td>
                        <td>
                            @if($voucher->discountType == 'percent')
                                <span class="badge-status badge-processing">
                                    <i class="bi bi-percent" style="font-size:0.7rem;"></i> Phần trăm
                                </span>
                            @else
                                <span class="badge-status badge-shipping">
                                    <i class="bi bi-cash" style="font-size:0.7rem;"></i> Cố định
                                </span>
                            @endif
                        </td>
                        <td class="fw-bold" style="color:#FF6B6B;">
                            @if($voucher->discountType == 'percent')
                                {{ $voucher->discountValue }}%
                            @else
                                {{ number_format($voucher->discountValue, 0, ',', '.') }}đ
                            @endif
                        </td>
                        <td class="text-muted">{{ number_format($voucher->minOrderValue, 0, ',', '.') }}đ</td>
                        <td>
                            <div style="font-size:0.8rem;">
                                <div class="text-muted">
                                    <i class="bi bi-calendar-event" style="font-size:0.7rem;"></i>
                                    {{ \Carbon\Carbon::parse($voucher->startDate)->format('d/m/Y') }}
                                </div>
                                <div class="text-muted">
                                    <i class="bi bi-calendar-x" style="font-size:0.7rem;"></i>
                                    {{ \Carbon\Carbon::parse($voucher->endDate)->format('d/m/Y') }}
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="fw-bold text-dark">{{ $voucher->usageCount }}</span>
                            <span class="text-muted"> / {{ $voucher->usageLimit ?? '∞' }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge-status {{ $voucher->status_badge }}">
                                <i class="bi bi-circle-fill" style="font-size:0.5rem;"></i> {{ $voucher->status_label }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('admin.vouchers.edit', $voucher->id) }}" class="btn-sm-edit">
                                    <i class="bi bi-pencil"></i> Sửa
                                </a>
                                <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST"
                                      class="d-inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-sm-delete">
                                        <i class="bi bi-trash"></i> Xóa
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-ticket-perforated fs-3 d-block mb-2"></i>
                            Chưa có mã giảm giá nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(method_exists($vouchers, 'hasPages') && $vouchers->hasPages())
        <div class="d-flex justify-content-center mt-4 admin-pagination">
            {{ $vouchers->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection