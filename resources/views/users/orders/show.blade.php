@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/orders_user.css') }}">
@endpush

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bolder m-0 text-uppercase" style="color: #1f2937; letter-spacing: 1px;">Chi tiết đơn hàng: {{ $order->order_code }}</h3>
        <a href="{{ route('user.orders') }}" class="btn btn-cosmic-soft">TRỞ LẠI</a>
    </div>

    <div class="cosmic-table-wrapper mb-4">
        <div class="card-body p-2">
            <p class="mb-3"><strong class="me-2" style="color: #6b7280;">Ngày đặt:</strong> <span class="fw-medium text-dark">{{ $order->created_at->format('d/m/Y H:i') }}</span></p>
            <p class="mb-3"><strong class="me-2" style="color: #6b7280;">Địa chỉ giao hàng:</strong> <span class="fw-medium text-dark">{{ $order->shipping_address ?? 'Không có' }}</span></p>
            <p class="mb-3"><strong class="me-2" style="color: #6b7280;">Số điện thoại:</strong> <span class="fw-medium text-dark">{{ $order->customer_phone ?? 'Không có' }}</span></p>
            <p class="mb-0"><strong class="me-2" style="color: #6b7280;">Trạng thái:</strong> 
                @if($order->status == 'pending')
                    <span class="badge" style="background-color: #fef3c7; color: #92400e; padding: 6px 14px; border-radius: 20px; font-weight: 600;">Chờ xử lý</span>
                @elseif($order->status == 'processing')
                    <span class="badge" style="background-color: #e0f2fe; color: #075985; padding: 6px 14px; border-radius: 20px; font-weight: 600;">Đang chuẩn bị</span>
                @elseif($order->status == 'shipping')
                    <span class="badge" style="background-color: #dbeafe; color: #1e40af; padding: 6px 14px; border-radius: 20px; font-weight: 600;">Đang giao</span>
                @elseif($order->status == 'completed')
                    <span class="badge" style="background-color: #d1fae5; color: #065f46; padding: 6px 14px; border-radius: 20px; font-weight: 600;">Đã giao</span>
                @elseif($order->status == 'cancelled')
                    <span class="badge" style="background-color: #fee2e2; color: #991b1b; padding: 6px 14px; border-radius: 20px; font-weight: 600;">Đã hủy</span>
                @else
                    <span class="badge bg-secondary rounded-pill px-3 py-2">{{ $order->status }}</span>
                @endif
            </p>
        </div>
    </div>

    <h5 class="fw-bolder mb-3 mt-5 text-uppercase" style="color: #1f2937; letter-spacing: 0.5px; font-size: 1.1rem;">Sản phẩm đã mua</h5>
    <div class="cosmic-table-wrapper">
        <div class="table-responsive">
            <table class="table cosmic-table align-middle">
                <thead>
                    <tr>
                        <th>SẢN PHẨM</th>
                        <th class="text-center">SỐ LƯỢNG</th>
                        <th class="text-end">ĐƠN GIÁ</th>
                        <th class="text-end">THÀNH TIỀN</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $item->product && $item->product->image_url ? asset($item->product->image_url) : 'https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?w=100' }}" 
                                         alt="" 
                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid #f0f0f0;">
                                    <div class="fw-bolder text-dark">{{ $item->product ? $item->product->name : 'Sản phẩm' }}</div>
                                </div>
                            </td>
                            <td class="text-center fw-medium">{{ $item->quantity }}</td>
                            <td class="text-end fw-medium">{{ number_format($item->price, 0, ',', '.') }}đ</td>
                            <td class="text-end fw-bolder" style="color: #ef4444;">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end fw-bolder" style="color: #6b7280; font-size: 0.85rem; letter-spacing: 0.5px;">TỔNG CỘNG:</td>
                        <td class="text-end fw-bolder fs-5" style="color: #ef4444;">{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection