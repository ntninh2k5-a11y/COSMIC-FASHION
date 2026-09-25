@extends('admin.layouts.admin')

@section('title', 'Quản lý Chân trang - Admin')
@section('page-title', 'QUẢN LÝ CHÂN TRANG')

@section('content')
<div class="row g-4">

    {{-- Stats --}}
    <div class="col-12">
        <div class="row g-3">
            @php
                $totalCols = \App\Models\FooterMenu::whereNull('parent_id')->count();
                $totalLinks = \App\Models\FooterMenu::whereNotNull('parent_id')->count();
                $totalActive = \App\Models\FooterMenu::where('status',1)->count();
            @endphp
            <div class="col-md-4">
                <div class="neo-card p-3 d-flex align-items-center gap-3">
                    <div style="width:44px;height:44px;background:#e0f2fe;border-radius:12px;" class="d-flex align-items-center justify-content-center">
                        <i class="bi bi-layout-text-sidebar fs-5 text-primary"></i>
                    </div>
                    <div>
                        <div class="fw-bolder fs-4">{{ $totalCols }}</div>
                        <div class="text-muted" style="font-size:12px;">Cột tiêu đề</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="neo-card p-3 d-flex align-items-center gap-3">
                    <div style="width:44px;height:44px;background:#fef3c7;border-radius:12px;" class="d-flex align-items-center justify-content-center">
                        <i class="bi bi-link-45deg fs-5 text-warning"></i>
                    </div>
                    <div>
                        <div class="fw-bolder fs-4">{{ $totalLinks }}</div>
                        <div class="text-muted" style="font-size:12px;">Liên kết</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="neo-card p-3 d-flex align-items-center gap-3">
                    <div style="width:44px;height:44px;background:#dcfce7;border-radius:12px;" class="d-flex align-items-center justify-content-center">
                        <i class="bi bi-eye fs-5 text-success"></i>
                    </div>
                    <div>
                        <div class="fw-bolder fs-4">{{ $totalActive }}</div>
                        <div class="text-muted" style="font-size:12px;">Đang hiển thị</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Table --}}
    <div class="col-12">
        <div class="neo-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bolder m-0">Danh sách Menu Chân trang</h4>
                    <p class="text-muted mb-0" style="font-size:13px;">Quản lý các cột và liên kết hiển thị ở footer website</p>
                </div>
                <a href="{{ route('admin.footer_menus.create') }}" class="neo-btn text-decoration-none">
                    <i class="bi bi-plus-lg me-1"></i> THÊM MỚI
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success rounded-3 fw-bold border-0 mb-4" style="background:#dcfce7; color:#166534;">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                </div>
            @endif

            {{-- Preview footer structure --}}
            @php
                $cols = \App\Models\FooterMenu::whereNull('parent_id')->where('status',1)->orderBy('sort_order')->with('children')->get();
            @endphp
            @if($cols->count() > 0)
            <div class="p-3 rounded-3 mb-4" style="background:#0f172a;">
                <div class="text-muted mb-2" style="font-size:11px; letter-spacing:1px; text-transform:uppercase;">Preview chân trang</div>
                <div class="row g-3">
                    @foreach($cols as $col)
                    <div class="col">
                        <div style="color:#e2e8f0; font-weight:700; font-size:11px; letter-spacing:1.5px; margin-bottom:8px; text-transform:uppercase;">{{ $col->name }}</div>
                        @foreach($col->children as $child)
                            <div style="color:#64748b; font-size:12px; margin-bottom:4px;">{{ $child->name }}</div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-borderless align-middle">
                    <thead class="border-bottom border-2" style="border-color:#e5e7eb!important;">
                        <tr>
                            <th class="fw-bolder text-dark" style="width:60px;">STT</th>
                            <th class="fw-bolder text-dark">TÊN HIỂN THỊ</th>
                            <th class="fw-bolder text-dark">LOẠI</th>
                            <th class="fw-bolder text-dark">ĐƯỜNG DẪN</th>
                            <th class="fw-bolder text-dark">ICON</th>
                            <th class="fw-bolder text-dark text-center">THỨ TỰ</th>
                            <th class="fw-bolder text-dark text-center">TRẠNG THÁI</th>
                            <th class="fw-bolder text-dark text-center" style="width:160px;">HÀNH ĐỘNG</th>
                        </tr>
                    </thead>
                    <tbody class="text-secondary">
                        @forelse($footerMenus as $menu)
                            <tr class="{{ is_null($menu->parent_id) ? 'table-light' : '' }}" style="{{ is_null($menu->parent_id) ? 'border-top:2px solid #e5e7eb;' : '' }}">
                                <td>
                                    @if(is_null($menu->parent_id))
                                        <span class="badge rounded-pill" style="background:#1e293b;color:#94a3b8;font-size:10px;">COL</span>
                                    @else
                                        <span style="color:#cbd5e1; font-size:13px;">└</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="{{ is_null($menu->parent_id) ? 'fw-bolder text-dark' : 'text-dark fw-medium ps-3' }}" style="font-size:{{ is_null($menu->parent_id) ? '14px' : '13px' }};">
                                        @if($menu->icon)<i class="bi {{ $menu->icon }} me-1 text-muted"></i>@endif
                                        {{ $menu->name }}
                                    </span>
                                    @if($menu->description)
                                        <div class="text-muted ps-3" style="font-size:11px;">{{ Str::limit($menu->description, 50) }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if(is_null($menu->parent_id))
                                        <span class="badge rounded-pill" style="background:#dbeafe;color:#1d4ed8;font-size:11px;">Cột tiêu đề</span>
                                    @else
                                        <span class="badge rounded-pill" style="background:#f3f4f6;color:#6b7280;font-size:11px;">
                                            Liên kết của: {{ optional(\App\Models\FooterMenu::find($menu->parent_id))->name }}
                                        </span>
                                    @endif
                                </td>
                                <td style="font-size:12px;" class="text-muted font-monospace">{{ $menu->url ?: '—' }}</td>
                                <td>
                                    @if($menu->icon)
                                        <span class="badge rounded-pill" style="background:#f3f4f6;color:#374151;font-size:11px;">
                                            <i class="bi {{ $menu->icon }}"></i> {{ $menu->icon }}
                                        </span>
                                    @else
                                        <span class="text-muted" style="font-size:12px;">—</span>
                                    @endif
                                </td>
                                <td class="text-center fw-bold">{{ $menu->sort_order }}</td>
                                <td class="text-center">
                                    @if($menu->status == 1)
                                        <span class="badge rounded-pill" style="background:#dcfce7;color:#166534;font-size:11px;">
                                            <i class="bi bi-eye me-1"></i>Hiển thị
                                        </span>
                                    @else
                                        <span class="badge rounded-pill" style="background:#fee2e2;color:#991b1b;font-size:11px;">
                                            <i class="bi bi-eye-slash me-1"></i>Đang ẩn
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.footer_menus.edit', $menu->id) }}" class="neo-btn-sm me-1 text-decoration-none">
                                        <i class="bi bi-pencil-fill"></i> SỬA
                                    </a>
                                    <form action="{{ route('admin.footer_menus.destroy', $menu->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="neo-btn-sm bg-danger text-white border-0"
                                                onclick="return confirm('Xóa mục này?')">
                                            <i class="bi bi-trash3-fill"></i> XÓA
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-layout-text-sidebar fs-2 mb-2 d-block opacity-25"></i>
                                    Chưa có dữ liệu. Hãy thêm cột tiêu đề đầu tiên!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection