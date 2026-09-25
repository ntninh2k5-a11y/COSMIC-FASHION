@extends('admin.layouts.admin')
@section('title', 'Đơn hàng - Admin')
@section('page-title', 'Quản lý Đơn hàng')
@section('content')

<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title"><i class="bi bi-receipt text-muted"></i> Danh sách đơn hàng</h2>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>MÃ ĐƠN</th>
                    <th>KHÁCH HÀNG</th>
                    <th>NGÀY ĐẶT</th>
                    <th>TỔNG TIỀN</th>
                    <th>TRẠNG THÁI</th>
                    <th class="text-center">HÀNH ĐỘNG</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>
                            <span class="fw-bold text-dark" style="font-size:0.85rem;">{{ $order->order_code }}</span>
                        </td>
                        <td>{{ $order->user ? $order->user->name : 'Khách vãng lai' }}</td>
                        <td class="text-muted">{{ $order->created_at->format('d/m/Y') }}</td>
                        <td class="fw-bold">{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                        <td>
                            <span class="badge-status {{ $order->status_badge }}">{{ $order->status_label }}</span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-sm-edit">
                                <i class="bi bi-eye"></i> Chi tiết
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            Chưa có đơn hàng nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
        <div class="d-flex justify-content-center mt-4 admin-pagination">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection