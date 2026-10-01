<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminOnly
{
    /**
     * Chỉ cho phép role admin truy cập.
     * Staff sẽ bị chặn với thông báo không có quyền.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $role = $request->session()->get('user_role');

        if ($request->session()->has('user_id') && $role === 'admin') {
            return $next($request);
        }

        return redirect()->route('admin.dashboard')->with('error', 'Bạn không có quyền truy cập chức năng này. Chỉ Admin mới được phép.');
    }
}
