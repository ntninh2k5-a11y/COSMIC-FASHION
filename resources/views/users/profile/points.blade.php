@extends('users.profile.layout')
@section('title', 'Đổi Điểm - Cosmic Fashion')
@section('profile_content')

{{-- CARD SỐ DƯ + ĐỔI ĐIỂM --}}
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="p-4 rounded-4 text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #FF6B6B 0%, #E55555 100%); min-height:160px;">
            <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; border-radius:50%; background:rgba(255,255,255,0.1);"></div>
            <div style="position:absolute; bottom:-30px; right:40px; width:70px; height:70px; border-radius:50%; background:rgba(255,255,255,0.08);"></div>
            <p class="mb-1 text-uppercase" style="font-size:0.75rem; letter-spacing:1.5px; opacity:0.85;">Điểm tích lũy hiện tại</p>
            <h2 class="fw-bold mb-1" style="font-size:2.2rem;">{{ number_format($balance) }}</h2>
            <p class="mb-0" style="font-size:0.85rem; opacity:0.85;">
                <i class="bi bi-star-fill me-1"></i> Tương đương {{ number_format($balance * 1000, 0, ',', '.') }}đ giảm giá
            </p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="p-4 rounded-4 border h-100 d-flex flex-column justify-content-center" style="background:#fff;">
            <h6 class="fw-bold mb-3"><i class="bi bi-gift text-danger me-2"></i>Đổi điểm lấy mã giảm giá</h6>

            @if(session('success'))
                <div class="alert border-0 rounded-3 py-2 px-3 mb-3 d-flex align-items-center gap-2" style="background:#dcfce7; color:#15803d; font-size:0.85rem;">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert border-0 rounded-3 py-2 px-3 mb-3 d-flex align-items-center gap-2" style="background:#fee2e2; color:#b91c1c; font-size:0.85rem;">
                    <i class="bi bi-x-circle-fill"></i> {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('user.points.redeem') }}" method="POST">
                @csrf
                <div class="input-group mb-2">
                    <input type="number" name="points" class="form-control rounded-start-3" placeholder="Nhập số điểm muốn đổi" min="10" max="{{ $balance }}" required style="border-color:#e9ecef;">
                    <button type="submit" class="btn text-white fw-semibold rounded-end-3 px-4" style="background:#FF6B6B;" {{ $balance < 10 ? 'disabled' : '' }}>
                        <i class="bi bi-arrow-repeat me-1"></i> Đổi điểm
                    </button>
                </div>
                <small class="text-muted">Tối thiểu 10 điểm. Quy đổi: 1 điểm = 1.000đ giảm giá.</small>
                @error('points')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </form>
        </div>
    </div>
</div>

{{-- HƯỚNG DẪN TÍCH ĐIỂM --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="p-3 rounded-3 border text-center" style="background:#f8fffe;">
            <i class="bi bi-bag-check d-block mb-2" style="font-size:1.5rem; color:#4ECDC4;"></i>
            <div class="fw-bold small mb-1">Mua hàng</div>
            <p class="text-muted mb-0" style="font-size:0.78rem;">Mỗi đơn hoàn thành +10 điểm</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="p-3 rounded-3 border text-center" style="background:#fff9f0;">
            <i class="bi bi-arrow-repeat d-block mb-2" style="font-size:1.5rem; color:#f59e0b;"></i>
            <div class="fw-bold small mb-1">Đổi điểm</div>
            <p class="text-muted mb-0" style="font-size:0.78rem;">1 điểm = 1.000đ giảm giá</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="p-3 rounded-3 border text-center" style="background:#fff5f5;">
            <i class="bi bi-ticket-perforated d-block mb-2" style="font-size:1.5rem; color:#FF6B6B;"></i>
            <div class="fw-bold small mb-1">Nhận voucher</div>
            <p class="text-muted mb-0" style="font-size:0.78rem;">Mã giảm giá có hiệu lực 30 ngày</p>
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
