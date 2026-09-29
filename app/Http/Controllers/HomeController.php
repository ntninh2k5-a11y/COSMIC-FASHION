<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductView;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $khoSanPhamGiamGia = Product::where('status', 1)
            ->where('discount_percent', '>', 0)
            ->take(4)
            ->get();

        $banners = \App\Models\Banner::where('is_active', 1)
            ->orderBy('order', 'asc')
            ->get();

        // === Sản phẩm nổi bật cho carousel ===
        // Ưu tiên 1: Sản phẩm admin đã ghim (is_featured), theo featured_order
        $featuredProducts = Product::with('variants')
            ->where('status', 1)
            ->where('is_featured', true)
            ->orderBy('featured_order', 'asc')
            ->get();

        // Ưu tiên 2: Bổ sung bằng sản phẩm xem nhiều nhất (nếu chưa đủ 12)
        $remaining = 12 - $featuredProducts->count();

        if ($remaining > 0) {
            $excludeIds = $featuredProducts->pluck('id')->toArray();

            $topViewedIds = ProductView::select('product_id', DB::raw('COUNT(*) as view_count'))
                ->whereNotIn('product_id', $excludeIds)
                ->groupBy('product_id')
                ->orderByDesc('view_count')
                ->limit($remaining)
                ->pluck('product_id');

            if ($topViewedIds->isNotEmpty()) {
                $topViewedProducts = Product::with('variants')
                    ->whereIn('id', $topViewedIds)
                    ->where('status', 1)
                    ->get()
                    ->sortBy(function ($product) use ($topViewedIds) {
                        return array_search($product->id, $topViewedIds->toArray());
                    })
                    ->values();
            } else {
                $topViewedProducts = collect();
            }

            // Nếu vẫn chưa đủ, bổ sung sản phẩm mới nhất
            $stillRemaining = 12 - $featuredProducts->count() - $topViewedProducts->count();
            if ($stillRemaining > 0) {
                $allExcludeIds = array_merge($excludeIds, $topViewedIds->toArray());
                $newestProducts = Product::with('variants')
                    ->where('status', 1)
                    ->whereNotIn('id', $allExcludeIds)
                    ->orderBy('id', 'desc')
                    ->take($stillRemaining)
                    ->get();
            } else {
                $newestProducts = collect();
            }

            $sanPhamXemNhieu = $featuredProducts->concat($topViewedProducts)->concat($newestProducts);
        } else {
            $sanPhamXemNhieu = $featuredProducts->take(12);
        }

        return view('users.home.index', compact('khoSanPhamGiamGia', 'banners', 'sanPhamXemNhieu'));
    }
}