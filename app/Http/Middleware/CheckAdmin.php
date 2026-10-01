<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Cho phép admin và staff truy cập khu vực quản trị.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $role = $request->session()->get('user_role');

        if ($request->session()->has('user_id') && in_array($role, ['admin', 'staff'])) {
            return $next($request);
        }

        return redirect('/')->with('error', 'Bạn không có quyền truy cập khu vực quản trị!');
    }
}