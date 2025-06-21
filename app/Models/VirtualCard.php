<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VirtualCard extends Model
{
    protected $fillable = ['user_id', 'card_number', 'balance'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
{
    return $this->hasMany(VirtualCardTransaction::class);
}

}