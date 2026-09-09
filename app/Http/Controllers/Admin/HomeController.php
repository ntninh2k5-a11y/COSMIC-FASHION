<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $newOrders = Order::count();

        $revenue = Order::where('status', 'completed')
            ->sum('total_amount');

        $productsInStock = ProductVariant::sum('stock_quantity');

        $customers = User::where('role', 'user')->count();

        $recentOrders = Order::with('user')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'newOrders',
            'revenue',
            'productsInStock',
            'customers',
            'recentOrders'
        ));
    }
}