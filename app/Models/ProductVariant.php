<?php

namespace App\Models;

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
}