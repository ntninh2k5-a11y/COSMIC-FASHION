@extends('layouts.app')

@section('title', 'Xác thực Email - Cosmic Fashion')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/verify.css') }}">
@endpush

@section('content')
<div class="container py-5">
    <div class="card border-0 shadow-sm p-4 my-4 text-center" style="background-color: #D1DDE3; border-radius: 12px; max-width: 600px; margin: 0 auto;">
        
        <h3 class="fw-bolder mb-3" style="letter-spacing: 1px; color: #1a1a1a;">VERIFY EMAIL</h3>
        
        <p class="fw-bold mb-4" style="font-size: 0.95rem; color: #444;">
            Vui lòng kiểm tra hộp thư email của bạn để xác thực tài khoản trước khi tiếp tục mua sắm.
        </p>

       @if (session('success'))
    <div class="alert alert-success fw-bold text-dark border-0" style="border-radius: 8px; background-color: #d1e7dd;" role="alert">
        {{ session('success') }}
    </div>
    @endif
        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit" class="btn w-100 btn-dark fw-bold" style="border-radius: 8px; padding: 12px;">GỬI EMAIL XÁC THỰC</button>
        </form>
        
    </div>
</div>
@endsection