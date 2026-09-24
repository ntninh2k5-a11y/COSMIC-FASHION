<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Order;

class ProfileController extends Controller
{
    public function index()
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::with('profile')->findOrFail($userId);
        
        // Ensure profile exists
        if (!$user->profile) {
            $user->profile()->create([
                'full_name' => $user->name,
                'phone' => $user->phone
            ]);
            $user->load('profile');
        }

        return view('users.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $request->validate([
            'full_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::findOrFail($userId);
        
        $profileData = $request->only(['full_name', 'phone', 'date_of_birth', 'gender']);
        
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/avatars'), $filename);
            $profileData['avatar_url'] = 'uploads/avatars/' . $filename;
            
            // Cập nhật session user_avatar
            session(['user_avatar' => $profileData['avatar_url']]);
        }
        
        if ($user->profile) {
            $user->profile->update($profileData);
        } else {
            $user->profile()->create($profileData);
        }

        return redirect()->route('user.profile')->with('success', 'Cập nhật thông tin thành công!');
    }

    public function orders()
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::with('profile')->findOrFail($userId);
        $orders = Order::where('user_id', $userId)->orderBy('created_at', 'desc')->get();

        return view('users.profile.orders', compact('user', 'orders'));
    }
}
