<?php

namespace App\Models;
use App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

protected $fillable = [
    'user_id', 'shipping_method', 'payment_method', 'city', 'address', 'shipping_fee', 'total', 'status', 
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function shipping()
    {
        return $this->hasOne(Shipping::class);
    }

public function products()
{
    return $this->belongsToMany(Product::class, 'order_items')
        ->withPivot('quantity', 'price')
        ->withTimestamps();
}




}
