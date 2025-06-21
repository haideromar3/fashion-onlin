<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'description'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // إذا كان هناك علاقة many-to-many مع المنتجات
    public function product()
    {
        return $this->belongsToMany(Product::class, 'category_product');
    }

    
}
