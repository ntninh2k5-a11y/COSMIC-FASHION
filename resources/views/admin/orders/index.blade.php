@extends('admin.layouts.admin')

@section('title', 'Quản lý Đơn hàng - Admin')

@section('page-title', 'QUẢN LÝ ĐƠN HÀNG')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="neo-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bolder m-0">Danh sách Đơn hàng</h4>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless align-middle">
                    <thead class="border-bottom border-dark border-2">
                        <tr>
                            <th class="fw-bolder text-dark">MÃ ĐƠN</th>
                            <th class="fw-bolder text-dark">KHÁCH HÀNG</th>
                            <th class="fw-bolder text-dark">NGÀY ĐẶT</th>
                            <th class="fw-bolder text-dark">TỔNG TIỀN</th>
                            <th class="fw-bolder text-dark">TRẠNG THÁI</th>
                            <th class="fw-bolder text-dark text-center">HÀNH ĐỘNG</th>
                        </tr>
                    </thead>
                    <tbody class="fw-bold text-secondary">
                        @forelse($orders as $order)
                            <tr>
                                <td class="text-dark">{{ $order->order_code }}</td>
                                <td>{{ $order->user ? $order->user->name : 'Khách vãng lai' }}</td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                <td>{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                                <td>
                                    @if($order->status == 'pending')
                                        <span class="badge rounded-pill px-3 py-2" style="background:#ffc107; color:#000; font-weight:600;">
                                            Chờ xử lý
                                        </span>
                                    @elseif($order->status == 'shipping')
                                        <span class="badge rounded-pill px-3 py-2" style="background:#0d6efd; color:#fff; font-weight:600;">
                                            Đang giao
                                        </span>
                                    @elseif($order->status == 'completed')
                                        <span class="badge rounded-pill px-3 py-2" style="background:#198754; color:#fff; font-weight:600;">
                                            Đã giao
                                        </span>
                                    @elseif($order->status == 'cancelled')
                                        <span class="badge rounded-pill px-3 py-2" style="background:#dc3545; color:#fff; font-weight:600;">
                                            Đã hủy
                                        </span>
                                    @else
                                        <span class="badge rounded-pill px-3 py-2 bg-secondary text-white" style="font-weight:600;">
                                            {{ $order->status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="neo-btn-sm text-decoration-none">
                                        CHI TIẾT
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">Chưa có đơn hàng nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                
                <div class="mt-4 d-flex justify-content-center">
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection