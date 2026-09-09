<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->session()->get('user_id');

        // Nếu chưa đăng nhập, đá về trang login
        if (!$userId) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        $user = User::find($userId);

        if ($user) {
            // 1. Kiểm tra tài khoản bị khóa
            if ($user->status === 'locked') {
                $request->session()->forget(['user_id', 'user_role']);
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect('/')->with('error', 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.');
            }

            // 2. Kiểm tra chưa xác thực Email
            if (is_null($user->email_verified_at)) {
                return redirect()->route('verification.notice')->with('error', 'Vui lòng xác thực email để sử dụng chức năng này.');
            }
        }

        return $next($request);
    }
}