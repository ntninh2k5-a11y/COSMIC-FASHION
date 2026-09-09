<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class GoogleLoginController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                if ($user->status === 'locked') {
                    return redirect('/')->with('error', 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên!');
                }

                $user->google_id = $googleUser->id;
                $user->save();

                if (is_null($user->email_verified_at)) {
                    $user->email_verified_at = now();
                    $user->save();
                }

            } else {
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => Hash::make(Str::random(16)),
                    'role' => 'user',
                    'email_verified_at' => now(),
                ]);
            }

            $request->session()->put('user_id', $user->id);
            $request->session()->put('user_name', $user->name);
            $request->session()->put('user_role', $user->role);

            if ($user->role === 'admin') {
                return redirect('/admin/dashboard');
            }
            
            return redirect('/');

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Đăng nhập Google thất bại!');
        }
    }
}