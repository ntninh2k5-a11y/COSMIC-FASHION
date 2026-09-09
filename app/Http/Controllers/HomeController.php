<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $khoSanPhamGiamGia = Product::where('status', 1)
            ->where('discount_percent', '>', 0)
            ->take(4)
            ->get();

        return view('users.home.index', compact('khoSanPhamGiamGia'));
    }
}