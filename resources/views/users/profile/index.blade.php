@extends('users.profile.layout')

@section('profile_content')
    <h4 class="mb-4">Thông tin tài khoản</h4>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label text-muted small fw-bold text-uppercase">Họ và tên</label>
                <input type="text" name="full_name" class="form-control neo-input" value="{{ old('full_name', $user->profile->full_name ?? '') }}">
            </div>
            <div class="col-md-6 mt-3 mt-md-0">
                <label class="form-label text-muted small fw-bold text-uppercase">Số điện thoại</label>
                <input type="text" name="phone" class="form-control neo-input" value="{{ old('phone', $user->profile->phone ?? '') }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label text-muted small fw-bold text-uppercase">Email</label>
                <input type="email" class="form-control neo-input bg-light" value="{{ $user->email }}" disabled>
                <small class="text-muted">Email không thể thay đổi</small>
            </div>
            <div class="col-md-6 mt-3 mt-md-0">
                <label class="form-label text-muted small fw-bold text-uppercase">Ngày sinh</label>
                <input type="date" name="date_of_birth" class="form-control neo-input" value="{{ old('date_of_birth', $user->profile->date_of_birth ?? '') }}">
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <label class="form-label text-muted small fw-bold text-uppercase">Giới tính</label>
                <select name="gender" class="form-select neo-input">
                    <option value="">Chọn giới tính</option>
                    <option value="male" {{ (old('gender', $user->profile->gender ?? '') == 'male') ? 'selected' : '' }}>Nam</option>
                    <option value="female" {{ (old('gender', $user->profile->gender ?? '') == 'female') ? 'selected' : '' }}>Nữ</option>
                    <option value="other" {{ (old('gender', $user->profile->gender ?? '') == 'other') ? 'selected' : '' }}>Khác</option>
                </select>
            </div>
            <div class="col-md-6 mt-3 mt-md-0">
                <label class="form-label text-muted small fw-bold text-uppercase">Ảnh đại diện</label>
                <input type="file" name="avatar" class="form-control neo-input" accept="image/*">
            </div>
        </div>

        <button type="submit" class="neo-btn">Lưu thay đổi</button>
    </form>
@endsection
