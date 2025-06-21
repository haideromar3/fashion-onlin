<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FashionModelImage extends Model
{
    protected $fillable = ['fashion_model_id', 'image_path'];

    public function fashionModel()
    {
        return $this->belongsTo(FashionModel::class);
    }
}

