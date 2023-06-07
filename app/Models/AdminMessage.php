<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'chat_room_id',
        'user_id',
    ];
    
    public function room() {
        return $this->belongsTo(ChatRoom::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
}
