<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 1)->get();

        $categories->map(function ($category) {
            if ($category->slug === 'sale' || $category->id == 10 || $category->slug === 'uu-dai-dac-biet') {
                $category->routeLink = route('shop.sale');
            } else {
                $category->routeLink = route('frontend.category.detail', $category->slug);
            }
            
            return $category;
        });

        return view('users.categories.categories', compact('categories'));
    }

    public function show(string $slug)
    {
        $category = Category::where('slug', $slug)->where('status', 1)->firstOrFail();
        
        if ($category->id == 10 || $category->slug === 'sale' || $category->slug === 'uu-dai-dac-biet') {
            $saleProducts = Product::with('variants')
                ->where('category_id', $category->id)
                ->where('status', 1)
                ->get();

            $spTo = $saleProducts->first();
            $spNhoBenCanh = $saleProducts->slice(1, 2);
            $spHangDuoi = $saleProducts->slice(3);

           return view('users.products.sale', compact('saleProducts', 'spTo', 'spNhoBenCanh', 'spHangDuoi'));
        }

        $products = Product::with('variants')
            ->where('category_id', $category->id)
            ->where('status', 1)
            ->get();

        $duLieuGoc = $products->map(function($sp) {
            $sizes = $sp->variants ? $sp->variants->pluck('size')->filter()->unique()->values()->toArray() : [];
            
            $colors = $sp->variants ? $sp->variants->pluck('color')->filter()->map(function($c) {
                return ['bg' => $c];
            })->unique()->values()->toArray() : [];

            return [
                'id' => $sp->id,
                'name' => $sp->name,
                'image' => asset($sp->image_url ?? 'images/default.jpg'),
                'price' => number_format($sp->discount_percent > 0 ? $sp->sale_price : $sp->price, 0, ',', '.') . 'đ',
                'priceNum' => $sp->discount_percent > 0 ? $sp->sale_price : $sp->price,
                'sizes' => $sizes,
                'colors' => $colors
            ];
        })->toJson();

        $showFilters = ($category->slug !== 'phu_kien');
        $categoryName = $category->name;
        $categoryDesc = $category->description ?? 'Khám phá bộ sưu tập ' . $category->name;

        return view('users.products.danh-sach-san-pham', compact('duLieuGoc', 'showFilters', 'categoryName', 'categoryDesc'));
    }
}