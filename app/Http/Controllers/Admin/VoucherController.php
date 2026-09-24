<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::orderBy('id', 'desc')->get();
        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        return view('admin.vouchers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:vouchers,code',
            'discountType' => 'required|in:percent,fixed',
            'discountValue' => 'required|numeric|min:0',
            'minOrderValue' => 'nullable|numeric|min:0',
            'maxDiscountAmount' => 'nullable|numeric|min:0',
            'usageLimit' => 'nullable|integer|min:1',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'isActive' => 'required|boolean'
        ]);

        Voucher::create($request->all());
        return redirect()->route('admin.vouchers.index')->with('success', 'Thêm Voucher thành công!');
    }

    public function edit(int $id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('admin.vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, int $id)
    {
        $voucher = Voucher::findOrFail($id);
        
        $request->validate([
            'code' => 'required|string|max:50|unique:vouchers,code,'.$id,
            'discountType' => 'required|in:percent,fixed',
            'discountValue' => 'required|numeric|min:0',
            'minOrderValue' => 'nullable|numeric|min:0',
            'maxDiscountAmount' => 'nullable|numeric|min:0',
            'usageLimit' => 'nullable|integer|min:1',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'isActive' => 'required|boolean'
        ]);

        $voucher->update($request->all());
        return redirect()->route('admin.vouchers.index')->with('success', 'Cập nhật Voucher thành công!');
    }

    public function destroy(int $id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();
        return redirect()->route('admin.vouchers.index')->with('success', 'Đã xóa Voucher!');
    }
}