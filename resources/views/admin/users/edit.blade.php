@extends('admin.layouts.admin')

@section('title', 'Sửa Tài khoản - Admin')

@section('page-title', 'SỬA TÀI KHOẢN')

@section('content')
<div class="row">
    <div class="col-12 col-md-6">
        <div class="neo-card">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="fw-bold mb-2">Tên hiển thị</label>
                    <input type="text" name="name" class="form-control border-dark border-2 rounded-0 fw-bold" value="{{ $user->name }}" required>
                </div>

                <div class="mb-3">
                    <label class="fw-bold mb-2">Email</label>
                    <input type="email" class="form-control border-dark border-2 rounded-0 text-muted" value="{{ $user->email }}" readonly disabled>
                    <small class="text-secondary d-block mt-1">Email là định danh nên không được phép thay đổi.</small>
                </div>

                <div class="mb-4">
                    <label class="fw-bold mb-2">Vai trò</label>
                    <select name="role" class="form-select border-dark border-2 rounded-0 fw-bold">
                        <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Khách hàng</option>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                    </select>
                </div>

                <button type="submit" class="neo-btn w-100 mb-2">LƯU THAY ĐỔI</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-dark w-100 fw-bold rounded-0 border-2">HỦY BỎ</a>
            </form>
        </div>
    </div>
</div>
@endsection