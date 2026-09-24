<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'avatar_url',
        'phone',
        'date_of_birth',
        'gender',
        'loyalty_points',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
