<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::where('user_id', $request->session()->get('user_id'))
            ->orderBy('id', 'desc')
            ->get();

        return view('users.orders.index', compact('orders'));
    }

    public function show(Request $request, int $id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);
        $userId = $request->session()->get('user_id');

        if ($order->user_id !== null && $order->user_id != $userId) {
            abort(403, 'Bạn không có quyền xem đơn hàng này.');
        }

        $user = \App\Models\User::with('profile')->findOrFail($userId);

        return view('users.profile.orders_show', compact('order', 'user'));
    }

    public function checkout(Request $request)
    {
        $colorNames = [
            '#000000' => 'Đen', '#FFFFFF' => 'Trắng', '#000080' => 'Xanh Navy', '#F5F5DC' => 'Be',
            '#808080' => 'Xám', '#ADD8E6' => 'Xanh nhạt', '#2F4F4F' => 'Xám đậm', '#8B4513' => 'Nâu',
            '#FF0000' => 'Đỏ', '#008000' => 'Xanh lá', '#D3D3D3' => 'Xám nhạt', '#A9A9A9' => 'Xám',
            '#4169E1' => 'Xanh dương', '#00008B' => 'Xanh đậm', '#FFB6C1' => 'Hồng', '#FFC0CB' => 'Hồng',
            '#D2B48C' => 'Be', '#FFDAB9' => 'Be', '#C0C0C0' => 'Bạc',
        ];

        $cartItems = Cart::with(['product', 'variant'])
            ->where('user_id', $request->session()->get('user_id'))
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $subtotal = $cartItems->sum(function ($item) {
            $price = $item->product->discount_percent > 0 ? $item->product->sale_price : $item->product->price;
            return $price * $item->quantity;
        });

        $discountAmount = 0;
        $voucher = null;

        if ($request->has('voucher_code')) {
            $voucherCode = strtoupper($request->voucher_code);
            $voucher = Voucher::where('code', $voucherCode)->first();

            if (!$voucher) {
                return back()->with('voucher_error', 'Mã giảm giá không tồn tại.');
            }
            if ($voucher->isActive == 0) {
                return back()->with('voucher_error', 'Mã giảm giá đang tạm dừng hoạt động.');
            }
            if (Carbon::now()->lt(Carbon::parse($voucher->startDate))) {
                return back()->with('voucher_error', 'Mã giảm giá chưa đến ngày sử dụng.');
            }
            if (Carbon::now()->gt(Carbon::parse($voucher->endDate))) {
                return back()->with('voucher_error', 'Mã giảm giá đã hết hạn.');
            }
            if ($voucher->usageLimit && $voucher->usageCount >= $voucher->usageLimit) {
                return back()->with('voucher_error', 'Mã giảm giá đã hết lượt sử dụng.');
            }
            if ($subtotal < $voucher->minOrderValue) {
                return back()->with('voucher_error', 'Đơn hàng chưa đạt giá trị tối thiểu ' . number_format($voucher->minOrderValue, 0, ',', '.') . 'đ để áp dụng mã này.');
            }

            if ($voucher->discountType === 'percent') {
                $discount = ($subtotal * $voucher->discountValue) / 100;
                if ($voucher->maxDiscountAmount && $discount > $voucher->maxDiscountAmount) {
                    $discount = $voucher->maxDiscountAmount;
                }
                $discountAmount = $discount;
            } else {
                $discountAmount = $voucher->discountValue;
            }

            if ($discountAmount > $subtotal) {
                $discountAmount = $subtotal;
            }
        }

        $total = $subtotal - $discountAmount;

        return view('users.cart.thanhtoan', compact('cartItems', 'subtotal', 'total', 'colorNames', 'voucher', 'discountAmount'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'shipping_address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'payment_method' => 'required|string|max:50',
            'subtotal_amount' => 'required|numeric|min:0',
            'voucher_code' => 'nullable|string|max:50',
            'cart_items' => 'required|array|min:1',
            'cart_items.*.product_id' => 'required|integer',
            'cart_items.*.quantity' => 'required|integer|min:1',
            'cart_items.*.price' => 'required|numeric|min:0',
            'cart_items.*.variant_id' => 'nullable|integer',
        ]);

        DB::beginTransaction();

        try {
            $discountAmount = 0;
            $voucherId = null;

            if (!empty($validated['voucher_code'])) {
                $voucher = Voucher::where('code', strtoupper($validated['voucher_code']))
                                  ->lockForUpdate()
                                  ->first();

                if ($voucher && $voucher->isActive == 1 
                    && Carbon::now()->between(Carbon::parse($voucher->startDate), Carbon::parse($voucher->endDate))
                    && (!$voucher->usageLimit || $voucher->usageCount < $voucher->usageLimit)
                    && $validated['subtotal_amount'] >= $voucher->minOrderValue) {

                    if ($voucher->discountType === 'percent') {
                        $discount = ($validated['subtotal_amount'] * $voucher->discountValue) / 100;
                        if ($voucher->maxDiscountAmount && $discount > $voucher->maxDiscountAmount) {
                            $discount = $voucher->maxDiscountAmount;
                        }
                        $discountAmount = $discount;
                    } else {
                        $discountAmount = $voucher->discountValue;
                    }

                    if ($discountAmount > $validated['subtotal_amount']) {
                        $discountAmount = $validated['subtotal_amount'];
                    }

                    $voucherId = $voucher->id;
                    $voucher->increment('usageCount');
                }
            }

            $finalAmount = $validated['subtotal_amount'] - $discountAmount;

            $order = Order::create([
                'user_id' => $request->session()->has('user_id') ? $request->session()->get('user_id') : null,
                'order_code' => 'ORD-' . strtoupper(uniqid()),
                'total_amount' => $finalAmount,
                'discount_amount' => $discountAmount,
                'voucher_id' => $voucherId,
                'status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'shipping_address' => $validated['shipping_address'],
                'customer_phone' => $validated['customer_phone'],
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['cart_items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            if ($request->session()->has('user_id')) {
                Cart::where('user_id', $request->session()->get('user_id'))->delete();
            }

            DB::commit();

            session()->put('order', $order);
            session()->put('order_code', $order->order_code);
            session()->put('customer_phone', $order->customer_phone);

            return redirect()->route('payment.qr', ['order' => $order->id]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Có lỗi xảy ra khi đặt hàng: ' . $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $sessionOrder = $request->session()->get('order');

        if (!$sessionOrder) {
            return redirect()->route('home');
        }

        $order = Order::with('user')->find($sessionOrder->id);

        if (!$order) {
            return redirect()->route('home');
        }

        if ($order->status !== 'paid') {
            return redirect()->route('payment.qr', ['order' => $order->id])
                ->with('error', 'Đơn hàng chưa được xác nhận thanh toán.');
        }

        $orderCode = $order->order_code;
        $name = $order->user ? $order->user->name : 'Khách hàng';
        $phone = $order->customer_phone;
        $payment = $order->payment_method === 'bank' ? 'Chuyển khoản ngân hàng' : 'Thanh toán khi nhận hàng (COD)';

        return view('users.cart.thanhcong', compact('orderCode', 'name', 'phone', 'payment'));
    }

    public function confirmQr(Request $request, int $id)
    {
        $order = Order::findOrFail($id);
        $userId = $request->session()->get('user_id');

        if ($order->user_id !== null && $order->user_id != $userId) {
            abort(403, 'Bạn không có quyền xác nhận đơn hàng này.');
        }

        if ($order->status === 'paid') {
            return redirect()->route('checkout.success');
        }

        return redirect()->route('payment.qr', ['order' => $order->id])
            ->with('error', 'Hệ thống chưa nhận được thanh toán. Vui lòng chuyển khoản đúng số tiền và nội dung.');
    }

    public function paymentStatus(Request $request, int $id)
    {
        $order = Order::findOrFail($id);
        $userId = $request->session()->get('user_id');

        if ($order->user_id !== null && $order->user_id != $userId) {
            abort(403, 'Bạn không có quyền xem đơn hàng này.');
        }

        return response()->json(['status' => $order->status]);
    }
}