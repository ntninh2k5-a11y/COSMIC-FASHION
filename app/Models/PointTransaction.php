<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'points',
        'transaction_type',
        'description',
        'order_id',
    ];

    // ===== Loại giao dịch =====
    const TYPE_EARN_ORDER   = 'earn_order';     // Tích điểm khi mua hàng
    const TYPE_REDEEM       = 'redeem';         // Đổi điểm lấy giảm giá
    const TYPE_ADMIN_ADD    = 'admin_add';      // Admin cộng điểm
    const TYPE_ADMIN_DEDUCT = 'admin_deduct';   // Admin trừ điểm

    // ===== Relationships =====
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // ===== Methods từ diagram =====

    /**
     * Cộng điểm cho user và ghi log giao dịch
     */
    public static function addPoints(int $userId, int $points, string $type, string $description = '', ?int $orderId = null): self
    {
        $transaction = self::create([
            'user_id'          => $userId,
            'points'           => abs($points),
            'transaction_type' => $type,
            'description'      => $description,
            'order_id'         => $orderId,
        ]);

        // Cập nhật tổng điểm trong profile
        $profile = \App\Models\UserProfile::where('user_id', $userId)->first();
        if ($profile) {
            $profile->increment('loyalty_points', abs($points));
        }

        return $transaction;
    }

    /**
     * Trừ điểm của user và ghi log giao dịch
     */
    public static function deductPoints(int $userId, int $points, string $type, string $description = '', ?int $orderId = null): self
    {
        $transaction = self::create([
            'user_id'          => $userId,
            'points'           => -abs($points),
            'transaction_type' => $type,
            'description'      => $description,
            'order_id'         => $orderId,
        ]);

        // Cập nhật tổng điểm trong profile
        $profile = \App\Models\UserProfile::where('user_id', $userId)->first();
        if ($profile) {
            $profile->decrement('loyalty_points', abs($points));
        }

        return $transaction;
    }

    // ===== Accessors =====

    public function getTypeLabelAttribute(): string
    {
        return match ($this->transaction_type) {
            self::TYPE_EARN_ORDER   => 'Tích điểm đơn hàng',
            self::TYPE_REDEEM       => 'Đổi điểm giảm giá',
            self::TYPE_ADMIN_ADD    => 'Admin cộng điểm',
            self::TYPE_ADMIN_DEDUCT => 'Admin trừ điểm',
            default                 => $this->transaction_type,
        };
    }

    public function getTypeBadgeAttribute(): string
    {
        return match ($this->transaction_type) {
            self::TYPE_EARN_ORDER   => 'background:#dcfce7; color:#16a34a;',
            self::TYPE_REDEEM       => 'background:#fee2e2; color:#dc2626;',
            self::TYPE_ADMIN_ADD    => 'background:#e0f2fe; color:#0284c7;',
            self::TYPE_ADMIN_DEDUCT => 'background:#fff8e1; color:#f59e0b;',
            default                 => 'background:#f3f4f6; color:#6b7280;',
        };
    }
}
