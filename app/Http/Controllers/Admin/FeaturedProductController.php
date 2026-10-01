<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeaturedProductController extends Controller
{
    /**
     * Trang quản lý sản phẩm nổi bật.
     */
    public function index(Request $request)
    {
        // Lấy lượt xem theo product_id
        $viewCounts = ProductView::select('product_id', DB::raw('COUNT(*) as view_count'))
            ->groupBy('product_id')
            ->pluck('view_count', 'product_id');

        // Lọc theo tab
        $tab = $request->query('tab', 'carousel');

        // === Tính 12 sản phẩm đang thực sự hiển thị trên carousel ===
        $carouselProducts = $this->getCarouselProducts($viewCounts);
        $carouselIds = $carouselProducts->pluck('id')->toArray();

        // Đếm số ghim tay và tự động
        $pinnedCount = Product::where('is_featured', true)->where('status', 1)->count();
        $autoCount = count($carouselIds) - $pinnedCount;
        $totalViews = ProductView::count();

        // Query theo tab
        $query = Product::with('category')->where('status', 1);

        if ($tab === 'carousel') {
            // Hiển thị đúng 12 sản phẩm đang trên carousel, đúng thứ tự
            if (!empty($carouselIds)) {
                $query->whereIn('id', $carouselIds)
                    ->orderByRaw('FIELD(id, ' . implode(',', $carouselIds) . ')');
            }
        } elseif ($tab === 'pinned') {
            $query->where('is_featured', true)->orderBy('featured_order', 'asc');
        } elseif ($tab === 'top-views') {
            $topIds = ProductView::select('product_id', DB::raw('COUNT(*) as vc'))
                ->groupBy('product_id')
                ->orderByDesc('vc')
                ->pluck('product_id');

            if ($topIds->isNotEmpty()) {
                $query->whereIn('id', $topIds)
                    ->orderByRaw('FIELD(id, ' . $topIds->implode(',') . ')');
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        $products = $query->paginate(15);

        return view('admin.featured.index', compact(
            'products', 'viewCounts', 'tab',
            'pinnedCount', 'autoCount', 'totalViews',
            'carouselIds'
        ));
    }

    /**
     * Lấy đúng 12 sản phẩm đang hiển thị trên carousel trang chủ.
     * Logic giống hệt HomeController.
     */
    private function getCarouselProducts($viewCounts)
    {
        // Ưu tiên 1: Ghim tay
        $pinned = Product::where('status', 1)
            ->where('is_featured', true)
            ->orderBy('featured_order', 'asc')
            ->get();

        $remaining = 12 - $pinned->count();
        $excludeIds = $pinned->pluck('id')->toArray();

        // Ưu tiên 2: Xem nhiều nhất
        $topViewed = collect();
        if ($remaining > 0) {
            $topIds = ProductView::select('product_id', DB::raw('COUNT(*) as vc'))
                ->whereNotIn('product_id', $excludeIds)
                ->groupBy('product_id')
                ->orderByDesc('vc')
                ->limit($remaining)
                ->pluck('product_id');

            if ($topIds->isNotEmpty()) {
                $topViewed = Product::where('status', 1)
                    ->whereIn('id', $topIds)
                    ->get()
                    ->sortBy(fn($p) => array_search($p->id, $topIds->toArray()))
                    ->values();
            }
        }

        // Ưu tiên 3: Mới nhất
        $stillRemaining = 12 - $pinned->count() - $topViewed->count();
        $newest = collect();
        if ($stillRemaining > 0) {
            $allExclude = array_merge($excludeIds, $topViewed->pluck('id')->toArray());
            $newest = Product::where('status', 1)
                ->whereNotIn('id', $allExclude)
                ->orderBy('id', 'desc')
                ->take($stillRemaining)
                ->get();
        }

        return $pinned->concat($topViewed)->concat($newest);
    }

    /**
     * Bật/tắt trạng thái nổi bật cho sản phẩm.
     */
    public function toggle(Request $request, int $id)
    {
        $product = Product::findOrFail($id);

        if (!$product->is_featured) {
            $currentFeatured = Product::where('is_featured', true)->count();
            if ($currentFeatured >= 12) {
                return back()->with('warning', 'Tối đa 12 sản phẩm ghim tay. Hãy bỏ bớt sản phẩm khác trước.');
            }

            $maxOrder = Product::where('is_featured', true)->max('featured_order') ?? 0;
            $product->is_featured = true;
            $product->featured_order = $maxOrder + 1;
        } else {
            $product->is_featured = false;
            $product->featured_order = 0;
        }

        $product->save();

        $status = $product->is_featured ? 'ghim lên carousel' : 'bỏ ghim';
        return back()->with('success', "Đã {$status} sản phẩm \"{$product->name}\".");
    }

    /**
     * Cập nhật thứ tự hiển thị.
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:products,id',
        ]);

        foreach ($request->order as $position => $productId) {
            Product::where('id', $productId)->update(['featured_order' => $position + 1]);
        }

        return back()->with('success', 'Đã cập nhật thứ tự hiển thị.');
    }
}
