@extends('admin.layouts.admin')

@section('title', 'Quản lý Tài khoản - Admin')

@section('page-title', 'QUẢN LÝ TÀI KHOẢN')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="neo-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bolder m-0">Danh sách Người dùng</h4>
            </div>
            <div class="table-responsive">
                <table class="table table-borderless align-middle">
                    <thead class="border-bottom border-dark border-2">
                        <tr>
                            <th class="fw-bolder text-dark">ID</th>
                            <th class="fw-bolder text-dark">TÊN HIỂN THỊ</th>
                            <th class="fw-bolder text-dark">EMAIL</th>
                            <th class="fw-bolder text-dark">VAI TRÒ</th>
                            <th class="fw-bolder text-dark">HÀNH ĐỘNG</th>
                        </tr>
                    </thead>
                    <tbody class="fw-bold text-secondary">
                        @foreach($users as $user)
                            <tr>
                                <td class="text-dark">{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->isAdmin())
                                        <span class="neo-badge pending">Quản trị viên</span>
                                    @else
                                        <span class="neo-badge completed bg-secondary">Khách hàng</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="neo-btn-sm me-1 text-decoration-none d-inline-block text-center" style="width: 55px;">SỬA</a>
                                    
                                    <form action="{{ route('admin.users.lock', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="neo-btn-sm text-white border-0 {{ $user->status === 'locked' ? 'bg-secondary' : 'bg-danger' }}" onclick="return confirm('Bạn có chắc chắn muốn thay đổi trạng thái của tài khoản này?')">
                                            {{ $user->status === 'locked' ? 'MỞ LẠI' : 'KHÓA' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection