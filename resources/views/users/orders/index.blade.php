@extends('layouts.app')

@section('title', 'Đơn hàng của tôi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/orders_user.css') }}">
@endpush

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bolder text-uppercase m-0" style="color: #1f2937; letter-spacing: 1px;">Đơn hàng của tôi</h3>
    </div>
    
    <div class="cosmic-table-wrapper">
        <div class="table-responsive">
            <table class="table cosmic-table align-middle">
                <thead>
                    <tr>
                        <th>MÃ ĐƠN</th>
                        <th>NGÀY ĐẶT</th>
                        <th>TỔNG TIỀN</th>
                        <th>TRẠNG THÁI</th>
                        <th class="text-center">CHI TIẾT</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="fw-bolder" style="color: #111827;">{{ $order->order_code }}</td>
                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                            <td class="fw-bolder" style="color: #ef4444;">{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                            <td>
                                @if($order->status == 'pending')
                                    <span class="badge" style="background-color: #fef3c7; color: #92400e; padding: 8px 16px; border-radius: 20px; font-weight: 600;">Chờ xử lý</span>
                                @elseif($order->status == 'processing')
                                    <span class="badge" style="background-color: #e0f2fe; color: #075985; padding: 8px 16px; border-radius: 20px; font-weight: 600;">Đang chuẩn bị</span>
                                @elseif($order->status == 'shipping')
                                    <span class="badge" style="background-color: #dbeafe; color: #1e40af; padding: 8px 16px; border-radius: 20px; font-weight: 600;">Đang giao</span>
                                @elseif($order->status == 'completed')
                                    <span class="badge" style="background-color: #d1fae5; color: #065f46; padding: 8px 16px; border-radius: 20px; font-weight: 600;">Đã giao</span>
                                @elseif($order->status == 'cancelled')
                                    <span class="badge" style="background-color: #fee2e2; color: #991b1b; padding: 8px 16px; border-radius: 20px; font-weight: 600;">Đã hủy</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill px-3 py-2">{{ $order->status }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('user.orders.show', $order->id) }}" class="btn btn-cosmic-soft btn-sm text-decoration-none">XEM</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 fw-bold text-muted border-0">Bạn chưa có đơn hàng nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection