<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductDetailController extends Controller
{
    public function show(int $id)
    {
        $product = Product::with('variants')->findOrFail($id);

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

        $colorNames = [
            '#000000' => 'Đen',
            '#FFFFFF' => 'Trắng',
            '#001F3F' => 'Xanh Navy',
            '#000080' => 'Xanh Navy',
            '#F5F5DC' => 'Be',
            '#808080' => 'Xám',
            '#ADD8E6' => 'Xanh nhạt',
            '#2F4F4F' => 'Xám đậm',
            '#8B4513' => 'Nâu',
            '#FF0000' => 'Đỏ',
            '#008000' => 'Xanh lá',
            '#D3D3D3' => 'Xám nhạt',
            '#A9A9A9' => 'Xám',
            '#4169E1' => 'Xanh dương',
            '#00008B' => 'Xanh đậm',
            '#FFB6C1' => 'Hồng',
            '#FFC0CB' => 'Hồng',
            '#D2B48C' => 'Be',
            '#FFDAB9' => 'Be',
            '#C0C0C0' => 'Bạc',
        ];

        return view('users.products.detail', compact(
            'product',
            'relatedProducts',
            'sizes',
            'colors',
            'colorNames'
        ));
    }
}