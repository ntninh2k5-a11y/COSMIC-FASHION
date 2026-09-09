@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Quản lý sản phẩm</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            + Thêm sản phẩm
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th width="60">ID</th>
                            <th width="80">Ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Giá</th>
                            <th>Giá KM</th>
                            <th width="80">% Giảm</th>
                            <th width="100">Trạng thái</th>
                            <th width="160">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    @if($product->image_url)
                                        <img src="{{ asset($product->image_url) }}" 
                                             alt="{{ $product->name }}" 
                                             width="60" height="60" 
                                             style="object-fit: cover; border-radius: 6px;">
                                    @else
                                        <span class="text-muted">No image</span>
                                    @endif
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->category->name ?? '—' }}</td>
                                <td>{{ number_format($product->price) }} đ</td>
                                <td>
                                    @if($product->sale_price)
                                        {{ number_format($product->sale_price) }} đ
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    @if($product->discount_percent > 0)
                                        <span class="badge bg-danger">{{ $product->discount_percent }}%</span>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    @if($product->status)
                                        <span class="badge bg-success">Hiển thị</span>
                                    @else
                                        <span class="badge bg-secondary">Ẩn</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.products.edit', $product->id) }}" 
                                       class="btn btn-sm btn-warning me-1">
                                        Sửa
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            Xóa
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    Chưa có sản phẩm nào
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection