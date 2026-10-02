<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductView;
use Illuminate\Http\Request;

class ProductDetailController extends Controller
{
    public function show(Request $request, int $id)
    {
        $product = Product::with(['variants', 'images'])->findOrFail($id);

        // Ghi nhận lượt xem sản phẩm
        ProductView::recordView($product->id, $request->session()->get('user_id'));

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 1)
            ->take(4)
            ->get();

        $sizes = $product->variants
            ->whereNotNull('size')
            ->unique('size')
            ->pluck('size');

        $colors = $product->variants
            ->whereNotNull('color')
            ->unique('color')
            ->pluck('color');

        $colorNames = \App\Helpers\ProductHelper::colorNames();

        return view('users.products.detail', compact(
            'product',
            'relatedProducts',
            'sizes',
            'colors',
            'colorNames'
        ));
    }
}