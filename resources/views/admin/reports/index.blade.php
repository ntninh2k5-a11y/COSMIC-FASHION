@extends('admin.layouts.admin')
@section('title', 'Báo cáo thống kê - Admin')
@section('page-title', 'Báo cáo & Thống kê')
@section('content')

@push('styles')
<style>
    .active-filter {
        background-color: #FF6B6B;
        border-color: #FF6B6B;
        color: #fff !important;
        box-shadow: 0 4px 10px rgba(255,107,107,0.3);
    }
</style>
@endpush
{{-- DATE RANGE FILTER --}}
<div class="admin-card" style="padding:16px 24px;">
    <form method="GET" action="{{ route('admin.reports.index') }}" class="d-flex align-items-center gap-3 flex-wrap">
        <div class="d-flex align-items-center gap-2">
            <label class="form-label mb-0 fw-bold" style="font-size:0.85rem;white-space:nowrap;">Từ ngày</label>
            <input type="date" name="from" value="{{ $from }}" class="form-control form-control-sm" style="width:160px;border-radius:10px;">
        </div>
        <div class="d-flex align-items-center gap-2">
            <label class="form-label mb-0 fw-bold" style="font-size:0.85rem;white-space:nowrap;">Đến ngày</label>
            <input type="date" name="to" value="{{ $to }}" class="form-control form-control-sm" style="width:160px;border-radius:10px;">
        </div>
        <button type="submit" class="btn-primary-admin"><i class="bi bi-funnel"></i> Lọc</button>

        {{-- Quick filters --}}
        @php
            $today = \Carbon\Carbon::today()->toDateString();
            $sevenDaysAgo = \Carbon\Carbon::today()->subDays(6)->toDateString();
            $monthStart = \Carbon\Carbon::today()->startOfMonth()->toDateString();
            $yearStart = \Carbon\Carbon::today()->startOfYear()->toDateString();
        @endphp
        <div class="d-flex gap-2 ms-auto">
            <a href="{{ route('admin.reports.index', ['from' => $today, 'to' => $today]) }}"
               class="btn-outline-admin {{ ($from == $today && $to == $today) ? 'active-filter' : '' }}" style="font-size:0.8rem;padding:6px 14px;">Hôm nay</a>
            <a href="{{ route('admin.reports.index', ['from' => $sevenDaysAgo, 'to' => $today]) }}"
               class="btn-outline-admin {{ ($from == $sevenDaysAgo && $to == $today) ? 'active-filter' : '' }}" style="font-size:0.8rem;padding:6px 14px;">7 ngày</a>
            <a href="{{ route('admin.reports.index', ['from' => $monthStart, 'to' => $today]) }}"
               class="btn-outline-admin {{ ($from == $monthStart && $to == $today) ? 'active-filter' : '' }}" style="font-size:0.8rem;padding:6px 14px;">Tháng này</a>
            <a href="{{ route('admin.reports.index', ['from' => $yearStart, 'to' => $today]) }}"
               class="btn-outline-admin {{ ($from == $yearStart && $to == $today) ? 'active-filter' : '' }}" style="font-size:0.8rem;padding:6px 14px;">Năm nay</a>
        </div>
    </form>
</div>

{{-- STAT CARDS --}}
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-decoration" style="background:#FF6B6B;"></div>
            <div class="stat-card-icon" style="background:#fff0f0; color:#FF6B6B;"><i class="bi bi-currency-dollar"></i></div>
            <div class="stat-card-value" style="font-size:1.2rem;">{{ number_format($totalRevenue, 0, ',', '.') }}đ</div>
            <div class="stat-card-label">Tổng doanh thu</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-decoration" style="background:#4ECDC4;"></div>
            <div class="stat-card-icon" style="background:#e0faf8; color:#4ECDC4;"><i class="bi bi-receipt"></i></div>
            <div class="stat-card-value">{{ number_format($totalOrders) }}</div>
            <div class="stat-card-label">Tổng đơn hàng</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-decoration" style="background:#FFE66D;"></div>
            <div class="stat-card-icon" style="background:#fffde7; color:#f59e0b;"><i class="bi bi-people"></i></div>
            <div class="stat-card-value">{{ number_format($newCustomers) }}</div>
            <div class="stat-card-label">Khách hàng mới</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-decoration" style="background:#a78bfa;"></div>
            <div class="stat-card-icon" style="background:#ede9fe; color:#7c3aed;"><i class="bi bi-eye"></i></div>
            <div class="stat-card-value">{{ number_format($totalViews) }}</div>
            <div class="stat-card-label">Lượt xem SP</div>
        </div>
    </div>
</div>

{{-- ROW 2: More stats --}}
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-icon" style="background:#dcfce7; color:#16a34a;"><i class="bi bi-check-circle"></i></div>
            <div class="stat-card-value">{{ number_format($completedOrders) }}</div>
            <div class="stat-card-label">Đơn hoàn thành</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-icon" style="background:#fee2e2; color:#dc2626;"><i class="bi bi-x-circle"></i></div>
            <div class="stat-card-value">{{ number_format($cancelledOrders) }}</div>
            <div class="stat-card-label">Đơn đã hủy</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-icon" style="background:#e0f2fe; color:#0284c7;"><i class="bi bi-cart-check"></i></div>
            <div class="stat-card-value">{{ number_format($totalProductsSold) }}</div>
            <div class="stat-card-label">SP đã bán</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-icon" style="background:#fef3c7; color:#b45309;"><i class="bi bi-graph-up"></i></div>
            <div class="stat-card-value" style="font-size:1.2rem;">{{ number_format($avgOrderValue, 0, ',', '.') }}đ</div>
            <div class="stat-card-label">Giá trị TB/đơn</div>
        </div>
    </div>
</div>

{{-- CHARTS ROW --}}
<div class="row g-4 mb-4">
    {{-- Revenue Line Chart --}}
    <div class="col-lg-8">
        <div class="admin-card" style="height:100%;">
            <div class="admin-card-header">
                <h2 class="admin-card-title"><i class="bi bi-graph-up text-muted"></i> Doanh thu theo ngày</h2>
            </div>
            <div style="position: relative; height: 280px; width: 100%;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Order Status Pie --}}
    <div class="col-lg-4">
        <div class="admin-card" style="height:100%;">
            <div class="admin-card-header">
                <h2 class="admin-card-title"><i class="bi bi-pie-chart text-muted"></i> Trạng thái đơn hàng</h2>
            </div>
            <div class="d-flex justify-content-center" style="position: relative; height: 260px; width: 100%;">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- TABLES ROW --}}
<div class="row g-4 mb-4">
    {{-- Top Selling --}}
    <div class="col-lg-6">
        <div class="admin-card">
            <div class="admin-card-header">
                <h2 class="admin-card-title"><i class="bi bi-trophy text-warning"></i> Top sản phẩm bán chạy</h2>
            </div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>SẢN PHẨM</th>
                            <th class="text-center">SL BÁN</th>
                            <th class="text-end">DOANH THU</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topSellingProducts as $i => $item)
                        <tr>
                            <td class="fw-bold text-muted">{{ $i + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($item->product->image_url)
                                        <img src="{{ asset($item->product->image_url) }}" style="width:36px;height:36px;object-fit:cover;border-radius:8px;">
                                    @endif
                                    <span class="fw-bold text-dark" style="font-size:0.85rem;">{{ Str::limit($item->product->name, 30) }}</span>
                                </div>
                            </td>
                            <td class="text-center fw-bold">{{ number_format($item->total_qty) }}</td>
                            <td class="text-end fw-bold" style="color:#FF6B6B;">{{ number_format($item->total_revenue, 0, ',', '.') }}đ</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-4 text-muted">Chưa có dữ liệu bán hàng.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Top Viewed --}}
    <div class="col-lg-6">
        <div class="admin-card">
            <div class="admin-card-header">
                <h2 class="admin-card-title"><i class="bi bi-eye text-info"></i> Top sản phẩm xem nhiều</h2>
            </div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>SẢN PHẨM</th>
                            <th class="text-center">LƯỢT XEM</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topViewedProducts as $i => $item)
                        <tr>
                            <td class="fw-bold text-muted">{{ $i + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($item->product->image_url)
                                        <img src="{{ asset($item->product->image_url) }}" style="width:36px;height:36px;object-fit:cover;border-radius:8px;">
                                    @endif
                                    <span class="fw-bold text-dark" style="font-size:0.85rem;">{{ Str::limit($item->product->name, 30) }}</span>
                                </div>
                            </td>
                            <td class="text-center fw-bold">{{ number_format($item->view_count) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-4 text-muted">Chưa có lượt xem nào.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- BOTTOM ROW: Category Revenue + Payment Methods --}}
<div class="row g-4 mb-4">
    {{-- Revenue by Category --}}
    <div class="col-lg-7">
        <div class="admin-card">
            <div class="admin-card-header">
                <h2 class="admin-card-title"><i class="bi bi-folder text-muted"></i> Doanh thu theo danh mục</h2>
            </div>
            <div style="position: relative; height: 220px; width: 100%;">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Payment Methods --}}
    <div class="col-lg-5">
        <div class="admin-card">
            <div class="admin-card-header">
                <h2 class="admin-card-title"><i class="bi bi-credit-card text-muted"></i> Phương thức thanh toán</h2>
            </div>
            <div class="d-flex justify-content-center" style="position: relative; height: 240px; width: 100%;">
                <canvas id="paymentChart"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- EXPORT BUTTONS --}}
<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title"><i class="bi bi-download text-muted"></i> Xuất báo cáo</h2>
    </div>
    <div class="d-flex gap-3 flex-wrap">
        <div class="export-group">
            <p class="fw-bold mb-2" style="font-size:0.85rem;color:#636E72;">Báo cáo đơn hàng</p>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.reports.export.csv', ['from' => $from, 'to' => $to, 'type' => 'orders']) }}" class="btn-export btn-export-csv">
                    <i class="bi bi-filetype-csv"></i> CSV
                </a>
                <a href="{{ route('admin.reports.export.pdf', ['from' => $from, 'to' => $to, 'type' => 'orders']) }}" class="btn-export btn-export-pdf">
                    <i class="bi bi-filetype-pdf"></i> PDF
                </a>
            </div>
        </div>
        <div class="export-group">
            <p class="fw-bold mb-2" style="font-size:0.85rem;color:#636E72;">Báo cáo sản phẩm</p>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.reports.export.csv', ['from' => $from, 'to' => $to, 'type' => 'products']) }}" class="btn-export btn-export-csv">
                    <i class="bi bi-filetype-csv"></i> CSV
                </a>
            </div>
        </div>
        <div class="export-group">
            <p class="fw-bold mb-2" style="font-size:0.85rem;color:#636E72;">Báo cáo doanh thu</p>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.reports.export.csv', ['from' => $from, 'to' => $to, 'type' => 'revenue']) }}" class="btn-export btn-export-csv">
                    <i class="bi bi-filetype-csv"></i> CSV
                </a>
                <a href="{{ route('admin.reports.export.pdf', ['from' => $from, 'to' => $to, 'type' => 'overview']) }}" class="btn-export btn-export-pdf">
                    <i class="bi bi-filetype-pdf"></i> PDF Tổng hợp
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .export-group { padding: 16px 20px; background: #f8f9fa; border-radius: 12px; flex: 1; min-width: 200px; }
    .btn-export {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 18px; border-radius: 10px; font-size: 0.85rem;
        font-weight: 600; text-decoration: none; transition: all 0.2s;
    }
    .btn-export-csv { background: #dcfce7; color: #16a34a; }
    .btn-export-csv:hover { background: #16a34a; color: #fff; }
    .btn-export-pdf { background: #fee2e2; color: #dc2626; }
    .btn-export-pdf:hover { background: #dc2626; color: #fff; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    // --- Revenue Line Chart ---
    const revCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($revenueByDay->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))) !!},
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: {!! json_encode($revenueByDay->pluck('revenue')) !!},
                borderColor: '#FF6B6B',
                backgroundColor: 'rgba(255,107,107,0.08)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#FF6B6B',
                borderWidth: 2.5,
            }, {
                label: 'Số đơn',
                data: {!! json_encode($revenueByDay->pluck('order_count')) !!},
                borderColor: '#4ECDC4',
                backgroundColor: 'rgba(78,205,196,0.08)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#4ECDC4',
                borderWidth: 2.5,
                yAxisID: 'y1',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            plugins: { legend: { position: 'bottom', labels: { font: { family: 'Inter', size: 12 }, usePointStyle: true } } },
            scales: {
                y: { beginAtZero: true, ticks: { callback: v => v >= 1000000 ? (v/1000000)+'M' : v >= 1000 ? (v/1000)+'K' : v } },
                y1: { position: 'right', beginAtZero: true, grid: { display: false } },
                x: { ticks: { font: { size: 11 } } }
            }
        }
    });

    // --- Order Status Pie ---
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusData = {!! json_encode($ordersByStatus) !!};
    const statusLabels = { pending: 'Chờ xử lý', processing: 'Đang chuẩn bị', shipping: 'Đang giao', completed: 'Hoàn thành', cancelled: 'Đã hủy', paid: 'Đã thanh toán' };
    const statusColors = { pending: '#f59e0b', processing: '#0284c7', shipping: '#7c3aed', completed: '#16a34a', cancelled: '#dc2626', paid: '#059669' };
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(statusData).map(k => statusLabels[k] || k),
            datasets: [{ data: Object.values(statusData), backgroundColor: Object.keys(statusData).map(k => statusColors[k] || '#94a3b8'), borderWidth: 0, spacing: 2 }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: { legend: { position: 'bottom', labels: { font: { family: 'Inter', size: 11 }, usePointStyle: true, padding: 12 } } }
        }
    });

    // --- Category Revenue Bar Chart ---
    const catCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(catCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($revenueByCategory->map(fn($i) => $i->category->name ?? '—')) !!},
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: {!! json_encode($revenueByCategory->pluck('revenue')) !!},
                backgroundColor: ['#FF6B6B','#4ECDC4','#FFE66D','#a78bfa','#f97316','#06b6d4','#ec4899','#84cc16'],
                borderRadius: 8,
                borderSkipped: false,
                barPercentage: 0.6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { callback: v => v >= 1000000 ? (v/1000000)+'M' : v >= 1000 ? (v/1000)+'K' : v } },
                x: { ticks: { font: { size: 11 } } }
            }
        }
    });

    // --- Payment Methods Pie ---
    const payCtx = document.getElementById('paymentChart').getContext('2d');
    const payData = {!! json_encode($paymentMethods) !!};
    const payLabels = { bank_transfer: 'Chuyển khoản', cod: 'COD', momo: 'MoMo', vnpay: 'VNPay' };
    new Chart(payCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(payData).map(k => payLabels[k] || k.toUpperCase()),
            datasets: [{ data: Object.values(payData), backgroundColor: ['#4ECDC4','#FF6B6B','#FFE66D','#a78bfa','#f97316'], borderWidth: 0, spacing: 2 }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: { legend: { position: 'bottom', labels: { font: { family: 'Inter', size: 11 }, usePointStyle: true, padding: 12 } } }
        }
    });
</script>
@endpush
