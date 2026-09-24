@extends('admin.layouts.admin')

@section('title', 'Quản lý Footer - Admin')

@section('page-title', 'QUẢN LÝ FOOTER')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="neo-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bolder m-0">Danh sách Link Footer</h4>
                <a href="{{ route('admin.footer_menus.create') }}" class="neo-btn text-decoration-none">
                    + THÊM MỚI
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
                            <th class="fw-bolder text-dark">TÊN HIỂN THỊ</th>
                            <th class="fw-bolder text-dark">ĐƯỜNG DẪN (URL)</th>
                            <th class="fw-bolder text-dark">CỘT TIÊU ĐỀ (CHA)</th>
                            <th class="fw-bolder text-dark text-center">TRẠNG THÁI</th>
                            <th class="fw-bolder text-dark text-center" style="width: 180px;">HÀNH ĐỘNG</th>
                        </tr>
                    </thead>
                    <tbody class="fw-bold text-secondary">
                        @forelse($footerMenus as $menu)
                            <tr>
                                <td class="text-dark">{{ $menu->id }}</td>
                                <td><span class="text-dark">{{ $menu->name }}</span></td>
                                <td>{{ $menu->url }}</td>
                                <td>
                                    @if($menu->parent_id)
                                        <span class="text-primary">{{ \App\Models\FooterMenu::find($menu->parent_id)->name }}</span>
                                    @else
                                        <span class="text-muted fst-italic">-- Cột tiêu đề --</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($menu->status == 1)
                                        <span class="text-success">Hiển thị</span>
                                    @else
                                        <span class="text-danger">Đang ẩn</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.footer_menus.edit', $menu->id) }}" class="neo-btn-sm me-1 text-decoration-none">SỬA</a>
                                    <form action="{{ route('admin.footer_menus.destroy', $menu->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="neo-btn-sm bg-danger text-white border-0">XÓA</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-secondary">Chưa có dữ liệu nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection