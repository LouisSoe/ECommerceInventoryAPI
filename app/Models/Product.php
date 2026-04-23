<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'tb_product';

    protected $fillable = [
        'name',
        'category_id',
        'price',
        'stock_quantity',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}