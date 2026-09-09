@extends('layouts.app')

@section('title', 'Đăng Ký - Cosmic Fashion')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endpush

@section('content')
<div class="container py-5">
    <div class="neo-card my-4">
        
        <div class="text-center mb-4">
            <h3 class="fw-bolder mb-1" style="letter-spacing: 1px; color: #1a1a1a;">SIGN UP</h3>
            <small class="fw-bold" style="font-size: 0.7rem; color: #555; letter-spacing: 0.5px;">CREATE YOUR ACCOUNT</small>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label class="fw-bolder mb-2" style="font-size: 0.75rem; letter-spacing: 1px; color: #1a1a1a;">NAME</label>
                <input type="text" class="form-control neo-input @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                @error('name')
                    <span class="text-danger fw-bold small mt-1 d-block">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="fw-bolder mb-2" style="font-size: 0.75rem; letter-spacing: 1px; color: #1a1a1a;">EMAIL</label>
                <input type="email" class="form-control neo-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                @error('email')
                    <span class="text-danger fw-bold small mt-1 d-block">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="fw-bolder mb-2" style="font-size: 0.75rem; letter-spacing: 1px; color: #1a1a1a;">PASSWORD</label>
                <input type="password" class="form-control neo-input @error('password') is-invalid @enderror" name="password" required>
                @error('password')
                    <span class="text-danger fw-bold small mt-1 d-block">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label class="fw-bolder mb-2" style="font-size: 0.75rem; letter-spacing: 1px; color: #1a1a1a;">CONFIRM PASSWORD</label>
                <input type="password" class="form-control neo-input" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn w-100 neo-btn-black mb-4">CREATE ACCOUNT</button>

            <div class="text-center" style="font-size: 0.85rem;">
                <span class="text-secondary fw-bold" style="color: #444 !important;">Already have an account?</span> 
                <a href="{{ route('login') }}" class="text-dark fw-bolder text-decoration-none">SIGN IN</a>
            </div>
        </form>
    </div>
</div>
@endsection