@extends('users.profile.layout')
@section('title', 'Đổi Điểm - Cosmic Fashion')
@section('profile_content')

{{-- CARD SỐ DƯ --}}
<div class="p-4 rounded-4 text-white position-relative overflow-hidden mb-4" style="background: linear-gradient(135deg, #FF6B6B 0%, #E55555 100%); min-height:140px;">
    <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; border-radius:50%; background:rgba(255,255,255,0.1);"></div>
    <div style="position:absolute; bottom:-30px; right:40px; width:70px; height:70px; border-radius:50%; background:rgba(255,255,255,0.08);"></div>
    <p class="mb-1 text-uppercase" style="font-size:0.75rem; letter-spacing:1.5px; opacity:0.85;">Điểm tích lũy hiện tại</p>
    <h2 class="fw-bold mb-1" style="font-size:2.2rem;">{{ number_format($balance) }}</h2>
    <p class="mb-0" style="font-size:0.85rem; opacity:0.85;">
        <i class="bi bi-star-fill me-1"></i> Tương đương {{ number_format($balance * 1000, 0, ',', '.') }}đ giảm giá
    </p>
</div>

{{-- HƯỚNG DẪN CÁCH ĐIỂM HOẠT ĐỘNG --}}
<div class="mb-4">
    <h6 class="fw-bold mb-3"><i class="bi bi-info-circle text-muted me-2"></i>Cách điểm hoạt động</h6>
    <div class="row g-3">
        <div class="col-md-4">
            <div class="p-3 rounded-3 border text-center h-100" style="background:#f8fffe;">
                <i class="bi bi-bag-check d-block mb-2" style="font-size:1.5rem; color:#4ECDC4;"></i>
                <div class="fw-bold small mb-1">1. Tích điểm</div>
                <p class="text-muted mb-0" style="font-size:0.78rem;">Mỗi đơn hàng hoàn thành, bạn được <strong>+10 điểm</strong> tự động</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 rounded-3 border text-center h-100" style="background:#fff9f0;">
                <i class="bi bi-cart-check d-block mb-2" style="font-size:1.5rem; color:#f59e0b;"></i>
                <div class="fw-bold small mb-1">2. Dùng tại checkout</div>
                <p class="text-muted mb-0" style="font-size:0.78rem;">Nhập số điểm muốn dùng ngay tại trang thanh toán. <strong>1 điểm = 1.000đ</strong></p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 rounded-3 border text-center h-100" style="background:#fff5f5;">
                <i class="bi bi-ticket-perforated d-block mb-2" style="font-size:1.5rem; color:#FF6B6B;"></i>
                <div class="fw-bold small mb-1">3. Kết hợp voucher</div>
                <p class="text-muted mb-0" style="font-size:0.78rem;">Có thể dùng <strong>cả voucher và điểm</strong> cùng lúc. Voucher tính trước, điểm tính sau</p>
            </div>
        </div>
    </div>
</div>

{{-- LỊCH SỬ GIAO DỊCH --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="fw-bold mb-0"><i class="bi bi-clock-history text-muted me-2"></i>Lịch sử giao dịch điểm</h6>
</div>

<div class="table-responsive">
    <table class="table align-middle mb-0" style="border-collapse:separate; border-spacing:0;">
        <thead>
            <tr>
                <th style="background:#f8f9fa; font-size:0.75rem; font-weight:700; letter-spacing:0.8px; text-transform:uppercase; color:#636E72; padding:12px 16px; border-bottom:1px solid #e9ecef;">NGÀY</th>
                <th style="background:#f8f9fa; font-size:0.75rem; font-weight:700; letter-spacing:0.8px; text-transform:uppercase; color:#636E72; padding:12px 16px; border-bottom:1px solid #e9ecef;">LOẠI</th>
                <th style="background:#f8f9fa; font-size:0.75rem; font-weight:700; letter-spacing:0.8px; text-transform:uppercase; color:#636E72; padding:12px 16px; border-bottom:1px solid #e9ecef;">MÔ TẢ</th>
                <th style="background:#f8f9fa; font-size:0.75rem; font-weight:700; letter-spacing:0.8px; text-transform:uppercase; color:#636E72; padding:12px 16px; border-bottom:1px solid #e9ecef; text-align:right;">ĐIỂM</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $t)
                <tr>
                    <td style="padding:14px 16px; border-bottom:1px solid #f0f2f5; font-size:0.85rem;" class="text-muted">
                        {{ $t->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td style="padding:14px 16px; border-bottom:1px solid #f0f2f5; font-size:0.85rem;">
                        <span style="display:inline-flex; align-items:center; padding:4px 10px; border-radius:50rem; font-size:0.73rem; font-weight:600; {{ $t->type_badge }}">
                            {{ $t->type_label }}
                        </span>
                    </td>
                    <td style="padding:14px 16px; border-bottom:1px solid #f0f2f5; font-size:0.85rem;">
                        {{ $t->description ?: '—' }}
                    </td>
                    <td style="padding:14px 16px; border-bottom:1px solid #f0f2f5; font-size:0.9rem; text-align:right;">
                        @if($t->points > 0)
                            <span class="fw-bold" style="color:#16a34a;">+{{ number_format($t->points) }}</span>
                        @else
                            <span class="fw-bold" style="color:#dc2626;">{{ number_format($t->points) }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-5 border-0">
                        <i class="bi bi-clock-history d-block mb-2" style="font-size:2.5rem; color:#d1d5db;"></i>
                        <p class="text-muted mb-0">Chưa có giao dịch điểm nào.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($transactions->hasPages())
    <div class="d-flex justify-content-center mt-4">
        <nav>{{ $transactions->links('pagination::bootstrap-5') }}</nav>
    </div>
@endif
@endsection

@push('styles')
<style>
    .pagination .page-link {
        color: #2D3436; border: 1.5px solid #e9ecef; border-radius: 8px !important;
        margin: 0 3px; font-weight: 600; font-size: 0.85rem; padding: 7px 13px; transition: all 0.18s;
    }
    .pagination .page-link:hover { background: #fff0f0; border-color: #FF6B6B; color: #FF6B6B; }
    .pagination .page-item.active .page-link { background: #FF6B6B; border-color: #FF6B6B; color: #fff; box-shadow: 0 4px 10px rgba(255,107,107,0.3); }
    .pagination .page-item.disabled .page-link { opacity: 0.4; }
</style>
@endpush
