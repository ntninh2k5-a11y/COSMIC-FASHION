@extends('admin.layouts.admin')
@section('title', 'Tài khoản - Admin')
@section('page-title', 'Quản lý Tài khoản')
@section('content')

<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title"><i class="bi bi-people text-muted"></i> Danh sách người dùng</h2>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>TÊN HIỂN THỊ</th>
                    <th>EMAIL</th>
                    <th>VAI TRÒ</th>
                    <th>TRẠNG THÁI</th>
                    <th class="text-center">HÀNH ĐỘNG</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td class="text-muted fw-bold" style="font-size:0.8rem;">#{{ $user->id }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#FF6B6B,#4ECDC4);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.8rem;flex-shrink:0;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="fw-bold text-dark">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="text-muted">{{ $user->email }}</td>
                        <td>
                            @if($user->isAdmin())
                                <span class="badge-status badge-admin">
                                    <i class="bi bi-shield-check" style="font-size:0.7rem;"></i> Quản trị viên
                                </span>
                            @else
                                <span class="badge-status badge-inactive">
                                    <i class="bi bi-person" style="font-size:0.7rem;"></i> Khách hàng
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($user->status === 'locked')
                                <span class="badge-status badge-locked">
                                    <i class="bi bi-lock-fill" style="font-size:0.7rem;"></i> Đã khóa
                                </span>
                            @else
                                <span class="badge-status badge-active">
                                    <i class="bi bi-circle-fill" style="font-size:0.5rem;"></i> Hoạt động
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-sm-edit">
                                    <i class="bi bi-pencil"></i> Sửa
                                </a>
                                <form action="{{ route('admin.users.lock', $user->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <button type="submit" class="btn-sm-delete"
                                            onclick="return confirm('Bạn có chắc chắn muốn thay đổi trạng thái của tài khoản này?')"
                                            style="{{ $user->status === 'locked' ? 'color:#16a34a;' : '' }}">
                                        @if($user->status === 'locked')
                                            <i class="bi bi-unlock"></i> Mở khóa
                                        @else
                                            <i class="bi bi-lock"></i> Khóa
                                        @endif
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if(method_exists($users, 'hasPages') && $users->hasPages())
        <div class="d-flex justify-content-center mt-4 admin-pagination">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection