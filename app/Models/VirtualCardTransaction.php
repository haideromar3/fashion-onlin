<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VirtualCardTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'virtual_card_id',
        'type',
        'amount',
        'balance_after',
        'description',
        'user_id',
    ];

    public function virtualCard()
    {
        return $this->belongsTo(VirtualCard::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

