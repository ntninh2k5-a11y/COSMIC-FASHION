@extends('admin.layouts.admin')
@section('title', 'Chi tiết đơn hàng - Admin')
@section('page-title', 'Chi tiết đơn hàng')
@section('content')

<div class="d-flex mb-3">
    <a href="{{ route('admin.orders.index') }}" class="btn-outline-admin">
        <i class="bi bi-arrow-left"></i> Quay lại
    </a>
</div>

<div class="row g-4">
    {{-- ORDER ITEMS --}}
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <h2 class="admin-card-title"><i class="bi bi-bag text-muted"></i> Sản phẩm đã đặt</h2>
                <span class="fw-bold text-muted" style="font-size:0.85rem;">{{ $order->order_code }}</span>
            </div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>SẢN PHẨM</th>
                            <th class="text-center">SL</th>
                            <th class="text-end">ĐƠN GIÁ</th>
                            <th class="text-end">THÀNH TIỀN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $item->product && $item->product->image_url ? asset($item->product->image_url) : 'https://via.placeholder.com/50' }}"
                                             style="width:52px;height:52px;object-fit:cover;border-radius:10px;border:1px solid #f0f0f0;"
                                             alt="">
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size:0.9rem;">
                                                {{ $item->product ? $item->product->name : 'Sản phẩm không xác định' }}
                                            </div>
                                            @if($item->variant)
                                                <small class="text-muted">
                                                    {{ $colorNames[strtoupper($item->variant->color)] ?? $item->variant->color }} / {{ $item->variant->size }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center fw-bold">{{ $item->quantity }}</td>
                                <td class="text-end">{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                <td class="text-end fw-bold">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Totals --}}
            <div class="border-top mt-3 pt-3">
                <div class="d-flex justify-content-between mb-2 text-muted">
                    <span>Tạm tính:</span>
                    <span class="fw-bold text-dark">
                        {{ number_format($order->total_amount + $order->discount_amount, 0, ',', '.') }}đ
                    </span>
                </div>
                @if($order->discount_amount > 0)
                    <div class="d-flex justify-content-between mb-2 text-muted">
                        <span>Giảm giá (Voucher):</span>
                        <span class="fw-bold" style="color:#FF6B6B;">
                            -{{ number_format($order->discount_amount, 0, ',', '.') }}đ
                        </span>
                    </div>
                @endif
                <div class="d-flex justify-content-between mt-3 pt-3 border-top">
                    <span class="fw-bold fs-6">TỔNG CỘNG:</span>
                    <span class="fw-bold fs-5" style="color:#FF6B6B;">
                        {{ number_format($order->total_amount, 0, ',', '.') }}đ
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- SIDEBAR INFO --}}
    <div class="col-lg-4">
        {{-- Customer Info --}}
        <div class="admin-card mb-4">
            <div class="admin-card-header" style="margin-bottom:16px;padding-bottom:12px;">
                <h2 class="admin-card-title" style="font-size:0.9rem;">
                    <i class="bi bi-person text-muted"></i> Thông tin khách hàng
                </h2>
            </div>
            <div class="d-flex flex-column gap-2">
                <div class="d-flex justify-content-between">
                    <span class="text-muted" style="font-size:0.85rem;">Tên:</span>
                    <span class="fw-bold text-dark" style="font-size:0.85rem;">
                        {{ $order->user ? $order->user->name : 'Khách vãng lai' }}
                    </span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted" style="font-size:0.85rem;">SĐT:</span>
                    <span class="fw-bold text-dark" style="font-size:0.85rem;">{{ $order->customer_phone ?? '—' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted" style="font-size:0.85rem;">Email:</span>
                    <span class="fw-bold text-dark" style="font-size:0.85rem;">
                        {{ $order->user ? $order->user->email : '—' }}
                    </span>
                </div>
                <div class="border-top pt-2 mt-1">
                    <span class="text-muted d-block mb-1" style="font-size:0.8rem;">Địa chỉ nhận hàng:</span>
                    <span class="fw-bold text-dark" style="font-size:0.85rem;">{{ $order->shipping_address ?? '—' }}</span>
                </div>
            </div>
        </div>

        {{-- Order Info --}}
        <div class="admin-card mb-4">
            <div class="admin-card-header" style="margin-bottom:16px;padding-bottom:12px;">
                <h2 class="admin-card-title" style="font-size:0.9rem;">
                    <i class="bi bi-info-circle text-muted"></i> Thông tin đơn hàng
                </h2>
            </div>
            <div class="d-flex flex-column gap-2">
                <div class="d-flex justify-content-between">
                    <span class="text-muted" style="font-size:0.85rem;">Mã đơn:</span>
                    <span class="fw-bold text-dark" style="font-size:0.85rem;">{{ $order->order_code }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted" style="font-size:0.85rem;">Ngày đặt:</span>
                    <span class="fw-bold text-dark" style="font-size:0.85rem;">
                        {{ $order->created_at->format('d/m/Y H:i') }}
                    </span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted" style="font-size:0.85rem;">Thanh toán:</span>
                    <span class="fw-bold text-dark" style="font-size:0.85rem;">
                        {{ strtoupper($order->payment_method ?? 'COD') }}
                    </span>
                </div>
                @if($order->notes)
                    <div class="border-top pt-2 mt-1">
                        <span class="text-muted d-block mb-1" style="font-size:0.8rem;">Ghi chú:</span>
                        <span class="fw-bold text-dark" style="font-size:0.85rem;">{{ $order->notes }}</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Update Status --}}
        <div class="admin-card">
            <div class="admin-card-header" style="margin-bottom:16px;padding-bottom:12px;">
                <h2 class="admin-card-title" style="font-size:0.9rem;">
                    <i class="bi bi-arrow-repeat text-muted"></i> Cập nhật trạng thái
                </h2>
            </div>
            @php
                $sm = [
                    'pending'    => ['Chờ xử lý',      'badge-pending'],
                    'processing' => ['Đang chuẩn bị',  'badge-processing'],
                    'shipping'   => ['Đang giao',       'badge-shipping'],
                    'completed'  => ['Đã giao',         'badge-completed'],
                    'cancelled'  => ['Đã hủy',          'badge-cancelled'],
                ];
                $s = $sm[$order->status] ?? [$order->status, 'badge-inactive'];
            @endphp
            <div class="mb-3">
                <span class="badge-status {{ $s[1] }}">{{ $s[0] }}</span>
            </div>
            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Trạng thái mới</label>
                    <select name="status" class="form-select">
                        <option value="pending"    {{ $order->status == 'pending'    ? 'selected' : '' }}>Chờ xử lý</option>
                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang chuẩn bị</option>
                        <option value="shipping"   {{ $order->status == 'shipping'   ? 'selected' : '' }}>Đang giao hàng</option>
                        <option value="completed"  {{ $order->status == 'completed'  ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="cancelled"  {{ $order->status == 'cancelled'  ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary-admin w-100 justify-content-center">
                    <i class="bi bi-check2"></i> Cập nhật
                </button>
            </form>
        </div>
    </div>
</div>
@endsection