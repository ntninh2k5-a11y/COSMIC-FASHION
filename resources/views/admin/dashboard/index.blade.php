@extends('admin.layouts.admin')
@section('title', 'Tổng quan - Admin')
@section('page-title', 'Bảng điều khiển')
@section('content')

{{-- STAT CARDS --}}
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-decoration" style="background:#FF6B6B;"></div>
            <div class="stat-card-icon" style="background:#fff0f0; color:#FF6B6B;"><i class="bi bi-receipt"></i></div>
            <div class="stat-card-value">{{ number_format($newOrders ?? 0) }}</div>
            <div class="stat-card-label">Đơn hàng mới</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-decoration" style="background:#4ECDC4;"></div>
            <div class="stat-card-icon" style="background:#e0faf8; color:#4ECDC4;"><i class="bi bi-currency-dollar"></i></div>
            <div class="stat-card-value" style="font-size:1.3rem;">{{ number_format($revenue ?? 0, 0, ',', '.') }}đ</div>
            <div class="stat-card-label">Tổng doanh thu</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-decoration" style="background:#FFE66D;"></div>
            <div class="stat-card-icon" style="background:#fffde7; color:#f59e0b;"><i class="bi bi-box-seam"></i></div>
            <div class="stat-card-value">{{ number_format($productsInStock ?? 0) }}</div>
            <div class="stat-card-label">Sản phẩm</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-decoration" style="background:#a78bfa;"></div>
            <div class="stat-card-icon" style="background:#ede9fe; color:#7c3aed;"><i class="bi bi-people"></i></div>
            <div class="stat-card-value">{{ number_format($customers ?? 0) }}</div>
            <div class="stat-card-label">Khách hàng</div>
        </div>
    </div>
</div>

{{-- RECENT ORDERS --}}
<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title"><i class="bi bi-clock-history text-muted"></i> Đơn hàng gần đây</h2>
        <a href="{{ route('admin.orders.index') }}" class="btn-outline-admin">Xem tất cả <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>MÃ ĐƠN</th>
                    <th>KHÁCH HÀNG</th>
                    <th>TỔNG TIỀN</th>
                    <th>TRẠNG THÁI</th>
                    <th class="text-center">HÀNH ĐỘNG</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders ?? [] as $order)
                    <tr>
                        <td><span class="fw-bold text-dark">{{ $order->order_code }}</span></td>
                        <td>{{ $order->user->name ?? 'Khách vãng lai' }}</td>
                        <td class="fw-bold">{{ number_format($order->total_amount ?? 0, 0, ',', '.') }}đ</td>
                        <td>
                            @php
                                $statusMap = [
                                    'pending'    => ['Chờ xử lý',      'badge-pending'],
                                    'processing' => ['Đang chuẩn bị',  'badge-processing'],
                                    'shipping'   => ['Đang giao',       'badge-shipping'],
                                    'completed'  => ['Đã giao',         'badge-completed'],
                                    'cancelled'  => ['Đã hủy',          'badge-cancelled'],
                                ];
                                $s = $statusMap[$order->status] ?? [$order->status, 'badge-inactive'];
                            @endphp
                            <span class="badge-status {{ $s[1] }}">{{ $s[0] }}</span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-sm-edit">
                                <i class="bi bi-eye"></i> Xem
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            Chưa có đơn hàng nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
