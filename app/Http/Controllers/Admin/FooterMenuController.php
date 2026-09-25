<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FooterMenu;
use Illuminate\Http\Request;

class FooterMenuController extends Controller
{
    public function index()
    {
        $footerMenus = FooterMenu::orderByRaw('COALESCE(parent_id, id)')->orderBy('sort_order')->orderBy('id')->get();
        return view('admin.footer_menus.index', compact('footerMenus'));
    }

    public function create()
    {
        $footerColumns = FooterMenu::whereNull('parent_id')->orderBy('sort_order')->get();
        return view('admin.footer_menus.create', compact('footerColumns'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'parent_id'   => 'nullable|integer|exists:footer_menus,id',
            'url'         => 'nullable|string|max:500',
            'icon'        => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'sort_order'  => 'nullable|integer|min:0',
            'type'        => 'nullable|string|in:default,social,payment',
            'status'      => 'required|in:0,1',
        ]);

        FooterMenu::create([
            'parent_id'   => $request->parent_id ?: null,
            'name'        => $request->name,
            'url'         => $request->url,
            'icon'        => $request->icon,
            'description' => $request->description,
            'sort_order'  => $request->sort_order ?? 0,
            'is_static'   => $request->boolean('is_static'),
            'type'        => $request->type ?? 'default',
            'status'      => $request->status,
        ]);

        return redirect()->route('admin.footer_menus.index')->with('success', 'Đã thêm mục footer thành công!');
    }

    public function edit(int $id)
    {
        $footerMenu = FooterMenu::findOrFail($id);
        $footerColumns = FooterMenu::whereNull('parent_id')->where('id', '!=', $id)->orderBy('sort_order')->get();
        return view('admin.footer_menus.edit', compact('footerMenu', 'footerColumns'));
    }

    public function update(Request $request, int $id)
    {
        $footerMenu = FooterMenu::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'parent_id'   => 'nullable|integer|exists:footer_menus,id',
            'url'         => 'nullable|string|max:500',
            'icon'        => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'sort_order'  => 'nullable|integer|min:0',
            'type'        => 'nullable|string|in:default,social,payment',
            'status'      => 'required|in:0,1',
        ]);

        $footerMenu->update([
            'parent_id'   => $request->parent_id ?: null,
            'name'        => $request->name,
            'url'         => $request->url,
            'icon'        => $request->icon,
            'description' => $request->description,
            'sort_order'  => $request->sort_order ?? 0,
            'is_static'   => $request->boolean('is_static'),
            'type'        => $request->type ?? 'default',
            'status'      => $request->status,
        ]);

        return redirect()->route('admin.footer_menus.index')->with('success', 'Đã cập nhật thành công!');
    }

    public function destroy(int $id)
    {
        $footerMenu = FooterMenu::findOrFail($id);
        FooterMenu::where('parent_id', $id)->delete();
        $footerMenu->delete();
        return redirect()->route('admin.footer_menus.index')->with('success', 'Đã xóa thành công!');
    }
}