<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /**
     * Hiển thị trang xác thực email.
     */
    public function show(Request $request)
    {
        $userId = $request->session()->get('user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);

        if ($user && $user->hasVerifiedEmail()) {
            return redirect('/')->with('success', 'Email đã được xác thực!');
        }

        return view('auth.verify');
    }

    /**
     * Xác thực email từ link trong mail.
     */
    public function verify(Request $request, string $id, string $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Đường dẫn xác thực không hợp lệ hoặc đã bị thay đổi.');
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        // Đảm bảo user đã đăng nhập session
        if (!$request->session()->has('user_id')) {
            $request->session()->regenerate();
            $request->session()->put('user_id', $user->id);
            $request->session()->put('user_role', $user->role);
            $request->session()->put('user_name', $user->name);
        }

        return redirect('/')->with('success', 'Xác thực email thành công! Chào mừng bạn đến với Cosmic Fashion.');
    }

    /**
     * Gửi lại email xác thực.
     */
    public function resend(Request $request)
    {
        $userId = $request->session()->get('user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);

        if ($user && $user->hasVerifiedEmail()) {
            return redirect('/')->with('success', 'Email đã được xác thực!');
        }

        if ($user) {
            $user->sendEmailVerificationNotification();
        }

        return back()->with('success', 'Email xác thực mới đã được gửi! Vui lòng kiểm tra hộp thư.');
    }
}