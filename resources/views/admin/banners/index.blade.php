@extends('admin.layouts.admin')

@section('title', 'Quản lý Banner - Admin')

@section('page-title', 'QUẢN LÝ BANNER')

@section('content')
<div class="neo-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0 fw-bold">Danh sách Banner</h5>
        <a href="{{ route('admin.banners.create') }}" class="neo-btn">THÊM BANNER</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-0 border-2 border-dark" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered border-dark neo-table align-middle">
            <thead class="table-dark">
                <tr>
                    <th width="50" class="text-center">#</th>
                    <th width="200">Hình ảnh</th>
                    <th>Tiêu đề / Link</th>
                    <th width="100" class="text-center">Vị trí</th>
                    <th width="120" class="text-center">Trạng thái</th>
                    <th width="150" class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banners as $banner)
                <tr>
                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                    <td>
                        <img src="{{ asset($banner->image_url) }}" alt="{{ $banner->title }}" class="img-fluid rounded border border-dark" style="max-height: 80px; object-fit: cover;">
                    </td>
                    <td>
                        <div class="fw-bold">{{ $banner->title ?? 'Không có tiêu đề' }}</div>
                        @if($banner->link)
                            <a href="{{ $banner->link }}" target="_blank" class="small text-muted text-truncate d-block" style="max-width: 250px;">{{ $banner->link }}</a>
                        @endif
                    </td>
                    <td class="text-center fw-bold">{{ $banner->order }}</td>
                    <td class="text-center">
                        @if($banner->is_active)
                            <span class="badge bg-success rounded-0 border border-dark border-1">Hiển thị</span>
                        @else
                            <span class="badge bg-secondary rounded-0 border border-dark border-1">Đã ẩn</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-sm btn-outline-dark rounded-0 border-2" title="Sửa">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa banner này không?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger rounded-0 border-2 border-dark" title="Xóa">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Chưa có banner nào được thêm.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
