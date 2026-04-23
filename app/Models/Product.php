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

    public function promotions()
    {
        return $this->hasMany(Promotion::class, 'product_id');
    }

    public function activePromotion()
    {
        return $this->hasOne(Promotion::class, 'product_id')
                    ->where('is_active', true)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->latest();
    }

    protected $appends = ['discounted_price', 'active_discount_percentage'];

    public function getDiscountedPriceAttribute()
    {
        if ($this->relationLoaded('activePromotion') && $this->activePromotion) {
            $discountAmount = ($this->price * $this->activePromotion->discount_percentage) / 100;
            return $this->price - $discountAmount;
        }
        return $this->price;
    }

    public function getActiveDiscountPercentageAttribute()
    {
        if ($this->relationLoaded('activePromotion') && $this->activePromotion) {
            return $this->activePromotion->discount_percentage;
        }
        return 0;
    }
}