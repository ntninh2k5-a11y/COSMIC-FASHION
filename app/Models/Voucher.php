<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Voucher extends Model
{
    use HasFactory;

    protected $table = 'vouchers';

    protected $fillable = [
        'code',
        'discountType',
        'discountValue',
        'minOrderValue',
        'maxDiscountAmount',
        'usageLimit',
        'usageCount',
        'startDate',
        'endDate',
        'isActive'
    ];

    /**
     * Boot: tự động vô hiệu hóa voucher hết hạn mỗi khi truy vấn
     */
    protected static function booted(): void
    {
        // Mỗi khi query Voucher, tự động deactivate những cái đã hết hạn
        static::retrieved(function (Voucher $voucher) {
            if ($voucher->isActive && $voucher->endDate && Carbon::parse($voucher->endDate)->endOfDay()->isPast()) {
                $voucher->updateQuietly(['isActive' => false]);
            }
        });
    }

    /**
     * Trạng thái hiển thị: Hoạt động / Hết hạn / Đã tắt / Hết lượt
     */
    public function getStatusLabelAttribute(): string
    {
        if ($this->endDate && Carbon::parse($this->endDate)->endOfDay()->isPast()) {
            return 'Hết hạn';
        }
        if ($this->startDate && Carbon::parse($this->startDate)->startOfDay()->isFuture()) {
            return 'Chưa bắt đầu';
        }
        if ($this->usageLimit && $this->usageCount >= $this->usageLimit) {
            return 'Hết lượt';
        }
        if (!$this->isActive) {
            return 'Đã tắt';
        }
        return 'Hoạt động';
    }

    /**
     * Badge CSS class cho từng trạng thái
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status_label) {
            'Hoạt động' => 'badge-completed',
            'Hết hạn' => 'badge-cancelled',
            'Chưa bắt đầu' => 'badge-processing',
            'Hết lượt' => 'badge-pending',
            'Đã tắt' => 'badge-inactive',
            default => 'badge-inactive',
        };
    }

    /**
     * Scope: chỉ lấy voucher đang hợp lệ (dùng cho frontend)
     */
    public function scopeValid($query)
    {
        $now = Carbon::now();
        return $query->where('isActive', true)
                     ->where('startDate', '<=', $now)
                     ->where('endDate', '>=', $now)
                     ->whereColumn('usageCount', '<', 'usageLimit');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'voucherId');
    }
}