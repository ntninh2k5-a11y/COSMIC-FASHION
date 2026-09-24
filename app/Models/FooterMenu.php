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
        'status'
    ];

    public function children()
    {
        return $this->hasMany(FooterMenu::class, 'parent_id');
    }
}