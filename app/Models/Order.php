<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'order_code', 
        'total_amount', 
        'status', 
        'payment_method', 
        'shipping_address', 
        'customer_phone', 
        'notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusMap()
    {
        return [
            'pending'    => ['label' => 'Chờ xử lý',      'class' => 'badge-pending'],
            'processing' => ['label' => 'Đang chuẩn bị',  'class' => 'badge-processing'],
            'shipping'   => ['label' => 'Đang giao',       'class' => 'badge-shipping'],
            'completed'  => ['label' => 'Đã giao',         'class' => 'badge-completed'],
            'cancelled'  => ['label' => 'Đã hủy',         'class' => 'badge-cancelled'],
            'paid'       => ['label' => 'Đã thanh toán',   'class' => 'badge-paid'],
        ];
    }

    public function getStatusLabelAttribute()
    {
        $map = $this->getStatusMap();
        return $map[$this->status]['label'] ?? $this->status;
    }

    public function getStatusBadgeAttribute()
    {
        $map = $this->getStatusMap();
        return $map[$this->status]['class'] ?? 'badge-inactive';
    }
}