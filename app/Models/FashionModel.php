<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FashionModel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'age', 'email', 'bio', 'instagram', 'country', 'image'];


    public function images()
{
    return $this->hasMany(FashionModelImage::class);
}



}

