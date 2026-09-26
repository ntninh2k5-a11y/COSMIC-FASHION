<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class UserAddressController extends Controller
{
    public function index()
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::with('profile')->findOrFail($userId);
        $addresses = UserAddress::where('user_id', $userId)
            ->orderByDesc('is_default')
            ->latest()
            ->get();

        return view('users.profile.addresses', compact('user', 'addresses'));
    }

    public function store(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'receiver_name'    => 'required|string|max:255',
            'phone_number'     => 'required|string|max:20',
            'province'         => 'required|string|max:255',
            'district'         => 'required|string|max:255',
            'ward'             => 'required|string|max:255',
            'street_address'   => 'required|string|max:500',
            'note'             => 'nullable|string',
            'is_default'       => 'boolean',
        ]);

        $validated['user_id'] = $userId;
        $validated['is_default'] = $request->has('is_default');
        // Tổng hợp địa chỉ đầy đủ
        $validated['receiver_address'] = implode(', ', array_filter([
            $validated['street_address'],
            $validated['ward'],
            $validated['district'],
            $validated['province'],
        ]));

        if ($validated['is_default']) {
            UserAddress::where('user_id', $userId)->update(['is_default' => false]);
        }

        UserAddress::create($validated);

        return redirect()->back()->with('success', 'Thêm địa chỉ thành công!');
    }

    public function update(Request $request, $id)
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $address = UserAddress::where('id', $id)->where('user_id', $userId)->firstOrFail();

        $validated = $request->validate([
            'receiver_name'    => 'required|string|max:255',
            'phone_number'     => 'required|string|max:20',
            'province'         => 'required|string|max:255',
            'district'         => 'required|string|max:255',
            'ward'             => 'required|string|max:255',
            'street_address'   => 'required|string|max:500',
            'note'             => 'nullable|string',
            'is_default'       => 'boolean',
        ]);

        $validated['is_default'] = $request->has('is_default');
        $validated['receiver_address'] = implode(', ', array_filter([
            $validated['street_address'],
            $validated['ward'],
            $validated['district'],
            $validated['province'],
        ]));

        if ($validated['is_default']) {
            UserAddress::where('user_id', $userId)->where('id', '!=', $id)->update(['is_default' => false]);
        }

        $address->update($validated);

        return redirect()->back()->with('success', 'Cập nhật địa chỉ thành công!');
    }

    public function destroy($id)
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $address = UserAddress::where('id', $id)->where('user_id', $userId)->firstOrFail();
        $address->delete();

        return redirect()->back()->with('success', 'Xóa địa chỉ thành công!');
    }

    public function setDefault($id)
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $address = UserAddress::where('id', $id)->where('user_id', $userId)->firstOrFail();

        UserAddress::where('user_id', $userId)->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return redirect()->back()->with('success', 'Đã đặt địa chỉ mặc định!');
    }
}
