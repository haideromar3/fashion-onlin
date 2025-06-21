<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
     use Notifiable;
    use HasFactory;

    use HasApiTokens, HasFactory, Notifiable;


    protected $fillable = [
        'name', 'email', 'password', 'role', 'is_vip',
    ];

 
    protected $hidden = [
        'password', 'remember_token',
    ];


// Profile
public function profile()
{
    return $this->hasOne(Profile::class);
}

// Orders
public function orders()
{
    return $this->hasMany(Order::class);
}

// Cart
public function cart()
{
    return $this->hasMany(Cart::class);
}

// Wishlist
public function wishlist()
{
    return $this->belongsToMany(Product::class, 'wishlists');
}

// Points
public function points()
{
    return $this->hasOne(Point::class);
}


public function blogs() {
    return $this->hasMany(Blog::class);
}

public function reviews()
{
    return $this->hasMany(Review::class);
}


public function comments() {
    return $this->hasMany(Comment::class);
}



    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function complaints()
{
    return $this->hasMany(\App\Models\Complaint::class);
}


public function virtualCard()
{
    return $this->hasOne(VirtualCard::class);
}


}
