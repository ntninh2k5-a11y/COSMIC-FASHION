<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        if ($request->session()->has('user_id')) {
            return redirect('/');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            if ($user->status === 'locked') {
                return redirect('/')->with('error', 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên!');
            }

            $request->session()->regenerate();
            $request->session()->put('user_id', $user->id);
            $request->session()->put('user_role', $user->role);
            $request->session()->put('user_name', $user->name);

            if ($request->boolean('remember')) {
                $token = Str::random(60);
                $user->remember_token = $token;
                $user->save();
                Cookie::queue('remember_token', $token, 43200);
            }

            if ($user->role === 'admin') {
                return redirect('/admin/dashboard');
            }

            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['user_id', 'user_role']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        Cookie::queue(Cookie::forget('remember_token'));

        return redirect('/');
    }
}