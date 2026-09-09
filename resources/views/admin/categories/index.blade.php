@extends('admin.layouts.admin')

@section('title', 'Quản lý Danh mục - Admin')

@section('page-title', 'QUẢN LÝ DANH MỤC')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="neo-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bolder m-0">Danh sách Danh mục</h4>
                <a href="{{ route('admin.categories.create') }}" class="neo-btn text-decoration-none">
                    + THÊM DANH MỤC
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success fw-bold">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-borderless align-middle">
                    <thead class="border-bottom border-dark border-2">
                        <tr>
                            <th class="fw-bolder text-dark" style="width: 80px;">ID</th>
                            <th class="fw-bolder text-dark">TÊN DANH MỤC</th>
                            <th class="fw-bolder text-dark text-center">TRẠNG THÁI</th>
                            <th class="fw-bolder text-dark text-center" style="width: 180px;">HÀNH ĐỘNG</th>
                        </tr>
                    </thead>
                    <tbody class="fw-bold text-secondary">
                        @forelse($categories as $category)
                            <tr>
                                <td class="text-dark">{{ $category->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @if($category->image_url)
                                            <img src="{{ asset($category->image_url) }}" 
                                                 alt="{{ $category->name }}" 
                                                 class="rounded" 
                                                 style="width: 48px; height: 48px; object-fit: cover;">
                                        @else
                                            <div class="rounded bg-light d-flex align-items-center justify-content-center text-secondary" style="width: 48px; height: 48px; font-size: 0.7rem;">
                                                No Img
                                            </div>
                                        @endif
                                        <span class="text-dark">{{ $category->name }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($category->status == 1)
                                        <span class="text-success">Hiển thị</span>
                                    @else
                                        <span class="text-danger">Đang ẩn</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="neo-btn-sm me-1 text-decoration-none">SỬA</a>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="neo-btn-sm bg-danger text-white border-0">XÓA</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-secondary">Chưa có danh mục nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection