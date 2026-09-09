<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SePayController extends Controller
{
    public function webhook(Request $request)
    {
        $data = $request->all();

        Log::info('SePay webhook received', $data);

        if (($data['transferType'] ?? null) !== 'in') {
            return response()->json([
                'success' => true
            ]);
        }

        $amount = (int) ($data['transferAmount'] ?? 0);
        $content = strtoupper(trim($data['content'] ?? ''));

        preg_match('/ORD-?[A-Z0-9]+/i', $content, $matches);

        if (empty($matches[0])) {
            return response()->json([
                'success' => true
            ]);
        }

        $rawOrderCode = strtoupper($matches[0]);

        $orderCode = str_starts_with($rawOrderCode, 'ORD-')
            ? $rawOrderCode
            : 'ORD-' . substr($rawOrderCode, 3);

        $order = Order::where('order_code', $orderCode)->first();

        if (!$order) {
            Log::warning('SePay: không tìm thấy đơn hàng', [
                'order_code' => $orderCode,
                'content' => $content
            ]);

            return response()->json([
                'success' => true
            ]);
        }

        if ($order->status === 'paid') {
            return response()->json([
                'success' => true
            ]);
        }

        if ($amount < (int) $order->total_amount) {
            Log::warning('SePay: sai số tiền', [
                'order_code' => $orderCode,
                'expected' => $order->total_amount,
                'received' => $amount
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Sai so tien thanh toan'
            ], 400);
        }

        $order->update([
            'status' => 'paid'
        ]);

        Log::info('SePay: order paid', [
            'order_id' => $order->id,
            'order_code' => $order->order_code,
            'amount' => $amount
        ]);

        return response()->json([
            'success' => true
        ]);
    }
}