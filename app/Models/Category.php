<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'tb_category';

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'image_url',
        'is_active',
    ];
}