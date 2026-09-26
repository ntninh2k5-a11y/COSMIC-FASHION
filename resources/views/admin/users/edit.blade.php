@extends('admin.layouts.admin')

@section('title', 'Sửa Tài khoản - Admin')

@section('page-title', 'SỬA TÀI KHOẢN')

@section('content')
<div class="row g-4">
    {{-- THÔNG TIN TÀI KHOẢN --}}
    <div class="col-12 col-xl-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <h2 class="admin-card-title"><i class="bi bi-person text-muted"></i> Thông tin tài khoản</h2>
            </div>
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Tên hiển thị</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control text-muted" value="{{ $user->email }}" readonly disabled>
                        <small class="text-muted d-block mt-1">Email là định danh, không được thay đổi.</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Họ và tên</label>
                        <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $user->profile->full_name ?? '') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Số điện thoại</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->profile->phone ?? '') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Ngày sinh</label>
                        <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $user->profile->date_of_birth ?? '') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Giới tính</label>
                        <select name="gender" class="form-select">
                            <option value="">Chọn giới tính</option>
                            <option value="male" {{ (old('gender', $user->profile->gender ?? '') == 'male') ? 'selected' : '' }}>Nam</option>
                            <option value="female" {{ (old('gender', $user->profile->gender ?? '') == 'female') ? 'selected' : '' }}>Nữ</option>
                            <option value="other" {{ (old('gender', $user->profile->gender ?? '') == 'other') ? 'selected' : '' }}>Khác</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold">Vai trò</label>
                        <select name="role" class="form-select">
                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Khách hàng</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold">Điểm tích lũy</label>
                        <input type="number" name="loyalty_points" class="form-control" value="{{ old('loyalty_points', $user->profile->loyalty_points ?? 0) }}" min="0">
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn-primary-admin flex-grow-1 justify-content-center">
                        <i class="bi bi-check-lg"></i> LƯU THAY ĐỔI
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn-outline-admin">HỦY BỎ</a>
                </div>
            </form>
        </div>
    </div>

    {{-- SỔ ĐỊA CHỈ --}}
    <div class="col-12 col-xl-4">
        <div class="admin-card">
            <div class="admin-card-header" style="margin-bottom:12px; padding-bottom:10px;">
                <h2 class="admin-card-title" style="font-size:0.95rem;">
                    <i class="bi bi-geo-alt text-muted"></i> Sổ địa chỉ ({{ $addresses->count() }})
                </h2>
            </div>

            @if($addresses->count() > 0)
                <div class="d-flex flex-column gap-3">
                    @foreach($addresses as $addr)
                    <div class="border rounded-3 p-3 position-relative {{ $addr->is_default ? 'border-danger' : '' }}" style="font-size: 0.85rem; {{ $addr->is_default ? 'background:#fff9f9;' : '' }}">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="fw-bold text-dark">{{ $addr->receiver_name }}</span>
                            <span class="text-muted">|</span>
                            <span class="text-muted">{{ $addr->phone_number }}</span>
                            @if($addr->is_default)
                                <span class="badge rounded-pill" style="background:#FF6B6B; font-size:0.65rem;">Mặc định</span>
                            @endif
                        </div>
                        <p class="text-muted mb-1" style="font-size:0.82rem; line-height:1.4;">{{ $addr->receiver_address }}</p>
                        @if($addr->note)
                            <p class="mb-0" style="font-size:0.78rem; color:#9ca3af; font-style:italic;">
                                <i class="bi bi-chat-left-text me-1"></i>{{ $addr->note }}
                            </p>
                        @endif
                        <div class="mt-2 pt-2 border-top">
                            <form action="{{ route('admin.users.addresses.destroy', [$user->id, $addr->id]) }}" method="POST"
                                  class="d-inline" onsubmit="return confirm('Xóa địa chỉ này?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-sm-delete" style="font-size:0.75rem; padding:3px 10px;">
                                    <i class="bi bi-trash"></i> Xóa
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-geo-alt text-muted" style="font-size:2rem;"></i>
                    <p class="text-muted small mt-2 mb-0">Chưa có địa chỉ nào.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection