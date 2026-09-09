@extends('layouts.app')

@section('title', 'Đăng Nhập - Cosmic Fashion')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush

@section('content')
<div class="container py-5">
    <div class="neo-card my-4">
        <div class="text-center mb-4">
            <h3 class="fw-bolder mb-1" style="letter-spacing: 1px; color: #1a1a1a;">SIGN IN</h3>
            <small class="fw-bold" style="font-size: 0.7rem; color: #555; letter-spacing: 0.5px;">ENTER YOUR CREDENTIALS</small>
        </div>

        @if(session('error'))
            <div class="alert alert-danger fw-bold small text-center rounded-0 border-dark mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label class="fw-bolder mb-2" style="font-size: 0.75rem; letter-spacing: 1px; color: #1a1a1a;">EMAIL</label>
                <input type="email" class="form-control neo-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="text-danger fw-bold small mt-1 d-block">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="fw-bolder mb-2" style="font-size: 0.75rem; letter-spacing: 1px; color: #1a1a1a;">PASSWORD</label>
                <div class="input-group">
                    <input type="password" class="form-control neo-input @error('password') is-invalid @enderror" name="password" required id="passwordField">
                    <span class="input-group-text password-toggle-btn" onclick="togglePassword(this)">SHOW</span>
                </div>
                @error('password')
                    <span class="text-danger fw-bold small mt-1 d-block">{{ $message }}</span>
                @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4" style="font-size: 0.8rem;">
                <div class="form-check">
                    <input class="form-check-input rounded-0 border-dark" type="checkbox" name="remember" id="remember" value="1" {{ old('remember') ? 'checked' : '' }} style="border-width: 2px;">
                    <label class="form-check-label fw-bold text-dark" for="remember">Remember me</label>
                </div>
                <a href="{{ route('password.request') }}" class="text-dark fw-bold text-decoration-none">FORGOT PASSWORD?</a>
            </div>

            <button type="submit" class="btn w-100 neo-btn-black mb-4">SIGN IN</button>

            <div class="position-relative text-center mb-4">
                <hr class="border-dark opacity-100" style="border-width: 2px;">
                <span class="position-absolute top-50 start-50 translate-middle px-3 fw-bolder" style="font-size: 0.8rem; background: #d1dde3; color: #1a1a1a;">OR</span>
            </div>

            <a href="{{ route('google.login') }}" class="btn w-100 neo-btn-white mb-4 d-flex align-items-center justify-content-center text-decoration-none">
                <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google" style="width: 16px; margin-right: 10px;">
                GOOGLE
            </a>

            <div class="text-center" style="font-size: 0.85rem;">
                <span class="text-secondary fw-bold" style="color: #444 !important;">No account?</span> 
                <a href="{{ route('register') }}" class="text-dark fw-bolder text-decoration-none">CREATE ONE</a>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePassword(btn) {
        var x = document.getElementById("passwordField");
        if (x.type === "password") {
            x.type = "text";
            btn.innerHTML = "HIDE";
        } else {
            x.type = "password";
            btn.innerHTML = "SHOW";
        }
    }
</script>
@endsection