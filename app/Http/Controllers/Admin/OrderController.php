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