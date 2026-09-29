<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Báo cáo tổng hợp - Cosmic Fashion</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #2D3436; line-height: 1.6; padding: 30px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #FF6B6B; padding-bottom: 16px; }
        .header h1 { font-size: 22px; color: #FF6B6B; margin-bottom: 4px; }
        .header p { font-size: 11px; color: #636E72; }
        .period { font-size: 13px; font-weight: bold; color: #2D3436; margin-top: 6px; }

        .stats-grid { display: table; width: 100%; margin-bottom: 24px; }
        .stat-row { display: table-row; }
        .stat-cell { display: table-cell; width: 25%; padding: 10px; text-align: center; border: 1px solid #e9ecef; }
        .stat-value { font-size: 18px; font-weight: bold; color: #2D3436; }
        .stat-label { font-size: 10px; color: #636E72; text-transform: uppercase; letter-spacing: 0.5px; }

        .section-title { font-size: 14px; font-weight: bold; color: #2D3436; margin: 24px 0 10px; padding-bottom: 6px; border-bottom: 1px solid #e9ecef; }

        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 11px; }
        table.data-table thead th { background: #f8f9fa; padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; color: #636E72; border-bottom: 1px solid #e9ecef; }
        table.data-table tbody td { padding: 8px 10px; border-bottom: 1px solid #f0f2f5; }
        table.data-table tbody tr:last-child td { border-bottom: none; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }
        .text-red { color: #FF6B6B; }

        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #94a3b8; border-top: 1px solid #e9ecef; padding-top: 12px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>COSMIC FASHION</h1>
        <p>BÁO CÁO THỐNG KÊ TỔNG HỢP</p>
        <div class="period">Từ {{ \Carbon\Carbon::parse($from)->format('d/m/Y') }} đến {{ \Carbon\Carbon::parse($to)->format('d/m/Y') }}</div>
    </div>

    {{-- TỔNG QUAN --}}
    <div class="stats-grid">
        <div class="stat-row">
            <div class="stat-cell">
                <div class="stat-value text-red">{{ number_format($totalRevenue, 0, ',', '.') }}đ</div>
                <div class="stat-label">Tổng doanh thu</div>
            </div>
            <div class="stat-cell">
                <div class="stat-value">{{ number_format($totalOrders) }}</div>
                <div class="stat-label">Tổng đơn hàng</div>
            </div>
            <div class="stat-cell">
                <div class="stat-value">{{ number_format($completedOrders) }}</div>
                <div class="stat-label">Đơn hoàn thành</div>
            </div>
            <div class="stat-cell">
                <div class="stat-value">{{ number_format($cancelledOrders) }}</div>
                <div class="stat-label">Đơn đã hủy</div>
            </div>
        </div>
        <div class="stat-row">
            <div class="stat-cell">
                <div class="stat-value">{{ number_format($newCustomers) }}</div>
                <div class="stat-label">Khách hàng mới</div>
            </div>
            <div class="stat-cell">
                <div class="stat-value">{{ number_format($totalViews) }}</div>
                <div class="stat-label">Lượt xem SP</div>
            </div>
            <div class="stat-cell" colspan="2">
                @php
                    $rate = $totalOrders > 0 ? round($completedOrders / $totalOrders * 100, 1) : 0;
                @endphp
                <div class="stat-value">{{ $rate }}%</div>
                <div class="stat-label">Tỷ lệ hoàn thành</div>
            </div>
            <div class="stat-cell">
                @php $cancelRate = $totalOrders > 0 ? round($cancelledOrders / $totalOrders * 100, 1) : 0; @endphp
                <div class="stat-value">{{ $cancelRate }}%</div>
                <div class="stat-label">Tỷ lệ hủy</div>
            </div>
        </div>
    </div>

    {{-- TRẠNG THÁI ĐƠN HÀNG --}}
    <div class="section-title">Phân bố trạng thái đơn hàng</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Trạng thái</th>
                <th class="text-center">Số lượng</th>
                <th class="text-right">Tỷ lệ</th>
            </tr>
        </thead>
        <tbody>
            @php
                $statusLabels = ['pending'=>'Chờ xử lý','processing'=>'Đang chuẩn bị','shipping'=>'Đang giao','completed'=>'Hoàn thành','cancelled'=>'Đã hủy','paid'=>'Đã thanh toán'];
            @endphp
            @foreach($ordersByStatus as $status => $count)
            <tr>
                <td class="fw-bold">{{ $statusLabels[$status] ?? $status }}</td>
                <td class="text-center">{{ number_format($count) }}</td>
                <td class="text-right">{{ $totalOrders > 0 ? round($count / $totalOrders * 100, 1) : 0 }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- TOP BÁN CHẠY --}}
    @if($topSellingProducts->isNotEmpty())
    <div class="section-title">Top 10 sản phẩm bán chạy</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Tên sản phẩm</th>
                <th class="text-center">SL bán</th>
                <th class="text-right">Doanh thu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topSellingProducts as $i => $item)
            <tr>
                <td class="fw-bold">{{ $i + 1 }}</td>
                <td>{{ $item->product->name }}</td>
                <td class="text-center fw-bold">{{ number_format($item->total_qty) }}</td>
                <td class="text-right fw-bold text-red">{{ number_format($item->total_revenue, 0, ',', '.') }}đ</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        Được tạo tự động bởi Cosmic Fashion Admin — {{ now()->format('d/m/Y H:i') }}
    </div>

</body>
</html>
