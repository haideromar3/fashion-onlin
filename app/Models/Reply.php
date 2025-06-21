<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Message;
use App\Models\Reply;

class Reply extends Model
{
    protected $fillable = ['message_id', 'admin_id', 'content'];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}