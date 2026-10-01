<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegistrationForm(Request $request)
    {
        if ($request->session()->has('user_id')) {
            return redirect('/');
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Tạo user mới (chưa verify email)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'status' => 'active',
        ]);

        // Đăng nhập session
        $request->session()->regenerate();
        $request->session()->put('user_id', $user->id);
        $request->session()->put('user_role', $user->role);
        $request->session()->put('user_name', $user->name);

        // Gửi email xác thực ngay lập tức
        $user->sendEmailVerificationNotification();

        // Redirect đến trang xác thực email (KHÔNG về trang chủ)
        return redirect()->route('verification.notice')
            ->with('success', 'Đăng ký thành công! Vui lòng kiểm tra email để xác thực tài khoản.');
    }
}