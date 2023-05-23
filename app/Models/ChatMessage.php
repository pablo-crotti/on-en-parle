<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ChatRoom;

class ChatMessage extends Model
{
    use HasFactory;

    public function room() {
        return $this->belongsTo(ChatRoom::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
