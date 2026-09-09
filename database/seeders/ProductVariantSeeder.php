<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        // Tắt foreign key check
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('carts')->truncate();
        DB::table('product_variants')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Danh sách size và màu được phép
        $availableSizes = ['S', 'M', 'L', 'XL', 'XXL'];
        
        $availableColors = [
            ['name' => 'Đen',       'code' => '#000000'],
            ['name' => 'Trắng',     'code' => '#FFFFFF'],
            ['name' => 'Xanh Navy', 'code' => '#000080'],
            ['name' => 'Be',        'code' => '#F5F5DC'],
            ['name' => 'Xám',       'code' => '#808080'],
        ];

        // Lấy tất cả sản phẩm (trừ phụ kiện)
        $products = Product::where('category_id', '!=', 9) // giả sử category phụ kiện là 9, bạn sửa lại cho đúng
            ->orWhereNull('category_id')
            ->get();

        // Nếu bạn không chắc category_id phụ kiện, dùng cách này:
        // $products = Product::all();

        foreach ($products as $product) {

            // Bỏ qua phụ kiện (bạn có thể lọc thêm theo tên nếu cần)
            if (str_contains(strtolower($product->name), 'kính') || 
                str_contains(strtolower($product->name), 'mũ') || 
                str_contains(strtolower($product->name), 'thắt lưng') ||
                str_contains(strtolower($product->name), 'vòng cổ') ||
                str_contains(strtolower($product->name), 'ví')) {
                continue;
            }

            // Random 2 đến 4 size
            $randomSizes = collect($availableSizes)
                ->random(rand(2, 4))
                ->values()
                ->toArray();

            // Random 2 đến 3 màu
            $randomColors = collect($availableColors)
                ->random(rand(2, 3))
                ->values()
                ->toArray();

            // Tạo tổ hợp size × màu
            foreach ($randomSizes as $size) {
                foreach ($randomColors as $color) {
                    ProductVariant::create([
                        'product_id'     => $product->id,
                        'color'          => $color['code'],
                        'size'           => $size,
                        'stock_quantity' => rand(8, 25),
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ]);
                }
            }
        }

        $this->command->info('Đã tạo variants ngẫu nhiên thành công!');
    }
}