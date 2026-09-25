@extends('users.profile.layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/orders_user.css') }}">
@endpush

@section('profile_content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="m-0">Đơn hàng của tôi</h4>
    </div>
    
    <div class="cosmic-table-wrapper">
        <div class="table-responsive">
            <table class="table cosmic-table align-middle m-0">
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
                                <span class="badge-status {{ $order->status_badge }}">{{ $order->status_label }}</span>
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

    @if($orders->hasPages())
        <div class="mt-4 d-flex justify-content-center custom-pagination">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    @endif
@endsection

@push('styles')
<style>
    .custom-pagination .page-link {
        color: #2D3436;
        border: none;
        background-color: #FAFAFA;
        margin: 0 4px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s;
    }
    .custom-pagination .page-item.active .page-link {
        background-color: #FF6B6B;
        color: #fff;
        box-shadow: 0 4px 10px rgba(255,107,107,0.3);
    }
    .custom-pagination .page-link:hover {
        background-color: #f1f2f4;
        color: #FF6B6B;
    }
    .custom-pagination .page-item.active .page-link:hover {
        background-color: #FF6B6B;
        color: #fff;
    }
</style>
@endpush

