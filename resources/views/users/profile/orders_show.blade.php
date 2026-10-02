@extends('users.profile.layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/orders_user.css') }}">
@endpush

@section('profile_content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="m-0">Chi tiết đơn hàng: {{ $order->order_code }}</h4>
        <a href="{{ route('user.orders') }}" class="btn btn-cosmic-soft btn-sm">TRỞ LẠI</a>
    </div>

    <div class="cosmic-table-wrapper mb-4">
        <div class="card-body p-2">
            <p class="mb-3"><strong class="me-2" style="color: #6b7280;">Ngày đặt:</strong> <span class="fw-medium text-dark">{{ $order->created_at->format('d/m/Y H:i') }}</span></p>
            <p class="mb-3"><strong class="me-2" style="color: #6b7280;">Địa chỉ giao hàng:</strong> <span class="fw-medium text-dark">{{ $order->shipping_address ?? 'Không có' }}</span></p>
            <p class="mb-3"><strong class="me-2" style="color: #6b7280;">Số điện thoại:</strong> <span class="fw-medium text-dark">{{ $order->customer_phone ?? 'Không có' }}</span></p>
            <p class="mb-0"><strong class="me-2" style="color: #6b7280;">Trạng thái:</strong> 
                <span class="badge-status {{ $order->status_badge }}">{{ $order->status_label }}</span>
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
                                    <img src="{{ asset($item->image_url ?? ($item->product->image_url ?? 'images/default.jpg')) }}" 
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
                        <td colspan="3" class="text-end fw-bold border-0 pb-1" style="color: #6b7280; font-size: 0.85rem; letter-spacing: 0.5px;">TẠM TÍNH:</td>
                        <td class="text-end fw-bold text-dark border-0 pb-1">{{ number_format($order->total_amount + $order->discount_amount, 0, ',', '.') }}đ</td>
                    </tr>
                    @if($order->discount_amount > 0)
                    <tr>
                        <td colspan="3" class="text-end fw-bold border-0 pb-1 pt-1" style="color: #6b7280; font-size: 0.85rem; letter-spacing: 0.5px;">GIẢM GIÁ VOUCHER:</td>
                        <td class="text-end fw-bold text-danger border-0 pb-1 pt-1">-{{ number_format($order->discount_amount, 0, ',', '.') }}đ</td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="3" class="text-end fw-bolder pt-2" style="color: #6b7280; font-size: 0.85rem; letter-spacing: 0.5px;">THÀNH TIỀN:</td>
                        <td class="text-end fw-bolder fs-5 pt-2" style="color: #ef4444;">{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection