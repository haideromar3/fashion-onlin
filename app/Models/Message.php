<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Message;
use App\Models\Reply;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['sender_id', 'content', 'is_read'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function replies()
{
    return $this->hasMany(Reply::class);
}
}