@extends('layouts.app')

@section('title', 'Xác thực Email - Cosmic Fashion')

@push('styles')
<style>
    .verify-container {
        max-width: 520px;
        margin: 0 auto;
    }
    .verify-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        padding: 48px 40px;
        text-align: center;
    }
    .verify-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #fff0f0, #ffe0e0);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
    }
    .verify-icon i {
        font-size: 36px;
        color: #FF6B6B;
    }
    .verify-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #2D3436;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
    .verify-subtitle {
        font-size: 0.9rem;
        color: #636E72;
        margin-bottom: 28px;
        line-height: 1.6;
    }
    .verify-email-highlight {
        display: inline-block;
        background: #f8f9fa;
        border: 1.5px solid #e9ecef;
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 700;
        color: #2D3436;
        font-size: 0.95rem;
        margin-bottom: 28px;
        letter-spacing: 0.3px;
    }
    .verify-steps {
        text-align: left;
        background: #f8f9fa;
        border-radius: 14px;
        padding: 20px 24px;
        margin-bottom: 28px;
    }
    .verify-step {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 12px;
    }
    .verify-step:last-child { margin-bottom: 0; }
    .verify-step-num {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #FF6B6B;
        color: #fff;
        font-size: 0.75rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-top: 1px;
    }
    .verify-step-text {
        font-size: 0.85rem;
        color: #2D3436;
        line-height: 1.5;
    }
    .btn-resend {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 14px 24px;
        background: linear-gradient(135deg, #FF6B6B, #E55555);
        color: #fff;
        border: none;
        border-radius: 50rem;
        font-size: 0.9rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: all 0.25s ease;
    }
    .btn-resend:hover {
        background: linear-gradient(135deg, #E55555, #cc4444);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(255,107,107,0.35);
    }
    .btn-back-home {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 16px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #636E72;
        text-decoration: none;
        transition: color 0.2s;
    }
    .btn-back-home:hover { color: #FF6B6B; }
    .verify-note {
        font-size: 0.78rem;
        color: #94a3b8;
        margin-top: 20px;
        line-height: 1.5;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="verify-container">
        <div class="verify-card">

            {{-- Icon --}}
            <div class="verify-icon">
                <i class="bi bi-envelope-paper"></i>
            </div>

            {{-- Title --}}
            <h2 class="verify-title">Xác thực email của bạn</h2>
            <p class="verify-subtitle">
                Chúng tôi đã gửi một email xác thực đến địa chỉ:
            </p>

            {{-- Email highlight --}}
            @php
                $user = \App\Models\User::find(session('user_id'));
            @endphp
            @if($user)
                <div class="verify-email-highlight">
                    <i class="bi bi-envelope me-2"></i>{{ $user->email }}
                </div>
            @endif

            {{-- Success alert --}}
            @if (session('success'))
                <div class="alert d-flex align-items-center gap-2" style="background:#dcfce7;color:#15803d;border:none;border-radius:12px;font-size:0.88rem;font-weight:600;" role="alert">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Steps --}}
            <div class="verify-steps">
                <div class="verify-step">
                    <span class="verify-step-num">1</span>
                    <span class="verify-step-text">Mở hộp thư email của bạn (kiểm tra cả thư mục <strong>Spam/Quảng cáo</strong>)</span>
                </div>
                <div class="verify-step">
                    <span class="verify-step-num">2</span>
                    <span class="verify-step-text">Tìm email từ <strong>Cosmic Fashion</strong> với tiêu đề "Verify Email Address"</span>
                </div>
                <div class="verify-step">
                    <span class="verify-step-num">3</span>
                    <span class="verify-step-text">Nhấn vào nút <strong>xác thực</strong> trong email để hoàn tất</span>
                </div>
            </div>

            {{-- Resend button --}}
            <form method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="btn-resend">
                    <i class="bi bi-arrow-clockwise"></i>
                    Gửi lại email xác thực
                </button>
            </form>

            {{-- Back to home --}}
            <a href="{{ route('home') }}" class="btn-back-home">
                <i class="bi bi-arrow-left"></i> Quay về trang chủ
            </a>

            {{-- Note --}}
            <p class="verify-note">
                <i class="bi bi-info-circle me-1"></i>
                Email xác thực có hiệu lực trong 60 phút. Nếu không nhận được email, hãy bấm gửi lại.
            </p>
        </div>
    </div>
</div>
@endsection