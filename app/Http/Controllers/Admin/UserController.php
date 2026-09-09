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
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request,int $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:admin,user'
        ]);

        $user->update([
            'name' => $request->name,
            'role' => $request->role
        ]);

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