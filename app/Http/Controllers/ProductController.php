<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function sale()
    {
        $saleProducts = Product::with('variants')
            ->where('status', 1)
            ->where('discount_percent', '>', 0)
            ->get();

        $spTo = $saleProducts->first();
        $spNhoBenCanh = $saleProducts->slice(1, 2)->values();
        $spHangDuoi = $saleProducts->slice(3);

        return view(
            'users.products.sale',
            compact('saleProducts', 'spTo', 'spNhoBenCanh', 'spHangDuoi')
        );
    }

    public function show(int $id)
    {
        $product = Product::with('variants')->findOrFail($id);

        return view('users.products.detail', compact('product'));
    }

    public function search(Request $request)
    {
        $tuKhoa = $request->query('q');

        $query = Product::where('status', 1);

        if ($tuKhoa) {
            $query->where('name', 'like', '%' . $tuKhoa . '%');
        }

        $ketQuaLoc = $query->get();

        $tatCaSanPham = Product::where('status', 1)->count();

        return view('users.products.timkiem', compact(
            'tuKhoa',
            'ketQuaLoc',
            'tatCaSanPham'
        ));
    }

    public function byCategory(int $categoryId, string $view)
    {
        $products = Product::with('variants')
            ->where('category_id', $categoryId)
            ->where('status', 1)
            ->get();

        $duLieuGocArr = $products->map(function ($sp) {
            $sizes = $sp->variants
                ? $sp->variants->pluck('size')->filter()->unique()->values()->toArray()
                : [];

            $colors = $sp->variants
                ? $sp->variants->pluck('color')->filter()->unique()->values()->toArray()
                : [];

            return [
                'id' => $sp->id,
                'name' => $sp->name,
                'priceNum' => $sp->discount_percent > 0
                    ? $sp->sale_price
                    : $sp->price,
                'price' => number_format(
                    $sp->discount_percent > 0
                        ? $sp->sale_price
                        : $sp->price,
                    0,
                    ',',
                    '.'
                ) . 'đ',
                'image' => asset($sp->image_url ?? 'images/default.jpg'),
                'discount' => $sp->discount_percent > 0
                    ? '-' . $sp->discount_percent . '%'
                    : null,
                'sizes' => $sizes,
                'colors' => array_map(fn($c) => ['bg' => $c], $colors),
            ];
        });

        return view($view, [
            'duLieuGoc' => $duLieuGocArr->toJson()
        ]);
    }

    public function shoes()
    {
        return $this->byCategory(6, 'users.products.giay_dep');
    }

    public function accessories()
    {
        return $this->byCategory(4, 'users.products.phukien');
    }

    public function kids()
    {
        return $this->byCategory(3, 'users.products.shopkids');
    }

    public function men()
    {
        return $this->byCategory(1, 'users.products.shopmen');
    }

    public function women()
    {
        return $this->byCategory(2, 'users.products.shopwomen');
    }

    public function sport()
    {
        return $this->byCategory(5, 'users.products.sport');
    }
}