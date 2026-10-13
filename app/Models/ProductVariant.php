<?php

namespace App\Models;

use App\Helpers\ProductHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'color', 'size', 'stock_quantity'];

    // Biến thể thuộc về 1 sản phẩm
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Tên màu tiếng Việt lấy từ ProductHelper dùng chung của dự án
     */
    public function getColorNameAttribute(): string
    {
        if (!$this->color) {
            return '';
        }

        $names = ProductHelper::colorNames();
        return $names[strtoupper($this->color)] ?? $names[strtolower($this->color)] ?? $this->color;
    }
}