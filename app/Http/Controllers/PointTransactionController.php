<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PointTransaction;
use Illuminate\Http\Request;

class PointTransactionController extends Controller
{
    /**
     * Trang đổi điểm — hiển thị số dư + hướng dẫn + lịch sử
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
}
