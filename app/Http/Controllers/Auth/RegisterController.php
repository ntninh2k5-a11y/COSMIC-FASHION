<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    // 1. Hiển thị form đăng ký
    public function showRegistrationForm(Request $request)
    {
        // Chặn người đã có Session (đã đăng nhập) vào lại trang đăng ký
        if ($request->session()->has('user_id')) {
            return redirect('/');
        }

        return view('auth.register');
    }

    // 2. Xử lý lưu dữ liệu khi bấm nút Đăng ký
    public function register(Request $request)
    {
        // Bước 1: Kiểm tra tính hợp lệ của dữ liệu form
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Bước 2: Tạo người dùng mới trong Database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', 
            'status' => 'active',
        ]);

        // Bước 3: Đăng nhập tự động ngay sau khi đăng ký thành công
        $request->session()->regenerate();
        $request->session()->put('user_id', $user->id);
        $request->session()->put('user_role', $user->role);
        $request->session()->put('user_name', $user->name); // Quan trọng để hiện tên trên Header

        // Bước 4: Chuyển hướng về trang chủ
        return redirect('/')->with('success', 'Đăng ký tài khoản thành công!');
    }
}