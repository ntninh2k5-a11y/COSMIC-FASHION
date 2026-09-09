<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('status', 1)->get();
        $sizes = ['S', 'M', 'L', 'XL', 'XXL'];
        $colors = [
            'Đen'       => '#000000',
            'Trắng'     => '#FFFFFF',
            'Xanh Navy' => '#001F3F',
            'Be'        => '#F5F5DC',
            'Xám'       => '#808080',
        ];

        return view('admin.products.create', compact('categories', 'sizes', 'colors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'name'             => 'required|string|max:255|unique:products,name',
            'price'            => 'required|numeric|min:0',
            'sale_price'       => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'description'      => 'nullable|string',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'status'           => 'required|boolean',
            'variants'         => 'nullable|array',
            'variants.*.size'  => 'required_with:variants|in:S,M,L,XL,XXL',
            'variants.*.color' => 'required_with:variants',
            'variants.*.stock_quantity' => 'required_with:variants|integer|min:0',
        ]);

        $data = $request->only([
            'category_id', 'name', 'price', 'sale_price',
            'discount_percent', 'description', 'status'
        ]);

        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/products'), $imageName);
            $data['image_url'] = 'uploads/products/' . $imageName;
        }

        $product = Product::create($data);

        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                if (!empty($variant['size']) && !empty($variant['color'])) {
                    ProductVariant::create([
                        'product_id'      => $product->id,
                        'size'            => $variant['size'],
                        'color'           => $variant['color'],
                        'stock_quantity'  => $variant['stock_quantity'] ?? 0,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')
                         ->with('success', 'Thêm sản phẩm thành công!');
    }

    public function edit(int $id)
    {
        $product = Product::with('variants')->findOrFail($id);
        $categories = Category::where('status', 1)->get();
        $sizes = ['S', 'M', 'L', 'XL', 'XXL'];
        $colors = [
            'Đen'       => '#000000',
            'Trắng'     => '#FFFFFF',
            'Xanh Navy' => '#001F3F',
            'Be'        => '#F5F5DC',
            'Xám'       => '#808080',
        ];

        return view('admin.products.edit', compact('product', 'categories', 'sizes', 'colors'));
    }

    public function update(Request $request,int $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'name'             => 'required|string|max:255|unique:products,name,' . $product->id,
            'price'            => 'required|numeric|min:0',
            'sale_price'       => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'description'      => 'nullable|string',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'status'           => 'required|boolean',
            'variants'         => 'nullable|array',
            'variants.*.size'  => 'required_with:variants|in:S,M,L,XL,XXL',
            'variants.*.color' => 'required_with:variants',
            'variants.*.stock_quantity' => 'required_with:variants|integer|min:0',
        ]);

        $data = $request->only([
            'category_id', 'name', 'price', 'sale_price',
            'discount_percent', 'description', 'status'
        ]);

        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            if ($product->image_url && file_exists(public_path($product->image_url))) {
                unlink(public_path($product->image_url));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/products'), $imageName);
            $data['image_url'] = 'uploads/products/' . $imageName;
        }

        $product->update($data);

        $product->variants()->delete();

        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                if (!empty($variant['size']) && !empty($variant['color'])) {
                    ProductVariant::create([
                        'product_id'      => $product->id,
                        'size'            => $variant['size'],
                        'color'           => $variant['color'],
                        'stock_quantity'  => $variant['stock_quantity'] ?? 0,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')
                         ->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function destroy(int $id)
    {
        $product = Product::findOrFail($id);

        if ($product->image_url && file_exists(public_path($product->image_url))) {
            unlink(public_path($product->image_url));
        }

        $product->variants()->delete();
        $product->delete();

        return redirect()->route('admin.products.index')
                         ->with('success', 'Xóa sản phẩm thành công!');
    }
}