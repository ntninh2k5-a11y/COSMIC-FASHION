<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'receiver_name',
        'province',
        'district',
        'ward',
        'street_address',
        'receiver_address',
        'phone_number',
        'note',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Tự động tạo receiver_address đầy đủ từ các trường riêng lẻ
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->street_address,
            $this->ward,
            $this->district,
            $this->province,
        ]);
        return implode(', ', $parts);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
