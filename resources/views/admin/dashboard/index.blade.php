```blade
@extends('admin.layouts.admin')

@section('title', 'Tổng quan - Admin')

@section('page-title', 'Bảng Điều Khiển')

@section('content')

<div class="row mb-4">

    <div class="col-md-3">
        <div class="neo-card text-center">
            <h6 class="fw-bolder text-secondary mb-3">ĐƠN HÀNG MỚI</h6>
            <h1 class="fw-bolder m-0 text-accent">
                {{ number_format($newOrders ?? 0) }}
            </h1>
        </div>
    </div>

    <div class="col-md-3">
        <div class="neo-card text-center">
            <h6 class="fw-bolder text-secondary mb-3">TỔNG DOANH THU</h6>
            <h1 class="fw-bolder m-0 text-dark">
                {{ number_format($revenue ?? 0, 0, ',', '.') }}đ
            </h1>
        </div>
    </div>

    <div class="col-md-3">
        <div class="neo-card text-center">
            <h6 class="fw-bolder text-secondary mb-3">SẢN PHẨM TRONG KHO</h6>
            <h1 class="fw-bolder m-0 text-dark">
                {{ number_format($productsInStock ?? 0) }}
            </h1>
        </div>
    </div>

    <div class="col-md-3">
        <div class="neo-card text-center">
            <h6 class="fw-bolder text-secondary mb-3">KHÁCH HÀNG</h6>
            <h1 class="fw-bolder m-0 text-dark">
                {{ number_format($customers ?? 0) }}
            </h1>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-12">
        <div class="neo-card">

            <h4 class="fw-bolder border-bottom border-dark pb-2 mb-3 border-2">
                Đơn Hàng Gần Đây
            </h4>

            <div class="table-responsive">
                <table class="table table-borderless align-middle">

                    <thead class="border-bottom border-dark border-2">
                        <tr>
                            <th class="fw-bolder text-dark">MÃ ĐƠN</th>
                            <th class="fw-bolder text-dark">KHÁCH HÀNG</th>
                            <th class="fw-bolder text-dark">TỔNG TIỀN</th>
                            <th class="fw-bolder text-dark">TRẠNG THÁI</th>
                            <th class="fw-bolder text-dark">HÀNH ĐỘNG</th>
                        </tr>
                    </thead>

                    <tbody class="fw-bold text-secondary">

                        @forelse($recentOrders ?? [] as $order)

                            <tr>
                                <td class="text-dark">
                                    {{ $order->order_code }}
                                </td>

                                <td>
                                    {{ $order->user->name ?? 'Khách vãng lai' }}
                                </td>

                                <td>
                                    {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}đ
                                </td>

                                <td>
                                    @switch($order->status)

                                        @case('pending')
                                            <span class="neo-badge pending">
                                                Chờ xử lý
                                            </span>
                                            @break

                                        @case('confirmed')
                                            <span class="neo-badge pending">
                                                Đã xác nhận
                                            </span>
                                            @break

                                        @case('shipping')
                                            <span class="neo-badge pending">
                                                Đang giao
                                            </span>
                                            @break

                                        @case('completed')
                                            <span class="neo-badge completed">
                                                Đã giao
                                            </span>
                                            @break

                                        @case('cancelled')
                                            <span class="neo-badge cancelled">
                                                Đã hủy
                                            </span>
                                            @break

                                        @default
                                            <span class="neo-badge">
                                                {{ $order->status }}
                                            </span>

                                    @endswitch
                                </td>

                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                       class="neo-btn-sm text-decoration-none">
                                        XEM
                                    </a>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    Chưa có đơn hàng nào.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>

        </div>
    </div>
</div>

@endsection
```
