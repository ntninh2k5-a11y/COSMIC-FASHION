<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function show(Request $request)
    {
        $userId = $request->session()->get('user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);

        if ($user && $user->hasVerifiedEmail()) {
            return redirect('/');
        }

        return view('auth.verify');
    }

    public function verify(Request $request,string $id,string $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Đường dẫn xác thực không hợp lệ hoặc đã bị thay đổi.');
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return redirect('/')->with('success', 'Xác thực email thành công!');
    }

    public function resend(Request $request)
    {
        $userId = $request->session()->get('user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);

        if ($user && $user->hasVerifiedEmail()) {
            return redirect('/');
        }

        if ($user) {
            $user->sendEmailVerificationNotification();
        }

        return back()->with('success', 'Link xác thực mới đã được gửi vào email của bạn.');
    }
}