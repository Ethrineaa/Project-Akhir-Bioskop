<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chats extends Model
{
    protected $table = 'chats';

    protected $fillable = [
        'user_id',
        'sender_type',
        'message',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
