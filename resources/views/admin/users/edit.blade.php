@extends('admin.layouts.admin')

@section('title', 'Sửa Tài khoản - Admin')

@section('page-title', 'SỬA TÀI KHOẢN')

@section('content')
<div class="row">
    <div class="col-12 col-xl-8">
        <div class="neo-card">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold mb-2">Tên hiển thị</label>
                        <input type="text" name="name" class="form-control border-dark border-2 rounded-0 fw-bold" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="fw-bold mb-2">Email</label>
                        <input type="email" class="form-control border-dark border-2 rounded-0 text-muted" value="{{ $user->email }}" readonly disabled>
                        <small class="text-secondary d-block mt-1">Email là định danh nên không được phép thay đổi.</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold mb-2">Họ và tên</label>
                        <input type="text" name="full_name" class="form-control border-dark border-2 rounded-0 fw-bold" value="{{ old('full_name', $user->profile->full_name ?? '') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="fw-bold mb-2">Số điện thoại</label>
                        <input type="text" name="phone" class="form-control border-dark border-2 rounded-0 fw-bold" value="{{ old('phone', $user->profile->phone ?? '') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold mb-2">Ngày sinh</label>
                        <input type="date" name="date_of_birth" class="form-control border-dark border-2 rounded-0 fw-bold" value="{{ old('date_of_birth', $user->profile->date_of_birth ?? '') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="fw-bold mb-2">Giới tính</label>
                        <select name="gender" class="form-select border-dark border-2 rounded-0 fw-bold">
                            <option value="">Chọn giới tính</option>
                            <option value="male" {{ (old('gender', $user->profile->gender ?? '') == 'male') ? 'selected' : '' }}>Nam</option>
                            <option value="female" {{ (old('gender', $user->profile->gender ?? '') == 'female') ? 'selected' : '' }}>Nữ</option>
                            <option value="other" {{ (old('gender', $user->profile->gender ?? '') == 'other') ? 'selected' : '' }}>Khác</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="fw-bold mb-2">Vai trò</label>
                        <select name="role" class="form-select border-dark border-2 rounded-0 fw-bold">
                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Khách hàng</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-4">
                        <label class="fw-bold mb-2">Điểm tích lũy</label>
                        <input type="number" name="loyalty_points" class="form-control border-dark border-2 rounded-0 fw-bold" value="{{ old('loyalty_points', $user->profile->loyalty_points ?? 0) }}" min="0">
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="neo-btn flex-grow-1">LƯU THAY ĐỔI</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-dark fw-bold rounded-0 border-2" style="width: 150px;">HỦY BỎ</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection