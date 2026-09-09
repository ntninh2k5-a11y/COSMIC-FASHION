<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('id', 'desc')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'status' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/categories'), $fileName);
            $imagePath = 'uploads/categories/' . $fileName;
        }

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'status' => $request->status,
            'image_url' => $imagePath,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Thêm danh mục thành công!');
    }

    public function edit(int $id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, int $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'status' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240'
        ]);

        $imagePath = $category->image_url;

        if ($request->hasFile('image')) {
            if ($category->image_url && File::exists(public_path($category->image_url))) {
                File::delete(public_path($category->image_url));
            }

            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/categories'), $fileName);
            $imagePath = 'uploads/categories/' . $fileName;
        }

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'status' => $request->status,
            'image_url' => $imagePath,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục thành công!');
    }

    public function destroy(int $id)
    {
        $category = Category::findOrFail($id);
        
        if ($category->image_url && File::exists(public_path($category->image_url))) {
            File::delete(public_path($category->image_url));
        }
        
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Đã xóa danh mục!');
    }
}