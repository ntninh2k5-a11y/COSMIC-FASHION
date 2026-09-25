@extends('admin.layouts.admin')
@section('title', 'Banner - Admin')
@section('page-title', 'Quản lý Banner')
@section('content')

<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title"><i class="bi bi-images text-muted"></i> Danh sách Banner</h2>
        <a href="{{ route('admin.banners.create') }}" class="btn-primary-admin">
            <i class="bi bi-plus-lg"></i> Thêm Banner
        </a>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:50px;" class="text-center">#</th>
                    <th style="width:180px;">HÌNH ẢNH</th>
                    <th>TIÊU ĐỀ / ĐƯỜNG DẪN</th>
                    <th class="text-center" style="width:90px;">VỊ TRÍ</th>
                    <th class="text-center" style="width:120px;">TRẠNG THÁI</th>
                    <th class="text-center" style="width:150px;">HÀNH ĐỘNG</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banners as $banner)
                    <tr>
                        <td class="text-center text-muted fw-bold" style="font-size:0.8rem;">
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            <img src="{{ asset($banner->image_url) }}"
                                 alt="{{ $banner->title }}"
                                 style="width:120px;height:68px;object-fit:cover;border-radius:10px;border:1px solid #f0f0f0;">
                        </td>
                        <td>
                            <div class="fw-bold text-dark mb-1">{{ $banner->title ?? 'Không có tiêu đề' }}</div>
                            @if($banner->link)
                                <a href="{{ $banner->link }}" target="_blank"
                                   class="text-muted text-decoration-none d-flex align-items-center gap-1"
                                   style="font-size:0.78rem;max-width:280px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    <i class="bi bi-link-45deg"></i>
                                    {{ $banner->link }}
                                </a>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge-status badge-inactive" style="font-size:0.8rem;">
                                {{ $banner->order }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($banner->is_active)
                                <span class="badge-status badge-active">
                                    <i class="bi bi-eye" style="font-size:0.7rem;"></i> Hiển thị
                                </span>
                            @else
                                <span class="badge-status badge-inactive">
                                    <i class="bi bi-eye-slash" style="font-size:0.7rem;"></i> Đã ẩn
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn-sm-edit">
                                    <i class="bi bi-pencil"></i> Sửa
                                </a>
                                <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST"
                                      class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa banner này không?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-sm-delete">
                                        <i class="bi bi-trash"></i> Xóa
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-images fs-3 d-block mb-2"></i>
                            Chưa có banner nào được thêm.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(method_exists($banners, 'hasPages') && $banners->hasPages())
        <div class="d-flex justify-content-center mt-4 admin-pagination">
            {{ $banners->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
