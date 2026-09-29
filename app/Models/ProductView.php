<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductView extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id', 'product_id', 'viewed_at'];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    // ---- Relationships ----

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // ---- Business Logic ----

    /**
     * Ghi nhận 1 lượt xem sản phẩm.
     * Mỗi user (hoặc guest) chỉ tính 1 lượt / sản phẩm / 1 giờ để tránh spam.
     */
    public static function recordView(int $productId, ?int $userId = null): void
    {
        $query = self::where('product_id', $productId);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $recentView = $query->where('viewed_at', '>=', now()->subHour())->first();

        if (!$recentView) {
            self::create([
                'user_id'    => $userId,
                'product_id' => $productId,
                'viewed_at'  => now(),
            ]);
        }
    }
}
