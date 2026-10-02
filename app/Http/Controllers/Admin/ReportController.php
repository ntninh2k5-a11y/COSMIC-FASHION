<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductView;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Trang báo cáo thống kê chính.
     */
    public function index(Request $request)
    {
        // Mặc định: 30 ngày gần nhất (thay vì đầu tháng hiện tại)
        $from = $request->query('from', now()->subDays(30)->toDateString());
        $to   = $request->query('to', now()->toDateString());

        $fromDate = \Carbon\Carbon::parse($from)->startOfDay();
        $toDate   = \Carbon\Carbon::parse($to)->endOfDay();

        // === 1. TỔNG QUAN ===
        $totalRevenue = Order::whereBetween('created_at', [$fromDate, $toDate])
            ->whereIn('status', ['completed', 'paid', 'shipping', 'processing'])
            ->sum('total_amount');

        $totalOrders = Order::whereBetween('created_at', [$fromDate, $toDate])->count();

        $completedOrders = Order::whereBetween('created_at', [$fromDate, $toDate])
            ->whereIn('status', ['completed', 'paid'])
            ->count();

        $cancelledOrders = Order::whereBetween('created_at', [$fromDate, $toDate])
            ->where('status', 'cancelled')
            ->count();

        $newCustomers = User::where('role', 'user')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->count();

        $totalViews = ProductView::whereBetween('viewed_at', [$fromDate, $toDate])->count();

        $avgOrderValue = $completedOrders > 0
            ? round($totalRevenue / $completedOrders)
            : 0;

        $totalProductsSold = OrderItem::whereHas('order', function ($q) use ($fromDate, $toDate) {
            $q->whereBetween('created_at', [$fromDate, $toDate])
              ->whereIn('status', ['completed', 'paid', 'shipping', 'processing']);
        })->sum('quantity');

        // === 2. BIỂU ĐỒ DOANH THU THEO NGÀY ===
        $revenueByDay = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as order_count')
            )
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->whereIn('status', ['completed', 'paid', 'shipping', 'processing'])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // === 3. PHÂN BỐ TRẠNG THÁI ĐƠN HÀNG ===
        $ordersByStatus = Order::select('status', DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->groupBy('status')
            ->pluck('count', 'status');

        // === 4. TOP SẢN PHẨM BÁN CHẠY ===
        $topSellingProducts = OrderItem::select(
                'product_id',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(price * quantity) as total_revenue')
            )
            ->whereHas('order', function ($q) use ($fromDate, $toDate) {
                $q->whereBetween('created_at', [$fromDate, $toDate])
                  ->whereIn('status', ['completed', 'paid', 'shipping', 'processing']);
            })
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                $item->product = Product::find($item->product_id);
                return $item;
            })
            ->filter(fn($item) => $item->product !== null)
            ->values();

        // === 5. TOP SẢN PHẨM XEM NHIỀU ===
        $topViewedProducts = ProductView::select(
                'product_id',
                DB::raw('COUNT(*) as view_count')
            )
            ->whereBetween('viewed_at', [$fromDate, $toDate])
            ->groupBy('product_id')
            ->orderByDesc('view_count')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                $item->product = Product::find($item->product_id);
                return $item;
            })
            ->filter(fn($item) => $item->product !== null)
            ->values();

        // === 6. DOANH THU THEO DANH MỤC ===
        $revenueByCategory = OrderItem::select(
                'products.category_id',
                DB::raw('SUM(order_items.price * order_items.quantity) as revenue')
            )
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereHas('order', function ($q) use ($fromDate, $toDate) {
                $q->whereBetween('created_at', [$fromDate, $toDate])
                  ->whereIn('status', ['completed', 'paid', 'shipping', 'processing']);
            })
            ->groupBy('products.category_id')
            ->orderByDesc('revenue')
            ->get()
            ->map(function ($item) {
                $item->category = \App\Models\Category::find($item->category_id);
                return $item;
            })
            ->filter(fn($item) => $item->category !== null)
            ->values();

        // === 7. PHƯƠNG THỨC THANH TOÁN ===
        $paymentMethods = Order::select('payment_method', DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->whereNotNull('payment_method')
            ->groupBy('payment_method')
            ->pluck('count', 'payment_method');

        return view('admin.reports.index', compact(
            'from', 'to',
            'totalRevenue', 'totalOrders', 'completedOrders', 'cancelledOrders',
            'newCustomers', 'totalViews', 'avgOrderValue', 'totalProductsSold',
            'revenueByDay', 'ordersByStatus',
            'topSellingProducts', 'topViewedProducts',
            'revenueByCategory', 'paymentMethods'
        ));
    }

    /**
     * Xuất báo cáo CSV.
     */
    public function exportCSV(Request $request)
    {
        $from = $request->query('from', now()->startOfMonth()->toDateString());
        $to   = $request->query('to', now()->toDateString());
        $type = $request->query('type', 'orders'); // orders | products | revenue

        $fromDate = \Carbon\Carbon::parse($from)->startOfDay();
        $toDate   = \Carbon\Carbon::parse($to)->endOfDay();

        $filename = "bao-cao-{$type}_{$from}_{$to}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($type, $fromDate, $toDate) {
            $file = fopen('php://output', 'w');
            // BOM for UTF-8 Excel compatibility
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            if ($type === 'orders') {
                fputcsv($file, ['Mã đơn', 'Khách hàng', 'Tổng tiền', 'Trạng thái', 'Thanh toán', 'Ngày đặt']);
                $orders = Order::with('user')
                    ->whereBetween('created_at', [$fromDate, $toDate])
                    ->orderBy('id', 'desc')
                    ->get();
                foreach ($orders as $order) {
                    $statusMap = collect(app(\App\Models\Order::class)->getStatusMap())->mapWithKeys(fn($v, $k) => [$k => $v['label']])->toArray();
                    fputcsv($file, [
                        $order->order_code,
                        $order->user->name ?? 'Khách vãng lai',
                        $order->total_amount,
                        $statusMap[$order->status] ?? $order->status,
                        strtoupper($order->payment_method ?? 'COD'),
                        $order->created_at->format('d/m/Y H:i'),
                    ]);
                }
            } elseif ($type === 'products') {
                fputcsv($file, ['ID', 'Tên sản phẩm', 'Danh mục', 'Giá gốc', 'Giá KM', 'Số lượng bán', 'Doanh thu', 'Lượt xem']);
                $products = Product::with('category')->where('status', 1)->get();
                foreach ($products as $p) {
                    $sold = OrderItem::where('product_id', $p->id)
                        ->whereHas('order', fn($q) => $q->whereBetween('created_at', [$fromDate, $toDate])->whereIn('status', ['completed','paid','shipping','processing']))
                        ->sum('quantity');
                    $rev = OrderItem::where('product_id', $p->id)
                        ->whereHas('order', fn($q) => $q->whereBetween('created_at', [$fromDate, $toDate])->whereIn('status', ['completed','paid','shipping','processing']))
                        ->selectRaw('SUM(price * quantity) as r')->value('r') ?? 0;
                    $views = ProductView::where('product_id', $p->id)->whereBetween('viewed_at', [$fromDate, $toDate])->count();
                    fputcsv($file, [
                        $p->id, $p->name, $p->category->name ?? '—',
                        $p->price, $p->sale_price ?? $p->price,
                        $sold, $rev, $views,
                    ]);
                }
            } elseif ($type === 'revenue') {
                fputcsv($file, ['Ngày', 'Số đơn hàng', 'Doanh thu (VNĐ)']);
                $data = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as cnt'), DB::raw('SUM(total_amount) as rev'))
                    ->whereBetween('created_at', [$fromDate, $toDate])
                    ->whereIn('status', ['completed','paid','shipping','processing'])
                    ->groupBy('date')->orderBy('date')->get();
                foreach ($data as $row) {
                    fputcsv($file, [$row->date, $row->cnt, $row->rev]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Xuất báo cáo PDF.
     */
    public function exportPDF(Request $request)
    {
        $from = $request->query('from', now()->startOfMonth()->toDateString());
        $to   = $request->query('to', now()->toDateString());
        $type = $request->query('type', 'overview');

        $fromDate = \Carbon\Carbon::parse($from)->startOfDay();
        $toDate   = \Carbon\Carbon::parse($to)->endOfDay();

        // Tổng quan
        $totalRevenue = Order::whereBetween('created_at', [$fromDate, $toDate])
            ->whereIn('status', ['completed', 'paid', 'shipping', 'processing'])
            ->sum('total_amount');
        $totalOrders = Order::whereBetween('created_at', [$fromDate, $toDate])->count();
        $completedOrders = Order::whereBetween('created_at', [$fromDate, $toDate])
            ->whereIn('status', ['completed', 'paid'])->count();
        $cancelledOrders = Order::whereBetween('created_at', [$fromDate, $toDate])
            ->where('status', 'cancelled')->count();
        $newCustomers = User::where('role', 'user')->whereBetween('created_at', [$fromDate, $toDate])->count();
        $totalViews = ProductView::whereBetween('viewed_at', [$fromDate, $toDate])->count();

        // Top bán chạy
        $topSellingProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(price * quantity) as total_revenue'))
            ->whereHas('order', fn($q) => $q->whereBetween('created_at', [$fromDate, $toDate])->whereIn('status', ['completed','paid','shipping','processing']))
            ->groupBy('product_id')->orderByDesc('total_qty')->limit(10)->get()
            ->map(fn($i) => tap($i, fn($i) => $i->product = Product::find($i->product_id)))
            ->filter(fn($i) => $i->product !== null)
            ->values();

        // Trạng thái đơn hàng
        $ordersByStatus = Order::select('status', DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->groupBy('status')->pluck('count', 'status');

        $pdf = Pdf::loadView('admin.reports.pdf', compact(
            'from', 'to', 'totalRevenue', 'totalOrders', 'completedOrders',
            'cancelledOrders', 'newCustomers', 'totalViews',
            'topSellingProducts', 'ordersByStatus'
        ));

        $pdf->setPaper('A4', 'portrait');
        return $pdf->download("bao-cao-tong-hop_{$from}_{$to}.pdf");
    }
}
