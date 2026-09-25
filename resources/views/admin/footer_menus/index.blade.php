@extends('admin.layouts.admin')
@section('title', 'Chân trang - Admin')
@section('page-title', 'Quản lý Chân trang')
@section('content')

{{-- STAT MINI CARDS --}}
@php
    $totalCols   = \App\Models\FooterMenu::whereNull('parent_id')->count();
    $totalLinks  = \App\Models\FooterMenu::whereNotNull('parent_id')->count();
    $totalActive = \App\Models\FooterMenu::where('status', 1)->count();
@endphp
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-card-decoration" style="background:#0284c7;"></div>
            <div class="stat-card-icon" style="background:#e0f2fe; color:#0284c7;"><i class="bi bi-layout-text-sidebar"></i></div>
            <div class="stat-card-value">{{ $totalCols }}</div>
            <div class="stat-card-label">Cột tiêu đề</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-card-decoration" style="background:#f59e0b;"></div>
            <div class="stat-card-icon" style="background:#fef3c7; color:#f59e0b;"><i class="bi bi-link-45deg"></i></div>
            <div class="stat-card-value">{{ $totalLinks }}</div>
            <div class="stat-card-label">Liên kết</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-card-decoration" style="background:#16a34a;"></div>
            <div class="stat-card-icon" style="background:#dcfce7; color:#16a34a;"><i class="bi bi-eye"></i></div>
            <div class="stat-card-value">{{ $totalActive }}</div>
            <div class="stat-card-label">Đang hiển thị</div>
        </div>
    </div>
</div>

{{-- FOOTER PREVIEW --}}
@php
    $cols = \App\Models\FooterMenu::whereNull('parent_id')->where('status', 1)->orderBy('sort_order')->with('children')->get();
@endphp
@if($cols->count() > 0)
    <div class="footer-preview-panel mb-4">
        <div class="footer-preview-label">Preview chân trang</div>
        <div class="row g-3">
            @foreach($cols as $col)
                <div class="col">
                    <div class="footer-preview-col-title">{{ $col->name }}</div>
                    @foreach($col->children as $child)
                        <div class="footer-preview-link">
                            @if($child->icon)<i class="bi {{ $child->icon }} me-1"></i>@endif
                            {{ $child->name }}
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
@endif

{{-- MAIN TABLE --}}
<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h2 class="admin-card-title"><i class="bi bi-layout-text-sidebar text-muted"></i> Danh sách Menu Chân trang</h2>
            <p class="text-muted mb-0 mt-1" style="font-size:0.8rem;">Quản lý các cột và liên kết hiển thị ở footer website</p>
        </div>
        <a href="{{ route('admin.footer_menus.create') }}" class="btn-primary-admin">
            <i class="bi bi-plus-lg"></i> Thêm mới
        </a>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:60px;">LOẠI</th>
                    <th>TÊN HIỂN THỊ</th>
                    <th>PHÂN LOẠI</th>
                    <th>ĐƯỜNG DẪN</th>
                    <th>ICON</th>
                    <th class="text-center" style="width:80px;">THỨ TỰ</th>
                    <th class="text-center" style="width:110px;">TRẠNG THÁI</th>
                    <th class="text-center" style="width:160px;">HÀNH ĐỘNG</th>
                </tr>
            </thead>
            <tbody>
                @forelse($footerMenus as $menu)
                    <tr style="{{ is_null($menu->parent_id) ? 'background:#fafbff;' : '' }}">
                        <td class="text-center">
                            @if(is_null($menu->parent_id))
                                <span class="badge-status badge-processing" style="font-size:0.65rem;padding:3px 8px;">COL</span>
                            @else
                                <span class="text-muted" style="font-size:1rem; padding-left:4px;">└</span>
                            @endif
                        </td>
                        <td>
                            <span class="{{ is_null($menu->parent_id) ? 'fw-bold text-dark' : 'text-dark ps-3' }}"
                                  style="font-size:{{ is_null($menu->parent_id) ? '0.9rem' : '0.85rem' }};">
                                @if($menu->icon)<i class="bi {{ $menu->icon }} me-1 text-muted"></i>@endif
                                {{ $menu->name }}
                            </span>
                            @if($menu->description)
                                <div class="text-muted ps-3" style="font-size:0.75rem;">
                                    {{ Str::limit($menu->description, 50) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            @if(is_null($menu->parent_id))
                                <span class="badge-status badge-processing" style="font-size:0.72rem;">Cột tiêu đề</span>
                            @else
                                <span class="badge-status badge-inactive" style="font-size:0.72rem;">
                                    Liên kết của: {{ optional(\App\Models\FooterMenu::find($menu->parent_id))->name }}
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted font-monospace" style="font-size:0.78rem;">
                                {{ $menu->url ?: '—' }}
                            </span>
                        </td>
                        <td>
                            @if($menu->icon)
                                <span class="badge-status badge-inactive" style="font-size:0.72rem;">
                                    <i class="bi {{ $menu->icon }}"></i> {{ $menu->icon }}
                                </span>
                            @else
                                <span class="text-muted" style="font-size:0.85rem;">—</span>
                            @endif
                        </td>
                        <td class="text-center fw-bold text-muted">{{ $menu->sort_order }}</td>
                        <td class="text-center">
                            @if($menu->status == 1)
                                <span class="badge-status badge-active" style="font-size:0.72rem;">
                                    <i class="bi bi-eye" style="font-size:0.65rem;"></i> Hiển thị
                                </span>
                            @else
                                <span class="badge-status badge-cancelled" style="font-size:0.72rem;">
                                    <i class="bi bi-eye-slash" style="font-size:0.65rem;"></i> Đang ẩn
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('admin.footer_menus.edit', $menu->id) }}" class="btn-sm-edit">
                                    <i class="bi bi-pencil-fill"></i> Sửa
                                </a>
                                <form action="{{ route('admin.footer_menus.destroy', $menu->id) }}" method="POST"
                                      class="d-inline-block" onsubmit="return confirm('Xóa mục này?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-sm-delete">
                                        <i class="bi bi-trash3-fill"></i> Xóa
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-layout-text-sidebar fs-3 d-block mb-2 opacity-25"></i>
                            Chưa có dữ liệu. Hãy thêm cột tiêu đề đầu tiên!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(method_exists($footerMenus, 'hasPages') && $footerMenus->hasPages())
        <div class="d-flex justify-content-center mt-4 admin-pagination">
            {{ $footerMenus->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection