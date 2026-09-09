<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $colorNames = [
            '#000000' => 'Đen',
            '#FFFFFF' => 'Trắng',
            '#000080' => 'Xanh Navy',
            '#001F3F' => 'Xanh Navy',
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

        $userId = $request->session()->get('user_id');

        if (!$userId) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập để xem giỏ hàng.');
        }

        $cartItems = Cart::with(['product', 'variant'])
            ->where('user_id', $userId)
            ->get();

        $total = $cartItems->sum(function ($item) {
            $price = $item->product->discount_percent > 0
                ? $item->product->sale_price
                : $item->product->price;

            return $price * $item->quantity;
        });

        return view('users.cart.cart', compact('cartItems', 'total', 'colorNames'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => [
                'nullable',
                'exists:product_variants,id',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value) {
                        $valid = \App\Models\ProductVariant::where('id', $value)
                            ->where('product_id', $request->product_id)
                            ->exists();

                        if (!$valid) {
                            $fail('Phiên bản Size và Màu bạn chọn không hợp lệ hoặc đã hết hàng.');
                        }
                    }
                },
            ],
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = $request->session()->get('user_id');

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để thêm vào giỏ hàng'
            ], 401);
        }

        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $validated['product_id'])
            ->where('variant_id', $validated['variant_id'])
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $validated['quantity']);
        } else {
            $cartItem = Cart::create([
                'user_id' => $userId,
                'product_id' => $validated['product_id'],
                'variant_id' => $validated['variant_id'] ?? null,
                'quantity' => $validated['quantity'],
            ]);
        }

        $cartCount = Cart::where('user_id', $userId)->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Thêm vào giỏ hàng thành công!',
            'cart_count' => $cartCount,
            'item' => $cartItem->load(['product', 'variant']),
        ]);
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = $request->session()->get('user_id');

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để cập nhật giỏ hàng'
            ], 401);
        }

        $cartItem = Cart::where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại trong giỏ hàng, vui lòng tải lại trang!'
            ], 404);
        }

        $cartItem->update([
            'quantity' => $validated['quantity'],
        ]);

        $cartCount = Cart::where('user_id', $userId)->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật số lượng thành công!',
            'cart_count' => $cartCount,
        ]);
    }

    public function destroy(Request $request, int $id)
    {
        $userId = $request->session()->get('user_id');

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để xóa sản phẩm'
            ], 401);
        }

        $cartItem = Cart::where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại trong giỏ hàng, vui lòng tải lại trang!'
            ], 404);
        }

        $cartItem->delete();

        $cartCount = Cart::where('user_id', $userId)->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa sản phẩm khỏi giỏ hàng!',
            'cart_count' => $cartCount,
        ]);
    }

    public function count(Request $request)
    {
        $userId = $request->session()->get('user_id');

        if (!$userId) {
            return response()->json([
                'count' => 0
            ]);
        }

        $count = Cart::where('user_id', $userId)->sum('quantity');

        return response()->json([
            'count' => $count
        ]);
    }
}