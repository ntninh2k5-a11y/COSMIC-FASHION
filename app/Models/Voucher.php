<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function orders()
    {
        return $this->hasMany(Order::class, 'voucherId');
    }
}