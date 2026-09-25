@extends('admin.layouts.admin')
@section('title', 'Danh mục - Admin')
@section('page-title', 'Quản lý Danh mục')
@section('content')

<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title"><i class="bi bi-folder text-muted"></i> Danh sách danh mục</h2>
        <a href="{{ route('admin.categories.create') }}" class="btn-primary-admin">
            <i class="bi bi-plus-lg"></i> Thêm danh mục
        </a>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ẢNH</th>
                    <th>TÊN DANH MỤC</th>
                    <th>DANH MỤC CHA</th>
                    <th>TRẠNG THÁI</th>
                    <th class="text-center">HÀNH ĐỘNG</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td class="text-muted fw-bold" style="font-size:0.8rem;">#{{ $category->id }}</td>
                        <td>
                            @if($category->image_url)
                                <img src="{{ asset($category->image_url) }}"
                                     style="width:42px;height:42px;object-fit:cover;border-radius:50%;border:2px solid #f0f0f0;">
                            @else
                                <div style="width:42px;height:42px;border-radius:50%;background:#f0f2f5;display:flex;align-items:center;justify-content:center;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td><span class="fw-bold text-dark">{{ $category->name }}</span></td>
                        <td>
                            @if($category->parent)
                                <a href="{{ route('admin.categories.edit', $category->parent->id) }}"
                                   class="text-decoration-none" style="color:#FF6B6B; font-weight:500;">
                                    {{ $category->parent->name }}
                                </a>
                            @else
                                <span class="badge-status badge-active">Cấp gốc</span>
                            @endif
                        </td>
                        <td>
                            @if($category->status == 1)
                                <span class="badge-status badge-active">
                                    <i class="bi bi-circle-fill" style="font-size:0.5rem;"></i> Hiển thị
                                </span>
                            @else
                                <span class="badge-status badge-inactive">
                                    <i class="bi bi-circle-fill" style="font-size:0.5rem;"></i> Ẩn
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn-sm-edit">
                                    <i class="bi bi-pencil"></i> Sửa
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST"
                                      class="d-inline" onsubmit="return confirm('Xóa danh mục này?')">
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
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            Chưa có danh mục nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(method_exists($categories, 'hasPages') && $categories->hasPages())
        <div class="d-flex justify-content-center mt-4 admin-pagination">
            {{ $categories->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection