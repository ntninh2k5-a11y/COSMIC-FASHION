<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeaturedProductController extends Controller
{
   
    public function index(Request $request)
    {
    
        $viewCounts = ProductView::select('product_id', DB::raw('COUNT(*) as view_count'))
            ->groupBy('product_id')
            ->pluck('view_count', 'product_id');

        $tab = $request->query('tab', 'all');

        $query = Product::with('category')->where('status', 1);

        if ($tab === 'featured') {
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

        // Đếm featured
        $featuredCount = Product::where('is_featured', true)->where('status', 1)->count();
        $totalViews = ProductView::count();

        return view('admin.featured.index', compact('products', 'viewCounts', 'tab', 'featuredCount', 'totalViews'));
    }

    /**
     * Bật/tắt trạng thái nổi bật cho sản phẩm.
     */
    public function toggle(Request $request, int $id)
    {
        $product = Product::findOrFail($id);

        if (!$product->is_featured) {
            // Đếm sản phẩm featured hiện tại
            $currentFeatured = Product::where('is_featured', true)->count();
            if ($currentFeatured >= 12) {
                return back()->with('warning', 'Tối đa 12 sản phẩm nổi bật. Hãy bỏ bớt sản phẩm khác trước.');
            }

            $maxOrder = Product::where('is_featured', true)->max('featured_order') ?? 0;
            $product->is_featured = true;
            $product->featured_order = $maxOrder + 1;
        } else {
            $product->is_featured = false;
            $product->featured_order = 0;
        }

        $product->save();

        $status = $product->is_featured ? 'đánh dấu nổi bật' : 'bỏ nổi bật';
        return back()->with('success', "Đã {$status} sản phẩm \"{$product->name}\".");
    }

    /**
     * Cập nhật thứ tự hiển thị (drag & drop hoặc manual).
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
