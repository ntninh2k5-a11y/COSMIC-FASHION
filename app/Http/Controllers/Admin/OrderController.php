<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PointTransaction;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(int $id)
    {
        $order = Order::with(['user', 'orderItems.product', 'orderItems.variant'])
            ->findOrFail($id);

        $colorNames = \App\Helpers\ProductHelper::colorNames();

        return view('admin.orders.show', compact('order', 'colorNames'));
    }

    public function update(Request $request, int $id)
    {
        $order = Order::findOrFail($id);
        $oldStatus = $order->status;

        $request->validate([
            'status' => 'required|string',
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        // Tự động tích điểm khi đơn hàng hoàn thành
        if ($oldStatus !== 'completed' && $request->status === 'completed' && $order->user_id) {
            $alreadyEarned = PointTransaction::where('order_id', $order->id)
                ->where('transaction_type', PointTransaction::TYPE_EARN_ORDER)
                ->exists();

            if (!$alreadyEarned) {
                PointTransaction::addPoints(
                    $order->user_id,
                    10,
                    PointTransaction::TYPE_EARN_ORDER,
                    'Tích điểm đơn hàng ' . $order->order_code,
                    $order->id
                );
            }
        }

        return redirect()
            ->route('admin.orders.show', $order->id)
            ->with('success', 'Cập nhật đơn hàng thành công.');
    }
}