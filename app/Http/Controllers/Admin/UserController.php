<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id', 'desc')->get();
        
        return view('admin.users.index', compact('users'));
    }
    public function edit(int $id)
    {
        $user = User::with('profile')->findOrFail($id);
        
        // Ensure profile exists for the edit form
        if (!$user->profile) {
            $user->profile()->create([
                'full_name' => $user->name,
                'phone' => $user->phone ?? ''
            ]);
            $user->load('profile');
        }
        
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request,int $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:admin,user',
            'full_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
            'loyalty_points' => 'nullable|integer|min:0',
        ]);

        $user->update([
            'name' => $request->name,
            'role' => $request->role
        ]);
        
        $profileData = $request->only(['full_name', 'phone', 'date_of_birth', 'gender', 'loyalty_points']);
        
        if ($user->profile) {
            $user->profile->update($profileData);
        } else {
            $user->profile()->create($profileData);
        }

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật tài khoản thành công!');
    }

    public function toggleLock(int $id)
    {
        $user = User::findOrFail($id);
        
        // Căn bản bảo mật: Không cho phép admin tự khóa chính mình
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Không thể tự khóa tài khoản của chính mình!');
        }

        // Đảo ngược trạng thái
        $user->status = $user->status === 'locked' ? 'active' : 'locked';
        $user->save();

        return back()->with('success', 'Đã thay đổi trạng thái tài khoản!');
    }
}