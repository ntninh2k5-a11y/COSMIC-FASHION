<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PointTransaction;
use Illuminate\Http\Request;

class PointTransactionController extends Controller
{
    // Quy đổi: 1 điểm = bao nhiêu VNĐ giảm giá
    const POINT_VALUE = 1000; // 1 điểm = 1.000đ
    const MIN_REDEEM  = 10;   // Tối thiểu 10 điểm mới được đổi

    /**
     * Trang đổi điểm — hiển thị số dư + lịch sử
     */
    public function index()
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::with('profile')->findOrFail($userId);

        $transactions = PointTransaction::where('user_id', $userId)
            ->orderBy('id', 'desc')
            ->paginate(10);

        $balance = $user->profile->loyalty_points ?? 0;

        return view('users.profile.points', compact('user', 'transactions', 'balance'));
    }

    /**
     * Đổi điểm lấy mã giảm giá
     */
    public function redeem(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $request->validate([
            'points' => 'required|integer|min:' . self::MIN_REDEEM,
        ]);

        $user = User::with('profile')->findOrFail($userId);
        $balance = $user->profile->loyalty_points ?? 0;
        $pointsToRedeem = (int) $request->points;

        if ($pointsToRedeem > $balance) {
            return back()->with('error', 'Bạn không đủ điểm để đổi!');
        }

        $discountValue = $pointsToRedeem * self::POINT_VALUE;

        // Tạo voucher từ điểm đổi
        $voucherCode = 'PT' . strtoupper(substr(md5(uniqid()), 0, 8));

        \App\Models\Voucher::create([
            'code'              => $voucherCode,
            'discountType'      => 'fixed',
            'discountValue'     => $discountValue,
            'minOrderValue'     => 0,
            'maxDiscountAmount' => $discountValue,
            'usageLimit'        => 1,
            'usageCount'        => 0,
            'startDate'         => now()->toDateString(),
            'endDate'           => now()->addDays(30)->toDateString(),
            'isActive'          => true,
        ]);

        // Trừ điểm
        PointTransaction::deductPoints(
            $userId,
            $pointsToRedeem,
            PointTransaction::TYPE_REDEEM,
            'Đổi ' . number_format($pointsToRedeem) . ' điểm → Mã ' . $voucherCode . ' (giảm ' . number_format($discountValue, 0, ',', '.') . 'đ)'
        );

        return back()->with('success', 'Đổi điểm thành công! Mã giảm giá của bạn: ' . $voucherCode . ' (giảm ' . number_format($discountValue, 0, ',', '.') . 'đ, hiệu lực 30 ngày)');
    }
}
