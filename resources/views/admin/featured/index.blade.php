@extends('admin.layouts.admin')
@section('title', 'Sản phẩm nổi bật - Admin')
@section('page-title', 'Quản lý Sản phẩm nổi bật')
@section('content')

{{-- STAT CARDS --}}
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-decoration" style="background:#FF6B6B;"></div>
            <div class="stat-card-icon" style="background:#fff0f0; color:#FF6B6B;"><i class="bi bi-star-fill"></i></div>
            <div class="stat-card-value">{{ $featuredCount }}</div>
            <div class="stat-card-label">Đang nổi bật</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-decoration" style="background:#4ECDC4;"></div>
            <div class="stat-card-icon" style="background:#e0faf8; color:#4ECDC4;"><i class="bi bi-eye-fill"></i></div>
            <div class="stat-card-value">{{ number_format($totalViews) }}</div>
            <div class="stat-card-label">Tổng lượt xem</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-decoration" style="background:#FFE66D;"></div>
            <div class="stat-card-icon" style="background:#fffde7; color:#f59e0b;"><i class="bi bi-collection"></i></div>
            <div class="stat-card-value">12</div>
            <div class="stat-card-label">Tối đa nổi bật</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-decoration" style="background:#a78bfa;"></div>
            <div class="stat-card-icon" style="background:#ede9fe; color:#7c3aed;"><i class="bi bi-graph-up-arrow"></i></div>
            <div class="stat-card-value">{{ 12 - $featuredCount }}</div>
            <div class="stat-card-label">Slot còn trống</div>
        </div>
    </div>
</div>

{{-- TABS --}}
<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title"><i class="bi bi-fire text-danger"></i> Danh sách sản phẩm</h2>
    </div>

    {{-- Tab navigation --}}
    <div class="d-flex gap-2 mb-4">
        <a href="{{ route('admin.featured.index', ['tab' => 'all']) }}"
           class="btn-tab {{ $tab === 'all' ? 'active' : '' }}">
            <i class="bi bi-grid"></i> Tất cả
        </a>
        <a href="{{ route('admin.featured.index', ['tab' => 'featured']) }}"
           class="btn-tab {{ $tab === 'featured' ? 'active' : '' }}">
            <i class="bi bi-star-fill"></i> Đang nổi bật ({{ $featuredCount }})
        </a>
        <a href="{{ route('admin.featured.index', ['tab' => 'top-views']) }}"
           class="btn-tab {{ $tab === 'top-views' ? 'active' : '' }}">
            <i class="bi bi-graph-up"></i> Xem nhiều nhất
        </a>
    </div>

    @if($tab === 'featured')
    <div class="admin-alert admin-alert-success" style="margin-bottom:16px;">
        <i class="bi bi-info-circle-fill fs-5"></i>
        <span>Các sản phẩm bên dưới đang hiển thị trên carousel trang chủ. Kéo thả hoặc sắp xếp thứ tự theo ý muốn.</span>
    </div>
    @endif

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:50px;">STT</th>
                    <th style="width:60px;">ẢNH</th>
                    <th>TÊN SẢN PHẨM</th>
                    <th>DANH MỤC</th>
                    <th class="text-center">GIÁ</th>
                    <th class="text-center"><i class="bi bi-eye"></i> LƯỢT XEM</th>
                    <th class="text-center">TRẠNG THÁI</th>
                    <th class="text-center">HÀNH ĐỘNG</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $index => $product)
                <tr>
                    <td class="text-muted fw-bold" style="font-size:0.8rem;">
                        {{ $products->firstItem() + $index }}
                    </td>
                    <td>
                        @if($product->image_url)
                            <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}"
                                 style="width:50px;height:50px;object-fit:cover;border-radius:10px;border:1px solid #f0f0f0;">
                        @else
                            <div style="width:50px;height:50px;border-radius:10px;background:#f0f2f5;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-image text-muted"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold text-dark">{{ $product->name }}</span>
                            @if($product->is_featured)
                                <span class="badge-status" style="background:#fff8e1;color:#f59e0b;font-size:0.65rem;">
                                    <i class="bi bi-star-fill"></i> #{{ $product->featured_order }}
                                </span>
                            @endif
                        </div>
                    </td>
                    <td><span class="text-muted">{{ $product->category->name ?? '—' }}</span></td>
                    <td class="text-center">
                        @if($product->discount_percent > 0)
                            <span class="fw-bold" style="color:#FF6B6B;">{{ number_format($product->sale_price, 0, ',', '.') }}đ</span>
                            <br><del class="text-muted" style="font-size:0.78rem;">{{ number_format($product->price, 0, ',', '.') }}đ</del>
                        @else
                            <span class="fw-bold text-dark">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @php $vc = $viewCounts[$product->id] ?? 0; @endphp
                        <div class="d-flex align-items-center justify-content-center gap-1">
                            <i class="bi bi-eye text-muted"></i>
                            <span class="fw-bold {{ $vc > 0 ? 'text-dark' : 'text-muted' }}">{{ number_format($vc) }}</span>
                        </div>
                        @if($vc >= 10)
                            <div class="view-bar mt-1">
                                <div class="view-bar-fill" style="width: {{ min($vc / ($viewCounts->max() ?: 1) * 100, 100) }}%;"></div>
                            </div>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($product->is_featured)
                            <span class="badge-status badge-active"><i class="bi bi-star-fill" style="font-size:0.5rem;"></i> Nổi bật</span>
                        @else
                            <span class="badge-status badge-inactive"><i class="bi bi-circle-fill" style="font-size:0.5rem;"></i> Thường</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <form action="{{ route('admin.featured.toggle', $product->id) }}" method="POST" class="d-inline">
                            @csrf
                            @if($product->is_featured)
                                <button type="submit" class="btn-sm-delete" title="Bỏ nổi bật">
                                    <i class="bi bi-star-fill"></i> Bỏ
                                </button>
                            @else
                                <button type="submit" class="btn-sm-edit" style="border-color:#FF6B6B;color:#FF6B6B;" title="Đánh dấu nổi bật">
                                    <i class="bi bi-star"></i> Đẩy lên
                                </button>
                            @endif
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        Không có sản phẩm nào.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
    <div class="d-flex justify-content-center mt-4 admin-pagination">
        {{ $products->appends(['tab' => $tab])->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

@endsection

@push('styles')
<style>
    .btn-tab {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 18px;
        border: 1.5px solid #e9ecef;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #636E72;
        text-decoration: none;
        transition: all 0.2s;
        background: #fff;
    }
    .btn-tab:hover {
        border-color: #FF6B6B;
        color: #FF6B6B;
    }
    .btn-tab.active {
        background: #FF6B6B;
        border-color: #FF6B6B;
        color: #fff;
    }

    .view-bar {
        width: 60px;
        height: 4px;
        background: #f0f2f5;
        border-radius: 4px;
        overflow: hidden;
        margin: 0 auto;
    }
    .view-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #4ECDC4, #FF6B6B);
        border-radius: 4px;
        transition: width 0.3s ease;
    }
</style>
@endpush
