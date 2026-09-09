@extends('admin.layouts.admin')

@section('title', 'Chi tiết Đơn hàng - Admin')

@section('page-title', 'CHI TIẾT ĐƠN HÀNG ' . $order->order_code)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="neo-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bolder m-0">Sản phẩm đã đặt</h5>
                <a href="{{ route('admin.orders.index') }}" class="neo-btn-sm text-decoration-none">← Quay lại</a>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless align-middle fw-bold text-secondary">
                    <thead class="border-bottom border-dark border-2 text-dark">
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
                                <td class="text-dark">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $item->product && $item->product->image_url ? asset($item->product->image_url) : 'https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?w=200&q=80' }}" 
                                             alt="{{ $item->product ? $item->product->name : 'Sản phẩm' }}" 
                                             class="rounded" 
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                        <div>
                                            <div class="fw-bold">{{ $item->product ? $item->product->name : 'Sản phẩm không xác định' }}</div>
                                            @if($item->variant)
                                                <small class="text-muted">
                                                    Biến thể: 
                                                    {{ $colorNames[strtoupper($item->variant->color)] ?? $colorNames[$item->variant->color] ?? $item->variant->color }}
                                                    / {{ $item->variant->size }}
                                                </small>
                                            @else
                                                <small class="text-muted">Biến thể: Mặc định</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                <td class="text-end fw-bolder text-dark">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-top border-dark border-2 pt-3 mt-3">
                <div class="d-flex justify-content-between">
                    <h5 class="fw-bolder m-0">TỔNG CỘNG:</h5>
                    <h5 class="fw-bolder m-0 text-dark">{{ number_format($order->total_amount, 0, ',', '.') }}đ</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="neo-card mb-4">
            <h5 class="fw-bolder border-bottom border-dark pb-2 mb-3 border-2">Thông tin Khách hàng</h5>
            <p class="mb-2">
                <span class="fw-bold text-dark">Tên:</span>
                <span class="text-secondary">{{ $order->user ? $order->user->name : 'Khách vãng lai' }}</span>
            </p>
            <p class="mb-2">
                <span class="fw-bold text-dark">SĐT:</span>
                <span class="text-secondary">{{ $order->customer_phone ?? 'Không có' }}</span>
            </p>
            <p class="mb-2">
                <span class="fw-bold text-dark">Email:</span>
                <span class="text-secondary">{{ $order->user ? $order->user->email : 'Không có' }}</span>
            </p>
            <p class="mb-0">
                <span class="fw-bold text-dark">Địa chỉ:</span>
                <span class="text-secondary">{{ $order->shipping_address ?? 'Không có' }}</span>
            </p>
        </div>

        <div class="neo-card mb-4">
            <h5 class="fw-bolder border-bottom border-dark pb-2 mb-3 border-2">Trạng thái đơn hàng</h5>
            
            <div class="mb-3">
                @if($order->status == 'pending')
                    <span class="badge rounded-pill px-3 py-2" style="background:#ffc107; color:#000; font-weight:600;">Chờ xử lý</span>
                @elseif($order->status == 'shipping')
                    <span class="badge rounded-pill px-3 py-2" style="background:#0d6efd; color:#fff; font-weight:600;">Đang giao</span>
                @elseif($order->status == 'completed')
                    <span class="badge rounded-pill px-3 py-2" style="background:#198754; color:#fff; font-weight:600;">Đã giao</span>
                @elseif($order->status == 'cancelled')
                    <span class="badge rounded-pill px-3 py-2" style="background:#dc3545; color:#fff; font-weight:600;">Đã hủy</span>
                @else
                    <span class="badge rounded-pill px-3 py-2 bg-secondary text-white" style="font-weight:600;">{{ $order->status }}</span>
                @endif
            </div>

            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                <label class="fw-bold mb-2 d-block">Cập nhật trạng thái</label>
                <select name="status" class="form-select border-dark border-2 rounded-0 fw-bold mb-3">
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang chuẩn bị</option>
                    <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>Đang giao hàng</option>
                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
                
                <button type="submit" class="neo-btn w-100 mb-2">CẬP NHẬT</button>
            </form>
        </div>

        <div class="neo-card">
            <h5 class="fw-bolder border-bottom border-dark pb-2 mb-3 border-2">Thông tin đơn hàng</h5>
            <p class="mb-2">
                <span class="fw-bold text-dark">Mã đơn:</span>
                <span class="text-secondary">{{ $order->order_code }}</span>
            </p>
            <p class="mb-2">
                <span class="fw-bold text-dark">Ngày đặt:</span>
                <span class="text-secondary">{{ $order->created_at->format('d/m/Y H:i') }}</span>
            </p>
            <p class="mb-2">
                <span class="fw-bold text-dark">Phương thức:</span>
                <span class="text-secondary">{{ strtoupper($order->payment_method ?? 'COD') }}</span>
            </p>
            <p class="mb-0">
                <span class="fw-bold text-dark">Ghi chú:</span>
                <span class="text-secondary">{{ $order->notes ?? 'Không có ghi chú' }}</span>
            </p>
        </div>
    </div>
</div>
@endsection