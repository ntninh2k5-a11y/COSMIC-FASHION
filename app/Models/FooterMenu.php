<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterMenu extends Model
{
    use HasFactory;

    protected $table = 'footer_menus';

    protected $fillable = [
        'parent_id',
        'name',
        'url',
        'icon',
        'description',
        'sort_order',
        'is_static',
        'type',
        'status'
    ];

    protected $casts = [
        'is_static' => 'boolean',
    ];

    public function children()
    {
        return $this->hasMany(FooterMenu::class, 'parent_id')->where('status', 1)->orderBy('sort_order');
    }

    public function parent()
    {
        return $this->belongsTo(FooterMenu::class, 'parent_id');
    }
}