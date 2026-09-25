@extends('admin.layouts.admin')
@section('title', 'Sản phẩm - Admin')
@section('page-title', 'Quản lý Sản phẩm')
@section('content')

<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title"><i class="bi bi-bag text-muted"></i> Danh sách sản phẩm</h2>
        <a href="{{ route('admin.products.create') }}" class="btn-primary-admin">
            <i class="bi bi-plus-lg"></i> Thêm sản phẩm
        </a>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ẢNH</th>
                    <th>TÊN SẢN PHẨM</th>
                    <th>DANH MỤC</th>
                    <th>GIÁ GỐC</th>
                    <th>GIÁ KM</th>
                    <th>GIẢM</th>
                    <th>TRẠNG THÁI</th>
                    <th class="text-center">HÀNH ĐỘNG</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td class="text-muted fw-bold" style="font-size:0.8rem;">#{{ $product->id }}</td>
                        <td>
                            @if($product->image_url)
                                <img src="{{ asset($product->image_url) }}"
                                     alt="{{ $product->name }}"
                                     style="width:50px;height:50px;object-fit:cover;border-radius:10px;border:1px solid #f0f0f0;">
                            @else
                                <div style="width:50px;height:50px;border-radius:10px;background:#f0f2f5;display:flex;align-items:center;justify-content:center;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td><span class="fw-bold text-dark">{{ $product->name }}</span></td>
                        <td><span class="text-muted">{{ $product->category->name ?? '—' }}</span></td>
                        <td>{{ number_format($product->price, 0, ',', '.') }}đ</td>
                        <td>{{ $product->sale_price ? number_format($product->sale_price, 0, ',', '.').'đ' : '—' }}</td>
                        <td>
                            @if($product->discount_percent > 0)
                                <span class="badge-status badge-pending">-{{ $product->discount_percent }}%</span>
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            @if($product->status)
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
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-sm-edit">
                                    <i class="bi bi-pencil"></i> Sửa
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Xóa sản phẩm này?')">
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
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            Chưa có sản phẩm nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
        <div class="d-flex justify-content-center mt-4 admin-pagination">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection