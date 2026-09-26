@extends('users.profile.layout')

@section('title', 'Đơn hàng của tôi - Cosmic Fashion')

@section('profile_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0"><i class="bi bi-receipt text-muted me-2"></i>Đơn hàng của tôi</h5>
</div>

<div class="table-responsive">
    <table class="table align-middle mb-0" style="border-collapse: separate; border-spacing: 0;">
        <thead>
            <tr>
                <th style="background:#f8f9fa; font-size:0.75rem; font-weight:700; letter-spacing:0.8px; text-transform:uppercase; color:#636E72; padding:12px 16px; border-bottom:1px solid #e9ecef;">MÃ ĐƠN</th>
                <th style="background:#f8f9fa; font-size:0.75rem; font-weight:700; letter-spacing:0.8px; text-transform:uppercase; color:#636E72; padding:12px 16px; border-bottom:1px solid #e9ecef;">NGÀY ĐẶT</th>
                <th style="background:#f8f9fa; font-size:0.75rem; font-weight:700; letter-spacing:0.8px; text-transform:uppercase; color:#636E72; padding:12px 16px; border-bottom:1px solid #e9ecef;">TỔNG TIỀN</th>
                <th style="background:#f8f9fa; font-size:0.75rem; font-weight:700; letter-spacing:0.8px; text-transform:uppercase; color:#636E72; padding:12px 16px; border-bottom:1px solid #e9ecef;">TRẠNG THÁI</th>
                <th style="background:#f8f9fa; font-size:0.75rem; font-weight:700; letter-spacing:0.8px; text-transform:uppercase; color:#636E72; padding:12px 16px; border-bottom:1px solid #e9ecef; text-align:center;">CHI TIẾT</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td style="padding:14px 16px; border-bottom:1px solid #f0f2f5; font-size:0.875rem;">
                        <span class="fw-bold text-dark">{{ $order->order_code }}</span>
                    </td>
                    <td style="padding:14px 16px; border-bottom:1px solid #f0f2f5; font-size:0.875rem;" class="text-muted">
                        {{ $order->created_at->format('d/m/Y') }}
                    </td>
                    <td style="padding:14px 16px; border-bottom:1px solid #f0f2f5; font-size:0.875rem;">
                        <span class="fw-bold" style="color:#FF6B6B;">{{ number_format($order->total_amount, 0, ',', '.') }}đ</span>
                    </td>
                    <td style="padding:14px 16px; border-bottom:1px solid #f0f2f5; font-size:0.875rem;">
                        @php
                            $sm = [
                                'pending'    => ['Chờ xử lý', 'background:#fff8e1; color:#f59e0b;'],
                                'processing' => ['Đang chuẩn bị', 'background:#e0f2fe; color:#0284c7;'],
                                'shipping'   => ['Đang giao', 'background:#ede9fe; color:#7c3aed;'],
                                'completed'  => ['Đã giao', 'background:#dcfce7; color:#16a34a;'],
                                'cancelled'  => ['Đã hủy', 'background:#fee2e2; color:#dc2626;'],
                                'paid'       => ['Đã thanh toán', 'background:#dcfce7; color:#16a34a;'],
                            ];
                            $s = $sm[$order->status] ?? [$order->status, 'background:#f3f4f6; color:#6b7280;'];
                        @endphp
                        <span style="display:inline-flex; align-items:center; padding:5px 12px; border-radius:50rem; font-size:0.75rem; font-weight:600; {{ $s[1] }}">{{ $s[0] }}</span>
                    </td>
                    <td style="padding:14px 16px; border-bottom:1px solid #f0f2f5; font-size:0.875rem; text-align:center;">
                        <a href="{{ route('user.orders.show', $order->id) }}" class="text-decoration-none fw-semibold" style="display:inline-flex; align-items:center; gap:4px; padding:5px 14px; background:#fff; border:1.5px solid #e9ecef; border-radius:8px; font-size:0.8rem; color:#2D3436; transition:all 0.18s;">
                            <i class="bi bi-eye"></i> Xem
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-5 border-0">
                        <i class="bi bi-inbox d-block mb-2" style="font-size:2.5rem; color:#d1d5db;"></i>
                        <p class="text-muted mb-0">Bạn chưa có đơn hàng nào.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($orders->hasPages())
    <div class="d-flex justify-content-center mt-4">
        <nav>
            {{ $orders->links('pagination::bootstrap-5') }}
        </nav>
    </div>
@endif
@endsection

@push('styles')
<style>
    .pagination .page-link {
        color: #2D3436;
        border: 1.5px solid #e9ecef;
        border-radius: 8px !important;
        margin: 0 3px;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 7px 13px;
        transition: all 0.18s;
    }
    .pagination .page-link:hover {
        background: #fff0f0;
        border-color: #FF6B6B;
        color: #FF6B6B;
    }
    .pagination .page-item.active .page-link {
        background: #FF6B6B;
        border-color: #FF6B6B;
        color: #fff;
        box-shadow: 0 4px 10px rgba(255,107,107,0.3);
    }
    .pagination .page-item.disabled .page-link {
        opacity: 0.4;
    }
</style>
@endpush
