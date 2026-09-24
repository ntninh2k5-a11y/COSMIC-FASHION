<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FooterMenu;
use Illuminate\Http\Request;

class FooterMenuController extends Controller
{
    public function index()
    {
        $footerMenus = FooterMenu::orderBy('id', 'desc')->get();
        return view('admin.footer_menus.index', compact('footerMenus'));
    }

    public function create()
    {
        $footerColumns = FooterMenu::whereNull('parent_id')->get();
        return view('admin.footer_menus.create', compact('footerColumns'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|integer',
            'url' => 'nullable|string|max:255',
            'status' => 'required|boolean'
        ]);

        FooterMenu::create($request->all());
        return redirect()->route('admin.footer_menus.index')->with('success', 'Thêm thành công!');
    }

    public function edit(int $id)
    {
        $footerMenu = FooterMenu::findOrFail($id);
        $footerColumns = FooterMenu::whereNull('parent_id')->where('id', '!=', $id)->get();
        return view('admin.footer_menus.edit', compact('footerMenu', 'footerColumns'));
    }

    public function update(Request $request, int $id)
    {
        $footerMenu = FooterMenu::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|integer',
            'url' => 'nullable|string|max:255',
            'status' => 'required|boolean'
        ]);

        $footerMenu->update($request->all());
        return redirect()->route('admin.footer_menus.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy(int $id)
    {
        $footerMenu = FooterMenu::findOrFail($id);
        $footerMenu->delete();
        return redirect()->route('admin.footer_menus.index')->with('success', 'Đã xóa!');
    }
}