<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;

use Illuminate\Support\Str;

class ImportReactImages extends Command
{
    protected $signature = 'import:react-images';
    protected $description = 'Import products from img_react directory';

    public function handle()
    {
        $basePath = public_path('img_react/Nam');
        
        if (!is_dir($basePath)) {
            $this->error("Directory not found: $basePath");
            return;
        }

        $categories = array_diff(scandir($basePath), ['.', '..']);
        
        $allSizes = ['S', 'M', 'L', 'XL', 'XXL'];
        $allColors = ['Đen', 'Trắng', 'Xám', 'Xanh Navy', 'Be', 'Nâu'];

        $totalProducts = 0;

        foreach ($categories as $catName) {
            $catPath = $basePath . '/' . $catName;
            if (!is_dir($catPath)) continue;

            $this->info("Processing Category: $catName");

            // Find or create category
            $slug = Str::slug($catName);
            $category = Category::firstOrCreate(
                ['slug' => $slug],
                ['name' => $catName, 'status' => 1]
            );

            $productDirs = array_diff(scandir($catPath), ['.', '..']);
            
            foreach ($productDirs as $prodIndex) {
                $prodPath = $catPath . '/' . $prodIndex;
                if (!is_dir($prodPath)) continue;

                $images = array_diff(scandir($prodPath), ['.', '..']);
                // Filter only images (png, jpg, jpeg)
                $images = array_filter($images, function($file) use ($prodPath) {
                    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    return in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                });
                
                if (empty($images)) continue;
                
                $this->info("  -> Product ID $prodIndex");

                // Generate random data
                $prodName = $catName . ' Thời Trang Mẫu ' . $prodIndex;
                $basePrice = rand(15, 60) * 10000; // 150k - 600k
                $hasDiscount = rand(1, 10) > 4; // 60% chance to have discount
                $discount = $hasDiscount ? rand(10, 40) : 0;
                $salePrice = $hasDiscount ? $basePrice * (100 - $discount) / 100 : $basePrice;

                // Make relative path for main image
                $firstImage = array_values($images)[0];
                $mainImgPath = 'img_react/Nam/' . $catName . '/' . $prodIndex . '/' . $firstImage;

                $product = Product::create([
                    'category_id' => $category->id,
                    'name' => $prodName,
                    'slug' => Str::slug($prodName . '-' . uniqid()),
                    'price' => $basePrice,
                    'sale_price' => $salePrice,
                    'discount_percent' => $discount,
                    'status' => 1,
                    'description' => "Sản phẩm $prodName cao cấp, thiết kế hiện đại, chất liệu thoáng mát phù hợp mặc hàng ngày.",
                    'image_url' => $mainImgPath
                ]);

                // No ProductImage model, skip multiple images

                // Insert random variants
                $numSizes = rand(2, 4);
                $numColors = rand(1, 3);
                
                $randomSizes = (array) array_rand(array_flip($allSizes), $numSizes);
                $randomColors = (array) array_rand(array_flip($allColors), $numColors);

                foreach ($randomSizes as $size) {
                    foreach ($randomColors as $color) {
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'size' => (string) $size,
                            'color' => (string) $color,
                            'stock' => rand(10, 100),
                            'price' => $basePrice // Or modify slightly based on variant
                        ]);
                    }
                }
                
                $totalProducts++;
            }
        }
        
        $this->info("Import completed successfully! Total products imported: $totalProducts");
    }
}
