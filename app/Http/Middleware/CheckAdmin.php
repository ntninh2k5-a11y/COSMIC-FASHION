<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra Session thay vì Auth::check()
        if ($request->session()->has('user_id') && $request->session()->get('user_role') === 'admin') {
            return $next($request);
        }

        return redirect('/')->with('error', 'Bạn không có quyền truy cập khu vực quản trị!');
    }
}