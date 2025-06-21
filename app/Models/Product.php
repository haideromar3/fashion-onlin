<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price', 'stock',
        'category_id', 'brand_id', 'user_id', 'is_published',
        'discount', 'size', 'color', 'sizes', 'is_black_friday', 'product_type_id'
    ];

    // العلامة التجارية
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // منشئ المنتج (مصمم، مورد، أو أدمن)
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // صور المنتج
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // المراجعات
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // متوسط تقييم المنتج
    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }

    // علاقته مع الـ Wishlist
    public function wishlistedBy()
    {
        return $this->belongsToMany(User::class, 'wishlists');
    }

    // التصنيف
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // الطلبات التي تحتوي المنتج
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }

    // نوع المنتج
    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }

    // Accessor لعرض اسم المنشئ مع دوره بشكل جاهز في العرض
    public function getCreatorInfoAttribute()
    {
        if ($this->creator) {
            return $this->creator->name . ' (' . ucfirst($this->creator->role) . ')';
        }
        return 'غير معروف';
    }

    public function user()
{
    return $this->belongsTo(User::class);
}


protected static function booted()
{
    static::deleting(function ($product) {
        // حذف الصور من التخزين أولًا
        foreach ($product->images as $image) {
            if (\Storage::disk('public')->exists($image->image_path)) {
                \Storage::disk('public')->delete($image->image_path);
            }
        }
        // حذف سجلات الصور من قاعدة البيانات
        $product->images()->delete();
    });
}


public function getAverageRatingAttribute()
{
    return round($this->reviews()->avg('rating'), 1);
}


}
